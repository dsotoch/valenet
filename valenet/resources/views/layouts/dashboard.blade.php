<!DOCTYPE html>

<html lang="es">

<head>

   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - Valenet</title>
    
    {{-- FONT AWESOME --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
     <style>
        button{
            cursor: pointer;
        }
        a{
            cursor: pointer;
        }
     </style>
    {{-- TAILWIND --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openButton = document.getElementById('open-sidebar');
            const closeButton = document.getElementById('close-sidebar');

            function abrirMenu() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function cerrarMenu() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openButton?.addEventListener('click', abrirMenu);
            closeButton?.addEventListener('click', cerrarMenu);
            overlay?.addEventListener('click', cerrarMenu);

            window.addEventListener('resize', () => {

                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

            });

        });
    </script>

    @stack('styles')
   

</head>

<body class="bg-slate-100 text-slate-800">

    <div class="min-h-screen">

       
        {{-- ============================================================
    OVERLAY MÓVIL
============================================================= --}}

        <div
            id="sidebar-overlay"
            class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>


        {{-- ============================================================
    SIDEBAR
============================================================= --}}

        <aside
            id="sidebar"
            class="
        fixed inset-y-0 left-0 z-50
        flex w-72 flex-col
        bg-slate-950 text-white
        shadow-xl
        transition-transform duration-300
        -translate-x-full
        lg:translate-x-0
    ">


            {{-- ========================================================
        LOGO
    ========================================================= --}}

            <div
                class="
            flex h-20 items-center justify-between
            border-b border-white/10
            px-6
        ">

                <div>

                    <h1 class="text-xl font-bold tracking-wide">
                        VALENET
                    </h1>

                    <p class="text-xs text-slate-400">
                        Sistema de Gestión ISP
                    </p>

                </div>


                {{-- CERRAR SIDEBAR EN MÓVIL --}}

                <button
                    id="close-sidebar"
                    type="button"
                    class="
                rounded-lg p-2
                text-slate-400
                transition
                hover:bg-white/10
                hover:text-white
                lg:hidden
            "
                    aria-label="Cerrar menú">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>

            </div>


            {{-- ========================================================
        MENU
    ========================================================= --}}

            <nav class="flex-1 overflow-y-auto px-4 py-6">


                {{-- PRINCIPAL --}}

                <p
                    class="
                mb-3 px-3
                text-xs font-semibold
                uppercase tracking-wider
                text-slate-500
            ">
                    Principal
                </p>


                {{-- DASHBOARD --}}

                <a
                    href="{{ route('dashboard') }}"
                    class="
                mb-1 flex items-center gap-3
                rounded-xl px-4 py-3
                text-sm font-medium
                transition
                {{ request()->routeIs('dashboard')
                    ? 'bg-cyan-500 text-white shadow-sm'
                    : 'text-slate-300 hover:bg-white/10 hover:text-white'
                }}
            ">

                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>

                    <span>
                        Dashboard
                    </span>

                </a>


                {{-- CLIENTES --}}

                <a
                    href="{{ route('clientes.index') }}"
                    class="{{ request()->routeIs('clientes.*')
        ? 'bg-cyan-500 text-white shadow-sm'
        : 'text-slate-300 hover:bg-white/10 hover:text-white' }}
    group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span>Clientes</span>
                </a>


                {{-- PLANES --}}

                <a
                     href="{{ route('planes.index') }}"
                     class="{{ request()->routeIs('planes.*')
        ? 'bg-cyan-500 text-white shadow-sm'
        : 'text-slate-300 hover:bg-white/10 hover:text-white' }}
                mb-1 flex items-center gap-3
                rounded-xl px-4 py-3
                text-sm font-medium
                text-slate-300
                transition
                hover:bg-white/10
                hover:text-white
            ">

                    <i class="fa-solid fa-wifi w-5 text-center"></i>

                    <span>
                        Planes
                    </span>

                </a>


                {{-- PAGOS --}}

                <a
                     href="{{ route('pagos.index') }}"
                     class="{{ request()->routeIs('pagos.*')
        ? 'bg-cyan-500 text-white shadow-sm'
        : 'text-slate-300 hover:bg-white/10 hover:text-white' }}
                mb-1 flex items-center gap-3
                rounded-xl px-4 py-3
                text-sm font-medium
                text-slate-300
                transition
                hover:bg-white/10
                hover:text-white
            ">

                    <i class="fa-solid fa-money-bill-wave w-5 text-center"></i>

                    <span>
                        Pagos
                    </span>

                </a>


                {{-- ADMINISTRACIÓN --}}

                <p
                    class="
                mb-3 mt-8 px-3
                text-xs font-semibold
                uppercase tracking-wider
                text-slate-500
            ">
                    Administración
                </p>


                {{-- USUARIOS --}}

                <a
                    href="#"
                    class="
                mb-1 flex items-center gap-3
                rounded-xl px-4 py-3
                text-sm font-medium
                text-slate-300
                transition
                hover:bg-white/10
                hover:text-white
            ">

                    <i class="fa-solid fa-user-gear w-5 text-center"></i>

                    <span>
                        Usuarios
                    </span>

                </a>


                {{-- REPORTES --}}

                <a
                    href="#"
                    class="
                mb-1 flex items-center gap-3
                rounded-xl px-4 py-3
                text-sm font-medium
                text-slate-300
                transition
                hover:bg-white/10
                hover:text-white
            ">

                    <i class="fa-solid fa-chart-column w-5 text-center"></i>

                    <span>
                        Reportes
                    </span>

                </a>

            </nav>


            {{-- ========================================================
        USUARIO SIDEBAR
    ========================================================= --}}

            <div class="border-t border-white/10 p-4">

                <div class="flex items-center gap-3">


                    {{-- AVATAR --}}

                    <div
                        class="
                    flex h-10 w-10 shrink-0
                    items-center justify-center
                    rounded-full
                    bg-cyan-500
                    font-semibold
                ">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>


                    {{-- DATOS --}}

                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </p>

                        <p class="truncate text-xs text-slate-400">
                            Administrador
                        </p>

                    </div>


                    {{-- LOGOUT --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="
                        rounded-lg p-2
                        text-slate-400
                        transition
                        hover:bg-red-500/10
                        hover:text-red-400
                    "
                            title="Cerrar sesión">

                            <i class="fa-solid fa-right-from-bracket"></i>

                        </button>

                    </form>

                </div>

            </div>

        </aside>


        {{-- ============================================================
    CONTENIDO PRINCIPAL
============================================================= --}}

        <div class="lg:pl-72">


            {{-- ========================================================
        HEADER
    ========================================================= --}}

            <header
                class="
            sticky top-0 z-30
            flex h-20 items-center
            justify-between
            border-b border-slate-200
            bg-white/95
            px-4
            backdrop-blur
            sm:px-6
        ">


                {{-- IZQUIERDA --}}

                <div class="flex items-center gap-3">


                    {{-- BOTÓN MENÚ MÓVIL --}}

                    <button
                        id="open-sidebar"
                        type="button"
                        class="
                    rounded-xl p-2.5
                    text-slate-600
                    transition
                    hover:bg-slate-100
                    hover:text-slate-900
                    lg:hidden
                "
                        aria-label="Abrir menú">

                        <i class="fa-solid fa-bars text-lg"></i>

                    </button>


                    {{-- TÍTULO --}}

                    <div>

                        <h2
                            class="
            text-lg font-bold
            text-slate-800
            sm:text-xl
        ">
                            @yield('header-title', 'Dashboard')
                        </h2>

                        <p class="hidden text-xs font-lg text-cyan-800 font-bold sm:block">
                            Sistema de Gestión ISP
                        </p>

                    </div>

                </div>


                {{-- ====================================================
            HEADER DERECHO
        ===================================================== --}}

                <div class="flex items-center gap-3">


                    {{-- DATOS USUARIO --}}

                    <div class="hidden text-right sm:block">

                        <p class="text-sm font-semibold text-slate-800">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Administrador
                        </p>

                    </div>


                    {{-- AVATAR --}}

                    <div
                        class="
                    flex h-10 w-10
                    items-center justify-center
                    rounded-full
                    bg-cyan-100
                    font-semibold
                    text-cyan-700
                ">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>

                </div>

            </header>


            {{-- ========================================================
        MAIN
    ========================================================= --}}

            <main class="p-4 sm:p-6 lg:p-8">

                @yield('content')

            </main>

        </div>
       

    </div>

    @stack('scripts')

</body>

</html>