@extends('layouts.dashboard')

@section('title', 'Reportes')

@section('header-title', 'Reportes')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Reportes
            </h1>

            <p class="mt-1 text-sm text-slate-700">
                Analiza el estado financiero y comercial de Valenet.
            </p>
        </div>

        <div class="flex gap-2">

            <button
                type="button"
                onclick="window.print()"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                <i class="fa-solid fa-print"></i>
                Imprimir
            </button>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('reportes.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
        >

            <div>
                <label
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Desde
                </label>

                <input
                    type="date"
                    name="fecha_desde"
                    value="{{ $fechaDesde }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100"
                >
            </div>


            <div>
                <label
                    class="mb-1.5 block text-sm font-semibold text-slate-700"
                >
                    Hasta
                </label>

                <input
                    type="date"
                    name="fecha_hasta"
                    value="{{ $fechaHasta }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100"
                >
            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700"
                >
                    <i class="fa-solid fa-filter mr-1"></i>
                    Consultar
                </button>

            </div>


            <div class="flex items-end">

                <a
                    href="{{ route('reportes.index') }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                >
                    <i class="fa-solid fa-rotate-left mr-1"></i>
                    Limpiar
                </a>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- TARJETAS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- COBRADO --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Total cobrado
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-800">
                        S/ {{ number_format($totalCobrado, 2) }}
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        {{ $cantidadPagos }} pagos con abono
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-money-bill-wave text-xl"></i>
                </div>

            </div>

        </div>


        {{-- FACTURADO --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Total facturado
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-800">
                        S/ {{ number_format($totalFacturado, 2) }}
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        Período seleccionado
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                </div>

            </div>

        </div>


        {{-- PENDIENTE --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Monto pendiente
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-800">
                        S/ {{ number_format($totalPendiente, 2) }}
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        Saldo por cobrar
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>

            </div>

        </div>


        {{-- VENCIDO --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Monto vencido
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-slate-800">
                        S/ {{ number_format($totalVencido, 2) }}
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        Deuda vencida
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CLIENTES --}}
    {{-- ========================================================= --}}

  <div class="flex w-full gap-4">

    <div class="flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <p class="text-sm font-medium text-slate-700">
            Clientes activos
        </p>

        <div class="mt-2 flex items-center gap-3">

            <span class="text-3xl font-bold text-slate-800">
                {{ $clientesActivos }}
            </span>

            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                Activos
            </span>

        </div>

    </div>


    <div class="flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <p class="text-sm font-medium text-slate-700">
            Clientes inactivos
        </p>

        <div class="mt-2 flex items-center gap-3">

            <span class="text-3xl font-bold text-slate-800">
                {{ $clientesInactivos }}
            </span>

            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                Inactivos
            </span>

        </div>

    </div>


    <div class="flex-1 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <p class="text-sm font-medium text-slate-700">
            Nuevos clientes
        </p>

        <div class="mt-2 flex items-center gap-3">

            <span class="text-3xl font-bold text-slate-800">
                {{ $clientesNuevos }}
            </span>

            <span class="rounded-full bg-cyan-50 px-2.5 py-1 text-xs font-semibold text-cyan-600">
                Período
            </span>

        </div>

    </div>

</div>


    {{-- ========================================================= --}}
    {{-- GRÁFICOS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


        {{-- INGRESOS --}}

        <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-slate-800">
                    Ingresos
                </h3>

                <p class="text-sm text-slate-700">
                    Dinero cobrado por día.
                </p>

            </div>

            <div class="relative h-[320px]">
                <canvas id="graficoIngresos"></canvas>
            </div>

        </div>


        {{-- ESTADOS --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-slate-800">
                    Estado de pagos
                </h3>

                <p class="text-sm text-slate-700">
                    Distribución de pagos.
                </p>

            </div>

            <div class="relative h-[320px]">
                <canvas id="graficoEstados"></canvas>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PLANES + MÉTODOS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- CLIENTES POR PLAN --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-slate-800">
                    Clientes por plan
                </h3>

                <p class="text-sm text-slate-700">
                    Distribución actual.
                </p>

            </div>

            <div class="relative h-[320px]">
                <canvas id="graficoPlanes"></canvas>
            </div>

        </div>


        {{-- MÉTODOS DE PAGO --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-slate-800">
                    Métodos de pago
                </h3>

                <p class="text-sm text-slate-700">
                    Cobros registrados en el período.
                </p>

            </div>

            <div class="space-y-3">

                @forelse($metodosPago as $metodo)

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">

                        <div class="flex items-center gap-3">

                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-cyan-600 shadow-sm">
                                <i class="fa-solid fa-wallet"></i>
                            </div>

                            <span class="text-sm font-semibold capitalize text-slate-700">
                                {{ $metodo->metodo }}
                            </span>

                        </div>

                        <span class="text-sm font-bold text-slate-800">
                            S/ {{ number_format($metodo->total, 2) }}
                        </span>

                    </div>

                @empty

                    <div class="py-12 text-center text-sm text-slate-800">
                        No hay pagos registrados en este período.
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MOROSIDAD --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="text-lg font-bold text-slate-800">
                    Clientes morosos
                </h3>

                <p class="text-sm text-slate-700">
                    Clientes con saldo pendiente o vencido.
                </p>

            </div>

            <div class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600">
                {{ $clientesMorosos->count() }} clientes
            </div>

        </div>


        {{-- DESKTOP --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Cliente
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Documento
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Teléfono
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Vencimiento
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-700">
                            Deuda
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($clientesMorosos as $cliente)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4">

                                <div class="font-semibold text-slate-800">
                                    {{ ucwords(strtolower($cliente['nombre'] ?: 'Sin cliente')) }}
                                </div>

                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ $cliente['documento'] ?: '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ $cliente['telefono'] ?: '-' }}
                            </td>

                            <td class="px-5 py-4">

                                @if($cliente['vencimiento'])

                                    <span class="text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($cliente['vencimiento'])->format('d/m/Y') }}
                                    </span>

                                @else

                                    <span class="text-sm text-slate-800">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                <span class="font-bold text-red-600">
                                    S/ {{ number_format($cliente['deuda'], 2) }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-5 py-12 text-center text-sm text-slate-800"
                            >
                                <i class="fa-solid fa-circle-check mb-2 text-2xl text-emerald-500"></i>

                                <div>
                                    No hay clientes morosos.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}

        <div class="divide-y divide-slate-100 md:hidden">

            @forelse($clientesMorosos as $cliente)

                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div>

                            <p class="font-semibold text-slate-800">
                                {{ ucwords(strtolower($cliente['nombre'] ?: 'Sin cliente')) }}
                            </p>

                            <p class="mt-1 text-xs text-slate-800">
                                {{ $cliente['documento'] ?: 'Sin documento' }}
                            </p>

                        </div>

                        <span class="font-bold text-red-600">
                            S/ {{ number_format($cliente['deuda'], 2) }}
                        </span>

                    </div>

                    <div class="mt-3 flex items-center justify-between text-xs text-slate-700">

                        <span>
                            <i class="fa-solid fa-phone mr-1"></i>
                            {{ $cliente['telefono'] ?: '-' }}
                        </span>

                        <span>
                            Vence:
                            {{ $cliente['vencimiento']
                                ? \Carbon\Carbon::parse($cliente['vencimiento'])->format('d/m/Y')
                                : '-'
                            }}
                        </span>

                    </div>

                </div>

            @empty

                <div class="p-8 text-center text-sm text-slate-800">
                    No hay clientes morosos.
                </div>

            @endforelse

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ÚLTIMOS PAGOS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 p-5">

            <h3 class="text-lg font-bold text-slate-800">
                Últimos pagos
            </h3>

            <p class="text-sm text-slate-700">
                Últimos cobros registrados en el período.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Cliente
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Plan
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Fecha
                        </th>

                        <th class="px-5 py-3 text-xs font-bold uppercase tracking-wide text-slate-700">
                            Método
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-700">
                            Cobrado
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($ultimosPagos as $pago)

                        @php

                            $nombre = trim(
                                ($pago->cliente->nombres ?? '') .
                                ' ' .
                                ($pago->cliente->apellidos ?? '')
                            );

                            $primerNombre = $nombre !== ''
                                ? explode(' ', $nombre)[0]
                                : 'Cliente';

                        @endphp

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4">

                                <span class="font-semibold text-slate-800">
                                    {{ ucwords(strtolower($primerNombre)) }}
                                </span>

                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">

                                {{ $pago->clientePlan?->plan?->nombre ?? 'Sin plan' }}

                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">

                                {{ $pago->fecha_pago
                                    ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i')
                                    : '-'
                                }}

                            </td>

                            <td class="px-5 py-4">

                                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize text-slate-600">
                                    {{ $pago->metodo_pago ?? 'Sin especificar' }}
                                </span>

                            </td>

                            <td class="px-5 py-4 text-right">

                                <span class="font-bold text-emerald-600">
                                    S/ {{ number_format($pago->monto_pagado, 2) }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-5 py-12 text-center text-sm text-slate-800"
                            >
                                No hay pagos registrados.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RECORDATORIOS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Recordatorios enviados
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $recordatoriosEnviados }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>

            </div>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-700">
                        Recordatorios WhatsApp
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $recordatoriosWhatsapp }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-brands fa-whatsapp text-xl"></i>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- CHART.JS --}}
{{-- ============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DATOS DESDE LARAVEL
    |--------------------------------------------------------------------------
    */

    const ingresos = @json($graficoIngresos);

    const clientesPlanes = @json(
        $clientesPorPlan->map(function ($plan) {
            return [
                'nombre' => $plan->nombre,
                'cantidad' => (int) $plan->clientes_count,
            ];
        })->values()
    );


    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE INGRESOS
    |--------------------------------------------------------------------------
    */

    const canvasIngresos =
        document.getElementById('graficoIngresos');

    if (canvasIngresos) {

        new Chart(canvasIngresos, {

            type: 'line',

            data: {

                labels: ingresos.map(item => item.fecha),

                datasets: [{

                    label: 'Ingresos',

                    data: ingresos.map(item => item.total),

                    borderWidth: 2,

                    tension: 0.35,

                    fill: true,

                    pointRadius: 3,

                    pointHoverRadius: 5

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return 'S/ ' +
                                    Number(
                                        context.raw
                                    ).toFixed(2);

                            }

                        }

                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function (value) {

                                return 'S/ ' +
                                    Number(value).toFixed(0);

                            }

                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ESTADOS DE PAGOS
    |--------------------------------------------------------------------------
    */

    const canvasEstados =
        document.getElementById('graficoEstados');

    if (canvasEstados) {

        new Chart(canvasEstados, {

            type: 'doughnut',

            data: {

                labels: [
                    'Pagados',
                    'Pendientes',
                    'Vencidos',
                    'Anulados'
                ],

                datasets: [{

                    data: [
                        {{ $pagosPagados }},
                        {{ $pagosPendientes }},
                        {{ $pagosVencidos }},
                        {{ $pagosAnulados }}
                    ],

                    borderWidth: 0

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '65%',

                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CLIENTES POR PLAN
    |--------------------------------------------------------------------------
    */

    const canvasPlanes =
        document.getElementById('graficoPlanes');

    if (canvasPlanes) {

        new Chart(canvasPlanes, {

            type: 'bar',

            data: {

                labels: clientesPlanes.map(
                    item => item.nombre
                ),

                datasets: [{

                    label: 'Clientes',

                    data: clientesPlanes.map(
                        item => item.cantidad
                    ),

                    borderRadius: 8,

                    borderWidth: 0

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        }

                    }

                }

            }

        });

    }

});

</script>


{{-- ============================================================= --}}
{{-- ESTILOS DE IMPRESIÓN --}}
{{-- ============================================================= --}}

<style>

@media print {

    body {
        background: white !important;
    }

    aside,
    nav,
    header,
    button,
    form,
    .no-print {
        display: none !important;
    }

    main {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .shadow-sm {
        box-shadow: none !important;
    }

}

</style>

@endsection