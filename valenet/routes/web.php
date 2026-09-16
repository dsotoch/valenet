<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::get('/logout', function () {
    return view('welcome');
})->name("logout");




Route::resource('clientes', ClienteController::class)
    ->only([
        'index',
        'store',
        'update',
        'destroy',
    ]);
Route::resource('planes', PlanController::class);
Route::prefix('pagos')
    ->name('pagos.')
    ->group(function () {

        Route::get(
            '/',
            [PagoController::class, 'index']
        )->name('index');

        Route::post(
            '/asignar-plan',
            [PagoController::class, 'asignarPlan']
        )->name('asignar-plan');

        Route::post(
            '/{pago}/registrar',
            [PagoController::class, 'registrarPago']
        )->name('registrar');

        Route::put(
            '/{pago}',
            [PagoController::class, 'update']
        )->name('update');

        Route::delete(
            '/{pago}',
            [PagoController::class, 'destroy']
        )->name('destroy');

        Route::get(
            '/{pago}/whatsapp',
            [PagoController::class, 'whatsapp']
        )->name('whatsapp');
    });
