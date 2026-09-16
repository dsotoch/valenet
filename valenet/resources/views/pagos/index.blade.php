@extends('layouts.dashboard')

@section('title', 'Pagos')

@section('header-title', 'Pagos')

@section('content')

<div class="space-y-6">

{{-- ========================================================= --}}
{{-- ENCABEZADO --}}
{{-- ========================================================= --}}

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>

        <h1 class="text-2xl font-bold text-slate-800">
            Pagos
        </h1>

        <p class="mt-1 text-sm text-slate-800">
            Gestiona los pagos, planes y cobranza de tus clientes.
        </p>

    </div>


    <button
        type="button"
        onclick="abrirModalAsignarPlan()"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">

        <i class="fa-solid fa-user-plus"></i>

        Asignar plan

    </button>

</div>


{{-- ========================================================= --}}
{{-- MENSAJE --}}
{{-- ========================================================= --}}

@if(session('success'))

<div
    id="mensaje-exito"
    class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">

    <i class="fa-solid fa-circle-check text-lg"></i>

    <span>
        {{ session('success') }}
    </span>

    <button
        type="button"
        onclick="document.getElementById('mensaje-exito').remove()"
        class="ml-auto text-emerald-600 hover:text-emerald-800">

        <i class="fa-solid fa-xmark"></i>

    </button>

</div>

@endif


{{-- ========================================================= --}}
{{-- ERRORES --}}
{{-- ========================================================= --}}

@if($errors->any())

<div class="rounded-xl border border-red-200 bg-red-50 p-4">

    <div class="flex items-center gap-2 font-semibold text-red-700">

        <i class="fa-solid fa-circle-exclamation"></i>

        <span>
            Revisa los siguientes errores:
        </span>

    </div>


    <ul class="mt-2 list-disc space-y-1 pl-6 text-sm text-red-600">

        @foreach($errors->all() as $error)

        <li>
            {{ $error }}
        </li>

        @endforeach

    </ul>

</div>

@endif


{{-- ========================================================= --}}
{{-- ESTADÍSTICAS --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


    {{-- Pendientes --}}

    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-amber-700">
                    Pendientes
                </p>

                <p class="mt-1 text-2xl font-bold text-amber-800">
                    S/ {{ number_format($pendientesMonto, 2) }}
                </p>

                <p class="mt-1 text-xs text-amber-700">
                    {{ $pendientesCantidad }}
                    {{ $pendientesCantidad === 1 ? 'pago' : 'pagos' }}
                </p>

            </div>


            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-amber-500 shadow-sm">

                <i class="fa-solid fa-clock text-lg"></i>

            </div>

        </div>

    </div>


    {{-- Vencidos --}}

    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-red-700">
                    Vencidos
                </p>

                <p class="mt-1 text-2xl font-bold text-red-800">
                    S/ {{ number_format($vencidosMonto, 2) }}
                </p>

                <p class="mt-1 text-xs text-red-700">
                    {{ $vencidosCantidad }}
                    {{ $vencidosCantidad === 1 ? 'pago' : 'pagos' }}
                </p>

            </div>


            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-red-500 shadow-sm">

                <i class="fa-solid fa-triangle-exclamation text-lg"></i>

            </div>

        </div>

    </div>


    {{-- Cobrado --}}

    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-emerald-700">
                    Cobrado este mes
                </p>

                <p class="mt-1 text-2xl font-bold text-emerald-800">
                    S/ {{ number_format($pagadosMonto, 2) }}
                </p>

                <p class="mt-1 text-xs text-emerald-700">
                    {{ $pagadosCantidad }}
                    {{ $pagadosCantidad === 1 ? 'pago' : 'pagos' }}
                </p>

            </div>


            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-500 shadow-sm">

                <i class="fa-solid fa-circle-check text-lg"></i>

            </div>

        </div>

    </div>


    {{-- Vence hoy --}}

    <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-cyan-700">
                    Vence hoy
                </p>

                <p class="mt-1 text-2xl font-bold text-cyan-800">
                    S/ {{ number_format($venceHoyMonto, 2) }}
                </p>

                <p class="mt-1 text-xs text-cyan-700">
                    {{ $venceHoyCantidad }}
                    {{ $venceHoyCantidad === 1 ? 'cliente' : 'clientes' }}
                </p>

            </div>


            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-cyan-500 shadow-sm">

                <i class="fa-solid fa-calendar-day text-lg"></i>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FILTROS --}}
{{-- ========================================================= --}}

