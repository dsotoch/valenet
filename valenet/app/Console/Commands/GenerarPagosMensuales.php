<?php

namespace App\Console\Commands;

use App\Models\ClientePlan;
use App\Models\Pago;
use Illuminate\Console\Command;

class GenerarPagosMensuales extends Command
{
    protected $signature = 'pagos:generar';

    protected $description =
        'Genera las mensualidades de los clientes con plan activo';

    public function handle(): int
    {
        $hoy = now();

        /*
         * El período actual siempre corresponde
         * al primer día del mes.
         *
         * Ejemplo:
         * 21/09/2026 → 01/09/2026
         */
        $periodoActual = $hoy->copy()->startOfMonth();

        /*
         * Obtener todos los planes activos
         * cuya fecha de inicio ya llegó.
         */
        $clientesPlanes = ClientePlan::with([
            'cliente',
            'plan',
        ])
            ->where('estado', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->get();

        $generados = 0;

        foreach ($clientesPlanes as $clientePlan) {

            /*
             * Buscar el último pago generado
             * para este cliente y plan.
             */
            $ultimoPago = Pago::where(
                'cliente_plan_id',
                $clientePlan->id
            )
                ->orderByDesc('periodo')
                ->first();

            /*
             * ==========================================
             * DETERMINAR EL NUEVO PERÍODO
             * ==========================================
             */
            if ($ultimoPago) {

                /*
                 * Si ya existe un pago:
                 *
                 * 01/08/2026
                 *      ↓ + 1 mes
                 * 01/09/2026
                 */
                $periodo = $ultimoPago
                    ->periodo
                    ->copy()
                    ->addMonthNoOverflow()
                    ->startOfMonth();

                /*
                 * MUY IMPORTANTE:
                 *
                 * El nuevo vencimiento debe respetar
                 * el día del vencimiento anterior.
                 *
                 * Ejemplo:
                 *
                 * 21/09/2026
                 *      ↓
                 * día = 21
                 */
                $diaVencimiento = $ultimoPago
                    ->fecha_vencimiento
                    ->day;

            } else {

                /*
                 * ======================================
                 * PRIMER PAGO
                 * ======================================
                 *
                 * Si el cliente todavía no tiene pagos,
                 * el período será el mes de inicio.
                 *
                 * Ejemplo:
                 *
                 * fecha_inicio = 21/08/2026
                 *
                 * período = 01/08/2026
                 */
                $periodo = $clientePlan
                    ->fecha_inicio
                    ->copy()
                    ->startOfMonth();

                /*
                 * El primer vencimiento toma
                 * el día de la fecha de inicio.
                 *
                 * 21/08 → día 21
                 */
                $diaVencimiento = $clientePlan
                    ->fecha_inicio
                    ->day;
            }

            /*
             * ==========================================
             * NO GENERAR PERÍODOS FUTUROS
             * ==========================================
             *
             * Si el nuevo período es octubre
             * y estamos en septiembre, no se genera.
             */
            if ($periodo->gt($periodoActual)) {
                continue;
            }

            /*
             * ==========================================
             * EVITAR DUPLICADOS
             * ==========================================
             */
            $existe = Pago::where(
                'cliente_plan_id',
                $clientePlan->id
            )
                ->whereDate('periodo', $periodo)
                ->exists();

            if ($existe) {
                continue;
            }

            /*
             * ==========================================
             * CALCULAR FECHA DE VENCIMIENTO
             * ==========================================
             *
             * El vencimiento corresponde al mes
             * siguiente al período.
             *
             * Ejemplo:
             *
             * período:       01/09/2026
             * vencimiento:   21/10/2026
             */
            $fechaVencimiento = $periodo
                ->copy()
                ->addMonthNoOverflow();

            /*
             * Obtener el último día del mes
             * del vencimiento.
             *
             * Ejemplo:
             *
             * febrero → 28
             * febrero bisiesto → 29
             * abril → 30
             * mayo → 31
             */
            $ultimoDiaDelMes = $fechaVencimiento
                ->copy()
                ->endOfMonth()
                ->day;

            /*
             * Si el día original existe en el mes,
             * se conserva.
             *
             * Ejemplo:
             *
             * día = 21
             * febrero tiene 28
             * → 21
             *
             * Si el día es 31 y febrero tiene 28:
             *
             * min(31, 28) = 28
             */
            $fechaVencimiento->day(
                min(
                    $diaVencimiento,
                    $ultimoDiaDelMes
                )
            );

            /*
             * ==========================================
             * CREAR PAGO
             * ==========================================
             */
            Pago::create([
                'cliente_id' => $clientePlan->cliente_id,

                'cliente_plan_id' => $clientePlan->id,

                'periodo' => $periodo,

                'fecha_vencimiento' => $fechaVencimiento,

                'monto' => $clientePlan->precio,

                'monto_pagado' => 0,

                'estado' => 'pendiente',
            ]);

            $generados++;

            /*
             * Mostrar información en consola.
             */
            $this->info(
                "Pago generado: ClientePlan #{$clientePlan->id} | " .
                "Periodo: {$periodo->format('Y-m-d')} | " .
                "Vencimiento: {$fechaVencimiento->format('Y-m-d')}"
            );
        }

        $this->newLine();

        $this->info(
            "Pagos generados: {$generados}"
        );

        return self::SUCCESS;
    }
}