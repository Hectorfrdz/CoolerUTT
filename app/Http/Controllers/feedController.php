<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Car;
use App\Models\Type_car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class feedController extends Controller
{
    public function addFeed(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:App\Models\Feed,name',
            ],
            [
                "name.required" => "El campo :attribute es obligatorio",
                "name.max" => "El campo :attribute tiene un maximo de 250 caracteres",
                "name.unique" => "El campo :attribute no puede repetirse",
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
        $response = Http::withHeaders([
            'X-AIO-Key' => $request->aio_key
        ])
        ->post('https://io.adafruit.com/api/v2/'.$request->username.'/feeds?group_key='.$request->group_key,
        [
            "name" => $request->name,
            "key" => $request->name,
        ]);
        if($response->successful())
        {
            $feed = new Feed();
            $feed->name = $request->name;
            $feed->enabled = 1;
            $feed->car_id = $request->car;
            if($feed->save())
            {
                Log::channel('slackInfo')->info('Se creo un feed');
                return response()->json([
                    "status"    => 200,
                    "message"   => "Feed creado correctamente",
                    "error"     => [],
                    "data"      => $response->body()
                ],200);
            }
            Log::channel('errores')->error('Error al crear un feed');
            return response()->json([
                "status"    => 400,
                "message"   => "Error al crear un Feed",
                "error"     => [],
                "data"      => $response->body()
            ],400);
        }
        Log::channel('errores')->error('Error al crear un feed');
        return response()->json([
            "status"    => 400,
            "message"   => "Error al crear un Feed",
            "error"     => [],
            "data"      => $response->body()
        ],400);
    }

    public function updateFeed(Request $request,$id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250',
                'car_id' => 'required',
            ],
            [
                "name.required" => "El campo :attribute es obligatorio",
                "name.max" => "El campo :attribute tiene un maximo de 250 caracteres",
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
        $response = Http::withHeaders([
            'X-AIO-Key' => $request->aio_key
        ])
        ->put('https://io.adafruit.com/api/v2/'.$request->username.'/feeds/'.$request->feed_key,
        [
            "name" => $request->name,
        ]);
        if($response->successful())
        {
            $feed = Feed::find($id);
            $feed->name = $request->name;
            $feed->enabled = 1;
            $feed->car_id = $request->car;
            if($feed->save())
            {
                Log::channel('slackInfo')->info('Se ha agregado un registro');
                return response()->json([
                    "status"    => 200,
                    "message"   => "Feed creado correctamente",
                    "error"     => [],
                    "data"      => $response->body()
                ],200);
            }
            return $feed;
        }
        return $response;
    }

    public function feed_car($id)
    {
        $grupo = Car::with("feeds")
        ->join('cars','feeds.car_id','=','cars.id')
        ->join('users','users.id','=','cars.user_id')
        ->where('users.id','=',$id)
        ->get();
        Log::channel('slackInfo')->info('Se ha mostraron registros');
        return response()->json([
            'status' => 200,
            'data' => $grupo
        ],200);
    }

    public function showFeed($id)
    {
        $feed = Feed::select("feeds.name","feeds.id","feeds.car_id")->from('feeds')
        ->join('cars','cars.id','=','feeds.car_id')
        ->where("cars.id","=",$id)
        ->get();
        Log::channel('slackInfo')->info('Se ha mostraron registros');
        return $feed;
    }

    public function showFeed_group()
    {
        $grupo = Car::with("feeds")->select("cars.id")
        ->join('cars','feeds.car_id','=','cars.id')
        ->join('users','users.id','=','cars.user_id')
        ->where('cars.id','=',1)
        ->groupBy('cars.id')
        ->get();
        Log::channel('slackInfo')->info('Se ha mostraron registros');
        return $grupo;
    }

    public function showGroup()
    {
        $grupo = Type_car::all();
        Log::channel('slackInfo')->info('Se ha mostraron registros');
        return $grupo;
    }

}