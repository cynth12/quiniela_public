<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect('/login'); // Redirige al login si entra a la raíz
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

use App\Http\Controllers\JugadorController;

Route::get('/jugadores', [JugadorController::class, 'index'])->name('jugadores.index');
Route::get('/jugadores/create', [JugadorController::class, 'create'])->name('jugadores.create');
Route::post('/jugadores', [JugadorController::class, 'store'])->name('jugadores.store');
Route::delete('/jugadores/{jugador}', [JugadorController::class, 'destroy'])->name('jugadores.destroy');

use App\Http\Controllers\JornadaController;

Route::get('/jornada', [JornadaController::class, 'index'])->name('jornadas.index');
Route::get('/jornada/create', [JornadaController::class, 'create'])->name('jornada.create');
Route::post('/jornada', [JornadaController::class, 'store'])->name('jornada.store');
Route::get('/jornada/{id}', [JornadaController::class, 'show'])->name('jornada.show');
Route::post('/jornadas/numero/{numero}/cerrar', [JornadaController::class, 'cerrarPorNumero'])->name('jornadas.cerrar');
Route::get('/ganadores', [JornadaController::class, 'todosLosGanadores'])->name('ganadores.todos');
Route::delete('/jornada/{id}', [JornadaController::class, 'destroy'])->name('jornada.destroy');
Route::get('/jornada/numero/{numero}', [JornadaController::class, 'showByNumero'])->name('jornada.show.numero');

use App\Http\Controllers\QuinielaPublicController;
Route::get('/public/jornada/numero/{numero}', [QuinielaPublicController::class, 'jornadaPorNumero'])->name('quiniela.public');
Route::post('/public/quiniela', [QuinielaPublicController::class, 'store'])->name('quiniela.store');
Route::get('/quiniela/pagar/{jugador}', [QuinielaPublicController::class, 'pagar'])->name('quiniela.pagar');
Route::get('/quiniela/exito', [QuinielaPublicController::class, 'exito'])->name('quiniela.exito');
Route::get('/quiniela/fallo', [QuinielaPublicController::class, 'fallo'])->name('quiniela.fallo');
Route::get('/quiniela/pendiente', [QuinielaPublicController::class, 'pendiente'])->name('quiniela.pendiente');
Route::post('/webhook/mp', [QuinielaPublicController::class, 'webhook'])->name('mp.webhook');

use App\Http\Controllers\ResultadoController;
Route::get('/resultados', [ResultadoController::class, 'index'])->name('resultados.index');
Route::post('/resultados/{numero}/guardar', [ResultadoController::class, 'guardarResultados'])->name('resultados.guardar');

use App\Http\Controllers\QuinielaController;
Route::get('/quiniela', [QuinielaController::class, 'index'])->name('quiniela.index');
Route::get('/quiniela/{id}', [QuinielaController::class, 'show'])->name('quiniela.show');
Route::delete('/quinielas/{id}', [QuinielaController::class, 'destroy'])->name('quinielas.destroy');
Route::get('/quinielas/jugador/{id}', [QuinielaController::class, 'verPorJugador'])->name('quiniela.jugador');


use App\Http\Controllers\PagoController;
Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
Route::get('/pago/{jugadorId}', [PagoController::class, 'generarPago'])->name('pagos.generar');
Route::delete('/pagos/{id}', [PagoController::class, 'destroy'])->name('pagos.destroy');

// Rutas de retorno de Mercado Pago 
Route::get('/pagos/success', [PagoController::class, 'success'])->name('pagos.success'); Route::get('/pagos/failure', [PagoController::class, 'failure'])->name('pagos.failure'); 
Route::get('/pagos/pending', [PagoController::class, 'pending'])->name('pagos.pending'); 



// Webhook para confirmación automática 

Route::post('/pagos/webhook', [PagoController::class, 'webhook'])->name('pagos.webhook');
