<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
   
    /**
     * Muestra la lista de empresas, retorna a la vista para mostrarlas
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $empresas = Empresa::all();
        return Inertia('Empresa/Index', ['empresas' => $empresas]);
    }

    /**
     * Retorna la ruta para ir al formulario de crear empresa
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return inertia('Empresa/Create');
    }

    /**
     * Crea una nueva empresa en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //valida los datos
        $request->validate([
            'nombre' => 'required',
            'responsable' => 'required',
        ]);

        //crea la empresa
        Empresa::create([
            'nombre' => $request->nombre,
            'responsable' => $request->responsable,
        ]);

        //retorna la vista con el listado
        return redirect()->route('empresas.index');
    }

    /**
     * Retorna el formulario para editar una empresa
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Inertia\Response
     */
    public function edit(Empresa $empresa)
    {
        return inertia('Empresa/Edit', ['empresa'=> $empresa]);
    }

    /**
     * Actualiza una empresa en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id){
        try{
            //valida los datos
            $request->validate([
                'nombre' => 'required',
                'responsable' => 'required',
            ]);
            //obtiene la empresa por el id
            $empresa = Empresa::findOrFail($id);
            
            //actualiza la empresa
            $empresa->update([
                'nombre' => $request->nombre,
                'responsable' => $request->responsable,
            ]);
            //retorna a la lista
            return redirect()->route('empresas.index')->with('success', 'Empresa actualizada con éxito');
        } catch (\Exception $e) {
            Log::error('Error al actualizar la empresa: ' . $e->getMessage());
            return redirect()->route('empresas.index')->with('error', 'Hubo un error al actualizar la empresa');
        }
    }

    /**
     * Elimina una empresa de la db y retorna a la lista
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Empresa $empresa)
    {
       
            $empresa->delete();
            return redirect()->route('empresas.index');

    }

    /**
     * Obtiene y retorna las empresas en funcion del usuario activo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerEmpresas(Request $request)
    {
        try {
            //obtenemos el id del usuario activo
            $userId = $request->user()->id;
    
            //si es 1, admin de la app, retornamos todas las empresas
            if ($userId == 1) {
                $empresas = Empresa::all();
            } else {
                //si no, obtenemos la empresa del usuario activo
                $empresas = Empresa::where('id', $request->user()->id_empresa)->get();
            }
            //retornamos la respuesta
            return response()->json($empresas);
        } catch (\Exception $e) {
            Log::error('Error en obtenerEmpresas: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
   
}