<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ejercicio extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'ejercicio',
        'video',
        'tipo',
        'genero',
        'objetivo',
        'dia_id'
    ];

    public function serie(){
        return $this->belongsTo('App\Serie', 'serie_id','id');
    }
}
