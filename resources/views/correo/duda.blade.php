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
            <label>{{$usuario->name." ".$usuario->last_name}} tiene la siguiente duda</label>
            <p>{{$mensaje}}</p>
        </div>
    </div>
@endsection
