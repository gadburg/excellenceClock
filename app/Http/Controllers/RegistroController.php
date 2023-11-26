<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Inertia\Inertia;

class RegistroController extends Controller
{


    // Método para mostrar la lista de registros
    public function index()
    {
        // Obtén los registros desde la base de datos
        $registros = Registro::all();

        // Devuelve los registros a la vista de Inertia
        return inertia('Registros/Index', [
            'registros' => $registros,
        ]);
    }

    /* Método para mostrar el formulario de creación
    public function create()
    {
        // Muestra el formulario de creación
        return inertia('Registros/Create');
    }*/

    public function store(Request $request)
    {
        $data = [
            'id_usuario' => $request->input('id_usuario'),
            'fecha' => $request->input('fecha'),
            'hora_entrada' => $request->input('hora_entrada'),
            'hora_salida' => $request->input('hora_salida'),
            'longitud' => $request->input('longitud'),
            'latitud' => $request->input('latitud'),
            'ip' => $request->ip(),
        ];

        Registro::create($data);

        return redirect()->back();
    }

    // Método para procesar la creación de un nuevo registro
    /*public function store(Request $request)
    {
        // Valida y crea un nuevo registro
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id', // Asegúrate de ajustar esto según tu lógica
            'fecha' => 'required|date',
            'hora_entrada' => 'required|date_format:H:i:s',
            'hora_salida' => 'required|date_format:H:i:s',
            'longitud' => 'required|numeric',
            'latitud' => 'required|numeric',
            'ip' => 'required|ip',
        ]);

        Registro::create([
            'id_usuario' => $request->input('id_usuario'),
            'fecha' => $request->input('fecha'),
            'hora_entrada' => $request->input('hora_entrada'),
            'hora_salida' => $request->input('hora_salida'),
            'longitud' => $request->input('longitud'),
            'latitud' => $request->input('latitud'),
            'ip' => $request->input('ip'),
        ]);

        // Redirige de vuelta a la lista de registros después de la creación
        return redirect(route('dashboard'));
    }*/
    /*public function index(){
        return Inertia::render('Registros/Index', [
            'registros' => Registro::all(),
        ]);
    }
    public function create(){
        return inertia('registros/Create');
    }

    public function store(Request $request){

        Request->validate([
            //'id_usuario' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'hora_salida' => 'required',
            'longitud' => 'required|numeric',
            'latitud' => 'required|numeric',
            'ip' => 'required|ip',
            
        ]);

         // Crear un nuevo registro
    $registro = new Registro([
        'id_usuario' => auth()->id(), // Obtener el ID del usuario autenticado
        'fecha' => $request->fecha,
        'hora_entrada' => $request->hora_entrada,
        'hora_salida' => $request->hora_salida,
        'longitud' => $request->longitud,
        'latitud' => $request->latitud,
        'ip' => $request->ip,
    ]);

    // Guardar el registro en la base de datos
    $registro->save();

    // Puedes devolver una respuesta, por ejemplo, un mensaje de éxito
    return response()->json(['message' => 'Registro almacenado con éxito']);
        //Registro::create($request->all());

        //return response()->json(['message' => 'Registro horario almacenado correctamente']);
    }


    public function insertarRegistro(Request $request)
    {
        // Aquí obtienes los datos del cuerpo de la solicitud
        $data = $request->all();

        // Luego, puedes usar Eloquent para insertar en la base de datos
        Registro::create($data);

        // Puedes devolver una respuesta si es necesario
        return response()->json(['message' => 'Registro insertado con éxito']);
    }
    /*public function storeEntrada(Request $request)
    {
        $data = $request->validate([
            'id_usuario' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_entrada' => 'required|date',
            'longitud' => 'required|numeric',
            'latitud' => 'required|numeric',
            'ip' => 'required|ip',
        ]);

        $registro = Registro::create($data);

        return response()->json(['message' => 'Entrada registrada con éxito', 'registro' => $registro], 201);
    }

    public function storeSalida(Request $request, Registro $registro)
    {
        $data = $request->validate([
            'hora_salida' => 'required|date',
        ]);

        $registro->update($data);

        return response()->json(['message' => 'Salida registrada con éxito'], 200);
    }

    public function show(Event $event)
    {
        return Inertia::render('Event/Show', [
            'event' => $event->only(
                'id',
                'title',
                'start_date',
                'description'
            ),
        ]);
    }*/
}
