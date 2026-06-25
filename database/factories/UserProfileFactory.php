<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProfile>
 */
class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->optional()->dateTimeBetween(
                '-80 years',
                '-18 years',
            ),
            'avatar_path' => fake()->optional(0.2)->filePath(),
            'preferred_contact_method' => fake()->randomElement([
                'email',
                'phone',
                'sms',
                'none',
            ]),
            'marketing_consent' => fake()->boolean(35),
            'preferences' => [
                'email_notifications' => fake()->boolean(),
                'sms_notifications' => fake()->boolean(),
            ],
        ];
    }

    public function withoutPhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
        ]);
    }

    public function marketingOptIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'marketing_consent' => true,
        ]);
    }
}
