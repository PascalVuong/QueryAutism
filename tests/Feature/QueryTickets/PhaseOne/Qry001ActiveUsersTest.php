<?php

namespace Tests\Feature\QueryTickets\PhaseOne;

use App\QuerySolutions\PhaseOne\Qry001ActiveUsersSolution;
use App\QueryTickets\PhaseOne\Qry001ActiveUsers;
use Database\Seeders\PhaseOneScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use LogicException;
use Tests\TestCase;

class Qry001ActiveUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_solution_returns_expected_results(): void
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        $results = app(Qry001ActiveUsersSolution::class)->run();

        $this->assertExpectedResults($results);
    }

    public function test_exercise_returns_expected_results_when_implemented(): void
    {
        $this->seed(PhaseOneScenarioSeeder::class);

        try {
            $results = app(Qry001ActiveUsers::class)->run();
        } catch (LogicException $exception) {
            $this->markTestIncomplete($exception->getMessage());
        }

        $this->assertExpectedResults($results);
    }

    private function assertExpectedResults(Collection $results): void
    {
        $this->assertSame([
            'owner@queryautism.test',
            'multi.manager@queryautism.test',
            'no.profile@queryautism.test',
            'never.logged.in@queryautism.test',
        ], $results->pluck('email')->all());

        $this->assertSame(
            ['active'],
            $results->pluck('status')->unique()->values()->all(),
        );

        $this->assertNull($results->last()->last_login_at);

        foreach ($results as $result) {
            $this->assertSame([
                'id',
                'name',
                'email',
                'status',
                'last_login_at',
            ], array_keys($result->getAttributes()));
        }
    }
}
