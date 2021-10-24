<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraRetos extends Model
{
    //
    protected $fillable = [
        'usuario_id', 'reto_id', 'like'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function reto(){
        return $this->belongsTo('App\Retos');
    }
}
