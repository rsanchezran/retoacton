<?php

namespace App\Code;

class TipoFitness{
    const OBJETIVO = ['bajar Peso', 'Subir Peso'];
    const SEXO = ['Hombre', 'Mujer'];
    const HOMBRE=0;
    const MUJER=1;
    const BAJAR=0;
    const SUBIR=1;

    public static function All(){
        return collect(["objetivo"=>self::OBJETIVO,"sexo"=>self::SEXO]);
    }
}