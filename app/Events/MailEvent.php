<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datos;
    public $mensaje;
    public $call;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($datos, $datos_mensaje, $call_mail)
    {
        $this->datos = $datos;
        $this->mensaje = $datos_mensaje;
        $this->call = $call_mail;
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
