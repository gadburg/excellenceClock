<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class RegistroController extends Controller
{

    /**
     * Muestra y retorna la lista de registros recientes de un usuario, solo muestra los ultimos 7 registros, retorna los datos y la vista
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {  
        //obtenemos el id del usuario
        $userId = $request->user()->id;

        //filtramos los registros por el usuario y ordenamos los recientes primero, solo mostramos 7
        $registros = Registro::where('id_usuario', $userId)->orderBy('fecha', 'desc')->take(7)->get();

        return inertia('Registro/Index', ['registros' => $registros,]);
    }

    /**
     * Retorna la ruta para la creacion del registro
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        //mostramos la creacion
        return inertia('Registro/Create');
    }

    /**
     * Inserta un registro en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //obtenemos el usuario activo
        $userId = $request->user()->id;

        //validamos los datos
        $request->validate([
            'fecha' => 'required',
            'rango' => 'required',
            'ubicacion' => 'required',
            'ip' => 'required',
            'total' => 'required',
        ]);

        try {
            //busca un registro existente para el mismo usuario y fecha
            $existingRecord = Registro::where('id_usuario', $userId)
                ->where('fecha', $request->fecha)
                ->first();

            //si ya existe un registro para este usuario y fecha, no hacemos nada y retornamos al listado
            if ($existingRecord) {
                return redirect()->route('registros.index');
            }

            //si no existe, crea un nuevo registro
            Registro::create([
                'id_usuario' => $userId,
                'fecha' => $request->fecha,
                'rango' => $request->rango,
                'ubicacion' => $request->ubicacion,
                'ip' => $request->ip,
                'total' => $request->total,
            ]);
            //retorna al listado
            return redirect()->route('registros.index');
        } catch (\Exception $e) {
            Log::error('Error al almacenar el registro: ' . $e->getMessage());
            return redirect()->route('registros.index')->with('error', 'Hubo un error al actualizar el registro.');
        }
    }

    /**
     * Retorna la vista para la edicion de un registro
     *
     * @param  \App\Models\Registro  $registro
     * @return \Inertia\Response
     */
    public function edit(Registro $registro)
    {
        return inertia('Registro/Edit', ['registro'=> $registro]);
    }


     /**
     * Actualizamos un registro en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Registro $registro)
    {
        try {
            //validamos los datos
            $request->validate([
                'rango' => 'required',
                'total' => 'required',
            ]);

            //verifica si el valor total no es '00:00:00' antes de actualizar
            if ($registro->total == '00:00:00') {
                //actualiza solo si el nuevo valor total no es '00:00:00'
                if ($request->total != '00:00:00') {
                    $registro->update([
                        'rango' => $request->rango,
                        'total' => $request->total,
                    ]);
                }
            }
            //retornamos a la vista del listado
            return redirect()->route('registros.index');
        } catch (\Exception $e) {
            Log::error('Error al almacenar el registro: ' . $e->getMessage());
            return redirect()->route('registros.index')->with('error', 'Hubo un error al actualizar el registro.');
        }
    }

    /**
     * Obtiene y retorna los datos de un mes del año del usuario activo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $mes
     * @param  int  $anio
     * @return \Illuminate\Http\JsonResponse
     */
    public function mostrarMes(Request $request, $mes, $anio){
        //obtenemos el usuario activo
        $userId = $request->user()->id;
        //obtenemos los datos
        $registros = Registro::where('id_usuario', $userId)
            ->whereMonth('fecha', $mes)  
            ->whereYear('fecha', $anio)           
            ->orderBy('fecha', 'asc')
            ->select('fecha', 'rango', 'ubicacion', 'ip','total')
            ->get();
        //retornamos los datos
        return response()->json($registros);
    }

    /**
     * Obtiene y retorna los datos en un rango de fechas especifico del usuario activo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     * @return \Illuminate\Http\JsonResponse
     */
    public function horasRango(Request $request, $fechaInicio, $fechaFin) {
        //obtenemos el usuario activo
        $userId = $request->user()->id;
    
        //obtenemos los registros para el rango recibido
        $registros = Registro::where('id_usuario', $userId)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'asc')
            ->select('total')
            ->get();
        //retornamos los datos
        return response()->json($registros);
    }

    
    /**
     * Obtiene y retorna los datos en un rango de fechas especifico de un usuario especifico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerRegistrosRango(Request $request, $userId, $fechaInicio, $fechaFin)
    {
        //obtenemos los datos del usuario recibido en el rango de fechas recibido
        $registros = Registro::with('usuario') 
        ->where('id_usuario', $userId)
        ->whereBetween('fecha', [$fechaInicio, $fechaFin])
        ->orderBy('fecha', 'asc')
        ->get();
        //retornamos los datos
        return response()->json($registros);
    }
    
}
