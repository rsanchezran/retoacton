<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 6/05/19
 * Time: 11:01 AM
 */

namespace App\Code;


class RolUsuario
{
    const ADMIN = 'admin';
    const CLIENTE = 'cliente';

    public static function all(){
        return collect(['admin'=>self::ADMIN,'cliente'=>self::CLIENTE]);
    }
}