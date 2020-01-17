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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'rol', 'inicio_reto', 'referencia', 'saldo', 'pagado', 'tarjeta', 'tipo_pago',
        'encuestado', 'objetivo', 'codigo',
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

    public static function crear($nombre, $apellidos = '', $email, $tipo, $tarjeta = null, $objetivo, $codigo = '')
    {
        $pass = Utils::generarRandomString();
        $usuario = User::create([
            'name' => $nombre,
            'last_name' => $apellidos,
            'email' => $email,
            'password' => Hash::make($pass),
            'pagado' => true,
            'encuestado' => false,
            'objetivo' => (int)$objetivo,
            'referencia' => Utils::generarRandomString(),
            'codigo' => $codigo,
            'rol' => RolUsuario::CLIENTE,
            'tipo_pago' => $tipo,
            'tarjeta' => $tarjeta,
            'modo' => LugarEjercicio::GYM,
            'fecha_inscripcion' => Carbon::now(),
            'correo_enviado' => 0
        ]);

        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido al Reto Acton de 8 semanas";
        $mensaje->pass = $pass;
        try {
            Mail::queue(new Registro($usuario, $mensaje));
            $usuario->correo_enviado = 1;
            $usuario->save();
        } catch (\Exception $e) {}
    }
}
