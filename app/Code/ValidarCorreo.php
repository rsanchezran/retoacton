<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 28/11/19
 * Time: 12:09 PM
 */

namespace App\Code;


class ValidarCorreo
{
    public static function validarCorreo($cadena)
    {
        $found = false;
        $needles = array('ñ', 'Ñ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', '!', '¿',
            'À', 'Ã', 'Ì', 'Ò', 'Ù', 'Ã™', 'Ã ', 'Ã¨', 'Ã¬',
            'Ã²', 'Ã¹', 'ç', 'Ç', 'Ã¢', 'ê', 'Ã®', 'Ã´', 'Ã»',
            'Ã‚', 'ÃŠ', 'ÃŽ', 'Ã”', 'Ã›', 'ü', 'Ã¶', 'Ã–', 'Ã¯',
            '(', ')', '"', '¡', '¿', ',', '°', '¬', '[', ']',
            'Ã¤', '«', 'Ò', 'Ã', 'Ã„', 'Ã‹', '�', 'á', 'é', 'í',
            'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', '\\', 'Á', 'É', 'Í', 'Ó', 'Ú',
            'Ä', 'Ë', 'Ï', 'Ö', 'Ü');

        foreach ($needles as $needle) {
            if (strpos($cadena, $needle) !== false) {
                $found = true;
                break;
            }
        }
        return $found;
    }
}