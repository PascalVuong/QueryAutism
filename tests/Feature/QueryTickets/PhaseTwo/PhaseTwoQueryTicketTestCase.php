<?php

namespace Tests\Feature\QueryTickets\PhaseTwo;

use Closure;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Support\Collection;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

abstract class PhaseTwoQueryTicketTestCase extends QueryTicketTestCase
{
    /**
     * @param class-string $ticketClass
     */
    protected function runPhaseTwoTicket(
        string $ticketClass,
        ?Closure $afterSeed = null,
    ): Collection {
        $this->seed(PhaseOneScenarioSeeder::class);
        $this->seed(PhaseTwoScenarioSeeder::class);

        $afterSeed?->__invoke();

        try {
            return app($ticketClass)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }
    }
}