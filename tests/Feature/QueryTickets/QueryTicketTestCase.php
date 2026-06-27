<?php

namespace Tests\Feature\QueryTickets;

use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use LogicException;
use Tests\TestCase;

abstract class QueryTicketTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * @param class-string $ticketClass
     */
    protected function runTicket(string $ticketClass): Collection
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        try {
            return app($ticketClass)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }
    }

    /**
     * @param array<int, string> $columns
     */
    protected function assertExactColumns(
        Collection $results,
        array $columns,
    ): void {
        foreach ($results as $result) {
            $this->assertSame(
                $columns,
                array_keys($result->getAttributes()),
            );
        }
    }
}