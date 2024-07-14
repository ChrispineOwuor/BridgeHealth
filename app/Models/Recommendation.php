<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'health_record_id', 'remarks'];
    protected  $hidden = ['doctor_id', 'health_record_id'];
    public function doctor()
    {
        return $this->belongsTo(Doctors::class);
    }

    public function healthRecord()
    {
        return $this->belongsTo(HealthRecord::class);
    }
}
