<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respuesta extends Model
{
    use SoftDeletes;

    public function pregunta()
    {
        return $this->belongsTo('App\User', 'usuario_id', 'id');
    }

}
