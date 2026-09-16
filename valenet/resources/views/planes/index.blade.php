@extends('layouts.dashboard')

@section('title', 'Planes')

@section('header-title', 'Planes')

@section('content')

<div class="space-y-6">


{{-- ========================================================= --}}
{{-- ENCABEZADO --}}
{{-- ========================================================= --}}

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Planes
        </h1>

        <p class="mt-1 text-sm text-slate-800">
            Gestiona los planes de internet registrados en Valenet.
        </p>
    </div>

    <button
        type="button"
        onclick="abrirModalCrear()"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">
        <i class="fa-solid fa-plus"></i>
        Nuevo plan
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
        <span>Revisa los siguientes errores:</span>
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
{{-- FILTROS --}}
{{-- ========================================================= --}}

<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    <form
        method="GET"
        action="{{ route('planes.index') }}"
        class="flex flex-col gap-3 sm:flex-row">

        <div class="relative flex-1">

            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

            <input
                type="text"
                name="buscar"
                value="{{ $busqueda }}"
                placeholder="Buscar por nombre, velocidad o descripción..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-800 focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

        </div>

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">
            <i class="fa-solid fa-filter"></i>
            Buscar
        </button>

        @if($busqueda)

        <a
            href="{{ route('planes.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            <i class="fa-solid fa-rotate-left"></i>
            Limpiar
        </a>

        @endif

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
                    Lista de planes
                </h2>

                <p class="mt-1 text-xs text-slate-800">
                    {{ $planes->total() }}
                    {{ $planes->total() === 1 ? 'plan registrado' : 'planes registrados' }}
                </p>

            </div>

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500">
                <i class="fa-solid fa-wifi"></i>
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- TABLA DESKTOP --}}
    {{-- ===================================================== --}}

    <div class="hidden overflow-x-auto md:block">

        <table class="w-full text-left">

            <thead class="bg-slate-50">

                <tr class="border-b border-slate-100">

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Plan
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Velocidad
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Precio
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                        Descripción
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

                @forelse($planes as $plan)

                <tr class="transition hover:bg-slate-50">

                    {{-- Plan --}}

                    <td class="px-5 py-4">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                                <i class="fa-solid fa-wifi"></i>

                            </div>

                            <div>

                                <p class="font-semibold text-slate-800">
                                    {{ $plan->nombre }}
                                </p>

                                <p class="text-xs text-slate-800">
                                    Registrado {{ $plan->created_at?->format('d/m/Y') }}
                                </p>

                            </div>

                        </div>

                    </td>


                    {{-- Velocidad --}}

                    <td class="px-5 py-4">

                        <span class="inline-flex items-center gap-2 rounded-lg bg-cyan-50 px-3 py-1.5 text-sm font-semibold text-cyan-700">

                            <i class="fa-solid fa-gauge-high text-xs"></i>

                            {{ $plan->velocidad }}

                        </span>

                    </td>


                    {{-- Precio --}}

                    <td class="px-5 py-4">

                        <span class="text-base font-bold text-slate-800">

                            S/ {{ number_format($plan->precio, 2) }}

                        </span>

                        <span class="text-xs text-slate-800">
                            / mes
                        </span>

                    </td>


                    {{-- Descripción --}}

                    <td class="max-w-xs px-5 py-4">

                        @if($plan->descripcion)

                        <p class="truncate text-sm text-slate-600">
                            {{ $plan->descripcion }}
                        </p>

                        @else

                        <span class="text-sm text-slate-800">
                            Sin descripción
                        </span>

                        @endif

                    </td>


                    {{-- Estado --}}

                    <td class="px-5 py-4">

                        @if($plan->estado)

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                            Activo

                        </span>

                        @else

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">

                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                            Inactivo

                        </span>

                        @endif

                    </td>


                    {{-- Acciones --}}

                    <td class="px-5 py-4">

                        <div class="flex justify-end gap-2">

                            <button
                                type="button"
                                onclick='abrirModalEditar(@json($plan))'
                                title="Editar"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 transition hover:bg-cyan-50 hover:text-cyan-600">

                                <i class="fa-solid fa-pen"></i>

                            </button>


                            <button
                                type="button"
                                onclick="eliminarPlan('{{ $plan->id }}', '{{ addslashes($plan->nombre) }}')"
                                title="Eliminar"
                                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 transition hover:bg-red-50 hover:text-red-600">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-800">

                            <i class="fa-solid fa-wifi text-2xl"></i>

                        </div>

                        <h3 class="mt-4 font-semibold text-slate-700">
                            No hay planes
                        </h3>

                        <p class="mt-1 text-sm text-slate-800">
                            Todavía no tienes planes registrados.
                        </p>

                        <button
                            type="button"
                            onclick="abrirModalCrear()"
                            class="mt-4 text-sm font-semibold text-cyan-600 hover:text-cyan-700">
                            Registrar primer plan
                        </button>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- ===================================================== --}}
    {{-- TARJETAS MOBILE --}}
    {{-- ===================================================== --}}

    <div class="divide-y divide-slate-100 md:hidden">

        @forelse($planes as $plan)

        <div class="p-4">

            <div class="flex items-start gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                    <i class="fa-solid fa-wifi"></i>

                </div>

                <div class="min-w-0 flex-1">

                    <div class="flex items-start justify-between gap-2">

                        <div>

                            <h3 class="font-semibold text-slate-800">
                                {{ $plan->nombre }}
                            </h3>

                            <p class="mt-0.5 text-xs text-slate-800">
                                {{ $plan->velocidad }}
                            </p>

                        </div>

                        @if($plan->estado)

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600">
                            Activo
                        </span>

                        @else

                        <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                            Inactivo
                        </span>

                        @endif

                    </div>


                    <div class="mt-3 space-y-2">

                        <p class="text-sm font-bold text-slate-800">

                            <i class="fa-solid fa-money-bill-wave mr-2 w-4 text-slate-800"></i>

                            S/ {{ number_format($plan->precio, 2) }}

                            <span class="font-normal text-xs text-slate-800">
                                / mes
                            </span>

                        </p>


                        @if($plan->descripcion)

                        <p class="text-sm text-slate-800">

                            <i class="fa-solid fa-align-left mr-2 w-4 text-slate-800"></i>

                            {{ $plan->descripcion }}

                        </p>

                        @endif

                    </div>


                    <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">

                        <button
                            type="button"
                            onclick='abrirModalEditar(@json($plan))'
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-200 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">

                            <i class="fa-solid fa-pen"></i>

                            Editar

                        </button>


                        <button
                            type="button"
                            onclick="eliminarPlan('{{ $plan->id }}', '{{ addslashes($plan->nombre) }}')"
                            class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-red-100 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">

                            <i class="fa-solid fa-trash"></i>

                            Eliminar

                        </button>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="px-5 py-16 text-center">

            <i class="fa-solid fa-wifi text-3xl text-slate-300"></i>

            <p class="mt-3 font-semibold text-slate-600">
                No hay planes registrados
            </p>

        </div>

        @endforelse

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINACIÓN --}}
    {{-- ========================================================= --}}

    @if($planes->hasPages())

    <div class="border-t border-slate-100 px-5 py-4">

        {{ $planes->links() }}

    </div>

    @endif

