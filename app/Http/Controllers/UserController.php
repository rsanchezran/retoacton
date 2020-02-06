<?php

namespace App\Http\Controllers;

use App\Code\RolUsuario;
use App\Code\Utils;
use App\Compra;
use App\Contacto;
use App\Dia;
use App\Pago;
use App\User;
use App\UsuarioDia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
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
        $usuarios = User::join('contactos', 'contactos.email', 'users.email')
            ->where('rol', '!=', RolUsuario::ADMIN)->whereNull('contactos.deleted_at');

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
            $usuario->isVencido();
            if ($usuario->inicio_reto != null) {
                $dias = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($usuario->inicio_reto)->startOfDay());
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
        $usuario = User::select('id', 'name', 'inicio_reto', 'created_at')->where('id', $usuario_id)->get()->first();
        $usuarioDias = UsuarioDia::where('usuario_id', $usuario->id)->count();
        if ($usuarioDias == 0) {
            $semana = 1;
        } else {
            $semana = $usuarioDias % 7 == 0 ? intval($usuarioDias / 7) : intval($usuarioDias / 7) + 1;
        }
        $dias = $this->getSemana($usuario, $semana);

        return view('users.imagenes', ['usuario' => $usuario, 'dias' => $dias, 'semana' => $semana,
            'maximo' => $usuarioDias, 'teorico' => intval(env('DIAS'))]);
    }

    public function getSemana(User $usuario, $semana)
    {
        $dias = collect();

        if ($usuario->inicio_reto == '') {//crear inicio del reto
            Storage::makeDirectory('public/reto/' . $usuario->id);
            $usuario->inicio_reto = Carbon::now();
            $usuario->save();
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

    public function pagar(Request $request)
    {
        \DB::beginTransaction();
        $user = User::find($request->id);
        if ($user != null && $user->saldo > 0) {
            $monto = 0 + $user->saldo;
            $user->cobrado = 1;
            $user->saldo = 0;
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
            $usuario->inicio_reto = Carbon::now()->startOfDay();
            $usuario->inicio_reto->subDays($request->dias_reto);
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

    public function verPagos(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $pagos = Pago::where('usuario_id', $usuario->id)->get();
            return $pagos;
        }
    }

    public function verCompras(Request $request)
    {
        $usuario = User::find($request->id);
        if ($usuario !== null) {
            $compras = Compra::where('usuario_id', $usuario->id)->get();
            return $compras;
        }
    }
}