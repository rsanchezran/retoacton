<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 27/05/19
 * Time: 09:08 AM
 */

namespace App\Code;


class TipoEjercicio
{
    const AEROBICO = 0; //cardio
    const ANAEROBICO= 1;

    public static function all(){
        return collect([self::AEROBICO, self::ANAEROBICO]);
    }
}