</div>


</div>

{{-- ============================================================= --}}
{{-- MODAL CREAR / EDITAR --}}
{{-- ============================================================= --}}

<div
    id="modal-plan"
    class="fixed inset-0 z-[100] hidden">


{{-- Fondo --}}

<div
    id="modal-plan-overlay"
    class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
    onclick="cerrarModalPlan()"></div>


{{-- Contenedor --}}

<div class="relative flex min-h-full items-center justify-center p-4">

    <div
        class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">

        {{-- Header --}}

        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-4">

            <div>

                <h2
                    id="modal-titulo"
                    class="text-lg font-bold text-slate-800">

                    Nuevo plan

                </h2>

                <p
                    id="modal-descripcion"
                    class="mt-0.5 text-sm text-slate-800">

                    Registra un nuevo plan de internet.

                </p>

            </div>


            <button
                type="button"
                onclick="cerrarModalPlan()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100 hover:text-slate-700">

                <i class="fa-solid fa-xmark text-lg"></i>

            </button>

        </div>


        {{-- Formulario --}}

        <form
            id="form-plan"
            method="POST"
            action="{{ route('planes.store') }}"
            class="p-6">

            @csrf

            <input
                type="hidden"
                id="metodo-formulario"
                name="_method"
                value="">


            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Nombre --}}

                <div class="sm:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Nombre del plan

                        <span class="text-red-500">*</span>

                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-wifi absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            required
                            maxlength="100"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                            placeholder="Ej. Plan Hogar 300 Mbps">

                    </div>

                </div>


                {{-- Velocidad --}}

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Velocidad

                        <span class="text-red-500">*</span>

                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-gauge-high absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                        <input
                            type="text"
                            id="velocidad"
                            name="velocidad"
                            required
                            maxlength="50"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                            placeholder="Ej. 300 Mbps">

                    </div>

                </div>


                {{-- Precio --}}

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Precio mensual

                        <span class="text-red-500">*</span>

                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-money-bill-wave absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                        <input
                            type="number"
                            id="precio"
                            name="precio"
                            required
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                            placeholder="Ej. 79.90">

                    </div>

                </div>


                {{-- Estado --}}

                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Estado

                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-circle-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                        <select
                            id="estado"
                            name="estado"
                            class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                            <option value="1">
                                Activo
                            </option>

                            <option value="0">
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Descripción --}}

                <div class="sm:col-span-2">

                    <label class="mb-2 block text-sm font-semibold text-slate-700">

                        Descripción

                    </label>

                    <div class="relative">

                        <i class="fa-solid fa-align-left absolute left-4 top-4 text-slate-800"></i>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            maxlength="500"
                            class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                            placeholder="Describe las características del plan"></textarea>

                    </div>

                </div>

            </div>


            {{-- Botones --}}

            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="cerrarModalPlan()"
                    class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">

                    Cancelar

                </button>


                <button
                    id="btn-guardar"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">

                    <i class="fa-solid fa-floppy-disk"></i>

                    <span id="texto-btn-guardar">
                        Guardar plan
                    </span>

                </button>

            </div>

        </form>

    </div>

