<?php

namespace App\Http\Controllers;

use App\Amistades;
use App\Carrito;
use App\Code\RolUsuario;
use App\Code\Utils;
use App\CodigosPostales;
use App\ComentariosAmigos;
use App\Compra;
use App\Contacto;
use App\Dia;
use App\Renovaciones;
use App\LikesFotos;
use App\Pago;
use App\User;
use App\UsuarioDia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('buscarReferencia');
    }

    public function index()
    {
        $this->authorize('usuarios');
        return view('users.index');
    }

    public function usuarios_gratis()
    {
        $this->authorize('usuarios');
        return view('users.usuarios_gratis');
    }

    public function usuarios_validar()
    {
        $this->authorize('usuarios');
        return view('users.usuarios_validar');
    }

    public function listado(Request $request)
    {
        $nombre_prop=$request->nombre;
        $conexion_prop=$request->conexion;
        $estado_prop=$request->estado;
        $ciudad_prop=$request->ciudad;
        $cp_prop=$request->cp;
        $colonia_prop=$request->colonia;
        $tienda_prop=$request->tienda;
        $codigo_personal_prop=$request->codigo_personal;

        return view('users.usuarios')
            ->with([
                'nombre_prop'=>$nombre_prop,
                'conexion_prop'=>$conexion_prop,
                'estado_prop'=>$estado_prop,
                'ciudad_prop'=>$ciudad_prop,
                'cp_prop'=>$cp_prop,
                'colonia_prop'=>$colonia_prop,
                'tienda_prop'=>$tienda_prop,
                'codigo_personal_prop'=>$codigo_personal_prop,
            ]);
    }

    public function buscarAll(Request $request)
    {
        $this->authorize('usuarios');
        $campos = json_decode($request->campos);
        $usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
            $referenciado_por = User::where('referencia', $usuario->codigo)->first();
            if($referenciado_por != null) {
                $usuario->referenciado_por = $referenciado_por->name . ' ' . $referenciado_por->last_name;
            }else{
                $usuario->referenciado_por = '';
            }
        }

        return $usuarios;
    }

    public function buscar(Request $request)
    {
        $this->authorize('usuarios');
        $campos = json_decode($request->campos);
        $usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);
        $usuarios = $usuarios->where('tipo_referencia', 3);
        $usuarios = $usuarios->where('enviado_validacion', 2);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $referidos = User::where('codigo', $usuario->referencia)->count();
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
            $usuario->referidos = $referidos;
            $referenciado_por = User::where('referencia', $usuario->codigo)->first();
            if($referenciado_por != null) {
                $usuario->referenciado_por = $referenciado_por->name . ' ' . $referenciado_por->last_name;
            }else{
                $usuario->referenciado_por = '';
            }
        }

        return $usuarios;
    }

    public function buscar_validar(Request $request)
    {
        $this->authorize('usuarios');
        $campos = json_decode($request->campos);
        $usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);
        $usuarios = $usuarios->where('enviado_validacion', 1);
        $usuarios = $usuarios->where('archivo_validacion_1', '!=', NULL);
        $usuarios = $usuarios->where('archivo_validacion_2', '!=', NULL);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', '>=', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
        }

        return $usuarios;
    }

    public function buscar_coach(Request $request)
    {
        $campos = json_decode($request->campos);

        $referencia = User::where('id', auth()->user()->id)->first();

        $usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::COACH);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::COACH);

        $usuarios = $usuarios->where('codigo', $referencia->referencia);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', '>=', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
        }

        return $usuarios;
    }

    public function buscar_gratis(Request $request)
    {
        $campos = json_decode($request->campos);

        $referencia = User::where('id', auth()->user()->id)->first();

        $usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::COACH);
        $usuarios = $usuarios->where('rol', '!=', RolUsuario::COACH);

        $usuarios = $usuarios->where('codigo', $referencia->referencia);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', '>=', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }
        $usuarios = $usuarios->where('tipo_referencia', 3);
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
        }

        return $usuarios;
    }

    public function buscarSeguir(Request $request)
    {
        $campos = json_decode($request->campos);
        $usuarios = User::where('rol', '!=', '111');

        if($campos->conexion != '0' and $campos->conexion != '') {
            $amistad = Amistades::where('usuario_solicita_id', auth()->user()->id)->select('usuario_amigo_id')->get();
            if($campos->conexion == 'Siguiendo'){
                $usuarios = $usuarios->whereIn('id', $amistad);
            }
            if($campos->conexion == 'Sin conexión'){
                $usuarios = $usuarios->whereNotIn('id', $amistad);
            }
            if($campos->conexion == 'Tiendas'){
                $usuarios = $usuarios->where('rol', 'tienda');
            }
            if($campos->conexion == 'Me siguen'){
                $amistad_me_siguen = Amistades::where('usuario_amigo_id', auth()->user()->id)->select('usuario_solicita_id')->get();
                $usuarios = $usuarios->whereIn('id', $amistad_me_siguen);
            }
        }
        if($campos->tiendagym != '0' and $campos->tiendagym != '') {
            $usuarios = $usuarios->where('name', $campos->tiendagym)->where('rol', 'tienda');
        }
        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', '>=', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->cp != '0' and $campos->cp != '') {
            $usuarios = $usuarios->where('cp', $campos->cp);
        }
        if ($campos->estado != '0' and $campos->estado != '') {
            $usuarios = $usuarios->where('estado', $campos->estado);
        }
        if ($campos->ciudad != '0' and $campos->ciudad != '') {
            $usuarios = $usuarios->where('ciudad', $campos->ciudad);
        }
        if ($campos->colonia != '0' and $campos->colonia != '') {
            $usuarios = $usuarios->where('colonia', $campos->colonia);
        }
        if ($campos->codigo_personal != null) {
            $usuarios = $usuarios->where('referencia', 'like', '%' . strtoupper($campos->codigo_personal).'%');
        }
        if ($campos->sexo != null) {
            $usuarios = $usuarios->where('genero', 'like', '%' . $campos->sexo.'%');
        }
        if ($campos->orientacion != null) {
            $usuarios = $usuarios->where('genero_2', 'like', '%' . $campos->orientacion.'%');
        }
        if ($campos->orientacion != null) {
            $usuarios = $usuarios->where('genero_2', 'like', '%' . $campos->orientacion.'%');
        }
        if (count($campos->intereses) > 0) {
            $campos->intereses = implode('% ', $campos->intereses);
            $usuarios = $usuarios->where('intereses', 'like', '%' . $campos->intereses. '%');
        }
        if (count($campos->idiomas) > 0) {
            $campos->idiomas = implode('% ', $campos->idiomas);
            $usuarios = $usuarios->where('intereses', 'like', '%' . $campos->idiomas. '%');
        }
        if (count($campos->estatus) > 0) {
            foreach ($campos->estatus as $valor) {
                $usuarios = $usuarios->where('situacion_actual', 'like', '%' . $valor . '%');
            }
        }
        if ($campos->edad_inicio != null && $campos->edad_fin != null) {
            $usuarios = $usuarios->where('edad', '>=', '' . $campos->edad_inicio.'');
            $usuarios = $usuarios->where('edad', '<=', '' . $campos->edad_fin.'');
        }
        /*if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }*/
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
            $amistad = Amistades::where('usuario_amigo_id', $usuario->id)->where('usuario_solicita_id', Auth::id())->first();
            $usuario->amistad = 'no';
            if($amistad){
                $usuario->amistad = 'si';
            }

        }

        return $usuarios;
    }

    public function imagenes($usuario_id)
    {
        $usuario = User::select('id', 'name', 'inicio_reto', 'created_at', 'dias')->where('id', $usuario_id)->get()->first();
        //$diasRetoOriginal = intval(env('DIAS'));
        //$diasReto = intval(env('DIAS2'));
        $diasRetoOriginal = intval($usuario->dias);
        $diasReto = intval($usuario->dias);
        $diasTranscurridos = UsuarioDia::where('usuario_id', $usuario->id)->count();
        $inicioReto = \Carbon\Carbon::parse($usuario->inicio_reto);
        if ($usuario->num_inscripciones > 1) {
            $teoricos = $diasRetoOriginal + (($usuario->num_inscripciones - 2) * $diasReto) + Carbon::now()->startOfDay()->diffInDays($inicioReto);
            if (Carbon::parse($usuario->fecha_inscripcion)->startOfDay() == $inicioReto) {
                $teoricos++;
            }
            if ($teoricos > $diasRetoOriginal + (($usuario->num_inscripciones - 1) * $diasReto)) {
                $teoricos = $diasRetoOriginal + ($usuario->num_inscripciones - 1) * $diasReto;
            }
        } else {
            $teoricos = Carbon::now()->startOfDay()->diffInDays($inicioReto) + 1;
            if ($teoricos > $diasRetoOriginal) {
                $teoricos = $diasRetoOriginal;
            }
        }
        $semana = $teoricos % 7 == 0 ? intval($teoricos / 7) : intval($teoricos / 7) + 1;
        if ($semana * 7 < $teoricos) {
            $dias = 7;
        } else {
            $diaInicial = ($semana * 7) - 6;
            $dias = $teoricos - ($diaInicial - 1);
        }
        $dias = $this->getSemana($usuario, $semana);
        $dia = Carbon::now()->diffInDays(Carbon::parse($usuario->inicio_reto)) + 1;
        //return view('users.imagenes', ['usuario' => $usuario, 'dias' => $dias, 'semana' => $semana,
        //    'maximo' => $diasTranscurridos, 'teorico' => intval(env('DIAS')), 'dia' => $dia]);
        return view('users.imagenes', ['usuario' => $usuario, 'dias' => $dias, 'semana' => $semana,
            'maximo' => $diasTranscurridos, 'teorico' => intval($usuario->dias), 'dia' => $dia]);
    }

    public function getSemana(User $usuario, $semana)
    {
        $dias = collect();

        if ($usuario->inicio_reto == '') {//crear inicio del reto
            Storage::makeDirectory('public/reto/' . $usuario->id);
        }
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->get()->keyBy('dia_id');

        for ($i = 1; $i <= 7; $i++) {//construir arreglo y ruta de las imagenes para la vista
            $dia = (7 * ($semana - 1)) + $i;
            $imagenDia = $usuarioDias->get($dia);
            if ($imagenDia === null) {
                $imagenDia = new UsuarioDia();
            }
            $imagenDia->comentario = $imagenDia->comentario ?? '';
            $imagenDia->comentar = 0;
            $imagenDia->imagen = url("/reto/getImagen/reto/$usuario->id/" . $dia) . "/" . (Utils::generarRandomString(10));
            $imagenDia->video = url("/reto/getVideo/reto/$usuario->id/" . $dia) . "/" . (Utils::generarRandomString(10));
            $imagenDia->dia = $dia;
            $imagenDia->subir = $usuario->rol == RolUsuario::ADMIN ? true : $dia <= $usuarioDias->count();
            $imagenDia->loading = false;
            $dias->push($imagenDia);
        }
        return $dias;
    }

    public function showEncuesta($usuario_id)
    {
        $usuario = User::with(['encuesta' => function ($encuesta) {
            $encuesta->select('usuario_id', 'pregunta_id', 'respuesta', 'pregunta', 'multiple');
        }])->where('id', $usuario_id)->get()->first();

        foreach ($usuario->encuesta as $pregunta) {
            $pregunta->respuesta = json_decode($pregunta->respuesta);
        }
        return view('users.encuesta', ['usuario' => $usuario]);
    }

    public function showEncuestaGratis($usuario_id)
    {
        $usuario = User::with(['respuestas' => function ($respuestas) {
            $respuestas->select('usuario_id', 'pregunta_id', 'respuesta', 'pregunta', 'multiple');
        }])->where('id', $usuario_id)->get()->first();

        foreach ($usuario->encuesta as $pregunta) {
            $pregunta->respuesta = json_decode($pregunta->respuesta);
        }
        return view('users.encuesta_gratis', ['usuario' => $usuario]);
    }

    public function pagar(Request $request)
    {
        \DB::beginTransaction();
        $user = User::find($request->id);
        if ($user != null && $user->saldo > 0) {
            $compras = Compra::join('users', 'users.id', 'compras.usuario_id')
                ->whereNull('compras.deleted_at')->where('users.codigo', $user->referencia)
                ->where('compras.created_at', '<', Carbon::now()->startOfDay())->get();
            foreach ($compras as $compra) {
                $compra->pagado = true;
                $compra->save();
            }
            $monto = $compras->count() * intval(env('COMISION'));
            $user->cobrado = 1;
            $user->saldo = $user->saldo - $monto;
            $user->save();
            $pago = new Pago();
            $pago->monto = $monto;
            $pago->usuario_id = $user->id;
            $pago->save();
        }
        \DB::commit();
    }

    public function getReferencias(Request $request)
    {
        $referencias = User::select(["id", "name", "email", "created_at", "num_inscripciones"])->where('codigo', $request->user()->referencia);
        return $referencias->paginate(5);
    }

    public function bajar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->num_inscripciones = 0;
        $usuario->deleted_at = Carbon::now();
        $usuario->save();
        $contacto = Contacto::where('email', $usuario->email)->first();
        if ($contacto !== null) {
            $contacto->delete();
        }
        return response()->json(['status' => 'ok', 'redirect' => url('/usuarios/')]);
    }

    public function verReferencias(Request $request)
    {
        $campos = json_decode($request->campos);
        $usuario = User::find($campos->id);
        if ($usuario !== null) {
            $referencias = User::where('codigo', $usuario->referencia)->whereNull('deleted_at')->paginate(10);
            return $referencias;
        }
    }

    public function cambiarDias(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $nuevaFecha = Carbon::now()->startOfDay();
            $nuevaFecha->subDays($request->dias_reto);
            $usuario->inicio_reto = $nuevaFecha;
            $usuario->fecha_inscripcion = $nuevaFecha;
            $usuario->save();
        }
    }

    public function aumentarSaldos(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $usuario->saldo = $request->saldoAumentado;
            $usuario->save();
        }
    }

    public function aumentarSemanas(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $dias_nuevo = intval($request->nuevaSemanas)*7+1;

            $usuario->inicio_reto = Carbon::now()->subDays($usuario->dias);
            $usuario->dias = $usuario->dias+intval($request->nuevaSemanas)*7;
            $usuario->encuestado = false;
            $usuario->save();

            $renovaciones = new Renovaciones();
            $renovaciones->dias = intval($request->nuevaSemanas)*7;
            $renovaciones->usuario_id = $request->id;
            $renovaciones->save();
        }
        return "{'status': 'ok'}";
    }

    public function cambiaFecha(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $fecha = join('-', array_reverse(explode('/', $request->fecha)));
            $usuario->inicio_reto = $fecha;
            $usuario->save();
        }
        return "{'status': 'ok'}";
    }

    public function cambiaContrasenia(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $usuario->password = Hash::make($request->contrasenia);
            $usuario->save();
        }
        return "{'status': 'ok'}";
    }

    public function exportar($filtros)
    {
        $this->authorize('usuarios');
        $campos = json_decode($filtros);
        $usuarios = User::join('contactos', 'contactos.email', 'users.email')->where('rol', '!=', RolUsuario::ADMIN);

        if ($campos->nombre != null) {
            $usuarios = $usuarios->where('name', 'like', '%' . $campos->nombre . '%');
        }
        if ($campos->email != null) {
            $usuarios = $usuarios->where('email', 'like', '%' . $campos->email . '%');
        }
        if ($campos->fecha_inicio != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_inicio)));
            $usuarios = $usuarios->where('inicio_reto', '>=', $fecha);
        }
        if ($campos->fecha_final != null) {
            $fecha = join('-', array_reverse(explode('/', $campos->fecha_final)));
            $usuarios = $usuarios->where('inicio_reto', '<=', $fecha);
        }
        if ($campos->saldo != null) {
            if (is_numeric($campos->saldo))
                $usuarios = $usuarios->where('saldo', $campos->saldo);
            else
                return collect();
        }
        if ($campos->ingresados != null) {
            if (is_numeric($campos->ingresados))
                $usuarios = $usuarios->where('ingresados', $campos->ingresados);
            else
                return collect();
        }
        if ($campos->ingresadosReto != null) {
            if (is_numeric($campos->ingresadosReto))
                $usuarios = $usuarios->where('ingresados_reto', $campos->ingresadosReto);
            else
                return collect();
        }
        if ($campos->estado != 0) {
            if ($campos->estado == 1) {
                $consulta = 'CURDATE() >= DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS') - 1) . ' DAY)';
            } else if ($campos->estado == 2) {
                $consulta = 'CURDATE() < DATE_ADD(fecha_inscripcion, interval ' . (env('DIAS')) . ' DAY)';
            }
            $usuarios = $usuarios->whereRaw($consulta);
        }

        $usuarios = $usuarios->select(['users.*', 'contactos.medio'])->get();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="invitados.xlsx"');
        header('Cache-Control: max-age=0');

        $spreadsheet = new Spreadsheet();
        $row = 1;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValueByColumnAndRow(1, 1, 'Nombre');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Email');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Referencia');
        $sheet->setCellValueByColumnAndRow(4, 1, 'Fecha inscripcion');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Inicio del reto');
        $sheet->setCellValueByColumnAndRow(6, 1, 'ingresados');
        $sheet->setCellValueByColumnAndRow(7, 1, 'saldo');
        $sheet->setCellValueByColumnAndRow(8, 1, 'genero');
        $sheet->setCellValueByColumnAndRow(9, 1, 'objetivo');
        $sheet->setCellValueByColumnAndRow(10, 1, 'Tipo de pago');
        $row++;
        foreach ($usuarios as $usuario) {
            $sheet->setCellValueByColumnAndRow(1, $row, $usuario->name . ' ' . $usuario->last_name);
            $sheet->setCellValueByColumnAndRow(2, $row, $usuario->email);
            $sheet->setCellValueByColumnAndRow(3, $row, $usuario->referencia);
            $sheet->setCellValueByColumnAndRow(4, $row, $usuario->fecha_inscripcion);
            $sheet->setCellValueByColumnAndRow(5, $row, $usuario->inicio_reto);
            $sheet->setCellValueByColumnAndRow(6, $row, $usuario->ingresados);
            $sheet->setCellValueByColumnAndRow(7, $row, $usuario->saldo);
            $sheet->setCellValueByColumnAndRow(8, $row, $usuario->genero);
            $sheet->setCellValueByColumnAndRow(9, $row, $usuario->objetivo);
            $sheet->setCellValueByColumnAndRow(10, $row, $usuario->tipo_pago);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function verPagos(Request $request)
    {
        $campos = json_decode($request->campos);
        $usuario = User::find($campos->id);
        if ($usuario !== null) {
            $pagos = Pago::where('usuario_id', $usuario->id)->orderByDesc('created_at')->paginate(10);
            return $pagos;
        }
    }

    public function verCompras(Request $request)
    {
        $campos = json_decode($request->campos);
        $usuario = User::find($campos->id);
        if ($usuario !== null) {
            $compras = Compra::where('usuario_id', $usuario->id)->orderByDesc('created_at')->paginate(10);
            return $compras;
        }
    }

    public function verComprasByReferencia(Request $request)
    {
        $campos = json_decode($request->campos);
        $usuario = User::find($campos->id);
        if ($usuario !== null) {
            $compras = Compra::join('users', 'users.id', 'compras.usuario_id')->where('compras.pagado', false)
                ->whereNull('compras.deleted_at')->where('users.codigo', $usuario->referencia)
                ->orderBy('created_at')
                ->select(['users.name', 'users.last_name', 'compras.monto', 'compras.created_at'])->paginate(10);
            return $compras;
        }
    }



    public function actualizarDias(Request $request, $dias)
    {
        $usuario = User::where('id', auth()->user()->id)->first();

        error_log(auth()->user());

        $usuario->dias_paso = $dias;
        $usuario->save();

        return $dias;

    }



    public function seguir(Request $request, $id)
    {
        $usuario = Amistades::create([
            'usuario_solicita_id'=> auth()->user()->id,
            'usuario_amigo_id' => $id
        ]);


        error_log($usuario);
        return "{'status': 'ok'}";

    }



    public function dejar_seguir(Request $request, $id)
    {
        $usuario = Amistades::where([
            'usuario_solicita_id'=> auth()->user()->id,
            'usuario_amigo_id' => $id
        ])->delete();


        error_log($usuario);
        return "{'status': 'ok'}";

    }


    public function comenatarios(Request $request, $dia, $id)
    {
        $comentarios = ComentariosAmigos::where([
            'usuario__id'=> $id,
            'dia' => $dia
        ])->with('usuario_comenta')->get();

        return $comentarios;

    }


    public function likes(Request $request, $dia, $id)
    {
        $likes = LikesFotos::where([
            'usuario__id'=> $id,
            'dia' => $dia
        ])->count();

        return $likes;

    }


    public function setlikes(Request $request, $dia, $id)
    {
        $likes = LikesFotos::where([
            'usuario__id'=> $id,
            'dia' => $dia,
            'usuario_like_id' => auth()->user()->id
        ])->count();

        if($likes > 0){
            LikesFotos::where([
                'usuario__id'=> $id,
                'dia' => $dia,
                'usuario_like_id' => auth()->user()->id
            ])->delete();
        }else{
            LikesFotos::create([
                'usuario__id'=> $id,
                'dia' => $dia,
                'usuario_like_id' => auth()->user()->id
            ]);
        }

        $likes = LikesFotos::where([
            'usuario__id'=> $id,
            'dia' => $dia
        ])->count();

        return $likes;

    }


    public function comentarioNuevo(Request $request, $dia, $id)
    {
        $comentario = $request->comentario;
        error_log('COMENTA');
        error_log($comentario);
        ComentariosAmigos::create([
            'usuario__id'=> $id,
            'dia' => $dia,
            'usuario_comenta_id' => auth()->user()->id,
            'comentario' => $comentario
        ]);

        $comentarios = ComentariosAmigos::where([
            'usuario__id'=> $id,
            'dia' => $dia
        ])->with('usuario_comenta')->get();

        return $comentarios;

    }


    public function getGYM()
    {
        $estados = User::select('gym')
            ->distinct()->get();

        return $estados;

    }


    public function getEstadosGYM()
    {
        $estados = CodigosPostales::select('estado')
            ->distinct()->get();

        return $estados;

    }


    public function getCiudadesGYM(Request $request)
    {
        $estado = $request->estado;
        $ciudades = CodigosPostales::where('estado', $estado)->select('ciudad')
        ->distinct()->get();

        return $ciudades;

    }


    public function getEstados()
    {
        $estados = CodigosPostales::select('estado')
            ->distinct()->get();

        return $estados;

    }


    public function getCiudades(Request $request)
    {
        $estado = $request->estado;
        $ciudades = CodigosPostales::where('estado', $estado)->select('ciudad')
        ->distinct()->get();

        return $ciudades;

    }


    public function getCPs(Request $request)
    {
        $ciudad = $request->ciudad;
        $cps = CodigosPostales::where('ciudad', $ciudad)->select('cp')
        ->distinct()->get();

        return $cps;

    }


    public function getColonias(Request $request)
    {
        $cp = $request->cp;
        $colonias = CodigosPostales::where('cp', $cp)->select('colonia')
        ->distinct()->get();

        return $colonias;

    }


    public function getTiendas(Request $request)
    {
        $tiendas = User::where('rol', 'tienda')->select('name')
        ->distinct()->get();

        return $tiendas;

    }


    public function guardaUbicacion(Request $request)
    {
        if(isset($request->usuario['intereses'])) {
            $intereses = implode(', ', $request->usuario['intereses']);
        }else{
            $intereses = '';
        }
        if(isset($request->usuario['idiomas'])) {
            $idiomas = implode(', ', $request->usuario['idiomas']);
        }else{
            $idiomas = '';
        }
        $estado = $request->estado;
        $ciudad = $request->ciudad;
        $cp = $request->cp;
        $colonia = $request->colonia;
        $usuario = User::where('id', auth()->user()->id)->first();

        $usuario->idiomas= $idiomas;
        $usuario->intereses = $intereses;
        $usuario->primer_inicio = 1;
        $usuario->intereses_publico = $request->usuario['intereses_publico'];
        if(isset($request->usuario['codigo_nuevo'])){
            $referidos = User::where('codigo', $usuario->referencia)->get();
            foreach ($referidos as $c){
                $c->codigo = $request->usuario['codigo_nuevo'];
                $c->save();
            }
            $usuario->referencia = $request->usuario['codigo_nuevo'];
        }
        if ($request->usuario['genero'] == 'Hombre'){
            $genero = 0;
        }else{
            $genero = 1;
        }
        $usuario->genero = $genero;
        $usuario->genero_2 = $request->usuario['genero_2'];
        $usuario->situacion_actual = $request->usuario['situacion_actual'];
        $usuario->situacion_actual_publico = $request->usuario['situacion_actual_publico'];
        $usuario->gym = $request->usuario['gym'];
        //$usuario->gym_ciudad = $request->usuario['gym_ciudad'];
        $usuario->gym_ciudad = $request->usuario['gym_ciudad'];
        $usuario->estado_gym = $request->usuario['estado_gym'];
        $usuario->numero = $request->usuario['numero'];
        $usuario->estado = $estado;
        $usuario->ciudad = $ciudad;
        $usuario->cp = $cp;
        $usuario->colonia = $colonia;
        $usuario->calle = $request->usuario['calle'];
        $usuario->numero = $request->usuario['numero'];
        $usuario->numero_tarjeta = $request->usuario['numero_tarjeta'];
        $usuario->banco = $request->usuario['banco'];
        $usuario->edad = $request->usuario['edad'];
        $usuario->edad_publico = $request->usuario['edad_publico'];

        $usuario->save();

        return $usuario;

    }


    public function guardaInfoGeneral(Request $request)
    {
        $edad = $request->edad;
        $gym = $request->gym;
        $intereses = implode(',', $request->intereses);
        $empleo = $request->empleo;
        $estudios = $request->estudios;
        $idiomas = implode(',', $request->idiomas);
        $edad_publico = $request->edad_publico;
        $empleo_publico = $request->empleo_publico;
        $gym_publico = $request->gym_publico;
        $intereses_publico = $request->intereses_publico;
        $estudios_publico = $request->estudios_publico;
        $idiomas_publico = $request->idiomas_publico;
        $usuario = User::where('id', auth()->user()->id)->first();

        $usuario->edad = $edad;
        $usuario->gym = $gym;
        $usuario->intereses = $intereses;
        $usuario->empleo = $empleo;
        $usuario->estudios = $estudios;
        $usuario->idiomas = $idiomas;
        $usuario->edad_publico = $edad_publico;
        $usuario->empleo_publico = $empleo_publico;
        $usuario->intereses_publico = $intereses_publico;
        $usuario->gym_publico = $gym_publico;
        $usuario->estudios_publico = $estudios_publico;
        $usuario->idiomas_publico = $idiomas_publico;

        $usuario->save();

        return $usuario;

    }

    public function refrendarPagoCeros(Request $request)
    {
        $mensaje = new \stdClass();
        $mensaje->subject = "Bienvenido de nuevo al Reto Acton";
        $mensaje->pass = "";
        $usuario = User::where('id', auth()->user()->id)->first();
        $usuario->objetivo = 0;
        $usuario->encuestado = false;
        $usuario->correo_enviado = 0;
        $usuario->pagado = true;
        $usuario->num_inscripciones = $usuario->num_inscripciones + 1;
        $usuario->fecha_inscripcion = Carbon::now();
        $usuario->inicio_reto = Carbon::now()->subDays($usuario->dias+1);
        $usuario->dias = $usuario->dias+$request->dias;
        $resta = 0;
        if($request->dias == 14){
            $resta = 500;
        }
        if($request->dias == 28){
            $resta = 1000;
        }
        if($request->dias == 56){
            $resta = 2000;
        }
        if($request->dias == 84){
            $resta = 3000;
        }
        $usuario->saldo = $usuario->saldo-$resta;
        $usuario->deleted_at = null;
        $usuario->save();

        $renovaciones = new Renovaciones();
        $renovaciones->dias = $request->dias;
        $renovaciones->usuario_id = $usuario->id;
        $renovaciones->save();


        $compra = new Compra();
        $compra->monto = '0';
        $compra->usuario_id = $usuario->id;
        $compra->save();

        return $usuario;
    }

    public function suplementos()
    {
        return view('users.suplementos');
    }

    public function fichasSuplementos($tipo)
    {
        return view('users.fichas_suplementos', ['tipo' => $tipo]);
    }

    public function agregarCarrito(Request $request)
    {
        $existe = Carrito::where('producto', $request->tipo)->where('pagado', 0)->first();
        $existe_c = Carrito::where('producto', $request->tipo)->where('pagado', 0)->count();
        $cantidad = 0;
        if(($existe_c)>0) {
            $cantidad = $existe->cantidad;
            $existe->delete();
        }else{
            $existe = 0;
        }
        $precio = 0;
        if($request->tipo == 'maximal'){
            $precio = 650;
        }
        if($request->tipo == 'creatina'){
            $precio = 450;
        }
        if($request->tipo == 'ergogen'){
            $precio = 450;
        }
        if($request->tipo == 'glutamina'){
            $precio = 450;
        }
        if($request->tipo == 'whey'){
            $precio = 1250;
        }
        if($request->tipo == 'bcaa'){
            $precio = 550;
        }
        $comision = $precio*.10;
        Carrito::create([
            'producto' => $request->tipo,
            'cantidad' => $cantidad+1,
            'usuario_id' => $request->user()->id,
            'precio' => $precio*($cantidad+1),
            'guia' => '',
            'servicio' => '',
            'comentarios' => '',
            'comision' => $comision,
        ]);
        return "{'status': 'ok'}";
    }

    public function verCarrito(Request $request)
    {
        $carrito = Carrito::where('pagado', 0)->where('usuario_id', $request->user()->id)->get();
        return view('users.carrito', ['carrito' => $carrito]);
    }

    public function pagarCarrito(Request $request)
    {
        $suma = Carrito::Where('usuario_id', $request->user()->id)->where('pagado', 0)
        ->selectRaw("SUM(precio) as total")
        ->groupBy('usuario_id')
        ->get();
        if($suma[0]->total > $request->user()->saldo) {
            return "No cuentas con saldo suficiente";
        }else{
            $carrito = Carrito::Where('usuario_id', $request->user()->id)->where('pagado', 0)->get();
            foreach ($carrito as $c){
                $c->pagado = 1;
                $c->save();
            }
            $request->user()->saldo = $request->user()->saldo-$suma[0]->total;
            $request->user()->save();
            return "Se ha completado la compra";
        }
    }

    public function eliminarCarrito(Request $request)
    {
        $existe = Carrito::where('id', $request->id)->first();
        $existe->delete();
        return "Se elimino el producto";
    }

    public function verPedidos(Request $request)
    {
        $carrito = Carrito::select('usuario_id')->where('pagado', 1)->get();
        $usuarios = User::whereIn('id', $carrito)->get();
        return view('users.pedidos', ['usuarios' => $usuarios]);
    }

    public function detallePedidos(Request $request)
    {
        $carrito = Carrito::where('usuario_id', $request->id)->where('pagado', 1)->get();
        return $carrito;
    }

    public function enviarPedidos(Request $request)
    {
        $carrito = Carrito::where('usuario_id', $request->id)->get();
        foreach ($carrito as $c){
            $c->enviado = 1;
            $c->save();
        }
        return $carrito;
    }

    public function usuarioPedidos(Request $request)
    {
        $carrito = User::where('id', $request->id)->first();
        return $carrito;
    }

    public function listadoReferidos(Request $request)
    {
        $nombre_prop=$request->nombre;
        $conexion_prop=$request->conexion;
        $estado_prop=$request->estado;
        $ciudad_prop=$request->ciudad;
        $cp_prop=$request->cp;
        $colonia_prop=$request->colonia;
        $tienda_prop=$request->tienda;
        $codigo_personal_prop=$request->codigo_personal;

        return view('users.referidos')
            ->with([
                'nombre_prop'=>$nombre_prop,
                'conexion_prop'=>$conexion_prop,
                'estado_prop'=>$estado_prop,
                'ciudad_prop'=>$ciudad_prop,
                'cp_prop'=>$cp_prop,
                'colonia_prop'=>$colonia_prop,
                'tienda_prop'=>$tienda_prop,
                'codigo_personal_prop'=>$codigo_personal_prop,
            ]);
    }

    public function listadoReferidosTop(Request $request)
    {

        return view('users.referidos_top');
    }

    public function buscarReferidos(Request $request)
    {
        $campos = json_decode($request->campos);
        //$usuarios = User::where('rol', '!=', RolUsuario::ADMIN);
        //$usuarios = $usuarios->where('rol', '!=', RolUsuario::TIENDA);
        //$usuarios = $usuarios->where('tipo_referencia', 3);
        $usuarios = User::where('codigo', $request->user()->referencia)->where('enviado_validacion', 2);

        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*'])->paginate(15);
        $comision = intval(env('COMISION'));
        $contactos = Contacto::whereIn('email', $usuarios->pluck('email'))->get()->keyBy('email');
        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            if ($usuario->inicio_reto != null) {
                $usuario->dias_reto = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay()) + 1;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
            $contacto = $contactos->get($usuario->email);
            $usuario->medio = $contacto == null ? '' : $contacto->medio;
            $usuario->telefono = $contacto == null ? '' : $contacto->telefono;
            $usuario->vigente = !$usuario->vencido;
        }



        return $usuarios;
    }

    public function buscarReferidosTop(Request $request)
    {


        $filt = User::select('codigo', \DB::raw("count(codigo) as count"))
            ->where('id', 411)
            ->orWhere('inicio_reto', '>=', '2022-01-01')
            ->groupBy('codigo')
            ->orderBy('count', 'DESC')
            ->limit(30)
            ->get();
        $cont = 0;
        $ids=array();
        foreach ($filt as $f){
            array_push($ids, $f['codigo']);
        }
        //if(isset($filt[0]['codigo'])){array_push($ids, $filt[0]['codigo']);}
        print_r($ids);
        //$ids = array($filt[0]['codigo'],$filt[1]['codigo'],$filt[2]['codigo'],$filt[3]['codigo'],$filt[4]['codigo'],$filt[5]['codigo'],$filt[6]['codigo'],$filt[7]['codigo'],$filt[8]['codigo'],$filt[9]['codigo'],$filt[10]['codigo'],$filt[11]['codigo'],$filt[12]['codigo']);
        $ids_ordered=array();
        foreach ($ids as $i) {
            if($i !== NULL && $i !== 'NULL' && $i !== '' && $i !== 'Pipolan' && $i != 'GC5ZG8J'){
                $v = User::where('referencia', $i)->first();
                if($v != NULL) {
                    array_push($ids_ordered, $v->id);
                }
            }
            $cont++;
        }
        $orden = implode(',', $ids_ordered);

        $usuarios = User::whereIn('id', $ids_ordered)->orderByRaw("FIELD(id, $orden)");

        $usuarios = $usuarios->select(['users.*'])->paginate(10);

        return $usuarios;
    }


    public function detalleReferido(Request $request, $id)
    {
        $referido = User::where('id', $id)->first();

        return view('users.detalle_referido')
            ->with([
                'referido'=>$referido,
            ]);
    }


}
