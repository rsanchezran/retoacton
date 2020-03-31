<?php

namespace App;

use App\Code\Genero;
use App\Code\LugarEjercicio;
use App\Code\Objetivo;
use App\Code\RolUsuario;
use App\Code\Utils;
use App\Mail\Registro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\MyResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
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
        'encuestado', 'objetivo', 'codigo', 'fecha_inscripcion', 'correo_enviado', 'modo', 'num_inscripciones'
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
//        $respuesta = new Respuesta();
//        $respuesta->pregunta_id = 9;
//        $respuesta->usuario_id = $usuario->id;
//        $respuesta->respuesta = "Bajar de peso";
//        $respuesta->save();
        $compra = new Compra();
        $compra->monto = $monto;
        $compra->usuario_id = $usuario->id;
        $compra->save();
        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido al Reto Acton de 8 semanas";
        $mensaje->pass = $pass;
        try {
            Mail::queue(new Registro($usuario, $mensaje));
            $usuario->correo_enviado = 1;
            $usuario->save();
        } catch (\Exception $e) {
        }
    }

    public static function calcularMontoCompra($codigo, $email, $created_at, $fecha_inscripcion, $inicio_reto, $deleted_at)
    {
        $compra = new \stdClass();
        $referenciado = User::where('referencia', $codigo)->where('id', '!=', 1)->first();
        $monto = intval(env('COBRO_ORIGINAL'));
        if ($created_at == null || $deleted_at != null) {
            if ($referenciado == null) {
                if($created_at==null){
                    $contacto = Contacto::where('email', $email)->first();
                    if ($contacto->etapa == 1) {
                        $descuento = intval(env('DESCUENTO'));
                    } else {
                        $descuento = intval(env("DESCUENTO" . ($contacto->etapa - 1)));
                    }
                }else{
                    $monto = intval(env('COBRO_REFRENDO'));
                    $descuento = 0;
                }
            } else {
                if ($deleted_at==null){
                    $descuento = intval(env('DESCUENTO_REFERENCIA'));
                }else{
                    $monto = intval(env('COBRO_REFRENDO'));
                    $descuento = 0;
                }
            }
        } else {
            if (self::isNuevo($created_at, $fecha_inscripcion)) {
                if (intval(env('DIAS') > Carbon::now()->diffInDays(Carbon::parse($inicio_reto)))) {
                    $monto = 0;
                    $descuento = 0;
                } else {
                    $monto = intval(env('COBRO_REFRENDO'));
                    $descuento = 0;
                }
            } else {
                $monto = intval(env('COBRO_REFRENDO'));
                $descuento = 0;
            }
        }
        $compra->original = $monto;
        $compra->descuento = $descuento;
        $compra->monto = round($monto - ($monto * ($descuento / 100)), 2);
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
        if($this->deleted_at!=null){
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
        }
    }

    public function aumentarSaldo()
    {
        $usuario = User::where('referencia', $this->codigo)->get()->first();
        if ($usuario != null) {
            $usuario->isVencido();
            if (!$usuario->vencido) {
                $usuario->ingresados_reto += 1;
                $usuario->ingresados += 1;
                $usuario->saldo += intval(env('COMISION'));
                unset($usuario->vencido);
                $usuario->save();
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
}