<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    <form
        method="GET"
        action="{{ route('pagos.index') }}"
        class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">


        {{-- Buscar --}}

        <div class="relative xl:col-span-2">

            <i
                class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-800">
            </i>

            <input
                type="text"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="Buscar cliente o plan..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-800 focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

        </div>


        {{-- Estado --}}

        <select
            name="estado"
            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

            <option value="">
                Todos los estados
            </option>

            <option
                value="pendiente"
                @selected($estado === 'pendiente')>
                Pendientes
            </option>

            <option
                value="vencido"
                @selected($estado === 'vencido')>
                Vencidos
            </option>

            <option
                value="pagado"
                @selected($estado === 'pagado')>
                Pagados
            </option>

            <option
                value="anulado"
                @selected($estado === 'anulado')>
                Anulados
            </option>

        </select>


        {{-- Plan --}}

        <select
            name="plan_id"
            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

            <option value="">
                Todos los planes
            </option>

            @foreach($planes as $plan)

            <option
                value="{{ $plan->id }}"
                @selected((string) $planId === (string) $plan->id)>

                {{ $plan->nombre }}

            </option>

            @endforeach

        </select>


        {{-- Periodo --}}

        <input
            type="month"
            name="periodo"
            value="{{ $periodo }}"
            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">


        <div class="flex gap-2 md:col-span-2 xl:col-span-5">

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">

                <i class="fa-solid fa-filter"></i>

                Buscar

            </button>


            @if($buscar || $estado || $planId || $periodo)

            <a
                href="{{ route('pagos.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">

                <i class="fa-solid fa-rotate-left"></i>

                Limpiar

            </a>

            @endif

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- TABLA --}}
{{-- ========================================================= --}}

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


    {{-- Cabecera --}}

    <div class="border-b border-slate-100 px-5 py-4">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-slate-800">
                    Lista de pagos
                </h2>

                <p class="mt-1 text-xs text-slate-800">

                    {{ $pagos->total() }}

                    {{ $pagos->total() === 1 ? 'pago registrado' : 'pagos registrados' }}

                </p>

            </div>


            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500">

                <i class="fa-solid fa-money-bill-wave"></i>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="hidden overflow-x-auto md:block">

        <table class="w-full text-left">

            <thead class="bg-slate-50">

                <tr class="border-b border-slate-100">

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Cliente
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Plan
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Vencimiento
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Monto
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Estado
                    </th>

                    <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($pagos as $pago)

                <tr class="transition hover:bg-slate-50">


                    {{-- Cliente --}}

                    <td class="px-5 py-4">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                                <i class="fa-solid fa-user"></i>

                            </div>


                            <div>

                                <p class="font-semibold text-slate-800">

                                    {{ $pago->cliente->nombre ?? 'Sin cliente' }}

                                </p>

                                <p class="text-xs text-slate-800">

                                    {{ $pago->cliente->telefono ?? 'Sin teléfono' }}

                                </p>

                            </div>

                        </div>

                    </td>


                    {{-- Plan --}}

                    <td class="px-5 py-4">

                        @if($pago->clientePlan?->plan)

                        <p class="font-semibold text-slate-700">

                            {{ $pago->clientePlan->plan->nombre }}

                        </p>

                        <p class="text-xs text-slate-800">

                            {{ $pago->clientePlan->plan->velocidad }}

                        </p>

                        @else

                        <span class="text-sm text-slate-800">
                            Sin plan
                        </span>

                        @endif

                    </td>


                    {{-- Vencimiento --}}

                    <td class="px-5 py-4">

                        <p class="text-sm font-semibold text-slate-700">

                            {{ $pago->fecha_vencimiento?->format('d/m/Y') }}

                        </p>

                        <p class="text-xs text-slate-800">

                            {{ $pago->periodo?->format('F Y') }}

                        </p>

                    </td>


                    {{-- Monto --}}

                    <td class="px-5 py-4">

                        <p class="text-base font-bold text-slate-800">

                            S/
                            {{ number_format($pago->monto, 2) }}

                        </p>

                        @if($pago->estado !== 'pagado')

                        <p class="text-xs text-red-500">

                            Pendiente:
                            S/
                            {{ number_format(
                                max(
                                    0,
                                    $pago->monto - $pago->monto_pagado
                                ),
                                2
                            ) }}

                        </p>

                        @endif

                    </td>


                    {{-- Estado --}}

                    <td class="px-5 py-4">

                        @if($pago->estado === 'pagado')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                            Pagado

                        </span>


                        @elseif($pago->estado === 'vencido')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                            Vencido

                        </span>


                        @elseif($pago->estado === 'anulado')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>

                            Anulado

                        </span>


                        @else

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                            Pendiente

                        </span>

                        @endif

                    </td>


                    {{-- Acciones --}}

                    <td class="px-5 py-4">

                        <div class="flex justify-end gap-1">


                            {{-- Registrar pago --}}

                            @if(
                                $pago->estado === 'pendiente' ||
                                $pago->estado === 'vencido'
                            )

                            <button
                                type="button"
                                onclick='abrirModalPago(@js($pago))'
                                title="Registrar pago"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-emerald-600 transition hover:bg-emerald-50">

                                <i class="fa-solid fa-money-bill-wave"></i>

                            </button>

                            @endif


                            {{-- WhatsApp --}}

                            @if(
                                $pago->estado === 'pendiente' ||
                                $pago->estado === 'vencido'
                            )

                            <a
                                href="{{ route('pagos.whatsapp', $pago) }}"
                                title="Enviar recordatorio por WhatsApp"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-green-600 transition hover:bg-green-50">

                                <i class="fa-brands fa-whatsapp"></i>

                            </a>

                            @endif


                            {{-- Editar --}}

                            <button
                                type="button"
                                onclick='abrirModalEditarPago(@js($pago))'
                                title="Editar"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100">

                                <i class="fa-solid fa-pen"></i>

                            </button>


                            {{-- Anular --}}

                            @if($pago->estado !== 'anulado')

                            <button
                                type="button"
                                onclick="anularPago('{{ $pago->id }}')"
                                title="Anular"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50">

                                <i class="fa-solid fa-ban"></i>

                            </button>

                            @endif

                        </div>

                    </td>

                </tr>


                @empty

                <tr>

                    <td colspan="6" class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-800">

                            <i class="fa-solid fa-money-bill-wave text-2xl"></i>

                        </div>

                        <h3 class="mt-4 font-semibold text-slate-700">
                            No hay pagos
                        </h3>

                        <p class="mt-1 text-sm text-slate-800">
                            Todavía no existen pagos registrados.
                        </p>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- MOBILE --}}
    {{-- ===================================================== --}}

    <div class="divide-y divide-slate-100 md:hidden">

        @forelse($pagos as $pago)

        <div class="p-4">

            <div class="flex items-start gap-3">


                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                    <i class="fa-solid fa-user"></i>

                </div>


                <div class="min-w-0 flex-1">


                    <div class="flex items-start justify-between gap-2">

                        <div>

                            <h3 class="font-semibold text-slate-800">

                                {{ $pago->cliente->nombre ?? 'Sin cliente' }}

                            </h3>

                            <p class="mt-0.5 text-xs text-slate-800">

                                {{ $pago->clientePlan?->plan?->nombre ?? 'Sin plan' }}

                            </p>

                        </div>


                        @if($pago->estado === 'pagado')

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600">
                            Pagado
                        </span>

                        @elseif($pago->estado === 'vencido')

                        <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                            Vencido
                        </span>

                        @elseif($pago->estado === 'anulado')

                        <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                            Anulado
                        </span>

                        @else

                        <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-600">
                            Pendiente
                        </span>

                        @endif

                    </div>


                    <div class="mt-3 space-y-2">

                        <p class="text-sm font-bold text-slate-800">

                            <i class="fa-solid fa-money-bill-wave mr-2 w-4 text-slate-800"></i>

                            S/
                            {{ number_format($pago->monto, 2) }}

                        </p>


                        <p class="text-sm text-slate-800">

                            <i class="fa-solid fa-calendar mr-2 w-4"></i>

                            Vence:

                            {{ $pago->fecha_vencimiento?->format('d/m/Y') }}

                        </p>


                        <p class="text-sm text-slate-800">

                            <i class="fa-solid fa-gauge-high mr-2 w-4"></i>

                            {{ $pago->clientePlan?->plan?->velocidad ?? 'Sin plan' }}

                        </p>

                    </div>


                    <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">


                        @if(
                            $pago->estado === 'pendiente' ||
                            $pago->estado === 'vencido'
                        )

                        <button
                            type="button"
                            onclick='abrirModalPago(@js($pago))'
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-emerald-100 py-2 text-xs font-semibold text-emerald-600 hover:bg-emerald-50">

                            <i class="fa-solid fa-money-bill-wave"></i>

                            Pagar

                        </button>


                        <a
                            href="{{ route('pagos.whatsapp', $pago) }}"
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-green-100 py-2 text-xs font-semibold text-green-600 hover:bg-green-50">

                            <i class="fa-brands fa-whatsapp"></i>

                            WhatsApp

                        </a>

                        @endif


                        <button
                            type="button"
                            onclick='abrirModalEditarPago(@js($pago))'
                            class="flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">

                            <i class="fa-solid fa-pen"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="px-5 py-16 text-center">

            <i class="fa-solid fa-money-bill-wave text-3xl text-slate-300"></i>

            <p class="mt-3 font-semibold text-slate-600">
                No hay pagos registrados
            </p>

        </div>

        @endforelse

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINACIÓN --}}
    {{-- ========================================================= --}}

    @if($pagos->hasPages())

    <div class="border-t border-slate-100 px-5 py-4">

        {{ $pagos->links() }}

    </div>

    @endif

