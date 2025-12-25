<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuinielaPublicController;

Route::post('/mercadopago/webhook', [QuinielaPublicController::class, 'webhook']);
