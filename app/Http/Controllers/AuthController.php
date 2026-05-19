<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use App\Models\User;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    private function ensureApplicantRecord(User $user): ?Applicant
    {
        if ($user->applicant_id) {
            return $user->applicant()->first();
        }

        if ($user->role !== User::ROLE_USER) {
            return null;
        }

        $nameParts = preg_split('/\s+/', trim((string) $user->name), 3, PREG_SPLIT_NO_EMPTY) ?: [];
        $firstName = $nameParts[0] ?? trim((string) $user->name);
        $lastName = $nameParts[2] ?? ($nameParts[1] ?? $firstName);

        $applicant = Applicant::create([
            'first_name' => Str::title(Str::lower(trim((string) $firstName))),
            'middle_name' => null,
            'last_name' => Str::title(Str::lower(trim((string) $lastName))),
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

        return $applicant;
    }

    private function storeAvatarFromUrl(User $user, ?string $avatarUrl, string $prefix): void
    {
        if (trim((string) $avatarUrl) === '') {
            return;
        }

        if ($user->profile_image && ! filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
            return;
        }

        try {
            $response = Http::timeout(10)->get($avatarUrl);

            if (! $response->successful()) {
                return;
            }

            $contentType = strtolower((string) $response->header('Content-Type', ''));
            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'jpeg'), str_contains($contentType, 'jpg') => 'jpg',
                str_contains($contentType, 'gif') => 'gif',
                str_contains($contentType, 'webp') => 'webp',
                default => 'jpg',
            };

            $fileName = $prefix . '-' . $user->id . '-' . Str::random(16) . '.' . $extension;
            $path = 'profile-images/provider-avatars/' . $fileName;

            Storage::disk('public')->put($path, $response->body());
            $user->profile_image = $path;
            $user->saveQuietly();
        } catch (\Throwable $e) {
            Log::warning('Unable to store provider avatar locally.', [
                'user_id' => $user->id,
                'avatar_url' => $avatarUrl,
                'exception_class' => $e::class,
                'exception_message' => $e->getMessage(),
            ]);

            if (empty($user->profile_image)) {
                $user->profile_image = $avatarUrl;
                $user->saveQuietly();
            }
        }
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Show applicant-only login form
    public function showApplicantLoginForm()
    {
        return view('auth.applicant-login');
    }

    // Show applicant registration form
    public function showApplicantRegisterForm()
    {
        return redirect()->route('applicant.login')->with('success', 'Applicant registration is now handled through Google sign-in.');
    }

    // Handle applicant portal registration
    public function registerApplicant(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $username = Str::lower($data['username']);
        $firstName = trim((string) $data['first_name']);
        $middleName = trim((string) ($data['middle_name'] ?? '')) ?: null;
        $lastName = trim((string) $data['last_name']);
        $suffix = trim((string) ($data['suffix'] ?? '')) ?: null;

        $applicant = DB::transaction(function () use ($data, $username, $firstName, $middleName, $lastName, $suffix) {
            $applicant = Applicant::create([
                'first_name' => Str::title(Str::lower($firstName)),
                'middle_name' => $middleName ? Str::title(Str::lower($middleName)) : null,
                'last_name' => Str::title(Str::lower($lastName)),
                'suffix' => $suffix ? Str::upper($suffix) : null,
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
                'profile_completed' => false,
            ]);

            $user = User::create([
                'name' => Str::upper(trim(implode(' ', array_filter([
                    Str::title(Str::lower($firstName)),
                    $middleName ? Str::title(Str::lower($middleName)) : null,
                    Str::title(Str::lower($lastName)),
                    $suffix ? Str::upper($suffix) : null,
                ])))),
                'username' => $username,
                'email' => $username.'@applicant.local',
                'password' => $data['password'],
                'role' => User::ROLE_USER,
                'permissions' => [],
                'auth_provider' => 'local',
                'applicant_id' => $applicant->id,
            ]);

            ActivityLogger::log(
                'applicant',
                'created',
                'Applicant registered through the portal.',
                $applicant,
                ActivityLogger::diff([], $applicant->only($applicant->getFillable())),
                $user
            );

            return $applicant;
        });

        return redirect()
            ->route('applicant.login')
            ->with('success', 'Your applicant account is ready. You can sign in with Google now.')
            ->with('username', $username);
    }

    // Handle applicant portal login
    public function loginApplicant(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim((string) $credentials['username']);
        $normalized = Str::lower($identifier);

        $user = User::query()
            ->where(function ($query) use ($identifier, $normalized) {
                $query->where('username', $normalized)
                    ->orWhere('username', $identifier)
                    ->orWhere('email', $normalized);
            })
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        $this->ensureApplicantRecord($user);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log(
            'auth',
            'login',
            'Applicant logged in using username and password.',
            null,
            [
                'provider' => [
                    'before' => null,
                    'after' => 'local',
                ],
            ],
            $user
        );

        return redirect()->route('dashboard');
    }

    // Handle login post
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = trim((string) $credentials['email']);

        // If identifier looks like an email use existing flow
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $email = strtolower($identifier);

            $existingUser = User::where('email', $email)->first();

            if ($existingUser && $existingUser->auth_provider === 'azure') {
                return back()->withErrors([
                    'email' => 'This account uses Microsoft sign-in. Please use "Sign in with Microsoft".',
                ])->onlyInput('email');
            }

            if (Auth::attempt([
                'email' => $email,
                'password' => $credentials['password'],
                'auth_provider' => 'local',
            ])) {
                $authenticatedUser = Auth::user();

                if ($authenticatedUser?->role === User::ROLE_USER) {
                    $this->ensureApplicantRecord($authenticatedUser);
                }

                $request->session()->regenerate();

                ActivityLogger::log(
                    'auth',
                    'login',
                    'User logged in using email and password.',
                    null,
                    [
                        'provider' => [
                            'before' => null,
                            'after' => 'local',
                        ],
                    ],
                    Auth::user()
                );

                return redirect()->route('dashboard');
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function redirectToAzure()
    {
        if (trim((string) config('services.azure.tenant')) === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Azure tenant is not configured.',
            ]);
        }

        return Socialite::driver('azure')->redirect();
    }

    public function redirectToGoogle()
    {
        if (trim((string) config('services.google.client_id')) === '' || trim((string) config('services.google.client_secret')) === '' || trim((string) config('services.google.redirect')) === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Google OAuth is not configured.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleAzureCallback(Request $request)
    {
        if (trim((string) config('services.azure.tenant')) === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Azure tenant is not configured.',
            ]);
        }

        try {
            try {
                $azureUser = Socialite::driver('azure')->user();
            } catch (InvalidStateException $e) {
                // Local/dev environments often hit state mismatch on callback.
                $azureUser = Socialite::driver('azure')->stateless()->user();
            }
        } catch (\Throwable $e) {
            Log::error('Azure SSO callback failed.', [
                'exception_class' => $e::class,
                'exception_message' => $e->getMessage(),
            ]);

            $message = 'Microsoft login failed. Please check Azure credentials and redirect URI, then try again.';

            if (config('app.debug')) {
                $message .= ' [' . class_basename($e) . '] ' . Str::limit($e->getMessage(), 180);
            }

            return redirect()->route('login')->withErrors([
                'email' => $message,
            ]);
        }

        $email = strtolower((string) $azureUser->getEmail());

        if ($email === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Microsoft account email was not provided.',
            ]);
        }

        $allowedDomains = collect(explode(',', (string) config('services.azure.allowed_domains')))
            ->map(fn (string $domain) => ltrim(strtolower(trim($domain)), '@'))
            ->filter();

        if ($allowedDomains->isEmpty()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Azure allowed domains are not configured.',
            ]);
        }

        $emailDomain = strtolower(Str::after($email, '@'));

        if (! $allowedDomains->contains($emailDomain)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your Microsoft account domain is not allowed.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->auth_provider !== 'azure') {
            return redirect()->route('login')->withErrors([
                'email' => 'This email is registered for local login. Please sign in using email and password.',
            ]);
        }

        if (! $user) {
            $user = User::create([
                'name' => $azureUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'role' => User::ROLE_USER,
                'permissions' => [],
                'auth_provider' => 'azure',
                'email_verified_at' => now(),
            ]);
        }

        if ($user->role === User::ROLE_USER) {
            $this->ensureApplicantRecord($user);
        }

        $this->storeAvatarFromUrl($user, $azureUser->getAvatar(), 'azure');

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log(
            'auth',
            'login',
            'User logged in using Microsoft Azure.',
            null,
            [
                'provider' => [
                    'before' => null,
                    'after' => 'azure',
                ],
            ],
            $user
        );

        return redirect()->route('dashboard');
    }

    public function handleGoogleCallback(Request $request)
    {
        if (trim((string) config('services.google.client_id')) === '' || trim((string) config('services.google.client_secret')) === '' || trim((string) config('services.google.redirect')) === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Google OAuth is not configured.',
            ]);
        }

        try {
            try {
                $googleUser = Socialite::driver('google')->user();
            } catch (InvalidStateException $e) {
                // Local/dev environments sometimes hit state mismatch on callback.
                $googleUser = Socialite::driver('google')->stateless()->user();
            }
        } catch (\Throwable $e) {
            Log::error('Google SSO callback failed.', [
                'exception_class' => $e::class,
                'exception_message' => $e->getMessage(),
            ]);

            $message = 'Google login failed. Please check Google credentials and redirect URI, then try again.';

            if (config('app.debug')) {
                $message .= ' [' . class_basename($e) . '] ' . Str::limit($e->getMessage(), 180);
            }

            return redirect()->route('login')->withErrors([
                'email' => $message,
            ]);
        }

        $email = strtolower((string) $googleUser->getEmail());

        if ($email === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Google account email was not provided.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->auth_provider !== 'google') {
            return redirect()->route('login')->withErrors([
                'email' => 'This email is registered for local login. Please sign in using email and password.',
            ]);
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(40)),
                'role' => User::ROLE_USER,
                'permissions' => [],
                'auth_provider' => 'google',
                'email_verified_at' => now(),
            ]);
        }

        if ($user->role === User::ROLE_USER) {
            $this->ensureApplicantRecord($user);
        }

        $this->storeAvatarFromUrl($user, $googleUser->getAvatar(), 'google');

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log(
            'auth',
            'login',
            'User logged in using Google.',
            null,
            [
                'provider' => [
                    'before' => null,
                    'after' => 'google',
                ],
            ],
            $user
        );

        return redirect()->route('dashboard');
    }

    // Logout user
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            ActivityLogger::log(
                'auth',
                'logout',
                'User logged out of the system.',
                null,
                null,
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && $user->role === User::ROLE_USER) {
            if ($user->auth_provider === 'azure') {
                $tenant = trim((string) config('services.azure.tenant'));

                if ($tenant !== '') {
                    $logoutRedirect = route('applicant.login');
                    $logoutUrl = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode($logoutRedirect);

                    return redirect()->away($logoutUrl);
                }
            }

            return redirect()->route('applicant.login');
        }

        if ($user && $user->auth_provider === 'azure') {
            $tenant = trim((string) config('services.azure.tenant'));

            if ($tenant === '') {
                return redirect()->route('login');
            }

            $logoutRedirect = config('services.azure.logout_redirect', url('/login'));
            $logoutUrl = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode($logoutRedirect);

            return redirect()->away($logoutUrl);
        }

        return redirect()->route('login');
    }
}
