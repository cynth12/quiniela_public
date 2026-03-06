<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagoController;



Route::post('webhook/mp', [PagoController::class, 'webhook'])->name('mp.webhook');