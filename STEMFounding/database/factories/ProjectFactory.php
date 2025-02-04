<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $user = User::inRandomOrder()->first();
            $activeProjects = Project::where('user_id', $user->id)
                ->where('state', 'active')
                ->count();
        } while ($activeProjects >= 2); // Asegura que no tenga más de 2 activos
    
        return [
            'title' => fake()->sentence(5),
            'description' => fake()->sentence(10),
            'image_url' => 'https://itequia.com/wp-content/uploads/2023/09/magnitud.jpg',
            'video_url' => fake()->sentence(5),
            'min_investment' => fake()->randomFloat(2, 100, 100000),
            'max_investment' => fake()->randomFloat(2, 10000, 500000),
            'limit_date' => fake()->dateTimeBetween('now', '+1 year'),
            'state' => fake()->randomElement(['active', 'inactive', 'pending']),
            'current_investment' => 0,
            'user_id' => $user->id,
        ];
    }
    
}
