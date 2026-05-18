<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ActivityLogger;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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
            if ($user->profile_image && ! filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
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

        DB::transaction(function () use ($user, $validated) {
            $user->save();

            if ($user->role !== User::ROLE_USER || ! empty($user->applicant_id)) {
                return;
            }

            $parsedName = preg_split('/\s+/', trim((string) $user->name), 3, PREG_SPLIT_NO_EMPTY) ?: [];

            $firstName = $parsedName[0] ?? Str::before(trim((string) $user->name), ' ');
            $lastName = $parsedName[2] ?? ($parsedName[1] ?? $firstName);

            $applicant = Applicant::create([
                'first_name' => Str::title(Str::lower(trim((string) $firstName ?: $user->name))),
                'middle_name' => null,
                'last_name' => Str::title(Str::lower(trim((string) $lastName ?: $user->name))),
                'suffix' => null,
                'age' => null,
                'contact_no' => '',
                'gender' => '',
                'civil_status' => null,
                'pwd' => '',
                'four_ps' => '',
                'address_line' => '',
                'province' => '',
                'city' => '',
                'barangay' => '',
                'educational_attainment' => null,
                'hiring_company' => null,
                'position_hired' => null,
                'first_time_job_seeker' => null,
            ]);

            $user->forceFill([
                'applicant_id' => $applicant->id,
            ])->saveQuietly();
        });

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
