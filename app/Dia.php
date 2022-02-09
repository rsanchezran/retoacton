<?php

namespace App;

use App\Code\LugarEjercicio;
use App\Code\TipoEjercicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dia extends Model
{
    use SoftDeletes;

    public function ejercicios()
    {
        return $this->hasMany('App\Ejercicio', 'dia_id', 'id')->where('tipo', TipoEjercicio::ANAEROBICO);
    }

    public function cardio()
    {
        return $this->hasMany('App\Ejercicio', 'dia_id', 'id')->where('tipo', TipoEjercicio::AEROBICO);
    }

    public function alimentos()
    {
        return $this->hasMany('App\Alimento', 'dia_id', 'id');
    }

    public function suplementos()
    {
        return $this->hasMany('App\Suplemento', 'dia_id', 'id');
    }

    public function notas()
    {
        return $this->hasMany('App\Notas', 'dia_id', 'id');
    }

    public function nota()
    {
        return $this->hasOne('App\Notas', 'dia_id', 'id');
    }

    public static function buildDia($dia, $genero, $objetivo, $user, $dieta = 1, $semanaSuplementacion = 1)
    {
        $filtro = function ($datos) use ($genero, $objetivo) { //funcion para cad with
            $datos->where('genero', $genero)->where('objetivo', $objetivo);
        };
        $diacardio = 1;
        if($dia>0){
            $diacardio = $dia;
        }

        $diacardio = $dia-54;
        if($diacardio<=0){
            $diacardio = $dia;
        }
        $diaDB = Dia::with(['cardio' => $filtro, 'notas' => $filtro])
            ->where('dia', $diacardio)->get()->first();
        if ($diaDB == null) {
            $diaDB = new Dia();
            $diaDB->dia = $dia;
            $diaDB->cardio = collect();
            $diaDB->nota = new \stdClass();
            $diaDB->nota->descripcion = "";
        }else{
            $diaDB->nota = new \stdClass();
            $diaDB->nota->descripcion = $diaDB->notas->count()==0?'': $diaDB->notas[0]->descripcion;
        }
        $diaDB->suplementos = Kits::select('s.id', 's.suplemento', 's.porcion')
            ->join('suplementos as s', 'kit_id', 'kits.id')->where('kits.objetivo', $objetivo)
            ->where('genero', $genero)->where('descripcion', 'like', '%'.($semanaSuplementacion).'%')->get();
        $diaDB->comidas = UsuarioDieta::where('usuario_id', $user->id)->where('dieta', $dieta)->get()->groupBy('comida')->values();
        $dia_gym = $dia-54;
        if($dia_gym<0){
            $dia_gym = $dia;
        }
        if($dia_gym==0){
            $dia_gym = 1;
        }

        $diaDB->gym = Serie::with(['ejercicios'=>function($q){
            $q->orderBy('orden');
        }])->where('dia_id', $dia_gym)->where($filtro)
            ->whereHas('ejercicios', function ($q){
            $q->where('lugar', LugarEjercicio::GYM);
        })->where($filtro)->orderBy('orden')->get();
        $diaDB->casa= Serie::with(['ejercicios'=>function($q){
            $q->orderBy('orden');
        }])->where('dia_id', $dia_gym)->where($filtro)
            ->whereHas('ejercicios', function ($q){
            $q->where('lugar', LugarEjercicio::CASA);
        })->where($filtro)->orderBy('orden')->get();
        foreach ($diaDB->gym as $serie){
            foreach ($serie->ejercicios as $ejercicio){
                $ejercicio->subseries = json_decode($ejercicio->subseries);
            }
        }
        foreach ($diaDB->casa as $serie){
            foreach ($serie->ejercicios as $ejercicio){
                $ejercicio->subseries = json_decode($ejercicio->subseries);
            }
        }
        return $diaDB;
    }
}
