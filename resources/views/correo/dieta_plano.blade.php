Dia {{ $dia }}
@foreach($comidas as $iComida=>$comida)
    Comida {{ $iComida+1 }}
    @foreach($comida as $alimento)
        {{$alimento->alimento}}
    @endforeach
@endforeach
Suplementos
@foreach($suplementos as $suplemento)
    {{ $suplemento->porcion." ".$suplemento->suplemento }}
@endforeach
Ejercicios
@foreach($ejercicios as $serie)
    {{$serie->nombre}}
    @foreach($serie->ejercicios as $ejercicio)
        {{ $ejercicio->ejercicio }}
        @foreach($ejercicio->subseries as $subserie)
            {{$subserie->repeticiones}}
        @endforeach
    @endforeach
@endforeach
Cardio
@foreach($cardio as $ejercicio)
    {{ $ejercicio->ejercicio }}
@endforeach