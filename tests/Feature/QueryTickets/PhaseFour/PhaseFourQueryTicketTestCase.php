<?php

namespace Tests\Feature\QueryTickets\PhaseFour;

use Closure;
use Database\Seeders\PhaseFourScenarioSeeder;
use Database\Seeders\PhaseOneScenarioSeeder;
use Database\Seeders\PhaseThreeScenarioSeeder;
use Database\Seeders\PhaseTwoScenarioSeeder;
use Illuminate\Support\Collection;
use LogicException;
use Tests\Feature\QueryTickets\QueryTicketTestCase;

abstract class PhaseFourQueryTicketTestCase extends QueryTicketTestCase
{
    /**
     * @param class-string $ticketClass
     */
    protected function runPhaseFourTicket(
        string $ticketClass,
        ?Closure $afterSeed = null,
    ): Collection {
        $this->seed(PhaseOneScenarioSeeder::class);
        $this->seed(PhaseTwoScenarioSeeder::class);
        $this->seed(PhaseThreeScenarioSeeder::class);
        $this->seed(PhaseFourScenarioSeeder::class);

        $afterSeed?->__invoke();

        try {
            return app($ticketClass)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }
    }
}
