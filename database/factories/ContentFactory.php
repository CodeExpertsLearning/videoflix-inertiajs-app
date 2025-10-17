<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'title' => $title,
            'code' => fake()->uuid(),
            'description' => fake()->sentence,
            'body'  => fake()->paragraphs(3, true),
            'slug'  => str($title)->slug(),
            'type'  => 'MOVIE'
        ];
    }

    public function series()
    {
        return $this->state(fn(array $attrs) => ['type' => 'SERIE']);
    }
}
