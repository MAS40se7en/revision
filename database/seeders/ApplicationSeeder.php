<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->each(function (User $user): void {
            Application::factory()
                ->count(8)
                ->for($user)
                ->has(Interview::factory()
                ->count(1))
                ->create();
        });
    }
}
