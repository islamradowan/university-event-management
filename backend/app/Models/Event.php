<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_at',
        'end_at',
        'location',
        'category',
        'status',
        'organizer_id',
        'poster_path',
        'capacity',
        'featured',
        'enable_waitlist',
        'recurring_pattern',
        'parent_event_id',
        'is_template'
    ];

    // Use casts in Laravel 10
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function media()
    {
        return $this->hasMany(EventMedia::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function waitlist()
    {
        return $this->hasMany(EventWaitlist::class);
    }

    public function childEvents()
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    public function parentEvent()
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }
}
