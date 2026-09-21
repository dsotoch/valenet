@extends('layouts.dashboard')

@section('title', 'Clientes')

@section('header-title', 'Clientes')

@section('content')

<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Clientes
            </h1>

            <p class="mt-1 text-sm text-slate-800">
                Gestiona los clientes registrados en Valenet.
            </p>
        </div>

        <button
            type="button"
            onclick="abrirModalCrear()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">
            <i class="fa-solid fa-plus"></i>
            Nuevo cliente
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
            action="{{ route('clientes.index') }}"
            class="flex flex-col gap-3 sm:flex-row">

            <div class="relative flex-1">

                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                <input
                    type="text"
                    name="buscar"
                    value="{{ $busqueda }}"
                    placeholder="Buscar por nombre, documento, teléfono o correo..."
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
                href="{{ route('clientes.index') }}"
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

        {{-- Cabecera tabla --}}

        <div class="border-b border-slate-100 px-5 py-4">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-slate-800">
                        Lista de clientes
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        {{ $clientes->total() }}
                        {{ $clientes->total() === 1 ? 'cliente registrado' : 'clientes registrados' }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500">
                    <i class="fa-solid fa-users"></i>
                </div>

            </div>

        </div>


        {{-- Tabla desktop --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left">

                <thead class="bg-slate-50">

                    <tr class="border-b border-slate-100">

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                            Cliente
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                            Documento
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                            Contacto
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-800">
                            Dirección
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

                    @forelse($clientes as $cliente)

                    <tr class="transition hover:bg-slate-50">

                        {{-- Cliente --}}

                        <td class="px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-cyan-100 font-bold text-cyan-600">

                                    {{ strtoupper(substr($cliente->nombres, 0, 1)) }}

                                </div>

                                <div>

                                    <p class="font-semibold text-slate-800">
                                        {{ $cliente->nombres }}
                                        {{ $cliente->apellidos }}
                                    </p>

                                    <p class="text-xs text-slate-800">
                                        Registrado {{ $cliente->created_at?->format('d/m/Y') }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- Documento --}}

                        <td class="px-5 py-4">

                            <span class="text-sm font-medium text-slate-700">
                                {{ $cliente->documento }}
                            </span>

                        </td>


                        {{-- Contacto --}}

                        <td class="px-5 py-4">

                            <div class="space-y-1">

                                @if($cliente->telefono)

                                <p class="flex items-center gap-2 text-sm text-slate-600">

                                    <i class="fa-solid fa-phone text-xs text-slate-800"></i>

                                    {{ $cliente->telefono }}

                                </p>

                                @endif

                                @if($cliente->email)

                                <p class="flex items-center gap-2 text-xs text-slate-800">

                                    <i class="fa-solid fa-envelope text-xs"></i>

                                    {{ $cliente->email }}

                                </p>

                                @endif

                            </div>

                        </td>


                        {{-- Dirección --}}

                        <td class="max-w-xs px-5 py-4">

                            <p class="truncate text-sm text-slate-600">

                                @if($cliente->direccion)

                                <i class="fa-solid fa-location-dot mr-1 text-slate-800"></i>

                                {{ $cliente->direccion }}

                                @else

                                <span class="text-slate-800">
                                    Sin dirección
                                </span>

                                @endif

                            </p>

                        </td>


                        {{-- Estado --}}

                        <td class="px-5 py-4">

                            @if($cliente->estado)

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
                                    onclick='abrirModalEditar(@json($cliente))'
                                    title="Editar"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 transition hover:bg-cyan-50 hover:text-cyan-600">
                                    <i class="fa-solid fa-pen"></i>
                                </button>


                                <button
                                    type="button"
                                    onclick="eliminarCliente('{{ $cliente->id }}', '{{ addslashes($cliente->nombres  .' '. $cliente->apellidos) }}')"
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

                                <i class="fa-solid fa-users text-2xl"></i>

                            </div>

                            <h3 class="mt-4 font-semibold text-slate-700">
                                No hay clientes
                            </h3>

                            <p class="mt-1 text-sm text-slate-800">
                                Todavía no tienes clientes registrados.
                            </p>

                            <button
                                type="button"
                                onclick="abrirModalCrear()"
                                class="mt-4 text-sm font-semibold text-cyan-600 hover:text-cyan-700">
                                Registrar primer cliente
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

            @forelse($clientes as $cliente)

            <div class="p-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-cyan-100 font-bold text-cyan-600">

                        {{ strtoupper(substr($cliente->nombres, 0, 1)) }}

                    </div>

                    <div class="min-w-0 flex-1">

                        <div class="flex items-start justify-between gap-2">

                            <div>

                                <h3 class="font-semibold text-slate-800">
                                    {{ $cliente->nombres }}
                                    {{ $cliente->apellidos }}
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-800">
                                    {{ $cliente->documento }}
                                </p>

                            </div>

                            @if($cliente->estado)

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600">
                                Activo
                            </span>

                            @else

                            <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                                Inactivo
                            </span>

                            @endif

                        </div>


                        <div class="mt-3 space-y-1.5">

                            @if($cliente->telefono)

                            <p class="text-sm text-slate-800">

                                <i class="fa-solid fa-phone mr-2 w-4 text-slate-800"></i>

                                {{ $cliente->telefono }}

                            </p>

                            @endif

                            @if($cliente->email)

                            <p class="truncate text-sm text-slate-800">

                                <i class="fa-solid fa-envelope mr-2 w-4 text-slate-800"></i>

                                {{ $cliente->email }}

                            </p>

                            @endif

                            @if($cliente->direccion)

                            <p class="truncate text-sm text-slate-800">

                                <i class="fa-solid fa-location-dot mr-2 w-4 text-slate-800"></i>

                                {{ $cliente->direccion }}

                            </p>

                            @endif

                        </div>


                        <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">

                            <button
                                type="button"
                                onclick='abrirModalEditar("@json($cliente)")'
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-200 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                <i class="fa-solid fa-pen"></i>
                                Editar
                            </button>

                            <button
                                type="button"
                                onclick="eliminarCliente('{{ $cliente->id }}', '{{ addslashes($cliente->nombres .' '. $cliente->apellidos) }}')"
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

                <i class="fa-solid fa-users text-3xl text-slate-300"></i>

                <p class="mt-3 font-semibold text-slate-600">
                    No hay clientes registrados
                </p>

            </div>

            @endforelse

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINACIÓN --}}
        {{-- ========================================================= --}}

        @if($clientes->hasPages())

        <div class="border-t border-slate-100 px-5 py-4">

            {{ $clientes->links() }}

        </div>

        @endif

    </div>

