<?php

namespace App\Providers;

use App\Events\AgreedPengajuan;
use App\Events\ApprovalProcessed;
use App\Events\ApprovedProcessed;
use App\Events\EkspedisiProcessed;
use App\Events\PDBarengProcessed;
use App\Events\RejectedProcessed;
use App\Listeners\SendApprovalNotification;
use App\Listeners\SendNotificationAgreedPengajuan;
use App\Listeners\SendNotificationApprovedToUser;
use App\Listeners\SendNotificationLayananOther;
use App\Listeners\SendNotificationRejected;
use App\Listeners\SendPDBarengNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        ApprovalProcessed::class => [
            SendApprovalNotification::class
        ],

        PDBarengProcessed::class => [
            SendPDBarengNotification::class
        ],

        EkspedisiProcessed::class => [
            SendNotificationLayananOther::class
        ],
        
        RejectedProcessed::class => [
            SendNotificationRejected::class
        ],

        ApprovedProcessed::class => [
            SendNotificationApprovedToUser::class
        ],

        AgreedPengajuan::class => [
            SendNotificationAgreedPengajuan::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
