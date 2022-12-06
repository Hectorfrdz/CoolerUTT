<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\primero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
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
        $user = User::create([
            'name'=>$request->name,
            'lastname'=>$request->lastname,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'red'=>$request->red,
            'contrasena_red'=>$request->contrasena_red,
            'Username'=>$request->Username,
            'Active_Key'=>$request->Active_Key,
            'password'=> Hash::make($request->password),
        ]);

        $url = URL::temporarySignedRoute('verificarTelefono',now()->addMinutes(5),
        ['id'=>$user->id]);

        primero::dispatch($user,$url)
        ->onQueue('email')
        ->onConnection('database')
        ->delay(now()->addSeconds(30));

        /*Mail::to($user)->send(new SendMail($user,$url));*/

        return response()->json([
            "status"    => 201,
            "message"   => "Usuario creado",
        ],201);
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
        [
            'email' => 'required|max:250|email',
            'password' => 'required|max:250',
        ],
        [
            "email.required"       => "El campo :attribute es obligatorio",
            "email.max"       => "El campo :attribute tiene que tener maximo :max caracteres",
            "email.email"       => "El campo :attribute tiene que ser un correo valido",
            "password.required"       => "El campo :attribute es obligatorio",
            "password.max"       => "El campo :attribute tiene que tener maximo :max caracteres",
        ]
        );
        if ($validator->fails())
        {
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => [$validator->errors()],
                "data"      => []
            ],400);
        }
            $user = User::where('email', $request['email'])->firstOrfail();
            if(!is_null($user) && Hash::check($request->password,$user->password))
            {
                $user->save();
                if($user->status == 1)
                {
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'status'=>200,
                        'access_token' => $token,
                        'token_type' => 'bearer'
                    ],200);
                }
                else{
                    return response()->json([
                        'message' => 'Usuario bloqueado'
                    ],400);
                }
            }
            else{
                return response()->json([
                    'message' => 'Credenciales no correctas'
                ],400);
            }
    }

    public function logout(Request $request)
    {
        $request->user()->Tokens()->delete();
        return response()->json([
            "status" => 200,
            "msg" => "Sesion Cerrada"
        ]);   
    }
}
