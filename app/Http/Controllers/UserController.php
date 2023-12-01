<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return Inertia('User/Index', ['users' => $users]);
    }

    public function obtenerUsuariosEmpresa(Request $request)
    {
        $empresaUserId = $request->user()->id_empresa;
        $usuarios = User::where('id_empresa',$empresaUserId)->get();
        
        return response()->json($usuarios);
    }
}