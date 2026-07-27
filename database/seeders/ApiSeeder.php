<?php

namespace Database\Seeders;

use App\Models\Api;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        Api::factory()
            ->count(8)
            ->for($user)
            ->create();
    }
}
