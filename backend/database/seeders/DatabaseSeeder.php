<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Feedback;
use App\Models\EventMedia;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1) Create users
        $admins = User::factory()->count(2)->state(['role'=>'admin'])->create();
        $organizers = User::factory()->count(10)->state(['role'=>'organizer'])->create();
        $students = User::factory()->count(50)->state(['role'=>'student'])->create();

        // 2) Create 100 events for organizers
        $events = collect();
        foreach ($organizers as $org) {
            $ev = Event::factory()->count(20)->create(['organizer_id'=>$org->id]);
            $events = $events->merge($ev);
        }

        // 3) Assign registrations randomly
        foreach ($students as $student) {
            $eventsToJoin = $events->random(rand(1,3));
            foreach ($eventsToJoin as $event) {
                EventRegistration::factory()->create([
                    'user_id' => $student->id,
                    'event_id'=> $event->id
                ]);

                // Optional: feedback
                if(rand(0,1)) {
                    Feedback::factory()->create([
                        'user_id' => $student->id,
                        'event_id'=> $event->id
                    ]);
                }
            }
        }

        // 4) Add media for events
        foreach ($events as $event) {
            EventMedia::factory()->count(rand(1,3))->create([
                'event_id' => $event->id
            ]);
        }

        // 5) Notifications
        foreach ($students->random(10) as $student) {
            Notification::factory()->count(2)->create([
                'user_id' => $student->id
            ]);
        }
    }
}
