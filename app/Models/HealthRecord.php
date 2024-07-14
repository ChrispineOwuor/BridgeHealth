<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'status','patient_id'];
    protected $hidden = [
        "patient_id"
    ];
     public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'record_symptom_pivots');
    }
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

}
