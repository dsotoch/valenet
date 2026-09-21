@extends('layouts.dashboard')

@section('title', 'Usuarios')

@section('header-title', 'Usuarios')

@section('content')

<div class="space-y-6">

    {{-- ENCABEZADO --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Usuarios
            </h1>

            <p class="mt-1 text-sm text-slate-700">
                Gestiona los usuarios del sistema Valenet.
            </p>
        </div>

        <button
            type="button"
            onclick="abrirModalCrear()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">

            <i class="fa-solid fa-user-plus"></i>

            Nuevo usuario

        </button>

    </div>


    {{-- MENSAJE --}}
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


    {{-- ERRORES --}}
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


    {{-- FILTROS --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

        <form
            method="GET"
            action="{{ route('usuarios.index') }}"
            class="flex flex-col gap-3 sm:flex-row">

            <div class="relative flex-1">

                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                <input
                    type="text"
                    name="buscar"
                    value="{{ $busqueda }}"
                    placeholder="Buscar por nombre, correo o rol..."
                    class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-800 focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">

                <i class="fa-solid fa-filter"></i>

                Buscar

            </button>

            @if($busqueda)

                <a
                    href="{{ route('usuarios.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">

                    <i class="fa-solid fa-rotate-left"></i>

                    Limpiar

                </a>

            @endif

        </form>

    </div>


    {{-- LISTADO --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="font-semibold text-slate-800">
                        Lista de usuarios
                    </h2>

                    <p class="mt-1 text-xs text-slate-800">
                        {{ $usuarios->total() }}
                        {{ $usuarios->total() === 1 ? 'usuario registrado' : 'usuarios registrados' }}
                    </p>

                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-500">

                    <i class="fa-solid fa-users"></i>

                </div>

            </div>

        </div>


        {{-- DESKTOP --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="w-full text-left">

                <thead class="bg-slate-50">

                    <tr class="border-b border-slate-100">

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-700">
                            Usuario
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-700">
                            Correo
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-700">
                            Rol
                        </th>

                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-slate-700">
                            Estado
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-700">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($usuarios as $usuario)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                                        <i class="fa-solid fa-user"></i>

                                    </div>

                                    <div>

                                        <p class="font-semibold text-slate-800">
                                            {{ $usuario->name }}
                                        </p>

                                        <p class="text-xs text-slate-800">
                                            Registrado {{ $usuario->created_at?->format('d/m/Y') }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $usuario->email }}
                            </td>


                            <td class="px-5 py-4">

                                <span class="inline-flex items-center rounded-lg bg-cyan-50 px-3 py-1.5 text-sm font-semibold capitalize text-cyan-700">
                                    {{ $usuario->rol }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                @if($usuario->estado)

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


                            <td class="px-5 py-4">

                                <div class="flex justify-end gap-2">

                                    <button
                                        type="button"
                                        onclick='abrirModalEditar(@json($usuario))'
                                        title="Editar"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-700 transition hover:bg-cyan-50 hover:text-cyan-600">

                                        <i class="fa-solid fa-pen"></i>

                                    </button>


                                    <button
                                        type="button"
                                        onclick='eliminarUsuario({{ $usuario->id }}, @json($usuario->name))'
                                        title="Eliminar"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-700 transition hover:bg-red-50 hover:text-red-600">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-800">

                                    <i class="fa-solid fa-users text-2xl"></i>

                                </div>

                                <h3 class="mt-4 font-semibold text-slate-700">
                                    No hay usuarios
                                </h3>

                                <p class="mt-1 text-sm text-slate-800">
                                    Todavía no tienes usuarios registrados.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        <div class="divide-y divide-slate-100 md:hidden">

            @forelse($usuarios as $usuario)

                <div class="p-4">

                    <div class="flex items-start gap-3">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">

                            <i class="fa-solid fa-user"></i>

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-2">

                                <div>

                                    <h3 class="font-semibold text-slate-800">
                                        {{ $usuario->name }}
                                    </h3>

                                    <p class="mt-0.5 truncate text-xs text-slate-700">
                                        {{ $usuario->email }}
                                    </p>

                                </div>

                                @if($usuario->estado)

                                    <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600">
                                        Activo
                                    </span>

                                @else

                                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                                        Inactivo
                                    </span>

                                @endif

                            </div>


                            <div class="mt-3">

                                <span class="inline-flex rounded-lg bg-cyan-50 px-3 py-1.5 text-xs font-semibold capitalize text-cyan-700">

                                    <i class="fa-solid fa-shield-halved mr-2"></i>

                                    {{ $usuario->rol }}

                                </span>

                            </div>


                            <div class="mt-4 flex gap-2 border-t border-slate-100 pt-3">

                                <button
                                    type="button"
                                    onclick='abrirModalEditar(@json($usuario))'
                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-slate-200 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">

                                    <i class="fa-solid fa-pen"></i>

                                    Editar

                                </button>


                                <button
                                    type="button"
                                    onclick='eliminarUsuario({{ $usuario->id }}, @json($usuario->name))'
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
                        No hay usuarios registrados
                    </p>

                </div>

            @endforelse

        </div>


        {{-- PAGINACIÓN --}}
        @if($usuarios->hasPages())

            <div class="border-t border-slate-100 px-5 py-4">

                {{ $usuarios->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL --}}
{{-- ========================================================= --}}

<div
    id="modal-usuario"
    class="fixed inset-0 z-[100] hidden">

    <div
        class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
        onclick="cerrarModalUsuario()">
    </div>


    <div class="relative flex min-h-full items-center justify-center p-4">

        <div class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


            {{-- HEADER --}}
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-4">

                <div>

                    <h2
                        id="modal-titulo"
                        class="text-lg font-bold text-slate-800">

                        Nuevo usuario

                    </h2>

                    <p
                        id="modal-descripcion"
                        class="mt-0.5 text-sm text-slate-700">

                        Registra un nuevo usuario en Valenet.

                    </p>

                </div>


                <button
                    type="button"
                    onclick="cerrarModalUsuario()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-800 hover:bg-slate-100 hover:text-slate-700">

                    <i class="fa-solid fa-xmark text-lg"></i>

                </button>

            </div>


            {{-- FORMULARIO --}}
            <form
                id="form-usuario"
                method="POST"
                action="{{ route('usuarios.store') }}"
                class="p-6">

                @csrf

                <input
                    type="hidden"
                    id="metodo-formulario"
                    name="_method"
                    value="">


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                    {{-- NOMBRE --}}
                    <div class="sm:col-span-2">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nombre completo
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                maxlength="100"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Ej. Juan Pérez">

                        </div>

                    </div>


                    {{-- EMAIL --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Correo electrónico

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                maxlength="255"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="correo@ejemplo.com">

                        </div>

                    </div>


                    {{-- ROL --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Rol

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <select
                                id="rol"
                                name="rol"
                                required
                                class="w-full  text-slate-600 appearance-none rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100">

                                <option value="usuario">
                                    Usuario
                                </option>

                               

                                <option value="administrador">
                                    Administrador
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- PASSWORD --}}
                    <div>

                        <label class="mb-2 block  text-sm font-semibold text-slate-700">

                            Contraseña

                            <span
                                id="asterisco-password"
                                class="text-red-500">
                                *
                            </span>

                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                minlength="6"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Mínimo 6 caracteres">

                        </div>

                        <p
                            id="ayuda-password"
                            class="mt-1 text-xs text-slate-800">
                            Mínimo 6 caracteres.
                        </p>

                    </div>


                    {{-- CONFIRMAR PASSWORD --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-slate-700">

                            Confirmar contraseña

                        </label>

                        <div class="relative">

                            <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-800"></i>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                minlength="6"
                                class="w-full text-slate-600 rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-cyan-400 focus:bg-white focus:ring-2 focus:ring-cyan-100"
                                placeholder="Repite la contraseña">

                        </div>

                    </div>


                    {{-- ESTADO --}}
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

                </div>


                {{-- BOTONES --}}
                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="cerrarModalUsuario()"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">

                        Cancelar

                    </button>


                    <button
                        id="btn-guardar"
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-600">

                        <i class="fa-solid fa-floppy-disk"></i>

                        <span id="texto-btn-guardar">
                            Guardar usuario
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- FORMULARIO ELIMINAR --}}
<form
    id="form-eliminar-usuario"
    method="POST"
    class="hidden">

    @csrf

    @method('DELETE')

</form>


@push('scripts')

<script>

    const modalUsuario =
        document.getElementById('modal-usuario');

    const formUsuario =
        document.getElementById('form-usuario');

    const metodoFormulario =
        document.getElementById('metodo-formulario');

    const modalTitulo =
        document.getElementById('modal-titulo');

    const modalDescripcion =
        document.getElementById('modal-descripcion');

    const textoBtnGuardar =
        document.getElementById('texto-btn-guardar');


    /**
     * CREAR
     */
    function abrirModalCrear() {

        formUsuario.reset();

        formUsuario.action =
            "{{ route('usuarios.store') }}";

        metodoFormulario.value = "";

        modalTitulo.textContent =
            "Nuevo usuario";

        modalDescripcion.textContent =
            "Registra un nuevo usuario en Valenet.";

        textoBtnGuardar.textContent =
            "Guardar usuario";

        document.getElementById('estado').value =
            "1";

        document.getElementById('rol').value =
            "usuario";

        document.getElementById('password').required =
            true;

        document.getElementById('password_confirmation').required =
            true;

        modalUsuario.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {

            document.getElementById('name').focus();

        }, 100);

    }


    /**
     * EDITAR
     */
    function abrirModalEditar(usuario) {

        formUsuario.action =
            `/usuarios/${usuario.id}`;

        metodoFormulario.value =
            "PUT";

        modalTitulo.textContent =
            "Editar usuario";

        modalDescripcion.textContent =
            "Actualiza la información del usuario.";

        textoBtnGuardar.textContent =
            "Guardar cambios";


        document.getElementById('name').value =
            usuario.name ?? '';

        document.getElementById('email').value =
            usuario.email ?? '';

        document.getElementById('rol').value =
            usuario.rol ?? 'usuario';

        document.getElementById('estado').value =
            usuario.estado ? '1' : '0';


        document.getElementById('password').value =
            '';

        document.getElementById('password_confirmation').value =
            '';

        document.getElementById('password').required =
            false;

        document.getElementById('password_confirmation').required =
            false;


        modalUsuario.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

        setTimeout(() => {

            document.getElementById('name').focus();

        }, 100);

    }


    /**
     * CERRAR
     */
    function cerrarModalUsuario() {

        modalUsuario.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    /**
     * ELIMINAR
     */
    function eliminarUsuario(id, nombre) {

        const confirmar = confirm(
            `¿Estás seguro de eliminar el usuario "${nombre}"?`
        );

        if (!confirmar) {
            return;
        }

        const formulario =
            document.getElementById('form-eliminar-usuario');

        formulario.action =
            `/usuarios/${id}`;

        formulario.submit();

    }


    /**
     * ESC
     */
    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            cerrarModalUsuario();

        }

    });

</script>

@endpush

@endsection