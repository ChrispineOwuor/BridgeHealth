<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    use HasFactory;

    protected $fillable = [
       "user_id"
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
