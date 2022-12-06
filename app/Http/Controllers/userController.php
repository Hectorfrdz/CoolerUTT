<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    public function updateUser(Request $request,$id)
    {
        $validateUser = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required|integer',
            'password'=>'required',
            'lastname'=>'required',
            'Username'=>'required|max:250',
            'Active_Key'=>'required|max:250',
            'red'=>'required|max:250',
            'contrasena_red'=>'required|max:250',
        ]);

        if($validateUser->fails()){
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => [$validateUser->errors()],
                "data"      => []
            ],400);
        }
        $user = User::find($id);

       
        $user->name=$request->name;
        $user->lastname=$request->lastname;
        $user->email=$request->email;
        $user->phone=$request->phone;
        $user->red=$request->red;
        $user->contrasena_red=$request->contrasena_red;
        $user->Username=$request->Username;
        $user->Active_Key=$request->Active_Key;
        $user->password= Hash::make($request->password);

        if($user->save())
        {
            return response()->json([
                "status"    => 200,
                "message"   => "Usuario Actualizado",
                "error"     => [],
                "data"      => $user
            ],200);
        }
        return response()->json([
            "status"    => 400,
            "message"   => "Ocurrio un error, vuelva a intentarlo",
            "error"     => $user,
            "data"      => []
        ],400);
    }
}
