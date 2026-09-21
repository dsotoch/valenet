<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\ClientePlan;
use App\Models\RecordatorioPago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FECHAS
        |--------------------------------------------------------------------------
        */

        $fechaDesde = $request->input(
            'fecha_desde',
            now()->startOfMonth()->format('Y-m-d')
        );

        $fechaHasta = $request->input(
            'fecha_hasta',
            now()->endOfMonth()->format('Y-m-d')
        );

        $desde = Carbon::parse($fechaDesde)->startOfDay();
        $hasta = Carbon::parse($fechaHasta)->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        */

        $clientesActivos = Cliente::where('estado', true)->count();

        $clientesInactivos = Cliente::where('estado', false)->count();

        $clientesNuevos = Cliente::whereBetween(
            'created_at',
            [$desde, $hasta]
        )->count();

        /*
        |--------------------------------------------------------------------------
        | PAGOS DEL PERÍODO
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | Para ingresos utilizamos monto_pagado y fecha_pago.
        |
        | Un pago puede seguir en estado "pendiente" pero tener:
        |
        | monto       = 39.50
        | monto_pagado = 20.00
        |
        | En ese caso los S/ 20.00 sí son ingresos.
        |
        */

        $pagosPeriodo = Pago::whereBetween(
            'fecha_pago',
            [$desde, $hasta]
        )
            ->whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0);

        /*
        |--------------------------------------------------------------------------
        | TOTAL COBRADO
        |--------------------------------------------------------------------------
        */

        $totalCobrado = (clone $pagosPeriodo)
            ->sum('monto_pagado');

        /*
        |--------------------------------------------------------------------------
        | TOTAL DE PAGOS CON ABONO
        |--------------------------------------------------------------------------
        */

        $cantidadPagos = (clone $pagosPeriodo)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | FACTURADO
        |--------------------------------------------------------------------------
        |
        | Aquí consideramos los pagos cuya fecha de vencimiento
        | está dentro del período.
        |
        */

        $totalFacturado = Pago::whereBetween(
            'fecha_vencimiento',
            [$desde, $hasta]
        )
            ->whereNotIn('estado', ['anulado'])
            ->sum('monto');

        /*
        |--------------------------------------------------------------------------
        | PENDIENTE
        |--------------------------------------------------------------------------
        |
        | monto - monto_pagado
        |
        */

        $totalPendiente = Pago::whereBetween(
            'fecha_vencimiento',
            [$desde, $hasta]
        )
            ->whereIn('estado', ['pendiente', 'vencido'])
            ->get()
            ->sum(function ($pago) {
                return max(
                    0,
                    (float) $pago->monto -
                    (float) $pago->monto_pagado
                );
            });

        /*
        |--------------------------------------------------------------------------
        | TOTAL VENCIDO
        |--------------------------------------------------------------------------
        */

        $totalVencido = Pago::where('estado', 'vencido')
            ->whereBetween(
                'fecha_vencimiento',
                [$desde, $hasta]
            )
            ->get()
            ->sum(function ($pago) {
                return max(
                    0,
                    (float) $pago->monto -
                    (float) $pago->monto_pagado
                );
            });

        /*
        |--------------------------------------------------------------------------
        | ESTADOS DE PAGOS
        |--------------------------------------------------------------------------
        */

        $pagosPagados = Pago::whereBetween(
            'fecha_pago',
            [$desde, $hasta]
        )
            ->where('estado', 'pagado')
            ->count();

        $pagosPendientes = Pago::whereBetween(
            'fecha_pago',
            [$desde, $hasta]
        )
            ->where('estado', 'pendiente')
            ->count();

        $pagosVencidos = Pago::whereBetween(
            'fecha_vencimiento',
            [$desde, $hasta]
        )
            ->where('estado', 'vencido')
            ->count();

        $pagosAnulados = Pago::whereBetween(
            'fecha_vencimiento',
            [$desde, $hasta]
        )
            ->where('estado', 'anulado')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | INGRESOS POR DÍA
        |--------------------------------------------------------------------------
        */

        $ingresos = (clone $pagosPeriodo)
            ->selectRaw(
                'DATE(fecha_pago) as fecha,
                 SUM(monto_pagado) as total'
            )
            ->groupByRaw('DATE(fecha_pago)')
            ->orderBy('fecha')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | COMPLETAR DÍAS SIN INGRESOS
        |--------------------------------------------------------------------------
        |
        | Esto permite que el gráfico no salte fechas.
        |
        */

        $graficoIngresos = [];

        $cursor = $desde->copy();

        while ($cursor->lte($hasta)) {

            $fecha = $cursor->format('Y-m-d');

            $registro = $ingresos->firstWhere(
                'fecha',
                $fecha
            );

            $graficoIngresos[] = [
                'fecha' => $cursor->format('d/m'),
                'total' => $registro
                    ? (float) $registro->total
                    : 0,
            ];

            $cursor->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | CLIENTES POR PLAN
        |--------------------------------------------------------------------------
        */

        $clientesPorPlan = Plan::withCount([
            'clientePlanes as clientes_count' => function ($query) {
                $query->where('estado', true);
            }
        ])
            ->orderByDesc('clientes_count')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CLIENTES MOROSOS
        |--------------------------------------------------------------------------
        */

        $clientesMorosos = Cliente::whereHas('pagos', function ($query) {
            $query->whereIn('estado', ['pendiente', 'vencido'])
                ->whereColumn(
                    'monto_pagado',
                    '<',
                    'monto'
                );
        })
            ->with([
                'pagos' => function ($query) {
                    $query->whereIn(
                        'estado',
                        ['pendiente', 'vencido']
                    )
                        ->whereColumn(
                            'monto_pagado',
                            '<',
                            'monto'
                        )
                        ->orderByDesc('fecha_vencimiento');
                }
            ])
            ->get()
            ->map(function ($cliente) {

                $deuda = $cliente->pagos->sum(function ($pago) {
                    return max(
                        0,
                        (float) $pago->monto -
                        (float) $pago->monto_pagado
                    );
                });

                $ultimoVencimiento = $cliente->pagos
                    ->sortByDesc('fecha_vencimiento')
                    ->first();

                return [
                    'id' => $cliente->id,
                    'nombre' => trim(
                        ($cliente->nombres ?? '') .
                        ' ' .
                        ($cliente->apellidos ?? '')
                    ),
                    'documento' => $cliente->documento ?? '',
                    'telefono' => $cliente->telefono ?? '',
                    'deuda' => $deuda,
                    'estado_pago' => $ultimoVencimiento?->estado,
                    'vencimiento' => $ultimoVencimiento?->fecha_vencimiento,
                ];
            })
            ->sortByDesc('deuda')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | ÚLTIMOS PAGOS
        |--------------------------------------------------------------------------
        */

        $ultimosPagos = Pago::with([
            'cliente',
            'clientePlan.plan',
        ])
            ->whereBetween(
                'fecha_pago',
                [$desde, $hasta]
            )
            ->whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0)
            ->orderByDesc('fecha_pago')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECORDATORIOS
        |--------------------------------------------------------------------------
        */

        $recordatoriosEnviados = RecordatorioPago::whereBetween(
            'fecha_envio',
            [$desde, $hasta]
        )->count();

        $recordatoriosWhatsapp = RecordatorioPago::whereBetween(
            'fecha_envio',
            [$desde, $hasta]
        )
            ->where('canal', 'whatsapp')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | MÉTODOS DE PAGO
        |--------------------------------------------------------------------------
        */

        $metodosPago = Pago::whereBetween(
            'fecha_pago',
            [$desde, $hasta]
        )
            ->whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0)
            ->selectRaw(
                'COALESCE(metodo_pago, "Sin especificar") as metodo,
                 SUM(monto_pagado) as total'
            )
            ->groupBy('metodo')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return view('reportes.index', compact(
            'fechaDesde',
            'fechaHasta',

            'clientesActivos',
            'clientesInactivos',
            'clientesNuevos',

            'totalCobrado',
            'cantidadPagos',
            'totalFacturado',
            'totalPendiente',
            'totalVencido',

            'pagosPagados',
            'pagosPendientes',
            'pagosVencidos',
            'pagosAnulados',

            'graficoIngresos',

            'clientesPorPlan',
            'clientesMorosos',
            'ultimosPagos',

            'recordatoriosEnviados',
            'recordatoriosWhatsapp',

            'metodosPago'
        ));
    }
}