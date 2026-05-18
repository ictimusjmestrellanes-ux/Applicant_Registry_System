<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function approvalIndex(Request $request)
    {
        $status = trim((string) $request->query('status', 'pending'));
        $search = trim((string) $request->query('search', ''));

        $allowedStatuses = array_merge(['all'], User::approvalStatuses());
        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'pending';
        }

        $query = User::query()
            ->whereNotNull('applicant_id')
            ->when($status !== 'all', function ($innerQuery) use ($status) {
                $innerQuery->where('approval_status', $status);
            })
            ->when($search !== '', function ($innerQuery) use ($search) {
                $innerQuery->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $users = $query->latest()->paginate(12)->withQueryString();

        $statusCounts = collect(User::approvalStatuses())
            ->mapWithKeys(fn (string $approvalStatus) => [
                $approvalStatus => User::query()
                    ->whereNotNull('applicant_id')
                    ->where('approval_status', $approvalStatus)
                    ->count(),
            ])
            ->all();

        $statusCounts['all'] = User::query()
            ->whereNotNull('applicant_id')
            ->count();

        return view('approvals.index', [
            'users' => $users,
            'search' => $search,
            'status' => $status,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->search);

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'permissionOptions' => User::permissionOptions(),
        ]);
    }

    public function approveApplicant(Request $request, User $user)
    {
        abort_if($user->role !== User::ROLE_USER, 422, 'Only applicant accounts can be approved.');

        $before = [
            'approval_status' => $user->approval_status,
            'disapproval_reason' => $user->disapproval_reason,
            'disapproval_notes' => $user->disapproval_notes,
        ];

        $user->approval_status = User::APPROVAL_APPROVED;
        $user->disapproval_reason = null;
        $user->disapproval_notes = null;
        $user->save();

        $after = [
            'approval_status' => $user->approval_status,
            'disapproval_reason' => $user->disapproval_reason,
            'disapproval_notes' => $user->disapproval_notes,
        ];

        ActivityLogger::log(
            'user',
            'approved',
            'Approved an applicant account.',
            $user->applicant,
            ActivityLogger::diff($before, $after),
            $request->user()
        );

        return redirect()
            ->route('approvals.index')
            ->with('success', 'Applicant account approved successfully.');
    }

    public function disapproveApplicant(Request $request, User $user)
    {
        abort_if($user->role !== User::ROLE_USER, 422, 'Only applicant accounts can be disapproved.');

        $validator = Validator::make($request->all(), [
            'disapproval_reason' => ['required', 'string', 'max:2000'],
            'disapproval_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('disapprove_user_id', $user->id);
        }

        $validated = $validator->validated();

        $before = [
            'approval_status' => $user->approval_status,
            'disapproval_reason' => $user->disapproval_reason,
            'disapproval_notes' => $user->disapproval_notes,
        ];

        $user->approval_status = User::APPROVAL_DISAPPROVED;
        $user->disapproval_reason = $validated['disapproval_reason'];
        $user->disapproval_notes = $validated['disapproval_notes'] ?? null;
        $user->save();

        $after = [
            'approval_status' => $user->approval_status,
            'disapproval_reason' => $user->disapproval_reason,
            'disapproval_notes' => $user->disapproval_notes,
        ];

        ActivityLogger::log(
            'user',
            'disapproved',
            'Disapproved an applicant account.',
            $user->applicant,
            ActivityLogger::diff($before, $after),
            $request->user()
        );

        return redirect()
            ->route('approvals.index')
            ->with('success', 'Applicant account disapproved successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'permissionOptions' => User::permissionOptions(),
            'selectedPermissions' => $user->permissions ?? [],
        ]);
    }

    public function editProfile(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'max:5120'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $before = [
            'name' => $user->name,
            'profile_image' => $user->profile_image ? basename($user->profile_image) : null,
        ];

        $user->name = $validated['name'];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->save();

        // Handle password change if requested
        if (! empty($validated['password'])) {
            // Ensure current password matches for local accounts. If user's account is linked to an applicant,
            // validate against the applicant portal password to avoid issues with double-hashed values.
            $current = $validated['current_password'] ?? '';

            if ($user->applicant_id) {
                $applicant = \App\Models\Applicant::find($user->applicant_id);
                if (! $applicant || ! Hash::check($current, $applicant->portal_password)) {
                    return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withInput();
                }
            } else {
                if (($user->auth_provider ?? 'local') !== 'azure' && ! Hash::check($current, $user->password)) {
                    return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withInput();
                }
            }

            // Set the new password on the User model (the cast will hash it)
            $user->password = $validated['password'];
            $user->save();

            // If linked to applicant, update applicant portal password (store hashed)
            if (isset($applicant) && $applicant) {
                $applicant->portal_password = Hash::make($validated['password']);
                $applicant->save();
            }

            ActivityLogger::log(
                'auth',
                'password_change',
                'Changed account password.',
                null,
                null,
                $user
            );
        }

        $after = [
            'name' => $user->name,
            'profile_image' => $user->profile_image ? basename($user->profile_image) : null,
        ];

        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'profile',
                'updated',
                'Updated personal profile details.',
                null,
                $changes,
                $user
            );
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(User::roles())],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::in(array_keys(User::permissionOptions()))],
        ]);

        $before = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'permissions' => implode(', ', $user->permissions ?? []),
        ];

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);
        $user->role = $validated['role'];
        $user->permissions = $validated['role'] === User::ROLE_ADMIN
            ? array_keys(User::permissionOptions())
            : ($validated['role'] === User::ROLE_STAFF
                ? array_values($validated['permissions'] ?? [])
                : []);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $after = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'permissions' => implode(', ', $user->permissions ?? []),
        ];

        $changes = ActivityLogger::diff($before, $after);

        if (! empty($changes)) {
            ActivityLogger::log(
                'user',
                'updated',
                'Updated a user account and permissions.',
                null,
                $changes,
                $request->user()
            );
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }
}
