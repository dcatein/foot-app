<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CreateTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $cities = $this->getCitiesData();
        $animals = $this->getAnimals();

        for ($i = 0; $i < 4; $i++) {
            $cityNumber = $faker->unique()->numberBetween(0, 5562);
            $animalNumber = $faker->unique()->numberBetween(0, 223);

            $city = $cities[$cityNumber];
            $animal = $animals[$animalNumber];

            DB::table('team')
                ->insert(
                    [
                        'name' => $animal . " de " .  $city['Nome'],
                        'strategy' => '4-4-2'
                    ]
                );
        }

    }

    private function getCitiesData()
    {
        $file = file_get_contents('resources/data/cidades.json');

        return json_decode($file, true);
    }

    private function getAnimals()
    {
        $file = file_get_contents('resources/data/animais.json');

        return json_decode($file, true);
    }
}
