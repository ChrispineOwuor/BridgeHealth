<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordSymptomPivot extends Model
{
    use HasFactory;
    protected $fillable = ['health_record_id', 'symptom_id'];

}
