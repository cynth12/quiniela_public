<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuinielaPublicController;


Route::post('pagos/webhook', [PagoController::class, 'webhook'])->name('pagos.webhook');