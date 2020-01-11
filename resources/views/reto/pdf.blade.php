<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        h4, h5 {
            font-weight: bold;
        }

        .container{
            display: block;
            width: 80%;
            margin: auto;
        }

        .card-header{
            background-color: #1b4b72;
            color: #FFF;
            padding: 10px;
        }

        .comida{
            background-color: #2e6da4;
            color: #FFF;
            padding: 10px;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="container">
        <div class="card">
            <img src="{{asset('/img/acton.png')}}" width="100">
            <div class="card-header">Dia {{$dia->dia}}:{{$dia->nota==null?'':$dia->nota->descripcion}}</div>
            <div class="card-body">
                <div class="caja">
                    @foreach($dia->comidas as $iComida=>$comida)
                        <div>
                            <h5 class="comida">Comida {{ $iComida+1 }}</h5>
                            <div class="seccion">
                                @foreach($comida as $alimento)
                                    <div class="ejercicio">
                                        <span>{{ $alimento->alimento }}</span>
                                        <span>{{ $alimento->porcion }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <hr>
                        </div>
                    @endforeach
                </div>
                <div class="caja">
                    <h4 class="comida">Suplementos</h4>
                    <div class="seccion">
                        @foreach($dia->suplementos as $suplemento)
                            <div class="ejercicio">
                                <span>{{ $suplemento->suplemento }}</span>
                                <span>{{ $suplemento->porcion }}</span>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                </div>
                <div class="caja">
                    <h4 class="comida">Ejercicios</h4>
                    <div class="seccion">
                        @foreach($dia->ejercicios as $serie)
                            <div>
                                <span>{{$serie->nombre}}</span>
                            </div>
                            <table>
                                @foreach($serie->ejercicios as $ejercicio)
                                    <tr>
                                        <td style="width: 150px">{{ $ejercicio->ejercicio }}</td>
                                        @foreach($ejercicio->subseries as $subserie)
                                            <td style="width: 20px">{{$subserie->repeticiones}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        @endforeach
                    </div>
                    <hr>
                </div>
                <div class="caja">
                    <h4 class="comida">Cardio</h4>
                    <div class="seccion">
                        @foreach($dia->cardio as $ejercicio)
                            <div class="ejercicio">
                                {{ $ejercicio->ejercicio }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div align="center">
{{--                    <img src="{{asset('/img/suplementos.png')}}" width="180">--}}
                </div>
            </div>
        </div>
    </div>
</div>