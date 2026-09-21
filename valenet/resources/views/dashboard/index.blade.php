@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('header-title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- BIENVENIDA --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Bienvenido al sistema
        </h1>

        <p class="mt-1 text-sm text-slate-800">
            Aquí tienes un resumen de la gestión de tu ISP.
        </p>
    </div>


    {{-- INDICADORES --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- CLIENTES --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-800">
                        Clientes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($totalClientes) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>

            </div>

            <p class="mt-4 text-xs text-emerald-600">
                <i class="fa-solid fa-user-plus mr-1"></i>

                {{ number_format($clientesNuevos) }}

                nuevos este mes
            </p>

        </div>


        {{-- PLANES --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-800">
                        Planes activos
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($planesActivos) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="fa-solid fa-wifi text-xl"></i>
                </div>

            </div>

            <p class="mt-4 text-xs text-slate-800">
                <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i>

                {{ number_format($totalPlanes) }}

                planes registrados
            </p>

        </div>


        {{-- PAGOS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-800">
                        Pagos del mes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        S/
                        {{ number_format($montoPagosMes, 2) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-money-bill-wave text-xl"></i>
                </div>

            </div>

            <p class="mt-4 text-xs
                {{ $variacionPagos >= 0
                    ? 'text-emerald-600'
                    : 'text-red-600'
                }}">

                <i class="fa-solid
                    {{ $variacionPagos >= 0
                        ? 'fa-arrow-trend-up'
                        : 'fa-arrow-trend-down'
                    }} mr-1">
                </i>

                {{ number_format(abs($variacionPagos), 1) }}%

                respecto al mes anterior

            </p>

        </div>


        {{-- VENCIDOS --}}
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-800">
                        Pagos vencidos
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($pagosVencidos) }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>

            </div>

            <p class="mt-4 text-xs text-red-600">

                <i class="fa-solid fa-circle-exclamation mr-1"></i>

                S/
                {{ number_format($montoVencido, 2) }}

                pendiente

            </p>

        </div>

    </div>


    {{-- CONTENIDO --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


        {{-- ACTIVIDAD --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 xl:col-span-2">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="font-bold text-slate-900">
                        Actividad reciente
                    </h3>

                    <p class="mt-1 text-sm text-slate-800">
                        Últimos movimientos del sistema
                    </p>
                </div>

                <a
                    href="{{ route('reportes.index') }}"
                    class="text-sm font-medium text-cyan-600 hover:text-cyan-700"
                >
                    Ver reportes

                    <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>

            </div>


            <div class="mt-6 divide-y divide-slate-100">

                @forelse($actividades as $actividad)

                    <div class="flex items-center gap-4 py-4">

                        {{-- ICONO --}}
                        <div
                            @class([
                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',

                                'bg-cyan-50 text-cyan-600'
                                    => $actividad['tipo'] === 'cliente',

                                'bg-emerald-50 text-emerald-600'
                                    => $actividad['tipo'] === 'pago',

                                'bg-indigo-50 text-indigo-600'
                                    => $actividad['tipo'] === 'plan',
                            ])
                        >

                            @if($actividad['tipo'] === 'cliente')

                                <i class="fa-solid fa-user-plus"></i>

                            @elseif($actividad['tipo'] === 'pago')

                                <i class="fa-solid fa-credit-card"></i>

                            @elseif($actividad['tipo'] === 'plan')

                                <i class="fa-solid fa-wifi"></i>

                            @endif

                        </div>


                        {{-- INFORMACIÓN --}}
                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-slate-800">
                                {{ $actividad['titulo'] }}
                            </p>

                            <p class="truncate text-xs text-slate-800">
                                {{ $actividad['descripcion'] }}
                            </p>

                        </div>


                        {{-- FECHA --}}
                        <span class="shrink-0 text-xs text-slate-700">

                            {{ $actividad['fecha']?->diffForHumans() }}

                        </span>

                    </div>

                @empty

                    <div class="py-8 text-center">

                        <i class="fa-solid fa-clock-rotate-left text-3xl text-slate-300"></i>

                        <p class="mt-3 text-sm text-slate-700">
                            No hay actividad reciente.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- RESUMEN --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">

                    <i class="fa-solid fa-chart-pie"></i>

                </div>

                <div>

                    <h3 class="font-bold text-slate-900">
                        Resumen de cobranza
                    </h3>

                    <p class="mt-1 text-sm text-slate-800">
                        Estado de los pagos
                    </p>

                </div>

            </div>


            <div class="mt-6 space-y-5">


                {{-- PAGADOS --}}
                <div>

                    <div class="mb-2 flex justify-between text-sm">

                        <span class="text-slate-800">

                            <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i>

                            Pagados

                        </span>

                        <span class="font-semibold">
                            {{ $porcentajePagados }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-100">

                        <div
                            class="h-2 rounded-full bg-emerald-500"
                            style="width: {{ $porcentajePagados }}%"
                        ></div>

                    </div>

                </div>


                {{-- PENDIENTES --}}
                <div>

                    <div class="mb-2 flex justify-between text-sm">

                        <span class="text-slate-800">

                            <i class="fa-solid fa-clock mr-1 text-amber-500"></i>

                            Pendientes

                        </span>

                        <span class="font-semibold">
                            {{ $porcentajePendientes }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-100">

                        <div
                            class="h-2 rounded-full bg-amber-500"
                            style="width: {{ $porcentajePendientes }}%"
                        ></div>

                    </div>

                </div>


                {{-- VENCIDOS --}}
                <div>

                    <div class="mb-2 flex justify-between text-sm">

                        <span class="text-slate-800">

                            <i class="fa-solid fa-circle-exclamation mr-1 text-red-500"></i>

                            Vencidos

                        </span>

                        <span class="font-semibold">
                            {{ $porcentajeVencidos }}%
                        </span>

                    </div>

                    <div class="h-2 rounded-full bg-slate-100">

                        <div
                            class="h-2 rounded-full bg-red-500"
                            style="width: {{ $porcentajeVencidos }}%"
                        ></div>

                    </div>

                </div>


            </div>


            {{-- INFORMACIÓN ADICIONAL --}}
            <div class="mt-7 border-t border-slate-100 pt-5">

                <div class="flex items-center justify-between">

                    <span class="text-sm text-slate-700">
                        Total de pagos
                    </span>

                    <span class="font-semibold text-slate-900">
                        {{ number_format($totalPagosEstados) }}
                    </span>

                </div>

                <div class="mt-3 flex items-center justify-between">

                    <span class="text-sm text-slate-700">
                        Monto vencido
                    </span>

                    <span class="font-semibold text-red-600">
                        S/ {{ number_format($montoVencido, 2) }}
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection