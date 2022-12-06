<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class feedController extends Controller
{
    public function addFeed(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250|unique:App\Models\Feed,name',
                'description' => 'required|max:250',
                'group' => 'required|integer'
            ],
            [
                "name.required" => "El campo :attribute es obligatorio",
                "name.max" => "El campo :attribute tiene un maximo de 250 caracteres",
                "name.unique" => "El campo :attribute no puede repetirse",
                "description.required" => "El campo :attribute es obligatorio",
                "description.max" => "El campo :attribute tiene un maximo de 250 caracteres",
                "group.required" => "El campo :attribute es obligatorio",
                "group.integer" => "El campo :attribute tiene que ser un entero"
            ]
        );
        if($validate->fails())
        {
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
            "description" => $request->description
        ]);
        if($response->successful())
        {
            $feed = new Feed();
            $feed->name = $request->name;
            $feed->description = $request->description;
            $feed->enabled = 1;
            $feed->group = $request->group;
            $feed->car = $request->car;
            if($feed->save())
            {
                return response()->json([
                    "status"    => 200,
                    "message"   => "Feed creado correctamente",
                    "error"     => [],
                    "data"      => $response->body()
                ],200);
            }
            return $response;
        }
        return $response;
    }

    public function updateFeed(Request $request,$id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:250',
                'description' => 'required|max:250',
                'group' => 'required|integer'
            ],
            [
                "name.required" => "El campo :attribute es obligatorio",
                "name.max" => "El campo :attribute tiene un maximo de 250 caracteres",
                "name.unique" => "El campo :attribute no puede repetirse",
                "description.required" => "El campo :attribute es obligatorio",
                "description.max" => "El campo :attribute tiene un maximo de 250 caracteres",
                "group.required" => "El campo :attribute es obligatorio",
                "group.integer" => "El campo :attribute tiene que ser un entero"
            ]
        );
        if($validate->fails())
        {
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
            "description" => $request->description
        ]);
        if($response->successful())
        {
            $feed = Feed::find($id);
            $feed->name = $request->name;
            $feed->description = $request->description;
            $feed->enabled = 1;
            $feed->group = $request->group;
            $feed->car = $request->car;
            if($feed->save())
            {
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

}
