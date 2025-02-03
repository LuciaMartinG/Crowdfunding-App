<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory(100)->create();

        Project::factory()->create([
            'title' => 'Proyecto1',
            'description'=> 'Proyecto proyectito',
            'image_url'=> 'https://imgs.search.brave.com/5BFgigCdSRNQjIo1mp9FL0jaX6J6Rt4HdCPn3cfsvjQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mcmFt/ZXJ1c2VyY29udGVu/dC5jb20vaW1hZ2Vz/L3JpNWp4ellReFBD/SHVyWmdYZU9scmVj/RWdkVS5wbmc', 
            'video_url'=> fake()->sentence(5),
            'min_investment' =>20000,
            'max_investment'=> 100000,
            'limit_date' => fake()->dateTimeBetween('now', '+1 year'),
            'state' => 'active',
            'current_investment' => 0,
            'user_id' =>22,
        ]);

        Project::factory()->create([
            'title' => 'Proyecto2',
            'description'=> 'Proyecto proyectaso',
            'image_url'=> 'https://imgs.search.brave.com/5BFgigCdSRNQjIo1mp9FL0jaX6J6Rt4HdCPn3cfsvjQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mcmFt/ZXJ1c2VyY29udGVu/dC5jb20vaW1hZ2Vz/L3JpNWp4ellReFBD/SHVyWmdYZU9scmVj/RWdkVS5wbmc', 
            'video_url'=> fake()->sentence(5),
            'min_investment' =>50000,
            'max_investment'=> 200000,
            'limit_date' => fake()->dateTimeBetween('now', '+1 year'),
            'state' => 'inactive',
            'current_investment' => 0,
            'user_id' =>22,
        ]);
    }
}
