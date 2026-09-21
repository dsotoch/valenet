<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Recibo #{{ $pago->id }} - Valenet
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-slate-100">

    <div class="flex min-h-screen justify-center p-4 print:bg-white">

        <div
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm print:max-w-none print:shadow-none">

            {{-- ENCABEZADO --}}
            <div class="border-b border-dashed border-slate-300 pb-5 text-center">

                <h1 class="text-2xl font-black text-cyan-600">
                    VALENET
                </h1>

                <p class="mt-1 text-xs text-slate-500">
                    Sistema de Gestión ISP
                </p>

                <div class="mt-4">

                    <p class="text-sm font-semibold text-slate-700">
                        RECIBO DE PAGO
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        N.º {{ str_pad($pago->id, 8, '0', STR_PAD_LEFT) }}
                    </p>

                </div>

            </div>

            {{-- CLIENTE --}}
            <div class="border-b border-dashed border-slate-300 py-5">

                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Cliente
                </p>

                @php
                    $nombres = $pago->cliente->nombres ?? '';
                    $apellidos = $pago->cliente->apellidos ?? '';
                @endphp

                <p class="font-bold text-slate-800">
                    {{ ucwords(strtolower(trim($nombres . ' ' . $apellidos))) }}
                </p>

                @if($pago->cliente?->dni)
                    <p class="mt-1 text-sm text-slate-500">
                        DNI: {{ $pago->cliente->dni }}
                    </p>
                @endif

                @if($pago->cliente?->telefono)
                    <p class="mt-1 text-sm text-slate-500">
                        Teléfono: {{ $pago->cliente->telefono }}
                    </p>
                @endif

            </div>

            {{-- SERVICIO --}}
            <div class="border-b border-dashed border-slate-300 py-5">

                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    Servicio
                </p>

                @if($pago->clientePlan?->plan)

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="font-semibold text-slate-800">
                                {{ $pago->clientePlan->plan->nombre }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $pago->clientePlan->plan->velocidad }}
                            </p>

                        </div>

                        <p class="font-semibold text-slate-700">
                            S/ {{ number_format($pago->monto, 2) }}
                        </p>

                    </div>

                @else

                    <p class="text-sm text-slate-500">
                        Servicio de internet
                    </p>

                @endif

            </div>

            {{-- DETALLE DEL PAGO --}}
            <div class="border-b border-dashed border-slate-300 py-5">

                <div class="flex justify-between text-sm">

                    <span class="text-slate-500">
                        Periodo
                    </span>

                    <span class="font-medium text-slate-700">
                        {{ $pago->periodo?->locale('es')->translatedFormat('F Y') }}
                    </span>

                </div>

                <div class="mt-3 flex justify-between text-sm">

                    <span class="text-slate-500">
                        Fecha de pago
                    </span>

                    <span class="font-medium text-slate-700">
                        {{ $pago->fecha_pago?->format('d/m/Y') }}
                    </span>

                </div>

                <div class="mt-3 flex justify-between text-sm">

                    <span class="text-slate-500">
                        Método
                    </span>

                    <span class="font-medium capitalize text-slate-700">
                        {{ $pago->metodo_pago ?? '—' }}
                    </span>

                </div>

                @if($pago->referencia)

                    <div class="mt-3 flex justify-between gap-4 text-sm">

                        <span class="text-slate-500">
                            Referencia
                        </span>

                        <span class="text-right font-medium text-slate-700">
                            {{ $pago->referencia }}
                        </span>

                    </div>

                @endif

            </div>

            {{-- TOTAL --}}
            <div class="py-5">

                <div class="flex items-center justify-between">

                    <span class="text-sm font-semibold text-slate-600">
                        TOTAL PAGADO
                    </span>

                    <span class="text-2xl font-black text-cyan-600">
                        S/ {{ number_format($pago->monto_pagado, 2) }}
                    </span>

                </div>

                <div class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-600">

                    <i class="fa-solid fa-circle-check"></i>

                    Pago confirmado

                </div>

            </div>

            {{-- OBSERVACIÓN --}}
            @if($pago->observacion)

                <div class="border-t border-dashed border-slate-300 pt-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Observación
                    </p>

                    <p class="mt-2 text-sm text-slate-600">
                        {{ $pago->observacion }}
                    </p>

                </div>

            @endif

            {{-- FOOTER --}}
            <div class="mt-6 border-t border-dashed border-slate-300 pt-5 text-center">

                <p class="text-xs text-slate-600">
                    Gracias por realizar su pago.
                </p>

                <p class="mt-1 text-xs text-slate-600">
                    Valenet
                </p>

            </div>

            {{-- BOTÓN IMPRIMIR --}}
            <div class="mt-6 flex justify-center print:hidden">

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-6 py-3 text-sm font-semibold text-white hover:bg-cyan-700">

                    <i class="fa-solid fa-print"></i>

                    Imprimir recibo

                </button>

            </div>

        </div>

    </div>

</body>

</html>