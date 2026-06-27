<?php

namespace Tests\Feature\QueryTickets\PhaseThree;

use Closure;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseThreeScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Support\Collection;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

abstract class PhaseThreeQueryTicketTestCase extends QueryTicketTestCase
{
    /**
     * @param class-string $ticketClass
     */
    protected function runPhaseThreeTicket(
        string $ticketClass,
        ?Closure $afterSeed = null,
    ): Collection {
        $this->seed(PhaseOneScenarioSeeder::class);
        $this->seed(PhaseTwoScenarioSeeder::class);
        $this->seed(PhaseThreeScenarioSeeder::class);

        $afterSeed?->__invoke();

        try {
            return app($ticketClass)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }
    }
}
