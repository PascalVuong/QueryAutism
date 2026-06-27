<?php

use App\Http\Controllers\QueryTicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/queries');

Route::get('/queries', [QueryTicketController::class, 'index'])
    ->name('queries.index');

Route::get('/queries/{ticket}', [QueryTicketController::class, 'show'])
    ->name('queries.show');
