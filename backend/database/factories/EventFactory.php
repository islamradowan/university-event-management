<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = \App\Models\Event::class;

    public function definition()
    {
        $titles = [
            'Advanced Web Development Workshop',
            'Machine Learning Fundamentals',
            'Digital Marketing Strategies', 
            'Leadership Skills Training',
            'Annual Science Fair',
            'Cultural Heritage Festival',
            'Inter-University Sports Meet',
            'Entrepreneurship Summit',
            'Career Guidance Seminar',
            'Innovation Challenge',
            'Photography Exhibition',
            'Music Concert Evening',
            'Debate Competition',
            'Tech Talk Series',
            'Alumni Networking Event'
        ];
        
        $locations = ['Main Auditorium', 'Conference Hall A', 'Conference Hall B', 'Library Hall', 'Sports Complex', 'Student Center', 'Engineering Building', 'Science Lab', 'Art Gallery', 'Outdoor Stadium'];
        
        $title = $this->faker->randomElement($titles) . ' ' . $this->faker->year;
        $start = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $end = (clone $start)->modify('+'.rand(1,4).' hours');

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->paragraph(3),
            'start_at' => $start,
            'end_at' => $end,
            'location' => $this->faker->randomElement($locations),
            'category' => $this->faker->randomElement(['Workshop','Seminar','Conference','Cultural','Sports','Competition','Festival','Networking','Training','Exhibition']),
            'status' => $this->faker->randomElement(['approved','approved','approved','pending','rejected']),
            'organizer_id' => null,
            'poster_path' => null,
            'capacity' => rand(20,200),
            'featured' => $this->faker->boolean(30),
        ];
    }
}
