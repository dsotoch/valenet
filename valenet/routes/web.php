<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {

    Route::get('/', [
        UsuarioController::class,
        'mostrarLogin'
    ])->name('login');

    Route::post('/login', [
        UsuarioController::class,
        'login'
    ])->name('login.procesar');
});

Route::post('/auth/admin/reset-db', function () {

    Artisan::call('migrate:fresh', [
        '--seed' => true,
    ]);

    $salida = Artisan::output();

    return redirect()->back()->with([
        'mensaje' => 'Base de datos restablecida correctamente',
        'salida' => $salida,
    ]);
})->middleware(['auth', 'rol:administrador'])->name('admin.reset-db');;

Route::middleware("auth")->group(function () {
    Route::middleware('rol:administrador')->get('/dashboard', [UsuarioController::class, 'indexDashboard'])->name('dashboard');
    Route::post('/logout', [
        UsuarioController::class,
        'logout'
    ])
        ->name('logout');


    Route::resource('clientes', ClienteController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy',
        ]);
    Route::resource('planes', PlanController::class)
        ->only(['index', 'show']);

    Route::middleware('rol:administrador')->group(function () {

        Route::get('/planes/create', [PlanController::class, 'create'])
            ->name('planes.create');

        Route::post('/planes', [PlanController::class, 'store'])
            ->name('planes.store');

        Route::get('/planes/{plane}/edit', [PlanController::class, 'edit'])
            ->name('planes.edit');

        Route::put('/planes/{plane}', [PlanController::class, 'update'])
            ->name('planes.update');

        Route::delete('/planes/{plane}', [PlanController::class, 'destroy'])
            ->name('planes.destroy');
    });

    Route::middleware('rol:administrador')->prefix('reportes')
        ->name('reportes.')
        ->group(function () {

            Route::get('/', [
                ReporteController::class,
                'index'
            ])->name('index');
        });
    Route::middleware('rol:administrador')->prefix('usuarios')
        ->name('usuarios.')
        ->group(function () {

            Route::get('/', [
                UsuarioController::class,
                'index'
            ])->name('index');

            Route::post('/', [
                UsuarioController::class,
                'store'
            ])->name('store');

            Route::put('/{usuario}', [
                UsuarioController::class,
                'update'
            ])->name('update');

            Route::delete('/{usuario}', [
                UsuarioController::class,
                'destroy'
            ])->name('destroy');
        });
    Route::prefix('pagos')
        ->name('pagos.')
        ->group(function () {
            Route::get('/pagos/{pago}/recibo', [PagoController::class, 'recibo'])
                ->name('recibo');

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

            Route::middleware('rol:administrador')->put(
                '/{pago}',
                [PagoController::class, 'update']
            )->name('update');

            Route::middleware('rol:administrador')->delete(
                '/{pago}',
                [PagoController::class, 'destroy']
            )->name('destroy');

            Route::get(
                '/{pago}/whatsapp',
                [PagoController::class, 'whatsapp']
            )->name('whatsapp');
        });
});
