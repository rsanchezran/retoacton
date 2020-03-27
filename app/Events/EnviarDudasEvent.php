<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class EnviarDudasEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contacto;
    public $tipo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($contacto, $tipo)
    {
        $this->contacto = $contacto;
        $this->tipo = $tipo;
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
