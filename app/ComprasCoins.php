<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprasCoins extends Model
{
    protected $fillable = [
        'monto', 'usuario_id', 'tipo_compra', 'referencia', 'pagado'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
