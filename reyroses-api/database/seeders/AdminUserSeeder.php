<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Fabricio Santa Cruz',
            'email' => 'admin@reyruses.com', 
            'password' => Hash::make('admin12345'), // Contraseña de prueba, luego la cambias
            'role' => 'admin',
        ]);
    }
}