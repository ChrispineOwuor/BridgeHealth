<?php

namespace App\Providers;

use App\Events\AdminRegistered;
use App\Events\DoctorRegistered;
use App\Events\UserRegistered;
use App\Listeners\CreateAdminProfile;
use App\Listeners\CreateDoctorProfile;
use App\Listeners\CreateProfile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            UserRegistered::class,
            CreateProfile::class,
        );
        Event::listen(
            AdminRegistered::class,
            CreateAdminProfile::class,
        );
        Event::listen(
            DoctorRegistered::class,
            CreateDoctorProfile::class,
        );
    }
}