</div>


</div>

{{-- ============================================================= --}}
{{-- FORMULARIO ELIMINAR --}}
{{-- ============================================================= --}}

<form
    id="form-eliminar-plan"
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

    const modalPlan =
        document.getElementById('modal-plan');

    const formPlan =
        document.getElementById('form-plan');

    const metodoFormulario =
        document.getElementById('metodo-formulario');

    const modalTitulo =
        document.getElementById('modal-titulo');

    const modalDescripcion =
        document.getElementById('modal-descripcion');

    const textoBtnGuardar =
        document.getElementById('texto-btn-guardar');


    /**
     * Abrir modal para crear.
     */
    function abrirModalCrear() {

        formPlan.reset();

        formPlan.action =
            "{{ route('planes.store') }}";

        metodoFormulario.value = "";

        modalTitulo.textContent =
            "Nuevo plan";

        modalDescripcion.textContent =
            "Registra un nuevo plan de internet en Valenet.";

        textoBtnGuardar.textContent =
            "Guardar plan";

        document.getElementById('estado').value =
            "1";

        modalPlan.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {

            document.getElementById('nombre').focus();

        }, 100);

    }


    /**
     * Abrir modal para editar.
     */
    function abrirModalEditar(plan) {

        formPlan.action =
            `/planes/${plan.id}`;

        metodoFormulario.value =
            "PUT";

        modalTitulo.textContent =
            "Editar plan";

        modalDescripcion.textContent =
            "Actualiza la información del plan.";

        textoBtnGuardar.textContent =
            "Guardar cambios";


        document.getElementById('nombre').value =
            plan.nombre ?? '';

        document.getElementById('velocidad').value =
            plan.velocidad ?? '';

        document.getElementById('precio').value =
            plan.precio ?? '';

        document.getElementById('descripcion').value =
            plan.descripcion ?? '';

        document.getElementById('estado').value =
            plan.estado ? '1' : '0';


        modalPlan.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {

            document.getElementById('nombre').focus();

        }, 100);

    }


    /**
     * Cerrar modal.
     */
    function cerrarModalPlan() {

        modalPlan.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    /**
     * Eliminar plan.
     */
    function eliminarPlan(id, nombre) {

        const confirmar = confirm(
            `¿Estás seguro de eliminar el plan "${nombre}"?`
        );

        if (!confirmar) {
            return;
        }

        const formulario =
            document.getElementById('form-eliminar-plan');

        formulario.action =
            `/planes/${id}`;

        formulario.submit();

    }


    /**
     * Cerrar modal con ESC.
     */
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            cerrarModalPlan();

        }

    });

</script>

@endpush

@endsection
