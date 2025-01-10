<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '1234',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => '1234',
            'email' => '1234@example.com',
            'password' => '1234',
            'role' => 'entrepeneur'
        ]);
    }
}
