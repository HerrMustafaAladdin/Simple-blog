<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   =>  $this->faker->numberBetween(1,10),
            'post_id'   =>  1,
            'is_active' =>  $this->faker->numberBetween(0,1),
            'content'   =>  $this->faker->text
        ];
    }
}
