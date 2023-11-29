<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class RegistroController extends Controller
{

    public function index(Request $request)
    {  
        //Obtenemos el id del usuario
        $userId = $request->user()->id;

        //filtramos los registros por el usuario y ordenamos los recientes primero
        $registros = Registro::where('user_id', $userId)->orderBy('fecha', 'desc')->get();

        return inertia('Registro/Index', [
            'registros' => $registros,
        ]);
    }

    public function create()
    {
        //mostramos la creacion
        return inertia('Registro/Create');
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $request->validate([
            'fecha' => 'required',
            'rango' => 'required',
            'ubicacion' => 'required',
            'ip' => 'required',
            'total' => 'required',
        ]);

        try {
            // Busca un registro existente para el mismo usuario y fecha
            $existingRecord = Registro::where('user_id', $userId)
                ->where('fecha', $request->fecha)
                ->first();

            if ($existingRecord) {
                // Si ya existe un registro para este usuario y fecha, no hacemos nada
                return redirect()->route('registros.index');
            }

            // Si no existe, crea un nuevo registro
            Registro::create([
                'user_id' => $userId,
                'fecha' => $request->fecha,
                'rango' => $request->rango,
                'ubicacion' => $request->ubicacion,
                'ip' => $request->ip,
                'total' => $request->total,
            ]);

            return redirect()->route('registros.index');
        } catch (\Exception $e) {
            Log::error('Error al almacenar el registro: ' . $e->getMessage());
            return response()->json(['message' => 'Error al almacenar el registro'], 500);
        }
    }

    public function edit(Registro $registro)
    {
        return inertia('Registro/Edit', ['registro'=> $registro]);
    }

    public function update(Request $request, Registro $registro){
        $request->validate([
            'rango' => 'required',
            'total' => 'required',
        ]);
    
        $registro->update([
            'rango' => $request->rango,
            'total' => $request->total,
        ]);
    
        return redirect()->route('registros.index');
    }

    public function mostrarMes(Request $request, $mes, $anio){

        $userId = $request->user()->id;

        $registros = Registro::where('user_id', $userId)
            ->whereMonth('fecha', $mes)  
            ->whereYear('fecha', $anio)           
            ->orderBy('fecha', 'asc')
            ->select('fecha', 'rango', 'ubicacion', 'ip','total')
            ->get();

        return response()->json($registros);
    }

    public function horasRango(Request $request, $fechaInicio, $fechaFin) {
        $userId = $request->user()->id;
    
        // Obtener registros para el usuario y el rango de fechas
        $registros = Registro::where('user_id', $userId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->select('total')
            ->get();
    
        return response()->json($registros);
    }

}
