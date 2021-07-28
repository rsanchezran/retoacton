<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MensajesDirectos extends Model
{
    //
    protected $fillable = [
        'usuario_emisor_id', 'usuario_receptor_id', 'visto', 'mensaje'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
