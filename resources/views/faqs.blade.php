@extends('layouts.welcome')
@section('header')
    <style>
        .imagen {
            display: flex;
            flex-direction: column;
        }

        .tienda {
            padding: 10px;
            background-color: #7FA0C1;
            color: #FFF;
        }

        #video {
            width: 100%;
            background-color: #ebf4ff;
            padding: 40px;
            margin: 20px 0;
        }

        .info {
            display: flex;
            flex-wrap: wrap;
        }

        .info div {
            align-content: center;
            border: 1px solid grey;
            padding: 5px;
            margin: 5px;
        }

        .info h4 {
            color: #1b4b72;
            text-transform: uppercase;
            text-align: center;
        }

        .items{
            text-align: left;
            height: 300px;
            width: 22%;
            font-size: 1em;
            padding: 20px !important;
        }

        .items ul{
            padding: 5px;
        }

        p {
            font-size: 1.2em;
        }

        .subtitle{
            color: #1b4b72;
            text-align: justify;
            text-transform: uppercase;
        }

        .subinfo{
            text-align: justify;
            font-size: 1em;
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <inicio></inicio>
    </div>
    <template id="inicio-template">
        <div class="container">
            <div>
                <h3 class="subtitle">QUE ES EL RETO ACTON?</h3>
                <p class="subinfo">
                    Es un programa de entrenamiento y nutrición de 8 semanas en el cual se te dan las herramientas necesarias
                    para que obtengas el cambio mas drástico que puedas dar en 56 días de una manera saludable, notando
                    mejoría desde los primeros 15 días.
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">QUE HACE DIFERENTE AL RETO ACTON DE LOS DEMAS?</h3>
                <p class="subinfo">
                    Que RETO ACTON te da oportunidad de generar ingresos al invitar amigos, por lo que no tenemos solo
                    un ganador de premios en efectivo, sino miles.
                </p>
                <p class="subinfo">
                    A diferencia de la mayoría de los retos que existen donde se les da una misma dieta a todos los
                    participantes, en el RETO ACTON tus 2 dietas son específicamente para ti, es decir, nunca serán
                    igual a la de algún otro participante, ya que son 100% personalizadas.
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">CUANTO DINERO PUEDO GENERAR EN EL RETO ACTON?</h3>
                <p class="subinfo">
                    Lo que tu te propongas, recuerda que por cada persona que invites se te bonifican $300 MXN a tu sesión,
                    pero a partir de 10 o mas personas entonces serán $500 MXN por cada uno, es decir, si invitaste a 30
                    personas dentro de las 8 semanas ya ganaste $15,000 MXN los cuales se te transfieren al finalizar tu reto.
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">QUE PASA UNA VEZ QUE ME INSCRIBI EN EL RETO?</h3>
                <p class="subinfo">
                    Una vez que eres parte del reto se te asigna un USUARIO y una CONTRASEÑA, los cuales son enviados a
                    tu correo. Con este usuario y contraseña ingresaras a tu sesión, en la cual, estará todo tu programa
                    personalizado.
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">QUE ES LO QUE VEO DENTRO DE MI SESION?</h3>
                <p class="subinfo">
                    Dentro de tu sesión lo que verás es todo tu programa calendarizado, desde el día 1 al día 56.
                </p>
                <p class="subinfo">
                    En cada día veras tu dieta, rutina de ejercicios explicados con videos, los suplementos que tienes
                    que usar y cuánto cardio te toca hacer ese día.
                </p>
                <p class="subinfo">
                    Tambien tienes una sección donde iras subiendo fotos de pequeñas tareas diarias para que no pierdas
                    la motivación y tus avances semanales, ya que es muy importante para nosotros darte un seguimiento.
                </p>
                <p class="subinfo">
                    Abajo de tu foto de perfil, tienes una clave alfanumérica UNICA de 5 dígitos (nùmeros y letras) la
                    cual te servirá cada que quieres invitar a un amigo al reto, cuando tu amigo se esta inscribiendo,
                    solo escribe tu clave e inmediatamente te aparecerá el dinero en tu sesión.
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">HABRA QUIEN ME RESUELVA DUDAS?</h3>
                <p class="subinfo">
                    Si, contamos con soporte para las dudas que pudieran surgir del programa, el cual estará disponible
                    en horarios de lunes a viernes de 9:00 am a 6 pm y sábados de 10 am a 2 pm
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">PUEDO INSCRIBIRME DESDE CUALQUIER PARTE DEL MUNDO?</h3>
                <p class="subinfo">
                    Si, como el programa es 100% en linea puedes empezarlo desde cualquier parte
                </p>
            </div>
            <hr>
            <div>
                <h3 class="subtitle">QUE PASA AL FINALIZAR EL RETO ACTON?</h3>
                <p class="subinfo">
                    Otra de las ventajas que tienes al inscribirte al RETO ACTON es que, una vez que lo finalizas tienes
                    la oportunidad de obtener un seguimiento mensual (dieta, rutina y suplementaciòn) teniendo los mismos
                    beneficios que cuando estabas en el reto, es decir, sigues teniendo tu sesión y puedes seguir
                    generando ingresos al invitar amigos al RETO ACTON.
                </p>
            </div>
            <hr>
            <div class="flex-center">
                <br>
                <a class="bigbutton" href="{{url('register')}}">
                    <i class="fa fa-thumbs-up"></i> Inscribirme al reto acton
                </a>
            </div>
        </div>
    </template>

@endsection

@section('scripts')
    <script>
        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['urls', 'testimonios'],
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection