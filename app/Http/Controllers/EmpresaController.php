<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Inertia\Inertia;

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
        $empresa->update($request);
        return redirect()->route('empresas.index');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index');
    }

    public function getEmpresas()
    {
        $empresas  = Empresa::all(['id', 'nombre']);
        return $empresas -> map (function ($empresa){
            return [
                'value' => $empresa->id,
                'label' => $empresa->nombre,
            ];
        });
    }
}