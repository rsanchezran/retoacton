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

        <div class="card">
            <div class="card-header">
                <span>Dia {{ $dia }} </span>
            </div>
            <div class="card-body">
                @foreach($comidas as $iComida=>$comida)
                <div>
                    <h5 class="comida">Comida {{ $iComida+1 }}</h5>
                    <div>
                        @foreach($comida as $alimento)
                        <div class="ejercicio">
                            {{ $alimento->alimento }}
                        </div>
                        @endforeach
                    </div>
                    <hr>
                </div>
                @endforeach
                <div>
                    <h4 class="comida">Suplementos</h4>
                    <div>
                        @foreach($suplementos as $suplemento)
                            <div class="ejercicio">
                                {{ $suplemento->porcion." ".$suplemento->suplemento }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div>
                    <h4 class="comida">Ejercicios</h4>
                    <div class="seccion">
                        @foreach($ejercicios as $serie)
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
                </div>
                <hr>
                <div>
                    <h4 class="comida">Cardio</h4>
                    @foreach($cardio as $ejercicio)
                        <div class="ejercicio">
                            <span>{{ $ejercicio->ejercicio }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection