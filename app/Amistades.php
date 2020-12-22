<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amistades extends Model
{
    //
    protected $fillable = [
        'usuario_solicita_id', 'usuario_amigo_id'
    ];
}
