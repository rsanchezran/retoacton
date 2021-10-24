<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCuenta extends Model
{
    //
    protected $fillable = [
        'usuario_id', 'usuario_transfiere_id', 'desripcion', 'coins'
    ];

    public function usuario_id(){
        return $this->belongsTo('App\User');
    }

    public function usuario_transfiere_id(){
        return $this->belongsTo('App\User');
    }
}
