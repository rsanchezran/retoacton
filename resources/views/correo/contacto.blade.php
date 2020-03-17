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
            @if($contacto->etapa==2)
                <h4>Hola {{$contacto->nombres}}.</h4>
                <h4>Vi que te intereso mi reto acton de 8 semanas y me tomé la libertad de mandarte este correo para platicarte un poco más.</h4>
                <h4>Probablemente ya has intentado algunas veces transformar tu cuerpo sin obtener el resultado que deseas y eso quizá te haya hecho creer que mejorar  tu físico es muy dificil pero dejame decirte no es así. Regalame 5 minutos de tu tiempo para poder explicarte porque es tan fácil cambiar nuestro cuerpo en este reto.</h4>
            @elseif($contacto->etapa==3)
                <h4>Hola {{$contacto->nombres}}. Que tal.</h4>
                <h4>Dando seguimiento al correo que te envie ayer.</h4>
                <h4>Te quiero platicar el lema que tenemos en ingeniería</h4>
                <h4> “Lo que no se puede medir no se puede mejorar” </h4>
                <h4>Y estoy totalmente de acuerdo. </h4>
                <h4>Por esa razón diseñe este simulador para que puedas ver el avance exacto que tendrias en el primer mes de llevar a cabo el reto acton, te gustaría ver tu resultado del primer mes?</h4>
            @else
                <h4>Hola {{$contacto->nombres}}.</h4>
                <h4>Estuve pensando las posibles razones de porque aun no has decidido unirte al reto Y se me vienen a la mente varias opciones, quiza te estes preguntando: </h4>
                <h4>Me dará buenos resultados?</h4>
                <h4>Será una buena opción invertir mi dinero en este programa?</h4>
                <h4>Serà fácil llevarlo ?</h4>
                <h4>Realmente puedo generar dinero aquí desde el primer día?</h4>
                <h4>Recibiré el seguimento adecuado?</h4>
                <h4>Bueno, te invito a ver el siguiente video para que veas que la respuesta a todas estas preguntas es SI!</h4>
                <h4>Decidimos mi equipo y yo que ya no habrá más descuentos por ahora pero me interesa tenerte en mi equipo, porque sé que realmente tienes interés y se que realmente te puede servir mucho este reto, si te quedas a ver el video hasta el final, podemos hacer solo por hoy el último descuento. Te parece?</h4>
            @endif
            <div style="padding-top:10px; margin: auto;">
                <a style=" padding: 10px; background-color: #1b4b72; color:#FFF;" href="{{env("APP_URL")."/etapa".($contacto->etapa-1)."/$contacto->id"}}">{{$boton}}</a>
            </div>
        </div>
    </div>
@endsection
