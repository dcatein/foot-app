<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('team')
        ->insert([
            'name' => 'TIME A',
            'strategy' => '4-4-2'
        ]);

        DB::table('team')
        ->insert([
            'name' => 'TIME B',
            'strategy' => '4-4-2'
        ]);
    }
}
