<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $hidden =['created_at','updated_at'];

    public function records()
    {
        return $this->belongsToMany(HealthRecord::class, 'record_symptom_pivots');
    }
}
