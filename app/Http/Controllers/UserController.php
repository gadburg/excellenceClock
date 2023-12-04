<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class UserController extends Controller
{

    /**
     * Muestra la lista de usuarios, retorna el objeto con los datos y la vista
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */

    public function index(Request $request)
    {
        //obtenemos el id del usuario activo
        $userId = $request->user()->id;

        //si el usuario es 1, el admin de la app, obentemos todos los resultados
        if ($userId === 1) {
            $users = User::with('empresa')->orderBy('id_empresa')->get();
            return Inertia('Users/Index', ['users' => $users]);
        }else{
            //si no, obtenemos los usuarios de la misma empresa
            $userEmpresa = $request->user()->id_empresa;
            $users = User::where('id_empresa', $userEmpresa)->get();
            return Inertia('Users/Index', ['users' => $users]);
        }
    }

     /**
     * Retorna los usuarios de la empresa que recibe
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerUsuariosEmpresa(Request $request)
    {
        $empresaUserId = $request->user()->id_empresa;
        $usuarios = User::where('id_empresa',$empresaUserId)->get();
        
        return response()->json($usuarios);
    }

    /**
     * Retorna la vista con el formulario para crear usuarios
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return inertia('Users/Create');
    }

    /**
     * Inserta un usuario nuevo en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            //valida los datos
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => 'required',
                'id_empresa' => 'required',
            ]);
            //crea el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_empresa' => $request->id_empresa,
            ]);

            //obtenemos el id del usuario activo
            $userId = $request->user()->id;

            //si es 1, el admin de la app, asigna el rol de responsable
            if ($userId == 1) {
                $user->assignRole('responsable');
            }

            $user->save();
            //redirige a la lista de usuarios
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al crear el usuario. Por favor, intenta de nuevo.']);
        }
    }

     /**
     * Comprueba si el email esta en uso en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comprobarEmail(Request $request)
    {
        try {
            //comprueba si existe
            $emailExists = User::where('email', $request->email)->exists();     
            return response()->json(['exists' => $emailExists]);

        } catch (\Exception $e) {

            Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retorna la vista para la edicion de un usuario
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    public function edit(User $user)
    {
       return inertia('Users/Edit', ['user' => $user]);
    }

    /**
     * Actualiza un usuario en la db
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Empresa $empresa)
    {
        //valida los datos
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        //actualiza la informacion
        $empresa->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        //retorna al listado
        return redirect()->route('users.index');
    }
   
    /**
     * Elimina un usuario de la db
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
       
            $user->delete();
            return redirect()->route('users.index');

    }
    
}