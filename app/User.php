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
        'dias_paso', 'pago_refrendo'
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
                'dias' => $contacto->dias
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
            error_log('AQUI ENTRO');
            $dias = User::where('id', auth()->user())->first();
        }
        if(intval($dias->dias) == 14){
            //$monto = 600;
            //$descuento = 25;
            $monto = 15;
            $descuento = 0;
        }elseif (intval($dias->dias) == 28) {
            //$monto = 1000;
            //$descuento = 35;
            $monto = 15;
            $descuento = 0;
        }elseif (intval($dias->dias) == 56) {
            //$monto = 2000;
            //$descuento = 40;
            $monto = 15;
            $descuento = 0;
        }elseif (intval($dias->dias) == 84) {
            //$monto = 3000;
            //$descuento = 50;
            $monto = 15;
            $descuento = 0;
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
                    /*$userref = User::where('referencia', $codigo)->where('id', '!=', 1)->first();
                    if(intval($dias->dias) == 14 && $userref->tipo_referencia !== 1){
                        $descuento = 40;
                    }elseif (intval($dias->dias) == 28 && $userref->tipo_referencia !== 1) {
                        $descuento = 55;
                    }elseif (intval($dias->dias) == 56 && $userref->tipo_referencia !== 1) {
                        $descuento = 55;
                    }elseif (intval($dias->dias) == 84 && $userref->tipo_referencia !== 1) {
                        $descuento = 63;
                    }else{
                        $descuento = intval(env('DESCUENTO_REFERENCIA'));
                    }*/
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
        error_log('AQUI ENTRO');
        error_log($monto);
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
        if ($monto <= env(COBRO_REFRENDO1)){
            $this->dias = $this->dias+24;
        }
        if ($monto <= env(COBRO_REFRENDO2) && $monto > env(COBRO_REFRENDO1)){
            $this->dias = $this->dias+48;
        }
        if ($monto <= env(COBRO_REFRENDO3) && $monto > env(COBRO_REFRENDO2)){
            $this->dias = $this->dias+56;
        }
        if ($monto <= env(COBRO_REFRENDO4) && $monto > env(COBRO_REFRENDO3)){
            $this->dias = $this->dias+84;
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
        if ($this->num_inscripciones == 1) {
            $this->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->inicio_reto)->startOfDay()) > intval(env('DIAS'));
        } else {
            $this->vencido = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->inicio_reto)->startOfDay()) > intval(env('DIAS2'));
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