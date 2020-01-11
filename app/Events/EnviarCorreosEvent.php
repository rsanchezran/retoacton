<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EnviarCorreosEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contactos;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($contactos)
    {
        $this->contactos = $contactos;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
