<?php

namespace App\Code;


class Videos{
    const INICIO = 0;
    const REGISTRO = 1;
    const SUBIR = 2;
    const BAJAR = 3;

    public static function all(){
        return collect([self::INICIO, self::REGISTRO]);
    }

    public static function allString(){
        return collect(['inicio', 'registro', 'objetivo subir', 'objetivo bajar','peso ideal','último','termino']);
    }
}
