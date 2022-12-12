<?php

namespace Database\Seeders;

use App\Models\Type_car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class type_carSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feed = new Type_car();
        $feed->name = "hielera";
        $feed->save();
        $feed = new Type_car();
        $feed->name = "aspiradora";
        $feed->save();
        $feed = new Type_car();
        $feed->name = "basurero";
        $feed->save();
    }
}
