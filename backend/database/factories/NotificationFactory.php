<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'read_at' => $this->faker->optional()->dateTime,
        ];
    }
}
