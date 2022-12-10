<?php

namespace App\Http\Controllers;

use App\Jobs\segundo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class verificarController extends Controller
{

    public function segundoCorreo(Request $request)
    {
        $user = User::find($request->id);
        $url = URL::temporarySignedRoute('verificarTelefono',now()->addMinutes(5),
        ['id'=>$user->id]);

        segundo::dispatch($user,$url
        )->onQueue('phone')
        ->onConnection('database')
        ->delay(now()->addSeconds(30));
    }

    public function telefono(Request $request)
    {
        $Code = rand(1000,9999);
        $user = User::find($request->id);
        //dd($user);
        $user->verificationCode = $Code;
        $user->save();

        segundo::dispatch($user,$Code)
        ->onQueue('phone')
        ->onConnection('database')
        ->delay(now()->addSeconds(30));

        return View('espera');
    }

    public function codigo(Request $request)
    {
        $user = User::find($request->id);
        $random = $user->verificationCode;
        $validateUser = Validator::make($request->all(),
        [
            'Code'=>'required|integer|min:4',
        ]);
        if($validateUser->fails()){
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => [$validateUser->errors()],
                "data"      => []
            ],400);
        }
            if($random == $request->Code)
            {
                $user -> status = 1;
                $user->save();
                return response()->json([
                    "message"=>"Cuenta Activada Correctamente"
                ],200);
            }
            else{
                return response()->json([
                    "message"=>"Codigo incorrecto"
                ],401);
            }
    }
}
