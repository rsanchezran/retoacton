<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 24/06/19
 * Time: 01:51 PM
 */

namespace App\Code;


class MedioContacto
{
    const FACEBOOK = "Por Facebook";
    const INSTAGRAM = "Por Instagram";
    const AMIGO = "Por medio de un amigo";
    const OTRO = "Otro";

    public static function all(){
        return collect([self::FACEBOOK, self::INSTAGRAM, self::AMIGO, self::OTRO]);
    }
}