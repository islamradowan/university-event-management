<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','event_id','qr_token','checked_in_at'];

    protected $dates = ['checked_in_at'];

    public function user() { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
}