</div>

</div>


{{-- ============================================================= --}}
{{-- MODAL ASIGNAR PLAN --}}
{{-- ============================================================= --}}

<div
    id="modal-asignar-plan"
    class="fixed inset-0 z-[100] hidden">

    <div
        class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
        onclick="cerrarModal('modal-asignar-plan')">
    </div>


    <div class="relative flex min-h-full items-center justify-center p-4">

        <div class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Asignar plan
                    </h2>

                    <p class="mt-0.5 text-sm text-slate-800">
                        Asigna un plan de internet a un cliente.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="cerrarModal('modal-asignar-plan')"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>


            <form
                method="POST"
                action="{{ route('pagos.asignar-plan') }}"
                class="p-6">

                @csrf


                {{-- Cliente --}}

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Cliente

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="cliente_id"
                        id="asignar_cliente_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                        <option value="">
                            Selecciona un cliente
                        </option>

                        @foreach($clientes as $cliente)

                        <option
                            value="{{ $cliente->id }}">

                            {{ $cliente->nombres }}

                            @if($cliente->telefono)
                                — {{ $cliente->telefono }}
                            @endif

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Plan --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Plan

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="plan_id"
                        id="asignar_plan_id"
                        required
                        onchange="mostrarPrecioPlan()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                        <option value="">
                            Selecciona un plan
                        </option>

                        @foreach($planes as $plan)

                        <option
                            value="{{ $plan->id }}"
                            data-precio="{{ $plan->precio }}">

                            {{ $plan->nombre }}
                            —
                            {{ $plan->velocidad }}
                            —
                            S/ {{ number_format($plan->precio, 2) }}

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- Precio --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Precio mensual
                    </label>

                    <div
                        id="precio-plan-mostrado"
                        class="rounded-xl border border-cyan-100 bg-cyan-50 px-4 py-3 text-lg font-bold text-cyan-700">

                        S/ 0.00

                    </div>

                </div>


                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">


                    {{-- Inicio --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Fecha de inicio

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="date"
                            name="fecha_inicio"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                    </div>


                    {{-- Vencimiento --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Primer vencimiento

                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="date"
                            name="fecha_vencimiento"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                    </div>

                </div>


                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="cerrarModal('modal-asignar-plan')"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-600">

                        <i class="fa-solid fa-link"></i>

                        Asignar plan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- MODAL REGISTRAR PAGO --}}
{{-- ============================================================= --}}

<div
    id="modal-pago"
    class="fixed inset-0 z-[110] hidden">

    <div
        class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
        onclick="cerrarModal('modal-pago')">
    </div>


    <div class="relative flex min-h-full items-center justify-center p-4">

        <div class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Registrar pago
                    </h2>

                    <p
                        id="pago-cliente-texto"
                        class="mt-0.5 text-sm text-slate-800">
                    </p>

                </div>


                <button
                    type="button"
                    onclick="cerrarModal('modal-pago')"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>


            <form
                id="form-registrar-pago"
                method="POST"
                class="p-6">

                @csrf


                <div class="rounded-xl bg-slate-50 p-4">

                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <p class="text-xs text-slate-500">
                                Plan
                            </p>

                            <p
                                id="pago-plan"
                                class="mt-1 font-semibold text-slate-700">
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-slate-500">
                                Monto
                            </p>

                            <p
                                id="pago-monto"
                                class="mt-1 text-lg font-bold text-cyan-600">
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Monto --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Monto pagado

                        <span class="text-red-500">*</span>

                    </label>

                    <input
                        type="number"
                        id="monto_pagado"
                        name="monto_pagado"
                        step="0.01"
                        min="0"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                </div>


                {{-- Fecha --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Fecha de pago

                        <span class="text-red-500">*</span>

                    </label>

                    <input
                        type="date"
                        name="fecha_pago"
                        value="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                </div>


                {{-- Método --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Método de pago

                        <span class="text-red-500">*</span>

                    </label>

                    <select
                        name="metodo_pago"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                        <option value="">
                            Selecciona
                        </option>

                        <option value="yape">
                            Yape
                        </option>

                        <option value="plin">
                            Plin
                        </option>

                        <option value="transferencia">
                            Transferencia
                        </option>

                        <option value="efectivo">
                            Efectivo
                        </option>

                        <option value="deposito">
                            Depósito
                        </option>

                        <option value="otro">
                            Otro
                        </option>

                    </select>

                </div>


                {{-- Referencia --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Referencia
                    </label>

                    <input
                        type="text"
                        name="referencia"
                        maxlength="100"
                        placeholder="Ej. número de operación"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                </div>


                {{-- Observación --}}

                <div class="mt-5">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Observación
                    </label>

                    <textarea
                        name="observacion"
                        rows="3"
                        maxlength="1000"
                        class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"></textarea>

                </div>


                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="cerrarModal('modal-pago')"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-600">

                        <i class="fa-solid fa-check"></i>

                        Registrar pago

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- MODAL EDITAR PAGO --}}
{{-- ============================================================= --}}

<div
    id="modal-editar-pago"
    class="fixed inset-0 z-[110] hidden">

    <div
        class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
        onclick="cerrarModal('modal-editar-pago')">
    </div>


    <div class="relative flex min-h-full items-center justify-center p-4">

        <div class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        Editar pago
                    </h2>

                    <p class="mt-0.5 text-sm text-slate-800">
                        Modifica la información de la mensualidad.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="cerrarModal('modal-editar-pago')"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>


            <form
                id="form-editar-pago"
                method="POST"
                class="p-6">

                @csrf

                @method('PUT')


                <div class="space-y-5">


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Cliente
                        </label>

                        <input
                            type="text"
                            id="editar-cliente"
                            readonly
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-600">

                    </div>


                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Fecha de vencimiento
                            </label>

                            <input
                                type="date"
                                id="editar-vencimiento"
                                name="fecha_vencimiento"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Monto
                            </label>

                            <input
                                type="number"
                                id="editar-monto"
                                name="monto"
                                step="0.01"
                                min="0"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                        </div>

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Estado
                        </label>

                        <select
                            id="editar-estado"
                            name="estado"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                            <option value="pendiente">
                                Pendiente
                            </option>

                            <option value="pagado">
                                Pagado
                            </option>

                            <option value="vencido">
                                Vencido
                            </option>

                            <option value="anulado">
                                Anulado
                            </option>

                        </select>

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Observación
                        </label>

                        <textarea
                            id="editar-observacion"
                            name="observacion"
                            rows="4"
                            maxlength="1000"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"></textarea>

                    </div>

                </div>


                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="cerrarModal('modal-editar-pago')"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-600">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- FORMULARIO ANULAR --}}
{{-- ============================================================= --}}

<form
    id="form-anular-pago"
    method="POST"
    class="hidden">

    @csrf

    @method('DELETE')

</form>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

@push('scripts')

<script>

    function cerrarModal(id) {

        const modal =
            document.getElementById(id);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');

        document.body.classList.remove(
            'overflow-hidden'
        );
    }


    function abrirModalAsignarPlan() {

        const modal =
            document.getElementById(
                'modal-asignar-plan'
            );

        modal.classList.remove('hidden');

        document.body.classList.add(
            'overflow-hidden'
        );

    }


    function mostrarPrecioPlan() {

        const select =
            document.getElementById(
                'asignar_plan_id'
            );

        const option =
            select.options[
                select.selectedIndex
            ];

        const precio =
            option?.dataset?.precio ?? '0';

        document.getElementById(
            'precio-plan-mostrado'
        ).textContent =
            `S/ ${parseFloat(precio).toFixed(2)}`;

    }


    function abrirModalPago(pago) {

        const modal =
            document.getElementById(
                'modal-pago'
            );

        const formulario =
            document.getElementById(
                'form-registrar-pago'
            );


        formulario.action =
            `/pagos/${pago.id}/registrar`;


        const cliente =
            pago.cliente?.nombre ??
            'Cliente';


        const plan =
            pago.cliente_plan?.plan?.nombre ??
            'Sin plan';


        document.getElementById(
            'pago-cliente-texto'
        ).textContent =
            cliente;


        document.getElementById(
            'pago-plan'
        ).textContent =
            plan;


        document.getElementById(
            'pago-monto'
        ).textContent =
            `S/ ${parseFloat(pago.monto).toFixed(2)}`;


        const pendiente =
            Math.max(
                0,
                parseFloat(pago.monto) -
                parseFloat(pago.monto_pagado ?? 0)
            );


        document.getElementById(
            'monto_pagado'
        ).value =
            pendiente.toFixed(2);


        modal.classList.remove('hidden');

        document.body.classList.add(
            'overflow-hidden'
        );

    }


    function abrirModalEditarPago(pago) {

        const modal =
            document.getElementById(
                'modal-editar-pago'
            );


        const formulario =
            document.getElementById(
                'form-editar-pago'
            );


        formulario.action =
            `/pagos/${pago.id}`;


        document.getElementById(
            'editar-cliente'
        ).value =
            pago.cliente?.nombre ??
            'Sin cliente';


        document.getElementById(
            'editar-vencimiento'
        ).value =
            pago.fecha_vencimiento ??
            '';


        document.getElementById(
            'editar-monto'
        ).value =
            pago.monto ??
            '';


        document.getElementById(
            'editar-estado'
        ).value =
            pago.estado ??
            'pendiente';


        document.getElementById(
            'editar-observacion'
        ).value =
            pago.observacion ??
            '';


        modal.classList.remove('hidden');

        document.body.classList.add(
            'overflow-hidden'
        );

    }


    function anularPago(id) {

        const confirmar =
            confirm(
                '¿Estás seguro de anular este pago?'
            );


        if (!confirmar) {
            return;
        }


        const formulario =
            document.getElementById(
                'form-anular-pago'
            );


        formulario.action =
            `/pagos/${id}`;


        formulario.submit();

    }


    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key !== 'Escape') {
                return;
            }


            [
                'modal-asignar-plan',
                'modal-pago',
                'modal-editar-pago'
            ].forEach(function(id) {

                cerrarModal(id);

            });

        }
    );

</script>

@endpush

@endsection