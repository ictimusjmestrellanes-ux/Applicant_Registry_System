<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login post
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = strtolower($credentials['email']);

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

            return redirect()->intended('dashboard');
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

        return redirect()->intended('dashboard');
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

        if ($user && $user->auth_provider === 'azure') {
            $tenant = trim((string) config('services.azure.tenant'));

            if ($tenant === '') {
                return redirect('/login');
            }

            $logoutRedirect = config('services.azure.logout_redirect', url('/login'));
            $logoutUrl = 'https://login.microsoftonline.com/' . $tenant . '/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode($logoutRedirect);

            return redirect()->away($logoutUrl);
        }

        return redirect('/login');
    }
}
