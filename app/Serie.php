<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    public function ejercicios(){
        return $this->hasMany('App\Ejercicio', 'serie_id', 'id');
    }
}
