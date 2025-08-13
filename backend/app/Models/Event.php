<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','description','start_at','end_at','location','category','status','organizer_id','poster_path','capacity','featured'];

    protected $dates = ['start_at','end_at'];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class);
    }

    public function media() {
        return $this->hasMany(EventMedia::class);
    }

    public function feedbacks() {
        return $this->hasMany(Feedback::class);
    }
}
