<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    public function ejercicios()
    {
        return $this->hasMany('App\Ejercicio', 'serie_id', 'id');
    }

    public function compras()
    {
        return $this->hasMany('App\Compra', 'id', 'usuario_id');
    }

    public function pagos()
    {
        return $this->hasMany('App\Compra', 'id', 'usuario_id');
    }
}
