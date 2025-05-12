<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Superuser',
            'surnames' => 'root',
            'password' => Hash::make(env('SEED_ADMIN_PASSWORD')),
            'username' => 'superadmin',
            'email' => 'ajrob.seyer@gmail.com',
            'role_id' => 1,
            'is_active' => 1,
            'crew_id' => 1,
            'cel_phone' => '5535094537',
            'genre' => 'H'
        ]);
    }
}
