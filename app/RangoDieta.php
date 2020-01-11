<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RangoDieta extends Model
{
    public function dieta(){
        return $this->belongsTo('App\Dieta', 'dieta_id','id');
    }
}
