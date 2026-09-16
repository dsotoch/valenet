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
                    1,248
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                <i class="fa-solid fa-users text-xl"></i>
            </div>

        </div>

        <p class="mt-4 text-xs text-emerald-600">
            <i class="fa-solid fa-arrow-trend-up mr-1"></i>
            8.4% este mes
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
                    8
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <i class="fa-solid fa-wifi text-xl"></i>
            </div>

        </div>

        <p class="mt-4 text-xs text-slate-800">
            <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i>
            Planes disponibles
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
                    S/ 42,580
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="fa-solid fa-money-bill-wave text-xl"></i>
            </div>

        </div>

        <p class="mt-4 text-xs text-emerald-600">
            <i class="fa-solid fa-arrow-trend-up mr-1"></i>
            12.6% respecto al mes anterior
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
                    37
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>

        </div>

        <p class="mt-4 text-xs text-red-600">
            <i class="fa-solid fa-circle-exclamation mr-1"></i>
            Requieren seguimiento
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

            <button class="text-sm font-medium text-cyan-600 hover:text-cyan-700">
                Ver todo
                <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
            </button>

        </div>


        <div class="mt-6 divide-y divide-slate-100">

            {{-- CLIENTE --}}
            <div class="flex items-center gap-4 py-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-cyan-50 text-cyan-600">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800">
                        Nuevo cliente registrado
                    </p>

                    <p class="text-xs text-slate-800">
                        Juan Pérez
                    </p>
                </div>

                <span class="text-xs text-slate-400">
                    Hace 10 min
                </span>

            </div>


            {{-- PAGO --}}
            <div class="flex items-center gap-4 py-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-credit-card"></i>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800">
                        Pago registrado
                    </p>

                    <p class="text-xs text-slate-800">
                        S/ 89.90
                    </p>
                </div>

                <span class="text-xs text-slate-400">
                    Hace 25 min
                </span>

            </div>


            {{-- PLAN --}}
            <div class="flex items-center gap-4 py-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <i class="fa-solid fa-wifi"></i>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800">
                        Nuevo plan creado
                    </p>

                    <p class="text-xs text-slate-800">
                        Fibra 600 Mbps
                    </p>
                </div>

                <span class="text-xs text-slate-400">
                    Hace 1 hora
                </span>

            </div>

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
                        78%
                    </span>

                </div>

                <div class="h-2 rounded-full bg-slate-100">
                    <div class="h-2 w-[78%] rounded-full bg-emerald-500"></div>
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
                        15%
                    </span>

                </div>

                <div class="h-2 rounded-full bg-slate-100">
                    <div class="h-2 w-[15%] rounded-full bg-amber-500"></div>
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
                        7%
                    </span>

                </div>

                <div class="h-2 rounded-full bg-slate-100">
                    <div class="h-2 w-[7%] rounded-full bg-red-500"></div>
                </div>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
