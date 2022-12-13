<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function updateUser(Request $request,$id)
    {
        $validateUser = Validator::make($request->all(),
        [
            'Username'=>'required|max:250',
            'Active_Key'=>'required|max:250',
            'red'=>'required|max:250',
            'contrasena_red'=>'required|max:250',
        ],
        [
            'Username.required'=>'El campo :attribute es obligatorio',
            'Username.max'=>'El campo :attribute debe tener maximo 250 caracteres',
            'Active_Key.required'=>'El campo :attribute es obligatorio',
            'Active_Key.max'=>'El campo :attribute debe tener maximo 250 caracteres',
            'red.required'=>'El campo :attribute es obligatorio',
            'red.max'=>'El campo :attribute debe tener maximo 250 caracteres',
            'contrasena_red.required'=>'El campo :attribute es obligatorio',
            'contrasena_red.max'=>'El campo :attribute debe tener maximo 250 caracteres',
        ]);

        if($validateUser->fails()){
            Log::channel('errores')->error('Error en las validaciones');
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => [$validateUser->errors()],
                "data"      => []
            ],400);
        }
        $user = User::find($id);

        $user->red=$request->red;
        $user->contrasena_red=$request->contrasena_red;
        $user->Username=$request->Username;
        $user->Active_Key=$request->Active_Key;

        if($user->save())
        {
            Log::channel('slackInfo')->info('usuario actualizado');
            return response()->json([
                "status"    => 200,
                "message"   => "Usuario Actualizado",
                "error"     => [],
                "data"      => $user
            ],200);
        }
        Log::channel('errores')->error('Error');
        return response()->json([
            "status"    => 400,
            "message"   => "Ocurrio un error, vuelva a intentarlo",
            "error"     => $user,
            "data"      => []
        ],400);
    }

    public function showUser($id)
    {
        $user = User::find($id);
        if($user)
        {
            Log::channel('slackInfo')->info('Se mostro a un usuario');
            return response()->json([
                "status"=>200,
                "data"=>$user
            ],200);
        }
        return response()->json([
            "status"=>400,
            "message"=>"usuario no encontrado"
        ],400);
    }

    public function adafruit($id)
    {
        $user = User::find($id);
        if($user)
        {
            Log::channel('slackInfo')->info('Se mostraron datos');
            return response()->json([
                "status"=>200,
                "Active_Key"=>$user->Active_Key,
                "Username"=>$user->Username,
            ],200);
        }
        Log::channel('errores')->error('usuario no encontrado');
        return response()->json([
            "status"=>400,
            "message"=>"usuario no encontrado"
        ],400);
    }
}