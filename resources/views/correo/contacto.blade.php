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

        .link{

        }
    </style>
@endsection
@section('content')

    <div style="display: inline;  "  >
        <div style="background-color: #edebec; padding: 60px 20px 20px 20px; font-size: 1.4em">
            <h4>Te invitamos a formar parte del <b>Reto Acton</b> donde muchas personas han transformado su cuerpo.</h4>
            <h4>¿Tienes alguna duda?</h4>
            <h4>En este link te explicamos más sobre el reto</h4>
            <div style="padding-top:10px; margin: auto;">
                <a style=" padding: 10px; background-color: #1b4b72; color:#FFF;" href="{{env("APP_URL")."/etapa$contacto->etapa/$contacto->id"}}">Ver informacion</a>
            </div>
        </div>
    </div>
@endsection