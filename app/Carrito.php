<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    //
    protected $fillable = [
        'producto', 'cantidad', 'precio', 'comision', 'pagado', 'enviado', 'guia', 'servicio', 'comentarios', 'usuario_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
