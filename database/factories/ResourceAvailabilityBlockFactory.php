<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\ResourceAvailabilityBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResourceAvailabilityBlock>
 */
class ResourceAvailabilityBlockFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = now()->addDays(fake()->numberBetween(1, 30));

        return [
            'resource_id' => Resource::factory(),
            'type' => 'unavailable',
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addHour(),
            'reason' => fake()->optional()->sentence(),
            'created_by_user_id' => null,
            'metadata' => null,
        ];
    }

    public function forResource(Resource $resource): static
    {
        return $this->state(fn (array $attributes) => [
            'resource_id' => $resource->id,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'maintenance',
        ]);
    }
}
