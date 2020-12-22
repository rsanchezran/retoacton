<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComentariosAmigos extends Model
{
    //

    protected $fillable = [
        'usuario__id', 'usuario_comenta_id', 'dia', 'comentario'
    ];

    public function usuario_comenta()
    {
        return $this->belongsTo(User::class);
    }
}
