<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InteraccionAlbum extends Model
{
    //
    protected $fillable = [
        'usuario_like_id', 'dinero_acton', 'tipo_like', 'album_id'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function mialbum(){
        return $this->belongsTo('App\MiAlbum');
    }
}
