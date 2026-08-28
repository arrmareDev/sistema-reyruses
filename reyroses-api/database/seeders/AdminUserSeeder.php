<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'adminrr@reyruses.com'],
            [
                'name' => 'Rey Ruses',
                'password' => Hash::make('adminrr12345'),
                'role' => 'admin',
            ]
        );
    }
}
