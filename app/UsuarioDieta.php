<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioDieta extends Model
{
    protected $table='usuario_dieta';
    use SoftDeletes;
}
