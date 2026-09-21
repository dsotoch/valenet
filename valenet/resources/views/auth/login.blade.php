<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Iniciar sesión | Valenet
    </title>

    {{-- Tailwind --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

</head>


<body class="min-h-screen bg-slate-50">


<div class="flex min-h-screen">


    {{-- ========================================================= --}}
    {{-- PANEL IZQUIERDO --}}
    {{-- ========================================================= --}}

    <div class="relative hidden overflow-hidden bg-slate-950 lg:flex lg:w-1/2">


        {{-- FONDO --}}
        <div class="absolute inset-0">

            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-cyan-400/10 blur-3xl"></div>

        </div>


        <div class="relative z-10 flex w-full flex-col justify-between p-12">


            {{-- LOGO --}}
            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500 text-white shadow-lg">

                        <i class="fa-solid fa-wifi text-xl"></i>

                    </div>

                    <div>

                        <h1 class="text-2xl font-bold text-white">
                            Valenet
                        </h1>

                        <p class="text-xs text-slate-400">
                            Gestión ISP
                        </p>

                    </div>

                </div>

            </div>


            {{-- TEXTO --}}
            <div class="max-w-lg">

                <p class="mb-3 text-sm font-semibold uppercase tracking-wider text-cyan-400">
                    Sistema de gestión ISP
                </p>

                <h2 class="text-4xl font-bold leading-tight text-white xl:text-5xl">

                    Administra tu ISP
                    <span class="text-cyan-400">
                        desde un solo lugar.
                    </span>

                </h2>

                <p class="mt-6 text-lg leading-relaxed text-slate-400">

                    Gestiona clientes, planes, pagos y cobranza
                    de forma sencilla y organizada.

                </p>


                {{-- CARACTERÍSTICAS --}}
                <div class="mt-10 space-y-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-400">

                            <i class="fa-solid fa-users text-sm"></i>

                        </div>

                        <span class="text-sm text-slate-300">
                            Gestión de clientes
                        </span>

                    </div>


                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-400">

                            <i class="fa-solid fa-money-bill-wave text-sm"></i>

                        </div>

                        <span class="text-sm text-slate-300">
                            Control de pagos y cobranza
                        </span>

                    </div>


                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-400">

                            <i class="fa-solid fa-chart-line text-sm"></i>

                        </div>

                        <span class="text-sm text-slate-300">
                            Reportes y estadísticas
                        </span>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div>

                <p class="text-xs text-slate-700">
                    © {{ date('Y') }} Valenet
                </p>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- PANEL DERECHO --}}
    {{-- ========================================================= --}}

    <div class="flex w-full items-center justify-center px-6 py-10 lg:w-1/2">


        <div class="w-full max-w-md">


            {{-- LOGO MOBILE --}}
            <div class="mb-10 text-center lg:hidden">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500 text-white shadow-lg">

                    <i class="fa-solid fa-wifi text-2xl"></i>

                </div>

                <h1 class="mt-4 text-2xl font-bold text-slate-900">
                    Valenet
                </h1>

                <p class="mt-1 text-sm text-slate-700">
                    Sistema de gestión ISP
                </p>

            </div>


            {{-- CABECERA --}}
            <div class="mb-8">

                <h2 class="text-3xl font-bold text-slate-900">
                    Iniciar sesión
                </h2>

                <p class="mt-2 text-sm text-slate-700">
                    Ingresa tus credenciales para continuar.
                </p>

            </div>


            {{-- MENSAJE SUCCESS --}}
            @if(session('success'))

                <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

                    <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>

                    <p class="text-sm text-emerald-700">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ERRORES --}}
            @if($errors->any())

                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">

                    <div class="flex items-start gap-3">

                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600"></i>

                        <div>

                            <p class="text-sm font-semibold text-red-700">
                                No se pudo iniciar sesión
                            </p>

                            <ul class="mt-1 space-y-1">

                                @foreach($errors->all() as $error)

                                    <li class="text-xs text-red-600">
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- FORMULARIO --}}
            <form
                action="{{ route('login.procesar') }}"
                method="POST"
                class="space-y-5"
            >

                @csrf


                {{-- EMAIL --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Correo electrónico
                    </label>


                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">

                            <i class="fa-solid fa-envelope"></i>

                        </div>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="correo@ejemplo.com"
                            required
                            autofocus
                            class="w-full rounded-xl border border-slate-200 bg-white py-3.5 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10"
                        >

                    </div>


                  

                </div>


                {{-- PASSWORD --}}
                <div>

                    <div class="mb-2 flex items-center justify-between">

                        <label
                            for="password"
                            class="block text-sm font-semibold text-slate-700"
                        >
                            Contraseña
                        </label>

                    </div>


                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">

                            <i class="fa-solid fa-lock"></i>

                        </div>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white py-3.5 pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10"
                        >


                        <button
                            type="button"
                            onclick="mostrarPassword()"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition hover:text-slate-600"
                        >

                            <i
                                id="icon-password"
                                class="fa-solid fa-eye"
                            ></i>

                        </button>

                    </div>


                   
                </div>


                {{-- RECORDAR --}}
                <div class="flex items-center">

                    <label class="flex cursor-pointer items-center gap-2.5">

                        <input
                            type="checkbox"
                            name="recordarme"
                            value="1"
                            {{ old('recordarme') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500"
                        >

                        <span class="text-sm text-slate-600">
                            Recordarme
                        </span>

                    </label>

                </div>


                {{-- BOTÓN --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-cyan-600 px-5 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/20 active:scale-[0.99]"
                >

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Iniciar sesión

                </button>


            </form>


            {{-- FOOTER --}}
            <div class="mt-8 text-center">

                <p class="text-xs text-primary">
                    Sistema de gestión ISP
                </p>

            </div>


        </div>

    </div>

</div>



<script>

function mostrarPassword()
{
    const input = document.getElementById('password');
    const icon = document.getElementById('icon-password');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('fa-eye');

        icon.classList.add('fa-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.remove('fa-eye-slash');

        icon.classList.add('fa-eye');

    }
}

</script>


</body>

</html>