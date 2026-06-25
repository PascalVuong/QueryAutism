<?php

namespace App\QuerySolutions\PhaseOne;

use App\Models\User;
use App\QueryTickets\PhaseOne\Qry001ActiveUsers;
use Illuminate\Support\Collection;

class Qry001ActiveUsersSolution extends Qry001ActiveUsers
{
    public function run(): Collection
    {
        return User::query()
            ->select([
                'id',
                'name',
                'email',
                'status',
                'last_login_at',
            ])
            ->where('status', 'active')
            ->orderByRaw('last_login_at DESC NULLS LAST')
            ->get();
    }
}
