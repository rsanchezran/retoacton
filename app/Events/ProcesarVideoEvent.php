<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 21/10/19
 * Time: 08:51 AM
 */

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcesarVideoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $origen;
    public $destino;
    public $ruta;

    public function __construct($origen, $destino, $ruta)
    {
        $this->origen = $origen;
        $this->destino = $destino;
        $this->ruta = $ruta;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}