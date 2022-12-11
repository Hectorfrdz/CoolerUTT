<?php

namespace App\Http\Controllers;

use App\Jobs\segundo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $response = Http::withBasicAuth('AC78410a00e0da71f3ebc678757cba36d1','e4c79c95b5793ae22f736ee9be431c0f')
        ->asForm()
        ->post('https://api.twilio.com/2010-04-01/Accounts/AC78410a00e0da71f3ebc678757cba36d1/Messages.json',[

            'To'=>"whatsapp:+5218721371167",
            'From'=>"whatsapp:+14155238886",
            'Body'=>'Tu codigo de verificaciones es: '.$Code,

        ]);

        return $response;
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
                    "status"=>200,
                    "message"=>"Cuenta Activada Correctamente"
                ],200);
            }
            else{
                return response()->json([
                    "status"=>400,
                    "message"=>"Codigo incorrecto"
                ],400);
            }
    }
}
