<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ["user_id",'phoneNumber', 'date_of_birth', 'blood_type', "emergencyContact", "residence"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function unreadNotifications()
    {
        return Notification::where('is_read', false)->orderBy('created_at', 'desc');
    }
}
