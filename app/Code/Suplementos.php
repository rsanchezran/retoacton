<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 24/06/19
 * Time: 01:51 PM
 */

namespace App\Code;


class Suplementos
{
    const suplementos = ['HB0', 'HB1','HS0', 'HS1', 'MB0', 'MB1', 'MS0', 'MS1'];

    public static function getObjGen($objGen){ //formato como self::suplementos[]
        $aux = collect();
        $aux->objetivo = $objGen[1]=='B'?Objetivo::BAJAR:Objetivo::SUBIR;
        $aux->genero = $objGen[0]=='H'?Genero::HOMBRE:Genero::MUJER;
        $aux->etapa = $objGen[2];
        return $aux;
    }


}