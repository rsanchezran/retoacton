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
            <p>
                {{$datos->name}} recuerda subir diariamente tus fotos del Reto Acton
            </p>
        </div>
    </div>
@endsection