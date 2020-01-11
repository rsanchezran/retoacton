<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioDia extends Model
{
    use SoftDeletes;
    protected $table = "usuario_dia";
}
