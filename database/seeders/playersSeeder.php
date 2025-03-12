<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Src\Entities\Player;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class playersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $faker = Faker::create();
//        for ($i = 0; $i < ; $i++) {
//
//        }
//
//        foreach ($this->getPlayers() as $player){
//            DB::table('player')
//            ->insert([
//                'atk' => $player->atk,
//                'mid' => $player->mid,
//                'def' => $player->def,
//                'gol' => $player->gol,
//                'position' => $player->position,
//                'name' => $faker->firstName . ' ' . $faker->lastName
//            ]);
//        }
    }
}
