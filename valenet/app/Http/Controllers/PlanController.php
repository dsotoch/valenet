<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    /**
     * Listado de planes.
     */
    public function index(Request $request)
    {
        $busqueda = trim($request->input('buscar', ''));

        $planes = Plan::query()

            ->when($busqueda !== '', function ($query) use ($busqueda) {

                $query->where(function ($q) use ($busqueda) {

                    $q->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('velocidad', 'like', "%{$busqueda}%")
                        ->orWhere('descripcion', 'like', "%{$busqueda}%");

                });

            })

            ->orderBy('nombre')

            ->paginate(100)

            ->withQueryString();


        return view('planes.index', compact(
            'planes',
            'busqueda'
        ));
    }


    /**
     * Guardar nuevo plan.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([

            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'velocidad' => [
                'required',
                'string',
                'max:50',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],

            'estado' => [
                'required',
                'boolean',
            ],

        ], [

            'nombre.required' =>
                'El nombre del plan es obligatorio.',

            'nombre.max' =>
                'El nombre del plan no puede superar los 100 caracteres.',

            'velocidad.required' =>
                'La velocidad es obligatoria.',

            'velocidad.max' =>
                'La velocidad no puede superar los 50 caracteres.',

            'precio.required' =>
                'El precio es obligatorio.',

            'precio.numeric' =>
                'El precio debe ser un número válido.',

            'precio.min' =>
                'El precio no puede ser negativo.',

            'descripcion.max' =>
                'La descripción no puede superar los 500 caracteres.',

            'estado.required' =>
                'El estado es obligatorio.',

            'estado.boolean' =>
                'El estado seleccionado no es válido.',
        ]);


        Plan::create($datos);


        return redirect()
            ->route('planes.index')
            ->with(
                'success',
                'Plan registrado correctamente.'
            );
    }


    /**
     * Mostrar plan.
     */
    public function show(Plan $plan)
    {
        return view('planes.show', compact('plan'));
    }


    /**
     * Actualizar plan.
     */
    public function update(Request $request, String $plan)
    {
        $datos = $request->validate([

            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'velocidad' => [
                'required',
                'string',
                'max:50',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],

            'estado' => [
                'required',
                'boolean',
            ],

        ], [

            'nombre.required' =>
                'El nombre del plan es obligatorio.',

            'nombre.max' =>
                'El nombre del plan no puede superar los 100 caracteres.',

            'velocidad.required' =>
                'La velocidad es obligatoria.',

            'velocidad.max' =>
                'La velocidad no puede superar los 50 caracteres.',

            'precio.required' =>
                'El precio es obligatorio.',

            'precio.numeric' =>
                'El precio debe ser un número válido.',

            'precio.min' =>
                'El precio no puede ser negativo.',

            'descripcion.max' =>
                'La descripción no puede superar los 500 caracteres.',

            'estado.required' =>
                'El estado es obligatorio.',

            'estado.boolean' =>
                'El estado seleccionado no es válido.',
        ]);


        $plan=Plan::findor($plan);
        $plan->update($datos);


        return redirect()
            ->route('planes.index')
            ->with(
                'success',
                'Plan actualizado correctamente.'
            );
    }


    /**
     * Eliminar plan.
     */
    public function destroy(string $plan)
    {
        $plan=Plan::findor($plan);
        $plan->delete();


        return redirect()
            ->route('planes.index')
            ->with(
                'success',
                'Plan eliminado correctamente.'
            );
    }
}