<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();
        return inertia('Empresa/Index', ['empresas' => $empresas]);
    }

    public function create()
    {
        return inertia('Empresa/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'responsable' => 'required',
        ]);

        Empresa::create([
            'nombre' => $request->nombre,
            'responsable' => $request->responsable,
        ]);

        return redirect()->route('empresas.index');
    }

    public function edit(Empresa $empresa)
    {
        return inertia('Empresa/Edit', ['empresa'=> $empresa]);
    }

    public function update(Request $request, Empresa $empresa){
        $request->validate([
            'nombre' => 'required',
            'responsable' => 'required',
        ]);
    
        $empresa->update([
            'nombre' => $request->nombre,
            'responsable' => $request->responsable,
        ]);
    
        return redirect()->route('empresas.index');
    }

    public function destroy(Empresa $empresa)
    {
        try{
            $empresa->delete();
            return Inertia::location(route('empresas.index'));
            //return redirect()->route('empresas.index');
        }catch (\Exception $e) {
            Log::error('Error borrar el registro: ' . $e->getMessage());
            return Inertia::location(route('empresas.index'));
        }
    }

    public function borrar(Request $request, Empresa $empresa)
    {
        try{
            $empresa->delete();
            return redirect()->route('empresas.index');
        }catch (\Exception $e) {
            Log::error('Error borrar el registro: ' . $e->getMessage());
        }
    }

   
}