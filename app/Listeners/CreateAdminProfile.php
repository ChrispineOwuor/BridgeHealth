<?php

namespace App\Listeners;

use App\Events\AdminRegistered;
use App\Models\Admins;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAdminProfile
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
    public function handle(AdminRegistered $event): void
    {
        Admins::create([
            "user_id" => $event->user->id
        ]);
    }
}
