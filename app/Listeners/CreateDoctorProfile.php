<?php

namespace App\Listeners;

use App\Events\DoctorRegistered;
use App\Models\Doctors;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDoctorProfile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DoctorRegistered $event): void
    {
        Doctors::create([
            'user_id' => $event->user->id,
        ]);
    }
}
