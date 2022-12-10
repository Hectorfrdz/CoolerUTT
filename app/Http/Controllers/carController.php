<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
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
        $car->user = $request->user;
        $car->type_car = $request->type_car;
        $car->group = $request->group;
        if($car->save())
        {
            return response()->json([
                "status"    => 200,
                "message"   => "Carrito creado",
                "error"     => [],
                "data"      => $car
            ],200);
        }
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
            return response()->json([
                "status"    => 200,
                "message"   => "Carrito Actualizado",
                "error"     => [],
                "data"      => $car
            ],200);
        }
        return response()->json([
            "status"    => 400,
            "message"   => "Ocurrio un error, vuelva a intentarlo",
            "error"     => $car,
            "data"      => []
        ],400);
    }

    public function groups()
    {
        
    }
}
