<?php

namespace App\Http\Controllers;

use App\Amistades;
use App\CompraRetos;
use App\Events\RetosEvent;
use App\Code\Utils;
use App\Code\ValidarCorreo;
use App\InteraccionAlbum;
use App\MiAlbum;
use App\Retos;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class CuentaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->tarjeta = $user->tarjeta ?? '';
        $user->pass = substr($user->password, 0, 6);
        $user->intereses = explode(',', $user->intereses);
        $user->idiomas = explode(',', $user->idiomas);
        return view('cuenta.index', ['user' => $user]);
    }

    public function perfil(Request $request)
    {
        $user = User::find($request->id);
        $user->tarjeta = $user->tarjeta ?? '';
        $user->intereses = str_replace(',', ', ', $user->intereses);
        $user->idiomas = str_replace(',', ', ', $user->idiomas);
        $amistades = Amistades::where('usuario_amigo_id', $request->id)->get()->count();
        $all_fotos = MiAlbum::where('usuario_id', $request->id)->take(9)->get();
        $fotos = $all_fotos;
        return view('cuenta.perfil', ['usuario' => $user, 'amistades' => $amistades, 'fotos' => $fotos]);
    }

    public function saveuno(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'pass' => 'confirmed|max:20|min:4',
                'pass_confirmation' => 'required|max:20|min:4',
                'tarjeta' => 'nullable|min:0|max:16'
            ],
            [
                'pass.required' => 'Este campo es obligatorio',
                'pass.max' => 'Debe tener máximo 20 caracteres',
                'pass.min' => 'Debe tener mínimo 4 caracteres',
                'pass.confirmed' => 'Los datos deben ser iguales al campo Contraseña',
                'pass_confirmation.required' => 'Este campo es obligatorio',
                'pass_confirmation.max' => 'Debe tener máximo 20 caracteres',
                'pass_confirmation.min' => 'Debe tener mínimo 4 caracteres',
                'tarjeta.max' => 'Debe tener exactamente 16 numeros',
                'tarjeta.min' => 'No debe ser negativo'
            ]
        );
        $validator->validate();

        \DB::beginTransaction();
        $user = User::find($request->id);
        if ($user !== null) {
            if ($request->pass != substr($user->password, 0, 6)) {
                $user->password = bcrypt($request->pass);
            }
            $user->tarjeta = $request->tarjeta;
            $user->save();
        }
        \DB::commit();

        return response()->json(['status' => 'ok', 'redirect' => url('home')]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'codigo_nuevo' => 'max:7|min:7|required',
            ],
            [
                'codigo_nuevo.required' => 'Este campo es obligatorio',
                'codigo_nuevo.max' => 'Debe tener máximo 8 caracteres',
                'codigo_nuevo.min' => 'Debe tener mínimo 7 caracteres',

            ]
        );
        $validator->validate();

        \DB::beginTransaction();
        $user = User::find($request->id);
        if ($user !== null) {
            $user->referencia = $request->codigo_nuevo;
            $user->save();
        }
        \DB::commit();

        return response()->json(['status' => 'ok', 'redirect' => url('home')]);
    }

    public function subirFoto(Request $request)
    {
        ini_set('memory_limit', '-1');
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [], []);
        $validator->after(function ($validator) use ($request) {
            $extension = strtolower($request->file('imagen')->getClientOriginalExtension());
            if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
                $size = ((($request->file('imagen')->getSize() / 1024) / 1024) * 100) / 100;
                if ($size > 20) {
                    $validator->errors()->add("imagen", "El tamaño de la imagen debe ser menor a 20MB");
                }
            } else {
                $validator->errors()->add("imagen", "El formato de la imagen no está permitido");
            }
        });
        $validator->validate();
        $user = User::find($request->id);
        if ($request->file('imagen') != null) {

            $image = \Intervention\Image\Facades\Image::make($request->file('imagen'))->orientate();
            if ($image->width() < $image->height()) {
                $image->resize(null, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $image->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            Storage::disk('local')->makeDirectory('public/users');
            $image->save(storage_path("app/public/users/$user->id.png"));
        }
        return response()->json(['status' => 'ok', 'imagen' => url("/cuenta/getFotografia/$user->id/" . rand(0, 100))]);
    }

    public function getFotografia($id, $random)
    {
        if (Storage::disk('local')->exists("public/users/$id.png")) {
            return response()->download(
                storage_path("app/public/users/$id.png"),
                'filename.png',
                ['Content-Type' => 'image/png']
            );
        } else {
            return response()->file(public_path('images/2021/sin_foto.png'));
            return response()->file(public_path('img/user.png'));
        }
    }

    public function cambiarModo(Request $request)
    {
        $user = $request->user();
        $user->modo = $request->lugar;
        $user->save();
        return response()->json(['status' => 'ok']);
    }

    public function nuevaFoto(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        $png_url = $user->id."-".time().".png";
        $path = 'images/2021/' . $png_url;
        $path = 'storage/app/public/mialbum/'.$user->id.'/' . $png_url;
        if(!File::isDirectory('storage/app/public/mialbum/'.$user->id)){

            File::makeDirectory('storage/app/public/mialbum/'.$user->id, 0777, true, true);

        }

        Image::make(file_get_contents($request->imagen))->save(public_path($path));
        MiAlbum::create([
            'archivo' => $path,
            'usuario_id' => $user->id,
            'descripcion' => $request->descripcion,
        ]);

        $response = array(
            'status' => 'success',
        );
        return response()->json(['status' => 'ok']);
    }

    public function darLike(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        $interaccion = InteraccionAlbum::where('usuario_like_id', $user->id)->where('tipo_like', $request->tipo)->where('album_id', $request->id)->first();
        if($interaccion == null) {
            InteraccionAlbum::create([
                'usuario_like_id' => $user->id,
                'tipo_like' => $request->tipo,
                'album_id' => $request->id,
            ]);
            return response()->json(['status' => 'agregado']);
        }else{
            $interaccion->delete();
            return response()->json(['status' => 'borrado']);
        }
    }

    public function reacciones(Request $request)
    {
        $user = $request->user();
        $interaccion = InteraccionAlbum::where('album_id', $request->id)->get();

        return response()->json($interaccion);
    }

    public function darCoins(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        if((int)$user->saldo >= (int)$request->coins){
            $user->saldo = $user->saldo-(int)$request->coins;
            $user->save();
            InteraccionAlbum::create([
                'usuario_like_id' => $user->id,
                'tipo_like' => 'coins',
                'album_id' => $request->id,
                'dinero_acton' => $request->coins
            ]);
            return response()->json(['status' => 'agregado']);
        }else{
            return response()->json(['status' => 'sin dinero']);
        }
    }

    public function guardaPublico(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        $album = MiAlbum::where('id', (int)$request->id)->first();
        print_r($album);
        if(isset($album)){
            if($request->tipo == 'privacidad'){
                $album->publica = $request->publico;
            }
            if($request->tipo == 'comentarios'){
                $album->comentarios_publico = $request->publico;
            }
            if($request->tipo == 'conteo'){
                $album->conteo_publico = $request->publico;
            }
            if($request->tipo == 'descripcion'){
                $album->descripcion = $request->publico;
            }
            $album->save();
            return response()->json(['status' => 'agregado']);
        }else{
            return response()->json(['status' => 'no generado']);
        }
    }

    public function eliminarElemento(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        $album = MiAlbum::find((int)$request->id);
        if(isset($album)){
            $interacciones = InteraccionAlbum::where('album_id', (int)$request->id)->get();
            foreach($interacciones as $i){
                $i->delete();
            }
            $album->delete();
            return response()->json(['status' => 'agregado']);
        }else{
            return response()->json(['status' => 'no generado']);
        }
    }

    public function enviarreto(Request $request)
    {
        $user = $request->user();
        $data = Input::all();
        if((int)$user->saldo > (int)$request->pago) {
            $reto = Retos::create([
                'usuario_retado_id' => $request->id,
                'usuario_reta_id' => $user->id,
                'descripcion' => $request->descripcion,
                'coins' => $request->pago,
            ]);
            $user->saldo = $user->saldo - (int)$request->pago;
            $user->save();
            event(new RetosEvent($reto));
            return response()->json(['status' => 'Reto enviado']);
        }else{
            return response()->json(['status' => 'No cuenta con saldo suficiente']);
        }
    }

    public function aceptarreto(Request $request)
    {
        $user = $request->user();
        $reto = Retos::where('id', $request->id)->first();
        if($request->tipo == 'aceptar'){
            $reto->aceptado = true;
        }else{
            $reto->aceptado = false;
            $usuario_retador = User::where('id', $reto->usuario_reta_id)->first();
            $usuario_retador->saldo = $usuario_retador->saldo+$reto->coins;
            $usuario_retador->save();
            $notification = auth()->user()->notifications()->find($request->id_notificacion);
            if($notification) {
                $notification->markAsRead();
            }
        }
        $reto->save();
        $reto->fecha_aceptado = $reto->updated_at;
        $reto->save();
        return response()->json(['status' => 'Reto']);
    }

    public function retoRespuesta(Request $request)
    {
        ini_set('memory_limit', '-1');
        Storage::disk('local')->makeDirectory('storage/app/public/retos/'.$request->id);

        $image = $request->file('video');
        $destinationPath = public_path("storage/app/public/retos/$request->id");
        $image->move($destinationPath, "$request->id.mp4");
        $reto = Retos::where('id', $request->id)->first();
        $reto->video = "storage/app/public/retos/$request->id/$request->id.mp4";
        $reto->save();
        $user = $request->user();
        $user->saldo = $user->saldo+$reto->coins;
        $user->save();
        return response()->json(['status' => 'ok', 'video' => $reto->video]);
    }

    public function getVideo(Request $request, $video)
    {
        $valido = false;
        $usuario = $request->user()->id;
        $usuario_valido = Retos::where(function ($query) use ($video, $usuario) {
            $query->where('id', '=', $video);
        })->where(function ($query) use ($video, $usuario) {
            $query->where('usuario_retado_id', '=', $usuario)
                ->orWhere('usuario_reta_id', '=', $usuario);
        })->count();
        if($usuario_valido>0){
            $valido = true;
        }else{
            $usuario_compra = CompraRetos::where(function ($query) use ($video, $usuario) {
                $query->where('reto_id', '=', $video);
            })->where(function ($query) use ($video, $usuario) {
                $query->where('usuario_id', '=', $usuario);
            })->count();
            if($usuario_compra > 0){
                $valido = true;
            }
        }
        if($valido) {
            $retos = Retos::where('id', $video)->first();
            return response()->file($retos->video);
        }else{
            return response()->json(['status' => 'no']);
        }
    }
}
