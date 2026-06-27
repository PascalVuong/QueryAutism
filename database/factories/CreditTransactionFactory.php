<?php

namespace Database\Factories;

use App\Models\CreditAccount;
use App\Models\CreditTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditTransaction>
 */
class CreditTransactionFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 5, 100);

        return [
            'credit_account_id' => CreditAccount::factory(),
            'reservation_id' => null,
            'type' => 'grant',
            'amount' => $amount,
            'balance_after' => $amount,
            'description' => fake()->optional()->sentence(),
            'occurred_at' => now(),
            'metadata' => null,
        ];
    }

    public function forAccount(CreditAccount $account): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_account_id' => $account->id,
        ]);
    }

    public function spend(float $amount = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'spend',
            'amount' => -abs($amount),
        ]);
    }

    public function refund(float $amount = 10): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'refund',
            'amount' => abs($amount),
        ]);
    }
}
