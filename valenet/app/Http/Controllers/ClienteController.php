<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Mostrar clientes.
     */
    public function index(Request $request)
    {
        $busqueda = $request->get('buscar');

        $clientes = Cliente::query()
            ->when($busqueda, function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombres', 'like', "%{$busqueda}%")
                        ->orWhere('apellidos', 'like', "%{$busqueda}%")
                        ->orWhere('documento', 'like', "%{$busqueda}%")
                        ->orWhere('telefono', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%");
                });
            })
            ->latest()
            ->paginate(100)
            ->withQueryString();

        return view('clientes.index', compact(
            'clientes',
            'busqueda'
        ));
    }

    /**
     * Guardar cliente.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres' => [
                'required',
                'string',
                'max:100',
            ],

            'apellidos' => [
                'required',
                'string',
                'max:100',
            ],

            'documento' => [
                'required',
                'string',
                'max:20',
                'unique:clientes,documento',
            ],

            'telefono' => [
                'nullable',
                'string',
                'min:9',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'estado' => [
                'nullable',
                'boolean',
            ],
        ]);

        $datos['estado'] = $request->boolean('estado');

        Cliente::create($datos);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $datos = $request->validate([
            'nombres' => [
                'required',
                'string',
                'max:100',
            ],

            'apellidos' => [
                'required',
                'string',
                'max:100',
            ],

            'documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clientes', 'documento')
                    ->ignore($cliente->id),
            ],

            'telefono' => [
                'nullable',
                'string',
                'min:9',
                'max:20',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'direccion' => [
                'nullable',
                'string',
                'max:255',
            ],

            'estado' => [
                'nullable',
                'boolean',
            ],
        ]);

        $datos['estado'] = $request->boolean('estado');

        $cliente->update($datos);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}