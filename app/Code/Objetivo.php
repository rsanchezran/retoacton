<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 24/06/19
 * Time: 01:51 PM
 */

namespace App\Code;


class Objetivo
{
    const BAJAR = 0;
    const SUBIR = 1;

    public static function getObjetivo($objetivoPagina){
        return strpos($objetivoPagina, 'Bajar')===false?self::SUBIR:self::BAJAR;
    }

    public static function all(){
        return collect(["Bajar de peso", "Subir de peso"]);
    }
}