
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>403 - Acceso denegado | Valenet</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-50">

    <div class="flex min-h-screen items-center justify-center px-6 py-12">

        <div class="w-full max-w-lg text-center">

            {{-- ICONO --}}
            <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-3xl bg-cyan-50">
                <i class="fa-solid fa-lock text-4xl text-cyan-600"></i>
            </div>

            {{-- CÓDIGO --}}
            <p class="text-7xl font-black tracking-tight text-slate-800">
                403
            </p>

            {{-- TÍTULO --}}
            <h1 class="mt-4 text-2xl font-bold text-slate-900">
                Acceso denegado
            </h1>

            {{-- DESCRIPCIÓN --}}
            <p class="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-500">
                No tienes permisos suficientes para acceder a esta página.
                Si necesitas acceder a este módulo, comunícate con un administrador.
            </p>

            {{-- INFORMACIÓN --}}
            <div class="mx-auto mt-8 max-w-md rounded-2xl border border-slate-200 bg-white p-5 text-left shadow-sm">

                <div class="flex items-start gap-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                    </div>

                    <div>
                        <p class="font-semibold text-slate-800">
                            Permiso requerido
                        </p>

                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Tu usuario está autenticado, pero tu rol actual
                            no permite realizar esta acción.
                        </p>
                    </div>

                </div>

            </div>

            {{-- BOTONES --}}
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">

                <a href="/"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                    <i class="fa-solid fa-arrow-left"></i>

                    Volver
                </a>

              

            </div>

            {{-- FOOTER --}}
            <p class="mt-10 text-xs text-slate-600">
                &copy; {{ date('Y') }} Valenet. Todos los derechos reservados.
            </p>

        </div>

    </div>

</body>

</html>

