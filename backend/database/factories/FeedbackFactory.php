<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = \App\Models\Feedback::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'event_id' => null, // assign later
            'rating' => rand(1,5),
            'comment' => $this->faker->optional()->sentence,
        ];
    }
}
