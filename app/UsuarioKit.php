<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioKit extends Model
{
    protected $table = 'usuario_kit';
    use SoftDeletes;
}
