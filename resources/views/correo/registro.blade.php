@extends('layouts.mail')
@section('styles')
    <style>
        .imagen{
            position: absolute;
        }
        p{
            position: absolute;
            z-index: 2;
        }
    </style>
@endsection
@section('content')
    <div style="display: inline;">
        <div style="background-color: #edebec; padding: 60px 20px 20px 20px; font-size: 1.4em">
            Bienvenido al Reto ACTON <br>{{$usuario->name}} {{$usuario->last_name}} <br>
            Ya puedes iniciar sesión en Acton:<br>
            correo: {{$usuario->email}} <br> <br>
            @if($usuario->pass!='')
                contraseña: {{$usuario->pass}} <br>
                <br>
                <span style="color: red;" >NOTA:</span>Recuerda que tu contraseña se escribe con mayusculas, si deseas asignar una nueva contraseña, lo podrás hacer en la seccion "Mi cuenta"
            @endif
            <br>
            <div style="padding-top:10px; margin: auto;">
                <a style=" padding: 10px; background-color: #007fdc; color:#FFF;" href="{{env("APP_URL")."/login"}}">Ingresa aquí</a>
            </div>
        </div>
    </div>
@endsection
