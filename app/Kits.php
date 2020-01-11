<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kits extends Model
{
    use SoftDeletes;
    protected $table='kits';


    public function KitsSuplementos(){
        return $this->hasMany('App\Suplemento','kit_id','id');
    }
}
