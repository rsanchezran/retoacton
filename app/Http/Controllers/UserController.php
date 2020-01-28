<?php

namespace App\Http\Controllers;

use App\Code\RolUsuario;
use App\Code\Utils;
use App\Contacto;
use App\User;
use App\UsuarioDia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function buscar(Request $request)
    {
        $this->authorize('usuarios');
        $campos = json_decode($request->campos);
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
        $usuarios = $usuarios->orderByDesc('created_at');
        $usuarios = $usuarios->select(['users.*', 'contactos.medio'])->paginate(20);
        $comision = intval(env('COMISION'));

        foreach ($usuarios as $usuario) {
            $usuario->dias_reto = 0;
            if ($usuario->inicio_reto != null) {
                $dias = Carbon::now()->diffInDays(Carbon::parse($usuario->inicio_reto))+1;
                $usuario->dias_reto = $dias;
            }
            $usuario->total = $usuario->ingresados * $comision;
            $usuario->depositado = $usuario->total - $usuario->saldo;
            $usuario->pendientes = $usuario->saldo / $comision;
            $usuario->pagados = $usuario->depositado / $comision;
        }

        return $usuarios;
    }

    public function imagenes($usuario_id)
    {
        $web = '/reto/getImagen/reto/'; //ruta para imagenes route /reto/getImagen... en carpeta .../reto
        $usuario = User::select('id', 'name', 'inicio_reto','created_at')->where('id', $usuario_id)->get()->first();
        $links = collect();
        $dias = UsuarioDia::where('usuario_id', $usuario_id)->orderBy('dia_id')->get()->keyBy('dia_id');
        $diasTranscurridos = Carbon::now()->diffInDays($usuario->inicio_reto)+1;
        if ($diasTranscurridos > env('DIAS')) {
            $diasTranscurridos = env('DIAS');
        }
        for ($i = 1; $i <= $diasTranscurridos; $i++) {
            $dia = $dias->get($i);
            if ($dia === null) {
                $dia = new UsuarioDia();
                $dia->imagen = '/images/none.png';
                $dia->comentario = '';
            } else {
                $dia->imagen = $web . $usuario_id . '/' . ($i) . '/' . (Utils::generarRandomString(10));
                $dia->comentario = $dia->comentario == null ? '' : $dia->comentario;
            }
            $dia->comentar = 0;
            $links->push($dia);
        }
        return view('users.imagenes', ['links' => $links, 'usuario' => $usuario]);
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

    public function pagar(Request $request)
    {
        $user = User::find($request->id);
        if ($user != null && $user->saldo > 0) {
            $user->cobrado = 1;
            $user->saldo = 0;
            $user->save();
        }
    }

    public function getReferencias(Request $request)
    {
        $referencias = User::select(["id", "name", "email", "created_at"])->where('codigo', $request->user()->referencia);
        return $referencias->paginate(5);
    }

    public function bajar(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->pass = '';
        $usuario->delete();
        $contacto = Contacto::where('email', $usuario->email)->first();
        if ($contacto !== null) {
            $contacto->delete();
        }
        return response()->json(['status' => 'ok', 'redirect' => url('/usuarios/')]);
    }

    public function verReferencias(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $referencias = User::where('codigo', $usuario->referencia)->whereNull('deleted_at')->get();
            return $referencias;
        }
    }

    public function cambiarDias(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $usuario->inicio_reto = Carbon::now();
            $usuario->inicio_reto->subDays($request->dias_reto - 1);
            $usuario->save();
        }
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
}