<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

      protected $fillable = [
        'message',
        'severity',
        'patient_id',
        'doctor_id',
        'admin_id',

    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctors::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admins::class);
    }

    
}
