<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiAlbum extends Model
{
    //
    protected $fillable = [
        'usuario_id', 'descripcion', 'archivo'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
