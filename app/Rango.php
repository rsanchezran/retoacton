<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rango extends Model
{

    public function rango_dietas(){
        return $this->hasMany('App\RangoDieta', 'rango_id', 'id');
    }

    public function dietas(){
        return $this->belongsToMany('App\Dieta', 'rango_dietas', 'rango_id', 'dieta_id');
    }
}
