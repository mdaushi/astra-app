<?php

namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;
use Illuminate\Support\ServiceProvider;
use Saade\FilamentLaravelLog\Pages\ViewLog;

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
        ViewLog::can(function (User $user) {
            return auth()->user()->getRoleNames()[0] === 'admin';
        });

        Filament::registerStyles([
            'https://cdn.jsdelivr.net/npm/evo-calendar@1.1.2/evo-calendar/css/evo-calendar.min.css',
            'https://cdn.jsdelivr.net/npm/evo-calendar@1.1.2/evo-calendar/css/evo-calendar.royal-navy.css'
        ]);
        
    }
}
