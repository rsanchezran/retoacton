<?php

namespace App;

use App\Code\Genero;
use App\Code\LugarEjercicio;
use App\Code\Objetivo;
use App\Code\RolUsuario;
use App\Code\Utils;
use App\CodigosTienda;
use App\Mail\Registro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\MyResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    public $vencido;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'rol', 'inicio_reto', 'referencia', 'saldo', 'pagado', 'tarjeta', 'tipo_pago',
        'encuestado', 'objetivo', 'codigo', 'fecha_inscripcion', 'correo_enviado', 'modo', 'num_inscripciones', 'tipo_dia', 'costo',
        'dias_paso', 'pago_refrendo', 'cp', 'estado', 'ciudad', 'colonia'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ejercicios()
    {
        return $this->belongsTo('App\Ejercicio', 'usuario_ejercicio', 'usuario_id', 'ejercicio_id');
    }

    public function encuesta()
    {
        return $this->hasMany('App\Respuesta', 'usuario_id', 'id')->join('preguntas', 'pregunta_id', 'preguntas.id');
    }

    public static function crear($nombre, $apellidos = '', $email, $tipo, $objetivo, $codigo = '', $monto)
    {
        $pass = Utils::generarRandomString();
        $usuario = User::withTrashed()->where('email', $email)->first();
        $contacto = Contacto::where('email', $email)->get()->last();
        if ($usuario == null) {
            $usuario = User::create([
                'name' => $nombre,
                'last_name' => $apellidos,
                'email' => $email,
                'password' => Hash::make($pass),
                'pagado' => true,
                'encuestado' => false,
                'objetivo' => (int)$objetivo,
                'referencia' => Utils::generarRandomString(7),
                'codigo' => $codigo,
                'rol' => RolUsuario::CLIENTE,
                'tipo_pago' => $tipo,
                'modo' => LugarEjercicio::GYM,
                'fecha_inscripcion' => Carbon::now(),
                'correo_enviado' => 0,
                'num_inscripciones' => 1,
                'dias' => $contacto->dias,
                'cp' => '0',
                'colonia' => '0',
                'estado' => '0',
                'ciudad' => '0',
            ]);
        } else {
            $usuario->password = Hash::make($pass);
            $usuario->pagado = true;
            $usuario->encuestado = false;
            $usuario->objetivo = (int)$objetivo;
            $usuario->referencia = Utils::generarRandomString(7);
            $usuario->codigo = $codigo;
            $usuario->tipo_pago = $tipo;
            $usuario->modo = LugarEjercicio::GYM;
            $usuario->fecha_inscripcion = Carbon::now();
            $usuario->inicio_reto = null;
            $usuario->correo_enviado = 0;
            $usuario->num_inscripciones = 1;
            $usuario->save();
        }
        if ($usuario->codigo != '') {
            $usuario->aumentarSaldo();
        }
        $compra = new Compra();
        $compra->monto = $monto;
        $compra->usuario_id = $usuario->id;
        $compra->save();
        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido al Reto Acton";
        $mensaje->pass = $pass;
        try {
            Mail::queue(new Registro($usuario, $mensaje));
            $usuario->correo_enviado = 1;
            $usuario->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function calcularMontoCompra($codigo, $email, $created_at, $fecha_inscripcion, $inicio_reto, $deleted_at)
    {
        $compra = new \stdClass();
        $dias = Contacto::where('email', $email)->first();
        $referenciado = $codigo == '' ? null : User::where('referencia', $codigo)->where('id', '!=', 1)->first();
        if (!$dias) {
            $dias = User::where('id', auth()->user())->first();
        }
        $descuento = 0;
        if(intval($dias->dias) == 14){
            $monto = 500;
        }elseif (intval($dias->dias) == 28) {
            $monto = 1000;
            $descuento = 35;
        }elseif (intval($dias->dias) == 56) {
            $monto = 2000;
            $descuento = 40;
        }elseif (intval($dias->dias) == 84) {
            $monto = 3000;
            $descuento = 50;
        }

        $codigo_tienda = CodigosTienda::where('codigo', $codigo)->where('email', $email)->get()->count();


        if($codigo_tienda>0){
            if(intval($dias->dias) == 14){
                //$descuento = 30;
            }elseif (intval($dias->dias) == 28) {
                //$descuento = 55;
            }elseif (intval($dias->dias) == 56) {
                //$descuento = 55;
            }elseif (intval($dias->dias) == 84) {
                //$descuento = 63;
            }
        }

        //$monto = intval(env('COBRO_ORIGINAL'));
        if ($created_at == null || $deleted_at != null) {
            if ($referenciado == null) {
                if ($created_at == null) {
                    $contacto = Contacto::where('email', $email)->first();
                    /*if ($contacto->etapa == 1) {
                        $descuento = intval(env('DESCUENTO'));
                    } else {
                        $descuento = intval(env("DESCUENTO" . ($contacto->etapa - 1)));
                    }*/
                } else {
                    //$monto = intval(env('COBRO_REFRENDO'));
                    //$descuento = 0;
                }
            } else {
                if ($deleted_at == null) {
                    $userref = User::where('referencia', $codigo)->where('id', '!=', 1)->first();
                    if(intval($dias->dias) == 14){
                        //$descuento = 30;
                    }elseif (intval($dias->dias) == 28) {
                        //$descuento = 55;
                    }elseif (intval($dias->dias) == 56) {
                        //$descuento = 55;
                    }elseif (intval($dias->dias) == 84) {
                        //$descuento = 63;
                    }else{
                        $descuento = intval(env('DESCUENTO_REFERENCIA'));
                    }



                } else {
                    //$monto = intval(env('COBRO_REFRENDO'));
                    //$descuento = 0;
                }
            }
        } else {
            if (self::isNuevo($created_at, $fecha_inscripcion)) {
                if (intval(intval($dias->dias) > Carbon::now()->diffInDays(Carbon::parse($inicio_reto)))) {
                    $monto = 0;
                    $descuento = 0;
                }
            }
        }
        $compra->original = $monto;
        $compra->descuento = $descuento;
        $compra->monto = round($monto - ($monto * ($descuento / 100)), 2);
        $now = \Carbon\Carbon::now();
        $horas = $now->diffInHours($dias->created_at);
        if($horas>20){
            $compra->monto = $monto;
            $compra->descuento = 0;
        }

        if ((20-$horas) > 0) {
            $compra->horas = 20 - $horas;
        }else{
            $compra->horas = 0;
        }

        return $compra;
    }

    public static function isNuevo($created_at, $fecha_inscripcion)
    {
        return $created_at->startOfDay()->diffInDays(Carbon::parse($fecha_inscripcion)->startOfDay()) == 0;
    }

    public function refrendarPago($monto, $telefono = null)
    {
        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido de nuevo al Reto Acton";
        $mensaje->pass = "";
        $this->objetivo = 0;
        $this->encuestado = false;
        $this->correo_enviado = 0;
        $this->pagado = true;
        $this->num_inscripciones = $this->num_inscripciones + 1;
        $this->fecha_inscripcion = Carbon::now();
        $this->inicio_reto = Carbon::now();
        $dias = 14;
        if ($monto <= env(COBRO_REFRENDO1)){
            $dias = 14;
            $this->dias = $this->dias+14;
            //$this->dias = $this->dias;
        }
        if ($monto <= env(COBRO_REFRENDO2) && $monto > env(COBRO_REFRENDO1)){
            $dias = 28;
            $this->dias = $this->dias+28;
            //$this->dias = $this->dias;
        }
        if ($monto <= env(COBRO_REFRENDO3) && $monto > env(COBRO_REFRENDO2)){
            $dias = 56;
            //$this->dias = $this->dias;
            $this->dias = $this->dias+56;
        }
        if ($monto <= env(COBRO_REFRENDO4) && $monto > env(COBRO_REFRENDO3)){
            $dias = 84;
            $this->dias = $this->dias+84;
            //$this->dias = $this->dias;
        }
        if ($this->deleted_at != null) {
            $pass = Utils::generarRandomString();
            $this->password = Hash::make($pass);
            $mensaje->pass = $pass;
        }
        $this->deleted_at = null;
        $this->save();
        if ($this->codigo != '') {
            $this->aumentarSaldo();
        }

        $renovaciones = new Renovaciones();
        $renovaciones->dias = $dias;
        $renovaciones->usuario_id = $this->id;
        $renovaciones->save();


        $ignorar = collect();//Generar dieta
        $preguntaAlimentos = Pregunta::where('pregunta', 'like', '%Eliminar de mi dieta lo siguiente%')->get();
        $respuestas = Respuesta::where('usuario_id', $this->id)->get()->keyBy('pregunta_id');
        foreach ($preguntaAlimentos as $preguntaAlimento) {
            foreach (json_decode($respuestas->get($preguntaAlimento->id)->respuesta) as $item) {
                if ($item == 'Pollo' || $item == 'Pavo')
                    $ignorar->push("Pechuga de $item");
                else if ($item == 'Huevo')
                    $ignorar->push("Claras de $item");
                $ignorar->push($item);
            }
        }


        $alimentosIgnorados = Dieta::whereIn('comida', $ignorar)->get()->pluck('id');
        $sexo = Pregunta::where('pregunta', 'like', '%Sexo%')->first();
        $objetivo = Pregunta::where('pregunta', 'like', '%Objetivo fitness%')->first();
        $preguntaPeso = Pregunta::where('pregunta', 'like', '%peso%')->first();
        $objetivo = strpos($respuestas->get($objetivo->id)->respuesta, "Bajar") ? 'bajar' : 'subir';
        $sexo = json_decode($respuestas->get($sexo->id)->respuesta);
        $peso = json_decode($respuestas->get($preguntaPeso->id)->respuesta);
        $this->genero = $sexo[0] == 'H' ? Genero::HOMBRE : Genero::MUJER;
        $this->objetivo = $objetivo == 'bajar' ? 0 : 1;


        $dietaAnterior = UsuarioDieta::where('usuario_id', $this->id)->where('dieta', '>', 1)->get()->last();
        if ($this->rol == RolUsuario::CLIENTE) {
            $numDieta = $dietaAnterior == null ? 1 : $dietaAnterior->dieta + 1;
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, $numDieta);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, $numDieta + 1);
            $kits = UsuarioKit::where('user_id', $this->id)->get();
            $this->agregarKit($this, $kits->count() == 0 ? 2 : 1);
        } else {
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 7);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 8);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 9);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 10);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 11);
            $this->generarDieta($this, $objetivo, $peso, $alimentosIgnorados, 12);
        }


        $compra = new Compra();
        $compra->monto = $monto;
        $compra->usuario_id = $this->id;
        $compra->save();
        try {
            Mail::queue(new Registro($this, $mensaje));
            $this->correo_enviado = 1;
            $this->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function aumentarSaldo()
    {
        $usuario = User::where('referencia', $this->codigo)->where('id', '!=', 1)->get()->first();
        error_log('aumenta saldo');
        error_log($usuario);
        if ($usuario != null) {
            $usuario->isVencido();
            if (!$usuario->vencido) {
                $usuario->ingresados_reto += 1;
                $usuario->ingresados += 1;
                if($usuario->tipo_referencia == 1){
                    $usuario->saldo += intval(env('COMISION'));
                }elseif ($usuario->tipo_referencia == 2) {
                    $semanas = intval($usuario->dias)/7;
                    switch ($semanas) {
                        case 2:
                            $comision = env('COMISION1');
                            break;
                        case 4:
                            $comision = env('COMISION2');
                            break;
                        case 8:
                            $comision = env('COMISION3');
                            break;
                        case 12:
                            $comision = env('COMISION4');
                            break;
                    }
                    $usuario->saldo += intval($comision);
                }
                unset($usuario->vencido);
                $usuario->save();
            }
        }else{
            $usuario = CodigosTienda::where('codigo', $this->codigo)->where('email', $this->email)->get()->first();
            if ($usuario != null) {
                $usuario->isVencido();
                if (!$usuario->vencido) {
                    $usuario->ingresados_reto += 1;
                    $usuario->ingresados += 1;
                    if($usuario->tipo_referencia == 1){
                        $usuario->saldo += intval(env('COMISION'));
                    }elseif ($usuario->tipo_referencia == 2) {
                        $semanas = intval($usuario->dias)/7;
                        switch ($semanas) {
                            case 2:
                                $comision = env('COMISION1');
                                break;
                            case 2:
                                $comision = env('COMISION2');
                                break;
                            case 3:
                                $comision = env('COMISION3');
                                break;
                            case 4:
                                $comision = env('COMISION4');
                                break;
                        }
                        $usuario->saldo += intval($comision);
                    }
                    unset($usuario->vencido);
                    $usuario->save();
                }
            }
        }
    }

    public function isVencido()
    {
        $num_dias = Renovaciones::where('usuario_id', $this->id)->sum('dias');
        if($this->num_inscripciones > 0) {
            $this->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->inicio_reto)->startOfDay()) >= (intval($this->dias)-1);
        }else{
            $this->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->inicio_reto)->startOfDay()) >= (intval($this->dias)-1);
        }
    }

    public function enviarContrasena(){
        $pass = Utils::generarRandomString();
        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido de nuevo al Reto Acton";
        $mensaje->pass = $pass;
        $this->password = Hash::make($pass);
        try {
            Mail::queue(new Registro($this, $mensaje));
            $this->correo_enviado = 1;
            $this->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}