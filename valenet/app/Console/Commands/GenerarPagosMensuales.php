<?php

namespace App\Console\Commands;

use App\Models\ClientePlan;
use App\Models\Pago;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerarPagosMensuales extends Command
{
    protected $signature = 'pagos:generar';

    protected $description =
        'Genera las mensualidades de los clientes con plan activo';


    public function handle(): int
    {
        $hoy = now();

        $periodo = $hoy->copy()->startOfMonth();


        $clientesPlanes = ClientePlan::with([
            'cliente',
            'plan',
        ])
            ->where('estado', true)
            ->whereDate(
                'fecha_inicio',
                '<=',
                $periodo->copy()->endOfMonth()
            )
            ->get();


        $generados = 0;


        foreach ($clientesPlanes as $clientePlan) {

            /*
             * Evitar duplicar mensualidad.
             */
            $existe = Pago::where(
                    'cliente_plan_id',
                    $clientePlan->id
                )
                ->whereDate(
                    'periodo',
                    $periodo
                )
                ->exists();


            if ($existe) {
                continue;
            }


            /*
             * Día de vencimiento basado en
             * la fecha de inicio.
             */
            $dia = $clientePlan
                ->fecha_inicio
                ->day;


            /*
             * Evitar problemas con meses que
             * no tienen 30/31 días.
             */
            $ultimoDia =
                $periodo->copy()
                    ->endOfMonth()
                    ->day;


            $diaVencimiento =
                min(
                    $dia,
                    $ultimoDia
                );


            $fechaVencimiento =
                $periodo->copy()
                    ->day($diaVencimiento);


            Pago::create([

                'cliente_id' =>
                    $clientePlan->cliente_id,

                'cliente_plan_id' =>
                    $clientePlan->id,

                'periodo' =>
                    $periodo,

                'fecha_vencimiento' =>
                    $fechaVencimiento,

                'monto' =>
                    $clientePlan->precio,

                'monto_pagado' =>
                    0,

                'estado' =>
                    'pendiente',

            ]);


            $generados++;
        }


        $this->info(
            "Pagos generados: {$generados}"
        );


        return self::SUCCESS;
    }
}