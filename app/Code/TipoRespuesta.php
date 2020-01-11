<?php

namespace App\Code;

class TipoRespuesta {
    const MULTIPLE = 1;
    const UNICA = 0;
    const ABIERTA = '';

    const PREGUNTAS_REGISTRO=['9'];

    public static function all(){
        return collect([self::ABIERTA, self::MULTIPLE, self::UNICA]);
    }
}