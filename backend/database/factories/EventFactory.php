<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = \App\Models\Event::class;

    public function definition()
    {
        $title = $this->faker->sentence(3);
        $start = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $end = (clone $start)->modify('+'.rand(1,4).' hours');

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->paragraph,
            'start_at' => $start,
            'end_at' => $end,
            'location' => $this->faker->city,
            'category' => $this->faker->randomElement(['Workshop','Seminar','Competition','Festival']),
            'status' => $this->faker->randomElement(['pending','approved','rejected']),
            'organizer_id' => null, // assign later in seeder
            'poster_path' => null,
            'capacity' => rand(10,100),
            'featured' => $this->faker->boolean(20),
        ];
    }
}
