<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class groupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feed = new Group();
        $feed->name = "hielera";
        $feed->save();
        $feed = new Group();
        $feed->name = "aspiradora";
        $feed->save();
        $feed = new Group();
        $feed->name = "basurero";
        $feed->save();
    }
}
