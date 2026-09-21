<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function mostrarLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
 dd([
            'id' => $user->id,
            'rol' => $user->rol,
        ]);
            if ($user->rol == "administrador") {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('pagos.index');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | RECORDAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $recordarme = $request->boolean('recordarme');


        /*
        |--------------------------------------------------------------------------
        | AUTENTICAR
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::attempt(
                [
                    'email' => $credenciales['email'],
                    'password' => $credenciales['password'],
                    'estado' => true,
                ],
                $recordarme
            )
        ) {
            return back()
                ->withInput($request->only('email', 'recordarme'))
                ->withErrors([
                    'email' => 'El correo o la contraseña son incorrectos.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERAR SESIÓN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();
        if ($user->rol == "administrador") {
            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Bienvenido a Valenet.');
        } else {
            return redirect()
                ->intended(route('pagos.index'))
                ->with('success', 'Bienvenido a Valenet.');
        }
    }


    /**
     * Cerrar sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    public function indexDashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        */

        $totalClientes = Cliente::count();

        $clientesActivos = Cliente::where('estado', 'Activo')->count();

        $clientesInactivos = Cliente::where('estado', 'Inactivo')->count();


        /*
        |--------------------------------------------------------------------------
        | PLANES
        |--------------------------------------------------------------------------
        */

        $totalPlanes = Plan::count();

        $planesActivos = Plan::where('estado', true)->count();


        /*
        |--------------------------------------------------------------------------
        | PAGOS DEL MES
        |--------------------------------------------------------------------------
        */

        $pagosMes = Pago::whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0)
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year);

        $totalPagosMes = (clone $pagosMes)->count();

        $montoPagosMes = (clone $pagosMes)->sum('monto_pagado');


        /*
        |--------------------------------------------------------------------------
        | PAGOS MES ANTERIOR
        |--------------------------------------------------------------------------
        */

        $pagosMesAnterior = Pago::whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0)
            ->whereMonth(
                'fecha_pago',
                now()->subMonth()->month
            )
            ->whereYear(
                'fecha_pago',
                now()->subMonth()->year
            )
            ->sum('monto_pagado');


        /*
        |--------------------------------------------------------------------------
        | VARIACIÓN DE PAGOS
        |--------------------------------------------------------------------------
        */

        if ($pagosMesAnterior > 0) {
            $variacionPagos = (
                ($montoPagosMes - $pagosMesAnterior)
                / $pagosMesAnterior
            ) * 100;
        } else {
            $variacionPagos = $montoPagosMes > 0 ? 100 : 0;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGOS VENCIDOS
        |--------------------------------------------------------------------------
        */

        $pagosVencidos = Pago::where('estado', 'vencido')
            ->count();

        $montoVencido = Pago::where('estado', 'vencido')
            ->get()
            ->sum(function ($pago) {
                return max(
                    0,
                    (float) $pago->monto -
                        (float) $pago->monto_pagado
                );
            });


        /*
        |--------------------------------------------------------------------------
        | ESTADOS DE PAGOS
        |--------------------------------------------------------------------------
        */

        $pagosPagados = Pago::where('estado', 'pagado')
            ->count();

        $pagosPendientes = Pago::where('estado', 'pendiente')
            ->count();

        $pagosVencidos = Pago::where('estado', 'vencido')
            ->count();

        $pagosAnulados = Pago::where('estado', 'anulado')
            ->count();

        $totalPagosEstados =
            $pagosPagados +
            $pagosPendientes +
            $pagosVencidos +
            $pagosAnulados;


        /*
        |--------------------------------------------------------------------------
        | PORCENTAJES
        |--------------------------------------------------------------------------
        */

        if ($totalPagosEstados > 0) {

            $porcentajePagados = round(
                ($pagosPagados / $totalPagosEstados) * 100
            );

            $porcentajePendientes = round(
                ($pagosPendientes / $totalPagosEstados) * 100
            );

            $porcentajeVencidos = round(
                ($pagosVencidos / $totalPagosEstados) * 100
            );
        } else {

            $porcentajePagados = 0;
            $porcentajePendientes = 0;
            $porcentajeVencidos = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CLIENTES NUEVOS DEL MES
        |--------------------------------------------------------------------------
        */

        $clientesNuevos = Cliente::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD RECIENTE
        |--------------------------------------------------------------------------
        */

        $ultimosClientes = Cliente::latest()
            ->limit(5)
            ->get();

        $ultimosPagos = Pago::with('cliente')
            ->whereNotNull('fecha_pago')
            ->where('monto_pagado', '>', 0)
            ->orderByDesc('fecha_pago')
            ->limit(5)
            ->get();

        $ultimosPlanes = Plan::latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ACTIVIDAD UNIFICADA
        |--------------------------------------------------------------------------
        */

        $actividades = collect();


        foreach ($ultimosClientes as $cliente) {

            $nombre = trim(
                ($cliente->nombres ?? '') . ' ' .
                    ($cliente->apellidos ?? '')
            );

            $actividades->push([
                'tipo' => 'cliente',
                'titulo' => 'Nuevo cliente registrado',
                'descripcion' => $nombre ?: 'Cliente',
                'fecha' => $cliente->created_at,
            ]);
        }


        foreach ($ultimosPagos as $pago) {

            $nombre = 'Cliente';

            if ($pago->cliente) {
                $nombre = trim(
                    ($pago->cliente->nombres ?? '') . ' ' .
                        ($pago->cliente->apellidos ?? '')
                );

                if ($nombre === '') {
                    $nombre = 'Cliente';
                }
            }

            $actividades->push([
                'tipo' => 'pago',
                'titulo' => 'Pago registrado',
                'descripcion' => 'S/ ' . number_format(
                    $pago->monto_pagado,
                    2
                ) . ' · ' . $nombre,
                'fecha' => $pago->fecha_pago,
            ]);
        }


        foreach ($ultimosPlanes as $plan) {

            $actividades->push([
                'tipo' => 'plan',
                'titulo' => 'Nuevo plan creado',
                'descripcion' => $plan->nombre,
                'fecha' => $plan->created_at,
            ]);
        }


        $actividades = $actividades
            ->sortByDesc('fecha')
            ->take(8)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', compact(
            'totalClientes',
            'clientesActivos',
            'clientesInactivos',
            'clientesNuevos',

            'totalPlanes',
            'planesActivos',

            'totalPagosMes',
            'montoPagosMes',
            'pagosMesAnterior',
            'variacionPagos',

            'pagosVencidos',
            'montoVencido',

            'pagosPagados',
            'pagosPendientes',
            'pagosAnulados',
            'totalPagosEstados',

            'porcentajePagados',
            'porcentajePendientes',
            'porcentajeVencidos',

            'actividades'
        ));
    }

    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');

        $usuarios = User::query()
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('name', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%")
                        ->orWhere('rol', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact(
            'usuarios',
            'busqueda'
        ));
    }


    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

            'rol' => [
                'required',
                Rule::in([
                    'administrador',
                    'operador',
                    'usuario',
                ]),
            ],

            'estado' => [
                'required',
                'boolean',
            ],
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
            'estado' => $datos['estado'],
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }


    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($usuario->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'rol' => [
                'required',
                Rule::in([
                    'administrador',
                    'operador',
                    'usuario',
                ]),
            ],

            'estado' => [
                'required',
                'boolean',
            ],
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->rol = $datos['rol'];
        $usuario->estado = $datos['estado'];

        if (!empty($datos['password'])) {
            $usuario->password = Hash::make(
                $datos['password']
            );
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }


    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
