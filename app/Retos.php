<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retos extends Model
{
    //
    //
    protected $fillable = [
        'usuario_retado_id', 'descripcion', 'publico', 'cumple', 'coins', 'video', 'usuario_reta_id'
    ];

    public function user_retado(){
        return $this->belongsTo('App\User');
    }

    public function user_reta(){
        return $this->belongsTo('App\User');
    }
}
