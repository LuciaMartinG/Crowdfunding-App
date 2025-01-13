<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => fake()->sentence(5),
            'description'=> fake()->sentence(10),
            'image_url'=> fake()->imageUrl(300, 450, 'finances', true, 'finances_img'), 
            'video_url'=> fake()->sentence(5),
            'min_investment' =>fake()->randomFloat(2, 100, 100000),
            'max_investment'=> fake()->randomFloat(2, 10000, 500000),
            'limit_date' => fake()->dateTimeBetween('now', '+1 year'),
            'state' => 'pending',
            'current_investment' => 0,
        ];
    }
}
