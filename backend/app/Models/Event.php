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
        'featured'
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
}
