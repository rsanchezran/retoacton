<?php

namespace App\Http\Controllers;

use App\Code\Utils;
use App\Code\ValidarCorreo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CuentaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $user->tarjeta = $user->tarjeta ?? '';
        $user->pass = substr($user->password, 0, 6);
        return view('cuenta.index', ['user' => $user]);
    }

    public function save(Request $request)
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
}
