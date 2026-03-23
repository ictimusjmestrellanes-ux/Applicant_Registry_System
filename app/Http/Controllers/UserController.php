<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            : array_values($validated['permissions'] ?? []);

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
