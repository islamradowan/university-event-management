<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'type',       // 'image', 'video', 'document'
        'poster_path',       // storage path
        'caption'
    ];

    // Relationships
    public function event() {
        return $this->belongsTo(Event::class);
    }
}
