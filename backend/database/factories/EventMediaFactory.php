<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventMediaFactory extends Factory
{
    protected $model = \App\Models\EventMedia::class;

    public function definition()
    {
        return [
            'event_id' => null,
            'type' => $this->faker->randomElement(['poster','gallery']),
            'file_path' => 'images/sample.png',
            'caption' => $this->faker->optional()->sentence,
        ];
    }
}
