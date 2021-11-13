<?php

namespace App\Providers;

use App\Events\MensajesDirectosEvent;
use App\Events\RetosEvent;
use App\Listeners\CoinsListener;
use App\Listeners\ReaccionesListener;
use App\Listeners\MensajesDirectosListener;
use App\Listeners\RetosListener;
use App\Events\CoinsEvent;
use App\Events\ReaccionesEvent;
use App\Events\SeguirListenerEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\ProcesarVideoEvent' => [
            'App\Listeners\ProcesarVideoEventListener',
        ],
        'App\Events\EnviarCorreosEvent' => [
            'App\Listeners\EnviarCorreosEventListener',
        ],
        'App\Events\EnviarDudasEvent' => [
            'App\Listeners\EnviarDudasEventListener',
        ],
        MensajesDirectosEvent::class => [
            MensajesDirectosListener::class,
        ],
        RetosEvent::class => [
            RetosListener::class,
        ],
        CoinsEvent::class => [
            CoinsListener::class,
        ],
        ReaccionesEvent::class => [
            ReaccionesListener::class,
        ],
        SeguirEvent::class => [
            SeguirListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
