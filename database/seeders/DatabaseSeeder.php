<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criação de 10 usuários
        User::factory(10)->create();

        // Criação de 5 doutores
        Doctor::factory(5)->create();
    }
}