</div>


{{-- ============================================================= --}}
{{-- MODAL CREAR / EDITAR --}}
{{-- ============================================================= --}}

<div
    id="modal-cliente"
    class="fixed inset-0 z-[100] hidden">

    {{-- Fondo --}}

    <div
        id="modal-cliente-overlay"
        class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
        onclick="cerrarModalCliente()"></div>


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
                        Nuevo cliente
                    </h2>

                    <p
                        id="modal-descripcion"
                        class="mt-0.5 text-sm text-slate-800">
                        Registra un nuevo cliente.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="cerrarModalCliente()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100 hover:text-slate-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

            </div>


            {{-- Formulario --}}

            <form
                id="form-cliente"
                method="POST"
                action="{{ route('clientes.store') }}"
                class="p-6">

                @csrf

                <input
                    type="hidden"
                    id="metodo-formulario"
                    name="_method"
                    value="">


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Nombres --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nombres
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="text"
                                id="nombres"
                                name="nombres"
                                required
                                maxlength="100"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Ej. Juan Carlos">

                        </div>

                    </div>


                    {{-- Apellidos --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Apellidos
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="text"
                                id="apellidos"
                                name="apellidos"
                                required
                                maxlength="100"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Ej. Pérez García">

                        </div>

                    </div>


                    {{-- Documento --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Documento
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="text"
                                id="documento"
                                name="documento"
                                required
                                maxlength="20"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="DNI / CE / RUC">

                        </div>

                    </div>


                    {{-- Teléfono --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Teléfono
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                maxlength="20"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Ej. 987654321">

                        </div>

                    </div>


                    {{-- Email --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Correo electrónico
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                maxlength="150"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="cliente@correo.com">

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
                                class="w-full text-slate-600 appearance-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">
                                <option value="1">
                                    Activo
                                </option>

                                <option value="0">
                                    Inactivo
                                </option>
                            </select>

                        </div>

                    </div>


                    {{-- Dirección --}}

                    <div class="sm:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Dirección
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-location-dot absolute left-4 top-4 text-slate-800"></i>

                            <textarea
                                id="direccion"
                                name="direccion"
                                rows="3"
                                maxlength="255"
                                class="w-full text-slate-600 resize-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Dirección del cliente"></textarea>

                        </div>

                    </div>

                </div>


                {{-- Botones --}}

                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="cerrarModalCliente()"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                        Cancelar
                    </button>

                    <button
                        id="btn-guardar"
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">
                        <i class="fa-solid fa-floppy-disk"></i>

                        <span id="texto-btn-guardar">
                            Guardar cliente
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
    id="form-eliminar-cliente"
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
    const modalCliente = document.getElementById('modal-cliente');

    const formCliente = document.getElementById('form-cliente');

    const metodoFormulario = document.getElementById('metodo-formulario');

    const modalTitulo = document.getElementById('modal-titulo');

    const modalDescripcion = document.getElementById('modal-descripcion');

    const textoBtnGuardar = document.getElementById('texto-btn-guardar');


    /**
     * Abrir modal para crear.
     */
    function abrirModalCrear() {
        formCliente.reset();

        formCliente.action = "{{ route('clientes.store') }}";

        metodoFormulario.value = "";

        modalTitulo.textContent = "Nuevo cliente";

        modalDescripcion.textContent =
            "Registra un nuevo cliente en Valenet.";

        textoBtnGuardar.textContent =
            "Guardar cliente";

        document.getElementById('estado').value = "1";

        modalCliente.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {
            document.getElementById('nombres').focus();
        }, 100);
    }


    /**
     * Abrir modal para editar.
     */
    function abrirModalEditar(cliente) {
        formCliente.action =
            `/clientes/${cliente.id}`;

        metodoFormulario.value = "PUT";

        modalTitulo.textContent =
            "Editar cliente";

        modalDescripcion.textContent =
            "Actualiza la información del cliente.";

        textoBtnGuardar.textContent =
            "Guardar cambios";


        document.getElementById('nombres').value =
            cliente.nombres ?? '';

        document.getElementById('apellidos').value =
            cliente.apellidos ?? '';

        document.getElementById('documento').value =
            cliente.documento ?? '';

        document.getElementById('telefono').value =
            cliente.telefono ?? '';

        document.getElementById('email').value =
            cliente.email ?? '';

        document.getElementById('direccion').value =
            cliente.direccion ?? '';

        document.getElementById('estado').value =
            cliente.estado ? '1' : '0';


        modalCliente.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {
            document.getElementById('nombres').focus();
        }, 100);
    }


    /**
     * Cerrar modal.
     */
    function cerrarModalCliente() {
        modalCliente.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    }


    /**
     * Eliminar cliente.
     */
    function eliminarCliente(id, nombre) {
        const confirmar = confirm(
            `¿Estás seguro de eliminar al cliente "${nombre}"?`
        );

        if (!confirmar) {
            return;
        }

        const formulario =
            document.getElementById('form-eliminar-cliente');

        formulario.action =
            `/clientes/${id}`;

        formulario.submit();
    }


    /**
     * Cerrar modal con ESC.
     */
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalCliente();
        }
    });
</script>

@endpush

@endsection