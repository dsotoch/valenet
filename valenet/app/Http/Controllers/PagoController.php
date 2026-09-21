<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClientePlan;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\RecordatorioPago;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function recibo(Pago $pago)
    {
        $pago->load([
            'cliente',
            'clientePlan.plan',
        ]);

        return view('pagos.recibo', compact('pago'));
    }

    /**
     * Lista principal de pagos.
     */
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $buscar = trim($request->get('buscar', ''));

        $estado = $request->get('estado', '');

        $planId = $request->get('plan_id', '');

        $periodo = $request->get('periodo', '');

        $pagos = Pago::with([
            'usuario',
            'cliente',
            'clientePlan.plan',
        ])

            ->when($buscar, function ($query) use ($buscar) {

                $query->where(function ($q) use ($buscar) {

                    $q->whereHas('cliente', function ($cliente) use ($buscar) {

                        $cliente->where('nombres', 'like', "%{$buscar}%")
                            ->orWhere('telefono', 'like', "%{$buscar}%");
                    })

                        ->orWhereHas('clientePlan.plan', function ($plan) use ($buscar) {

                            $plan->where('nombre', 'like', "%{$buscar}%");
                        });
                });
            })

            ->when($estado, function ($query) use ($estado) {

                $query->where('estado', $estado);
            })

            ->when($planId, function ($query) use ($planId) {

                $query->whereHas('clientePlan', function ($q) use ($planId) {

                    $q->where('plan_id', $planId);
                });
            })

            ->when($periodo, function ($query) use ($periodo) {

                $query->whereYear('periodo', substr($periodo, 0, 4))
                    ->whereMonth('periodo', substr($periodo, 5, 2));
            })

            ->orderByRaw("
        CASE
            WHEN estado = 'vencido' THEN 1
            WHEN estado = 'pendiente' THEN 2
            WHEN estado = 'pagado' THEN 3
            ELSE 4
        END
    ")

            ->orderBy('fecha_vencimiento', 'desc')
            ->paginate(1000)

            ->withQueryString();


        /*
         * Actualizar automáticamente pendientes vencidos.
         */
        Pago::where('estado', 'pendiente')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())

            ->update([
                'estado' => 'vencido',
            ]);


        /*
         * Estadísticas.
         */
        $pendientesMonto = Pago::where('estado', 'pendiente')
            ->sum(DB::raw('monto - monto_pagado'));

        $vencidosMonto = Pago::where('estado', 'vencido')
            ->sum(DB::raw('monto - monto_pagado'));

        $pagadosMonto = Pago::whereIn('estado', ['pagado', 'pendiente'])
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto_pagado');

        $venceHoyMonto = Pago::whereIn('estado', [
            'pendiente',
            'vencido',
        ])
            ->whereDate('fecha_vencimiento', now()->toDateString())
            ->sum(DB::raw('monto - monto_pagado'));

        $pendientesCantidad = Pago::where('estado', 'pendiente')
            ->count();

        $vencidosCantidad = Pago::where('estado', 'vencido')
            ->count();

        $pagadosCantidad = Pago::whereIn('estado', ['pagado', 'pendiente'])
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->count();

        $venceHoyCantidad = Pago::whereIn('estado', [
            'pendiente',
            'vencido',
        ])
            ->whereDate('fecha_vencimiento', now()->toDateString())
            ->count();


        $clientes = Cliente::orderBy('nombres')
            ->get();

        $planes = Plan::where('estado', true)
            ->orderBy('nombre')
            ->get();


        return view('pagos.index', compact(
            'pagos',
            'clientes',
            'planes',
            'buscar',
            'estado',
            'planId',
            'periodo',
            'pendientesMonto',
            'vencidosMonto',
            'pagadosMonto',
            'venceHoyMonto',
            'pendientesCantidad',
            'vencidosCantidad',
            'pagadosCantidad',
            'venceHoyCantidad'
        ));
    }


    /**
     * Asignar un plan a un cliente.
     */
    public function asignarPlan(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => [
                'required',
                'exists:clientes,id',
            ],

            'plan_id' => [
                'required',
                'exists:planes,id',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_vencimiento' => [
                'required',
                'date',
            ],
        ], [
            'cliente_id.required' =>
            'Debes seleccionar un cliente.',

            'cliente_id.exists' =>
            'El cliente seleccionado no existe.',

            'plan_id.required' =>
            'Debes seleccionar un plan.',

            'plan_id.exists' =>
            'El plan seleccionado no existe.',

            'fecha_inicio.required' =>
            'La fecha de inicio es obligatoria.',

            'fecha_vencimiento.required' =>
            'La fecha de vencimiento es obligatoria.',
        ]);
        $planActivo = ClientePlan::where(
            'cliente_id',
            $datos['cliente_id']
        )
            ->where('estado', true)
            ->first();


        if ($planActivo) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'El cliente ya tiene un plan activo. No se puede asignar otro.'
                )
                ->withInput();
        }

        DB::transaction(function () use ($datos) {

            /*
             * Desactivar plan anterior.
             */
            ClientePlan::where('cliente_id', $datos['cliente_id'])
                ->where('estado', true)
                ->update([
                    'estado' => false,
                    'fecha_fin' => Carbon::parse(
                        $datos['fecha_inicio']
                    )->subDay(),
                ]);


            $plan = Plan::findOrFail(
                $datos['plan_id']
            );


            /*
             * Crear nuevo contrato.
             */
            $clientePlan = ClientePlan::create([
                'cliente_id' => $datos['cliente_id'],
                'plan_id' => $datos['plan_id'],
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => null,
                'precio' => $plan->precio,
                'estado' => true,
            ]);


            /*
             * Crear primer pago.
             */
            Pago::create([
                'cliente_id' => $datos['cliente_id'],
                'cliente_plan_id' => $clientePlan->id,
                'periodo' => Carbon::parse(
                    $datos['fecha_inicio']
                )->startOfMonth(),

                'fecha_vencimiento' =>
                $datos['fecha_vencimiento'],

                'monto' => $plan->precio,

                'monto_pagado' => 0,

                'estado' => 'pendiente',
                'modificado' => auth()->user()->id ?? 0
            ]);
        });


        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Plan asignado correctamente al cliente y se generó su pago.'
            );
    }


    /**
     * Registrar un pago.
     */
    public function registrarPago(Request $request, Pago $pago)
    {
        $datos = $request->validate([
            'monto_pagado' => [
                'required',
                'numeric',
                'min:0',
            ],

            'fecha_pago' => [
                'required',
                'date',
            ],

            'metodo_pago' => [
                'required',
                'string',
                'max:30',
            ],

            'referencia' => [
                'nullable',
                'string',
                'max:100',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'monto_pagado.required' =>
            'El monto pagado es obligatorio.',

            'monto_pagado.numeric' =>
            'El monto pagado debe ser numérico.',

            'monto_pagado.min' =>
            'El monto no puede ser negativo.',

            'fecha_pago.required' =>
            'La fecha de pago es obligatoria.',

            'metodo_pago.required' =>
            'Selecciona un método de pago.',
        ]);

        $monto_pagado_actual = $pago->monto_pagado;

        $total_abonado = (float)$monto_pagado_actual + (float) $datos['monto_pagado'];
        if (
            $total_abonado >
            (float) $pago->monto
        ) {

            return back()
                ->withErrors([
                    'monto_pagado' =>
                    'El monto pagado no puede superar el monto del pago.',
                ])
                ->withInput();
        }



        $pago->monto_pagado =
            $total_abonado;

        $pago->fecha_pago =
            $datos['fecha_pago'];

        $pago->metodo_pago =
            $datos['metodo_pago'];

        $pago->referencia =
            $datos['referencia'] ?? null;

        $pago->observacion =
            $datos['observacion'] ?? null;





        if (
            $total_abonado
            >=
            (float) $pago->monto
        ) {

            $pago->estado = 'pagado';
        } else {

            $pago->estado = 'pendiente';
        }
        $pago->modificado = auth()->user()->id ?? 0;

        $pago->save();


        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago registrado correctamente.'
            );
    }


    /**
     * Editar información del pago.
     */
    public function update(Request $request, Pago $pago)
    {
        $datos = $request->validate([
            'fecha_vencimiento' => [
                'required',
                'date',
            ],

            'monto' => [
                'required',
                'numeric',
                'min:0',
            ],

            'observacion' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'estado' => [
                'required',
                'in:pendiente,pagado,vencido,anulado',
            ],
        ]);


        $pago->fecha_vencimiento =
            $datos['fecha_vencimiento'];

        $pago->monto =
            $datos['monto'];

        $pago->observacion =
            $datos['observacion'] ?? null;

        if ($pago->estado === 'pagado') {
            return redirect()
                ->back()
                ->with('error', 'El pago ya no se puede modificar.');
        }
        $pago->estado =
            $datos['estado'];
        $pago->modificado = auth()->user()->id ?? 0;
        $pago->save();


        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago actualizado correctamente.'
            );
    }


    /**
     * Anular pago.
     */
    public function destroy(Pago $pago)
    {
        $clientePlan = $pago->clientePlan;

        // Anular pago
        $pago->estado = 'anulado';
        $pago->modificado = auth()->user()->id ?? 0;
        $pago->save();

        // Verificar si quedan otros pagos activos
        if ($clientePlan) {

            $tienePagosActivos = Pago::where(
                'cliente_plan_id',
                $clientePlan->id
            )
                ->where('id', '!=', $pago->id)
                ->whereIn('estado', [
                    'pendiente',
                    'vencido',
                    'pagado',
                ])
                ->exists();

            if (!$tienePagosActivos) {
                $clientePlan->estado = false;
                $clientePlan->save();
            }
        }

        return redirect()
            ->route('pagos.index')
            ->with(
                'success',
                'Pago anulado correctamente.'
            );
    }


    /**
     * Generar enlace de WhatsApp.
     */
    public function whatsapp(Pago $pago)
    {
        $pago->load([
            'cliente',
            'clientePlan.plan',
        ]);


        $cliente = $pago->cliente;

        $nombre = $cliente->nombre;

        $telefono = preg_replace(
            '/\D/',
            '',
            $cliente->telefono
        );


        if (!$telefono) {

            return back()
                ->withErrors([
                    'whatsapp' =>
                    'El cliente no tiene un número de teléfono válido.',
                ]);
        }


        /*
         * Perú.
         */
        if (strlen($telefono) === 9) {
            $telefono = '51' . $telefono;
        }


        $plan = $pago->clientePlan?->plan?->nombre
            ?? 'Servicio de internet';


        $montoPendiente =
            max(
                0,
                (float) $pago->monto -
                    (float) $pago->monto_pagado
            );


        if ($pago->estado === 'vencido') {

            $mensaje =
                "Hola {$nombre} 👋\n\n" .
                "Te recordamos que tienes un pago vencido " .
                "de tu servicio de internet Valenet.\n\n" .
                "📦 Plan: {$plan}\n" .
                "💰 Monto pendiente: S/ " .
                number_format(
                    $montoPendiente,
                    2
                ) .
                "\n" .
                "📅 Vencimiento: " .
                $pago->fecha_vencimiento
                ->format('d/m/Y') .
                "\n\n" .
                "Por favor realiza tu pago para mantener " .
                "activo tu servicio.\n\n" .
                "Gracias por confiar en Valenet.";
        } else {

            $mensaje =
                "Hola {$nombre} 👋\n\n" .
                "Te recordamos el pago de tu servicio " .
                "de internet Valenet.\n\n" .
                "📦 Plan: {$plan}\n" .
                "💰 Monto: S/ " .
                number_format(
                    $montoPendiente,
                    2
                ) .
                "\n" .
                "📅 Fecha de vencimiento: " .
                $pago->fecha_vencimiento
                ->format('d/m/Y') .
                "\n\n" .
                "Gracias por confiar en Valenet.";
        }


        RecordatorioPago::create([
            'pago_id' => $pago->id,
            'canal' => 'whatsapp',
            'tipo' => 'manual',
            'fecha_envio' => now(),
            'mensaje' => $mensaje,
        ]);


        $url =
            'https://wa.me/' .
            $telefono .
            '?text=' .
            urlencode($mensaje);


        return redirect()->away($url);
    }
}
