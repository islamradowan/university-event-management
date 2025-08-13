<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventRegistrationFactory extends Factory
{
    protected $model = \App\Models\EventRegistration::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'event_id' => null, // assign later
            'qr_token' => Str::uuid(),
            'checked_in_at' => $this->faker->optional()->dateTimeBetween('-1 days','+1 days'),
        ];
    }
}
