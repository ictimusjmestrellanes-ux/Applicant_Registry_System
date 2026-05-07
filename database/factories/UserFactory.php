<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => \App\Models\User::ROLE_USER,
            'permissions' => [],
            'auth_provider' => 'local',
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'role' => \App\Models\User::ROLE_ADMIN,
            'permissions' => array_keys(\App\Models\User::permissionOptions()),
        ]);
    }

    public function staff(): static
    {
        return $this->state(fn () => [
            'role' => \App\Models\User::ROLE_STAFF,
        ]);
    }

    public function user(): static
    {
        return $this->state(fn () => [
            'role' => \App\Models\User::ROLE_USER,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
