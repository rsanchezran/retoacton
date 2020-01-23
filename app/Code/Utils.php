<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 18/06/19
 * Time: 08:38 AM
 */

namespace App\Code;


use Carbon\Carbon;

class Utils
{
    public static function generarRandomString($length = 5)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function postRegistro($fecha){
        $reto_fecha = Carbon::parse($fecha);
        $dias_reto = Carbon::now()->diffInDays($reto_fecha->format('y-m-d')) + 1;
        return $dias_reto < env('DIAS');
    }

    public static function quitarTildes($cadena)
    {
        $no_permitidas = array('.', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ','Ñ', 'À', 'Ã', 'Ì', 'Ò', 'Ù', 'Ã™', 'Ã ', 'Ã¨', 'Ã¬', 'Ã²', 'Ã¹',
            'ç', 'Ç', 'Ã¢', 'ê', 'Ã®', 'Ã´', 'Ã»', 'Ã‚', 'ÃŠ', 'ÃŽ', 'Ã”', 'Ã›', 'ü', 'Ã¶', 'Ã–', 'Ã¯', 'Ã¤', '«', 'Ò', 'Ã', 'Ã„', 'Ã‹');
        $permitidas = array('', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'c', 'C',
            'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'u', 'o', 'O', 'i', 'a', 'e', 'U', 'I', 'A', 'E');
        $texto = str_replace($no_permitidas, $permitidas, $cadena);

        return $texto;
    }

    public static function clearString($string, $ignore=null)
    {
        $nuevo = str_replace('.', '', self::quitarTildes($string));
        $nuevo = str_replace('–','', $nuevo);
        $nuevo = str_replace('’','', $nuevo);
        $nuevo = str_replace("'",'', $nuevo);
        $nuevo = str_replace("´",'', $nuevo);
        $nuevo = str_replace('-','', $nuevo);
        $nuevo = str_replace('(','', $nuevo);
        $nuevo = str_replace(')','', $nuevo);
        $nuevo = str_replace('[','', $nuevo);
        $nuevo = str_replace(']','', $nuevo);
        $nuevo = str_replace('/','', $nuevo);
        $nuevo = str_replace('&','', $nuevo);
        $nuevo = str_replace('<','', $nuevo);
        $nuevo = str_replace('>','', $nuevo);
        $nuevo = str_replace('¬','', $nuevo);
        $nuevo = str_replace('¡','', $nuevo);
        $nuevo = str_replace('!','', $nuevo);
        $nuevo = str_replace('?','', $nuevo);
        $nuevo = str_replace(',','', $nuevo);
        $nuevo = str_replace('°','', $nuevo);
        if ($ignore !=null && !$ignore){
            $nuevo = str_replace(' ', '', $nuevo);
        }
        return strtolower($nuevo);
    }
}
