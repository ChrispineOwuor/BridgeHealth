<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone_number'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
    public function unreadNotifications()
    {
        return Notification::where('is_read', false)->orderBy('severity', 'desc');
    }
    public function getAppointments()
    {
        return Appointment::where('doctor_id', $this->id)->orderBy('created_at', 'desc');
    }
}
