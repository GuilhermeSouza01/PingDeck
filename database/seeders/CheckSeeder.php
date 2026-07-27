<?php

namespace Database\Seeders;

use App\Models\Api;
use App\Models\Check;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Api::all()->each(function ($api) {

            Check::factory()
                ->count(50)
                ->for($api)
                ->create();

        });
    }
}
