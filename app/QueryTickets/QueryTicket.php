<?php

namespace App\QueryTickets;

use Illuminate\Support\Collection;

abstract class QueryTicket
{
    abstract public function id(): string;

    abstract public function title(): string;

    abstract public function description(): string;

    /**
     * @return array<int, string>
     */
    abstract public function concepts(): array;

    /**
     * @return array<int, string>
     */
    abstract public function expectedColumns(): array;

    /**
     * Execute the learner's query.
     *
     * @return Collection<int, mixed>
     */
    abstract public function run(): Collection;

    public function filePath(): string
    {
        $reflection = new \ReflectionClass($this);

        return str_replace(
            base_path().DIRECTORY_SEPARATOR,
            '',
            $reflection->getFileName(),
        );
    }
}
