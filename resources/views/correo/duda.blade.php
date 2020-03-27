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
            <label>{{$contacto->nombres." ".$contacto->apellidos}} tiene la siguiente duda</label>
            <p>{{$contacto->mensaje}}</p>
        </div>
    </div>
@endsection
