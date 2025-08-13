<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; // <- add this

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',     // e.g., 1-5 stars
        'comment'
    ];

    // Optional: cast rating to integer
    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
