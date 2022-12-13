<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class carController extends Controller
{
    public function addCar(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250',
                'description' => 'required|max:250',
            ],
            [
                "nombre.required" => "El :attribute es obligatorio",
                "nombre.max" => "El :attribute tiene un maximo de 250 caracteres",
                "description.required" => "El :attribute es obligatorio",
                "description.max" => "El :attribute tiene un maximo de 250 caracteres"
            ]
        );
        if($validate->fails())
        {
            Log::channel('errores')->error('Error en las validaciones');
            return response()->json([
                "status"    => 400,
                "message"   => "Alguno de los campos no se ha llenado",
                "error"     => [$validate->errors()],
                "data"      => []
            ],400);
        }
        
            $car = new Car();
            $car->name = $request->name;
            $car->description = $request->description;
            $car->user_id = $request->user;
            $car->type_car_id = $request->type_car;
            if($car->save())
            {
                $sensores = array("temperatura","distancia","nivelagua","bateria","desague");
                for($i = 0; $i <= 4; $i++)
                {
                    $feed = new Feed();
                    $feed->name = $sensores[$i];
                    $feed->enabled = 1;
                    $feed->car_id = $car->id;
                    $feed->save();
                }
                Log::channel('slackInfo')->info('Se creo un carro');
                return response()->json([
                    "status"    => 200,
                    "message"   => "Carrito creado",
                    "error"     => [],
                    "data"      => $car
                ],200);
            }
            Log::channel('errores')->error('Error');
            return response()->json([
                "status"    => 400,
                "message"   => "Ocurrio un error, vuelva a intentarlo",
                "error"     => $car,
                "data"      => []
            ],400);
    }

    public function viewCar()
    {
        $request = Car::all();
        Log::channel('slackInfo')->info('Se mostraron datos');
        return response()->json([
            "status"=>200,
            "data"=>$request
        ],200);
    }

    public function updateCar(Request $request,$id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250',
                'description' => 'required|max:250',
            ],
            [
                "nombre.required" => "El :attribute es obligatorio",
                "nombre.max" => "El :attribute tiene un maximo de 250 caracteres",
                "description.required" => "El :attribute es obligatorio",
                "description.max" => "El :attribute tiene un maximo de 250 caracteres"
            ]
        );
        if($validate->fails())
        {
            Log::channel('errores')->error('Error en las validaciones');
            return response()->json([
                "status"    => 400,
                "message"   => "Alguno de los campos no se ha llenado",
                "error"     => [$validate->errors()],
                "data"      => []
            ],400);
        }
        $car = Car::find($id);
        $car->name = $request->name;
        $car->description = $request->description;
        if($car->save())
        {
            Log::channel('slackInfo')->info('Se actualizo un carrito');
            return response()->json([
                "status"    => 200,
                "message"   => "Carrito Actualizado",
                "error"     => [],
                "data"      => $car
            ],200);
        }
        Log::channel('errores')->error('Error');
        return response()->json([
            "status"    => 400,
            "message"   => "Ocurrio un error, vuelva a intentarlo",
            "error"     => $car,
            "data"      => []
        ],400);
    }

    public function gruposss($id)
    {
        $feed = Car::select("cars.name","cars.id")->from('cars')
        ->join('users','users.id','=','cars.user_id')
        ->where("cars.user_id","=",$id)
        ->get();
        Log::channel('slackInfo')->info('Se mostraron datos');
        return $feed;
    }
}
