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
            'role' => 'admin',
            'photo' => 'https://imgs.search.brave.com/sad2S3MNn8RgymZZHwWwr-8EORCbEcd-fvpGnou6tNg/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzAxLzA1LzcyLzYx/LzM2MF9GXzEwNTcy/NjE5NV9yME1wTDBO/b3hwMk5lTWgzUnNS/d0Nza2JlTDdlbnNq/Vi5qcGc'
        ]);

        User::factory()->create([
            'name' => 'entrepreneur',
            'email' => 'entrepreneur@example.com',
            'password' => '1234',
            'role' => 'entrepreneur',
            'photo' => 'https://imgs.search.brave.com/efhUPqxWuoNvDL_m0qVtCwfd0elc6OiQ3WVP2irKUZk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/cHJvZC53ZWJzaXRl/LWZpbGVzLmNvbS82/NGY2YzAxZTUyNmIx/OWEwODE2MzE2NGMv/NjUzZmI0ZGM1MTA1/Njg3OTFhMTg0YjQ3/X2VtcHJlbmRlZG9y/LmpwZWc'
        ]);

        User::factory()->create([
            'name' => 'investor',
            'email' => 'investor@example.com',
            'password' => '1234',
            'role' => 'investor',
            'photo' => 'https://imgs.search.brave.com/8nsWpeg4txE0obUgvWd54UWGgU6QDyZGisMiIn52iBg/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzAwLzgyLzIzLzcy/LzM2MF9GXzgyMjM3/MjQ1X1N2VHVQdkFV/VzJBdFc3cm1kVDV3/RzRrR25tTHY0cm5O/LmpwZw'
        ]);
    }
}
