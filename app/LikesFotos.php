<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikesFotos extends Model
{
    //

    protected $fillable = [
        'usuario__id', 'usuario_like_id', 'dia'
    ];

    public function usuario_comenta()
    {
        return $this->belongsTo(User::class);
    }
}
