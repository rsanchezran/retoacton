<?php $__env->startSection('header'); ?>
    <style>
        .fade-enter-active, .fade-leave-active {
            transition: opacity .1s;
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
            opacity: 0;
        }

        video {
            height: 200px;
            width: 100%;
        }

        .info {
            padding: 20px 10px;
            background-color: #f6f6f6;
        }

        .section {
            margin-top: 60px;
        }

        .big {
            font-size: 1.4em;
        }

        .bigger {
            font-size: 2em;
            line-height: 1 !important;
        }

        .biggest {
            font-size: 3em;
            line-height: 1 !important;
        }

        .subtitle {
            color: #8F9191;
            display: inline;
            text-align: justify;
            text-transform: uppercase;
            font-size: 1.2em;
            font-family: unitext_light;
            font-weight: bold;
        }

        .subinfo {
            text-align: justify;
            font-size: 1em;
        }

        .btn-primary {
            font-size: 1em;
            background-color: #ff9900;
            border: 1px solid #ff9900;
            padding: 0.5em 2em;
            text-transform: uppercase;
            font-weight: bold;
        }

        btn-primary:hover {
            background-color: #2c628c;
        }

        .feature {
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .feature .img {
            height: 100%;
        }

        .subinfo img {
            width: 100%;
            height: 50%;
        }

        .subinfo div{
            padding: 20px;
        }

        #features {
            background-color: #005D9C;
            padding: 20px;
        }

        #features .subinfo {
            background-color: #F0F0F0;
            font-size: .7rem;
            font-family: unitext_light;
            flex-grow: 1;
            height: 100%;
        }

        #features .subtitle {
            font-family: unitext;
            position: absolute;
            width: 70%;
            top: 40%;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            z-index: 2;
            color: #454545;
            text-justify: distribute;
            text-align: center;
            font-weight: bold;
            font-size: 1.5vw;
        }

        #features .subinfo h6 {
            font-size: 0.7rem;
            color: #1b1e21;
            font-weight: bold !important;
            line-height: 1.5;
        }

        .rey {
            background-color: #015289;
            padding: 20px;
            color: #fff;
        }

        .turquesa {
            color: #01ffff
        }

        .gris {
            color: #9c9c9c;
        }

        #verde {
            background-color: #93c468;
            color: #fff;
        }

        .marino {
            background-color: #003450;
        }

        .azul {
            color: #4BA9E6 !important;
        }

        .testimonio {
            text-align: center;
            color: #fff;
            padding: 10px;
        }

        #test {
            background-image: url('<?php echo e(asset('img/backrayo.png')); ?>');
            background-repeat: no-repeat;
            background-position: top left;
            background-size: auto;
            /*padding-bottom: 20px;*/
        }

        #testtitulo {
            background-image: url('<?php echo e(asset('img/logo reto.png')); ?>');
            background-repeat: no-repeat;
            background-size: 150px;
            background-position: top right
        }

        .modo{
            height: 2rem !important;
            width: auto !important;
        }

        #pipo {
            background-color: #013451;
            color: #FFF;
            background-image: url("<?php echo e(asset('img/lineas.png')); ?>");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 100%;
            min-height: 400px;
        }

        #curva {
            background-image: url("<?php echo e(asset('img/radius.png')); ?>");
            background-size: 100% 100%;
            height: 60px;
            width: 100%;
        }

        #quote {
            background-image: url('<?php echo e(asset('img/comillas.png')); ?>');
            background-repeat: no-repeat;
            background-size: 200px;
            background-position: 200px 120px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        #cree {
            width: 320px;
            text-align: center;
            margin: 1px auto;
            line-height: 1.2;
        }

        #cree p {
            padding: 1px;
            margin: 1px;
        }

        #desicion {
            margin-top: 80px;
            margin-bottom: 100px;
        }

        #ranking{
            margin-top: 80px;
        }

        #finanzas {
            padding: 20px 0px 80px 40px;
            background-image: url("<?php echo e(asset('img/rayoback.png')); ?>");
            background-size: 100% 100%;
            background-repeat: no-repeat;
            color: #fff;
        }

        #bonus {
            margin-left: 140px;
            margin-top: 120px;
            z-index: 10;
        }

        #bonus p {
            margin: 1px;
            text-align: justify;
        }

        #chica {
            margin-top: -190px;
            margin-left: -213px;
            height: 1000px;
        }

        #pipoImg {
            width: 430px;
            margin-top: -120px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        #monitores h6 {
            margin: auto;
            width: 70%;
            text-align: center;
        }

        #monitores img {
            display: block;
            margin: auto;
        }

        .monitor {
            margin: 10px 0px;
        }

        #garantia {
            text-align: left;
            font-size: 2.8rem;
            color: #929494;
        }

        #garantiaDiv {
            margin-left: -50px
        }

        #garantiaDes {
            font-family: unitext_light;
        }

        #soñarlo {
            font-size: 3.0em;
            font-weight: bold;
        }

        #hacerlo {
            font-size: 3.8em;
        }

        #meta {
            margin-top: 40px;
            font-size: 1.2rem;
        }

        #inscripcion {
            margin-left: 0px
        }

        #ganar {
            font-size: 2.8rem
        }

        .semana p {
            margin: 1px 8px;
            padding: 1px 12px;
            line-height: 1;
        }

        .semana h6 {
            margin-bottom: 1px;
            color: #000;
        }

        #mejora {
            margin-left: -40px
        }

        #ganadores {
            font-size: 1.8em;
            margin-bottom: 1px
        }

        #todospueden {
            font-size: 1.7rem
        }

        #compensacion {
            width: 80%;
            font-size: 1rem;
        }

        #otrobonus {
            font-size: 1.4rem;
        }

        #trofeobonus {
            margin-right: 10px;
        }

        #frase {
            margin: 40px auto;
        }

        .comienza {
            font-size: .75rem;
        }

        @media  only screen and (max-width: 990px) {
            #features .subtitle {
                font-size: 2.5vw;
            }

            #features .subinfo h6 {
                font-size: .7rem !important;
                line-height: 1.2 !important;
            }

            .comienza {
                font-size: .62rem;
            }

            @media  only screen and (max-width: 800px) {
                .feature .subinfo {
                    height: 450px !important;
                }

                #testtitulo {
                    background: none;
                }

                #pipo {
                    min-height: auto;
                }

                #frase {
                    margin: 20px auto;
                }

                #pipoImg {
                    width: 210px !important;
                    margin-top: -70px;
                }

                #pipoDiv {
                    padding-left: 0;
                }

                #quote {
                    background-size: 100px !important;
                    align-items: flex-start;
                    background-position: top;
                }

                #finanzas {
                    background-image: url("<?php echo e(asset('img/rayobackmovil.png')); ?>");
                }

                #cree {
                    width: 100%;
                    text-align: right;
                    font-size: 0.65rem;
                }

                .momento {
                    font-size: 1.2rem !important;
                    text-align: center;
                }

                #cree h6 {
                    font-size: 0.8rem;
                }

                #chica {
                    margin-top: -80px;
                    margin-left: -400px;
                    height: 700px;
                }

                #garantiaDiv {
                    display: block;
                    margin-top: 20px;
                    margin-left: auto;
                    margin-right: auto;
                }

                #garantiaDes {
                    padding-top: 10px;
                    text-align: center;
                }

                #soñarlo {
                    font-size: 1.5rem;
                }

                #hacerlo {
                    width: 50%;
                    font-size: 2.2em;
                }

                #transformar h6 {
                    font-size: .75rem;
                    margin-bottom: 1px;
                }

                #transformar p {
                    font-size: 0.65rem;
                    line-height: 1.4;
                }

                #meta {
                    width: 62%;
                    margin-top: 40px;
                    font-size: .9rem;
                }

                #inscripcion {
                    margin-left: 0px
                }

                #garantia {
                    text-align: center;
                    font-size: 3rem;
                }

                #ganar {
                    font-size: 3.2rem;
                    text-align: center;
                }

                .semana {
                    box-shadow: none;
                    border-bottom: 2px solid #AAA9A9;
                    padding: 5px;
                    height: 45px;
                    margin-top: 10px;
                }

                .semana h6 {
                    font-size: .7rem;
                }

                .semana p {
                    font-size: .65rem;
                }

                #mejora {
                    margin-top: 40px;
                    margin-left: -20px
                }

                #mejora h3 {
                    font-size: 1.5rem !important;
                    margin-bottom: 1px;
                }

                #ganadores {
                    font-size: 1.2em;
                }

                #todospueden {
                    font-size: 1rem;
                }

                #compensacion {
                    width: 80%;
                    font-size: 0.65rem;
                }

                #otrobonus {
                    font-size: 1.2rem;
                }

                #trofeobonus {
                    margin-right: 10px;
                }

                #features .subtitle {
                    font-size: 3.5vw;
                }

                #bonus {
                    margin-left: 40px;
                    margin-top: 100px;
                }
            }

            @media  only screen and (max-width: 420px) {
                #bonus {
                    margin-top: 140px;
                    margin-left: 10px;
                    margin-right: -300px;
                }

                #verdadero {
                    padding: 0;
                }

                #tituloFeature {
                    padding-left: 5px;
                    padding-right: 5px;
                }

                #features .subtitle {
                    font-size: 4.5vw !important;
                }

                #features .subinfo h6 {
                    font-size: .7rem !important;
                    line-height: 1.2 !important;
                }

                #cree {
                    font-size: .55rem !important;
                }

                #pipoImg {
                    width: 160px !important;
                    margin-top: -60px;
                }

                #frase {
                    margin: 0px auto;
                }

                #garantia {
                    font-size: 2.5rem;
                }

                #ganar {
                    font-size: 2.8rem;
                }
            }
        }

        @media  only screen and (min-width: 1920px) {

            .subinfo div {
                padding: 20px !important;
            }
        }

        @media  only screen and (max-width: 1280px) {
            #features .subinfo h6 {
                font-size: .62rem;
                line-height: 1;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div id="vue">
        <inicio></inicio>
    </div>
    <template id="inicio-template">
        <div>
            <div v-animate="'slide-up'" align="center">
                <div id="inicioFeature">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-pause="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="<?php echo e(asset('img/landing3.jpg')); ?>" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="<?php echo e(asset('img/landing2.jpg')); ?>" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="<?php echo e(asset('img/landing1.jpg')); ?>" alt="First slide">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="<?php echo e(asset('img/landing4.jpg')); ?>" alt="Forth slide">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                           data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                           data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="info text-center">
                <div class="col-12 col-sm-8 mr-auto ml-auto" style="padding: 40px 10px">
                    <h6 class="text-uppercase bigger thin" style="color:#929494; font-size: 2.4rem">El mejor
                        momento</h6>
                    <h6 class="text-uppercase font-weight-bold biggest">para empezar</h6>
                    <h6 class="text-uppercase font-weight-bold biggest"> es hoy</h6>
                    <br>
                    <div>
                        <h6 style="font-size: 1.3rem; margin-bottom: 2px;" class="text-uppercase">+ 33,000 personas</h6>
                        <h6 style="font-size: 1.3rem" class="text-uppercase">mejoraron su vida</h6>
                    </div>
                </div>
            </div>
            <div class="">
                <div id="features" class="d-flex flex-wrap mr-auto ml-auto">
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="comidasFeature" class="feature" @click="features.comidas=false" @mouseover="features.comidas=false"
                             @mouseleave="features.comidas=true">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.comidas" key="primero">
                                    <img id="comidasImg" class="img" src="<?php echo e(asset('/img/comidasblanco.jpg')); ?>" width="100%">
                                    <h3 id="comidasSub" class="subtitle">
                                        <span>Plan de alimentación </span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="segundo">
                                    <img src="<?php echo e(asset('img/comidas.jpg')); ?>">
                                    <div>
                                        <h6 style="font-family: unitext_bold_cursive;">Los planes de alimentación que
                                            recibes en este programa de 8 semanas son totalmente personalizados.</h6>
                                        <h6>En <b style="font-family: unitext_bold_cursive">ACTON</b> estamos seguros de que,
                                            para tener más posibilidades de cambio, tu plan de alimentación te debe agradar, por
                                            lo que tú puedes elegir cuáles alimentos NO quieres que aparezcan en tu programa
                                            para que sea más fácil llevarlo</h6>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="entrenamientoFeature" class="feature" @click="features.entrenamiento=false" @mouseover="features.entrenamiento=false"
                            @mouseleave="features.entrenamiento=true">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.entrenamiento" key="first">
                                    <img id="entrenamientoImg" class="img" src="<?php echo e(asset('/img/entrenamientoblanco.jpg')); ?>"
                                         width="100%">
                                    <h3 id="entrenamientoSub" class="subtitle">
                                        <span>Plan flexible de entrenamiento</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="<?php echo e(asset('img/entrenamiento.jpg')); ?>">
                                    <div>
                                        <h6 class="text-justify">Puedes elegir si deseas entrenar en el gym, desde la comodidad
                                            de tu hogar o en el lugar donde te encuentres, ya que dentro de tu sesión tienes un
                                            botón en el cual puedes cambiar tu rutina a </h6>
                                        <h6 class="font-weight-bold text-center" style="font-family: unitext;">Modo: GYM o CASA</h6>
                                        <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 d-flex justify-content-between p-0 ml-auto mr-auto">
                                            <img class="modo" src="<?php echo e(asset('img/Boton01.png')); ?>"/>
                                            <img class="modo" src="<?php echo e(asset('img/Boton02.png')); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="suplementosFeature" class="feature" @click="features.suplementos=false" @mouseover="features.suplementos=false"
                             @mouseleave="features.suplementos=true">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.suplementos" key="first">
                                    <img id="suplementosImg" class="img" src="<?php echo e(asset('/img/suplementosblanco.jpg')); ?>"
                                     width="100%">
                                    <h3 id="suplementosSub" class="subtitle">
                                        <span>Guía de suplementación</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="<?php echo e(asset('img/suplementos.jpg')); ?>">
                                    <div>
                                        <h6>Te diremos cuáles son los suplementos adecuados con las dosis óptimas para alcanzar
                                            de una manera más efectiva y rápida el objetivo de que deseas <span
                                                class="font-weight-bold"
                                                style="font-family:unitext;">siempre cuidando tu salud.</span></h6>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="videosFeature" class="feature" @click="features.videos=false" @mouseover="features.videos=false"
                             @mouseleave="features.videos=true">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.videos" key="first">
                                    <img id="videosImg" class="img" src="<?php echo e(asset('/img/videosblanco.jpg')); ?>" width="100%">
                                    <h3 id="videosSub" class="subtitle">
                                        <span>Videos personalizados</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="<?php echo e(asset('img/videos.jpg')); ?>">
                                    <div>
                                        <h6><span class="font-weight-bold text-center" style="font-family: unitext">Más de 500 videos de alta resolución </span>
                                            en tu sesión con explicación de cada ejercicio para que puedas ver la técnica
                                            correcta de cada movimiento. Cada uno de los ejercicios trae video, ya sea de casa o
                                            gym.</h6>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tituloFeature" class="section container text-center">
                <div class="col-12 col-sm-9 mr-auto ml-auto" id="verdadero">
                    <h6 class="text-uppercase bigger thin" style="color:#929494">El verdadero cambio </h6>
                    <h6 class="text-uppercase font-weight-bold biggest">Comienza</h6>
                    <h6 class="text-uppercase font-weight-bold biggest">en tu mente</h6>
                    <br>
                    <h6 class="comienza gris" style="font-family: unitext_light; font-weight: bold;">Nuestro reto
                        contigo durante estas 8 semanas es demostrarte que</h6>
                    <h6 class="comienza gris" style="font-family: unitext_light; font-weight: bold;">puedes alcanzar el
                        cuerpo que deseas cuando tu mente así lo decide.</h6>
                    <h6 class="comienza text-uppercase font-weight-bold">Buscamos ser inspiración y motivación para</h6>
                    <h6 class="comienza text-uppercase font-weight-bold">alcanzar cualquier meta que te propongas.</h6>
                </div>
            </div>
            <div class="section">
                <div id="curva"></div>
                <div id="pipo" class="d-flex flex-wrap">
                    <div class="col-sm-5 col-5" id="pipoDiv">
                        <img id="pipoImg" src="<?php echo e(asset('img/pipo.png')); ?>">
                    </div>
                    <div class="col-sm-7 col-7" id="quote">
                        <div id="frase">
                            <div id="cree">
                                <p class="text-uppercase">Tus más grandes deseos llegarán a ti tarde o temprano</p>
                                <br>
                                <p class="text-uppercase">La rapidez con la que aparecerán en</p>
                                <p class="text-uppercase">tu vida depende de tu nivel de fe</p>
                                <p class="text-uppercase">en que lo conseguirás</p>
                                <br>
                                <p class="text-uppercase">¡Cree en ti y todo será posible! </p>
                            </div>
                            <br>
                            <span class="turquesa text-uppercase float-right"
                                  style="font-family: unitext_cursive; font-weight:bold;">- Reto Acton</span>
                            <br>
                            <br>
                            <h6 class="momento biggest text-uppercase font-weight-bold"
                                style="font-style: oblique"><span
                                    class="turquesa">Tu momento </span> es hoy</h6>
                        </div>
                    </div>
                </div>
                <div id="desicion" class="section container text-center">
                    <h6 class="text-uppercase bigger thin" style="color:#929494">Ranking de participantes actualizado</h6>
                    <table id="ranking" class="table text-left">
                        <tr>
                            <td style="border:0;">Top 5</td>
                            <td style="border:0;" class="text-right">Puntos</td>
                        </tr>
                        <tr>
                            <td>1. Alejandro Castellanos</td>
                            <td class="text-right">2400</td>
                        </tr>
                        <tr>
                            <td>2. Carolina Torres</td>
                            <td class="text-right">2100</td>
                        </tr>
                        <tr>
                            <td>3. Arturo Cortez</td>
                            <td class="text-right">1850</td>
                        </tr>
                        <tr>
                            <td>4. Diana Alvarado</td>
                            <td class="text-right">1275</td>
                        </tr>
                        <tr>
                            <td>5. Daniel Rojas</td>
                            <td class="text-right">1050</td>
                        </tr>
                    </table>
                    <div class="mt-4 mb-4">
                        <p style="font-family: unitext_light">Tienes hasta el 31 de diciembre del presente año para meterte en este top 5</p>
                    </div>
                    <div class="col-12 col-sm-9 mr-auto ml-auto" style="margin-top: 60px;">
                        <h6 class="text-uppercase bigger thin" style="color:#929494">Estás a una decisión</h6>
                        <h6 class="text-uppercase font-weight-bold biggest">De cambiarlo todo</h6>
                        <a class="btn btn-primary text-uppercase mt-4" href="<?php echo e(url('register')); ?>"
                           style="width: 80%; font-family: unitext_bold_cursive; padding: 10px">Quiero entrar al reto</a>
                    </div>
                </div>
                <div id="finanzas" class="d-flex flex-wrap">
                    <div class="col-0 col-sm-1"></div>
                    <div class="col-sm-5 col-12"
                         style="display: flex; flex-direction: column; justify-content: space-between">
                        <div id="mejora">
                            <h3 class="turquesa text-uppercase bigger font-weight-bold" style="font-size: 3em">Mejora
                                tus</h3>
                            <h3 class="turquesa text-uppercase bigger font-weight-bold" style="font-size: 3em">
                                Finanzas</h3>
                            <h6 id="ganadores" class="thin">Aquí no hay sólo 1, 2 o 3 ganadores</h6>
                            <h6 id="todospueden" class="big">¡Todos pueden ganar dinero!</h6>
                            <div id="compensacion">
                                <p>
                                    <span style="font-family: unitext_bold_cursive">EL RETO ACTON</span> tiene un PLAN
                                    DE
                                    COMPENSACIÓN muy atractivo para todos los que se inscriben.
                                </p>
                                <p style="margin-bottom: 1px">
                                    <span>¡Gana dinero invitando a tus amigos!</span>
                                </p>
                                <p>
                                    <span>Se te bonificará $500 MXN por cada uno de ellos que acepte el reto.</span>
                                </p>
                                <p style="margin-bottom: 1px"><span>Un ejemplo:</span></p>
                                <p>
                                <span>Con solo 20 personas <b class="turquesa"
                                                              style="font-family: unitext_bold_cursive">ya ganaste $10,000 <span
                                            class="small">MXN.</span></b></span>
                                </p>
                            </div>
                        </div>
                        <a v-if="screen>801" class="btn btn-primary text-uppercase" href="<?php echo e(url('register')); ?>"
                           style="width: 60%; font-family: unitext_bold_cursive; padding: 10px">Quiero ganar</a>
                    </div>
                    <div class="col-sm-6 col-12">
                        <img class="mr-auto ml-auto d-block" src="<?php echo e(asset('img/celular.png')); ?>" width="250">
                    </div>
                    <div v-if="screen<801" class="col-10 d-block mr-auto ml-auto text-center">
                        <br>
                        <a class="btn btn-primary text-uppercase" href="<?php echo e(url('register')); ?>"
                           style="width: 80%; font-family: unitext_bold_cursive; padding: 10px">Quiero ganar</a>
                    </div>
                </div>
            </div>
            <div style="margin-top: 40px;">
                <div style="margin-top:60px; margin-bottom: 70px">
                    <div class="d-flex flex-wrap">
                        <div class="col-sm-12 col-md-12 col-lg-5 col-12 text-center">
                            <img src="<?php echo e(asset("img/garantia.png")); ?>" width="300">
                        </div>
                        <div id="garantiaDiv" class="col-sm-10 col-md-10 col-lg-6 col-10">
                            <h4 id="garantia" class="text-uppercase bigger thin">Garantía de reembolso</h4>
                            <h4 id="ganar" class="text-uppercase bigger font-weight-bold">¡Aquí todo es ganar!</h4>
                            <div id="garantiaDes" class="col-sm-12 col-md-12 col-lg-9">
                                <h6 class="font-weight-bold">Estamos seguros que al finalizar el reto serás una persona
                                    totalmente diferente, también sabemos que estas 8 semanas serán una experiencia que
                                    disfrutarás al máximo.</h6>
                                <h6 style="margin: 10px 0;" class="font-weight-bold">Sin embargo, para hacerte saber que
                                    no
                                    tienes nada que perder, decidimos ofrecerte las primeras 24 horas para poder pedir
                                    un <b style="font-family: unitext_bold_cursive">REEMBOLSO TOTAL</b> si el reto no
                                    superó
                                    tus expectativas.</h6>
                                <h6 class="font-weight-bold">Solo, nos enviarías un correo a
                                    <b style="font-family: unitext_bold_cursive">reembolsos@retoacton.com</b>
                                    y sin hacer preguntas se te devuelve <span id="inscripcion">el total de tu inscripción.</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="verde" style="margin-top: 40px; display: flex; justify-content: space-between">
                    <div class="d-flex">
                        <div id="bonus">
                            <h6 id="meta" class="text-uppercase acton">La meta esta más cerca de lo que crees</h6>
                            <h6 id="soñarlo" class="text-uppercase bigger thin">Si puedes soñarlo,</h6>
                            <h6 id="hacerlo" class="text-uppercase bigger font-weight-bold">Puedes hacerlo</h6>
                            <div id="transformar">
                                <h6>Este reto está diseñado para transformar tu físico</h6>
                                <h6>rápidamente, aumentar tu motivación y al mismo </h6>
                                <h6>tiempo ganar dinero.</h6>
                                <br>
                                <div class="d-flex" style="align-items: center">
                                    <div id="trofeobonus">
                                        <span id="otrobonus" class="text-uppercase">BONUS</span>
                                        <br>
                                    </div>
                                    <div>
                                        <img v-if="screen<801" style="margin-top:10px;"
                                             src="<?php echo e(asset('img/trofeo.png')); ?>"
                                             alt="trofeo" height="20">
                                        <img v-else style="margin-top:10px;" src="<?php echo e(asset('img/trofeo.png')); ?>"
                                             alt="trofeo"
                                             height="40">
                                    </div>
                                </div>
                                <h6>Cada día se te presentará una actividad a </h6>
                                <h6>realizar, tú decides si quieres hacerla, nuestra </h6>
                                <h6>recomendación es realizar todas para obtener una</h6>
                                <h6>recompensa sorpresa al finalizar el reto.</h6>
                                <br>
                                <br>
                                <a class="btn btn-primary" href="<?php echo e(url('register')); ?>"
                                   style="width: 90%; font-family: unitext_bold_cursive; padding: 10px">Unirme al reto acton</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img v-if="screen>750" id="chica" src="<?php echo e(asset('img/chica.png')); ?>">
                        <img v-else id="chica" src="<?php echo e(asset('img/chicamovil.png')); ?>">
                    </div>
                </div>
            </div>
            <div>
                <div id="test" class="marino" style="padding-top:100px; padding-bottom:10px;">
                    <div id="testtitulo" class="text-center col-12 col-sm-8 col-8 d-block mr-auto ml-auto">
                        <h6 class="text-uppercase bigger thin font-weight-bold" style="color: #00abe5">Gente real,
                            resultados reales</h6>
                        <h6 class="text-uppercase biggest font-weight-bold" style="color: #fff; font-size:3rem">
                            Historias
                            reales</h6>
                    </div>
                    <div v-show="screen<801" class="mr-auto ml-auto mt-8">
                        <div class="d-flex flex-wrap">
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/angelica.png')); ?>" width="120">
                                <h6>Angelica</h6>
                                <h5>Londres, UK</h5>
                                <p>
                                    "Obtener ganancias fue mas fácil de lo que pensé.
                                    Excelente experiencia, mucha motivación, fácil de seguir y todo el equipo siempre estuvo para resolver mis dudas.
                                    Me quedo dentro del reto sin duda."
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/vicenteruelas.png')); ?>" width="120">
                                <h6>Vicente</h6>
                                <h5>Jalisco, México</h5>
                                <p>
                                    “El reto me pareció bastante bueno, no me quedaba con hambre, rutinas muy buenas, videos muy bien explicados. Las atenciones siempre de lo mejor.
                                    Muy contento con los resultados”
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/diana.png')); ?>" width="120">
                                <h6>Diana</h6>
                                <h5>Guanajuato, México </h5>
                                <p>
                                    “El plan de compensación para mi fue lo mejor, gané mas en 8 semanas que lo que gano en mi trabajo en el mismo periodo de tiempo.
                                    Todas las dietas me encantaron, las rutinas son muy buenas, muy bien explicadas y mis dudas siempre fueron aclaradas en menos de 24 horas.”
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/luislazo.png')); ?>" width="120">
                                <h6>Luis</h6>
                                <h5>Quintana Roo, México</h5>
                                <p>
                                    "Hacer el reto con Pipo fue super motivante, vi resultados desde los primeros 15 días, siempre esta al pendiente de todos por medio del grupo privado y todo el equipo Acton siempre esta al pendiente para resolver las dudas”
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/fabricio.png')); ?>" width="120">
                                <h6>Fabricio</h6>
                                <h5>Goiania, Brasil</h5>
                                <p>
                                    "Soy profesor de Capoeria y siempre tengo que mantenerme en forma, en las 8 semanas del reto vi mas resultados en mi cuerpo que el ultimo año que estuve entrenando en el gym."
                                </p>
                            </div>
                            <div class="col-12 col-sm-4 testimonio">
                                <img src="<?php echo e(asset('images/daniel.png')); ?>" width="120">
                                <h6>Daniel</h6>
                                <h5>Baja California, México </h5>
                                <p>
                                    "El programa me pareció excelente, vi cambios desde la semana 2, superó por mucho mis expectativas, todo el equipo Acton siempre fue amable y todo el reto fue muy motivador."
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-show="screen>801" id="carouselVideos" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="d-flex flex-wrap">
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/angelica.png')); ?>" width="120">
                                        <h6>Angelica</h6>
                                        <h5>Londres, UK</h5>
                                        <p>
                                            "Obtener ganancias fue mas fácil de lo que pensé.
                                            Excelente experiencia, mucha motivación, fácil de seguir y todo el equipo siempre estuvo para resolver mis dudas.
                                            Me quedo dentro del reto sin duda."
                                        </p>
                                    </div>
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/vicenteruelas.png')); ?>" width="120">
                                        <h6>Vicente</h6>
                                        <h5>Jalisco, México</h5>
                                        <p>
                                            “El reto me pareció bastante bueno, no me quedaba con hambre, rutinas muy buenas, videos muy bien explicados. Las atenciones siempre de lo mejor.
                                            Muy contento con los resultados”
                                        </p>
                                    </div>
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/diana.png')); ?>" width="120">
                                        <h6>Diana</h6>
                                        <h5>Guanajuato, México </h5>
                                        <p>
                                            “El plan de compensación para mi fue lo mejor, gané mas en 8 semanas que lo que gano en mi trabajo en el mismo periodo de tiempo.
                                            Todas las dietas me encantaron, las rutinas son muy buenas, muy bien explicadas y mis dudas siempre fueron aclaradas en menos de 24 horas.”
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="d-flex flex-wrap">
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/luislazo.png')); ?>" width="120">
                                        <h6>Luis</h6>
                                        <h5>Quintana Roo, México</h5>
                                        <p>
                                            "Hacer el reto con Pipo fue super motivante, vi resultados desde los primeros 15 días, siempre esta al pendiente de todos por medio del grupo privado y todo el equipo Acton siempre esta al pendiente para resolver las dudas”
                                        </p>
                                    </div>
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/fabricio.png')); ?>" width="120">
                                        <h6>Fabricio</h6>
                                        <h5>Goiania, Brasil</h5>
                                        <p>
                                            "Soy profesor de Capoeria y siempre tengo que mantenerme en forma, en las 8 semanas del reto vi mas resultados en mi cuerpo que el ultimo año que estuve entrenando en el gym."
                                        </p>
                                    </div>
                                    <div class="col-12 col-sm-4 testimonio">
                                        <img src="<?php echo e(asset('images/daniel.png')); ?>" width="120">
                                        <h6>Daniel</h6>
                                        <h5>Baja California, México </h5>
                                        <p>
                                            "El programa me pareció excelente, vi cambios desde la semana 2, superó por mucho mis expectativas, todo el equipo Acton siempre fue amable y todo el reto fue muy motivador."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselVideos" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselVideos" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="col-10 col-sm-4 col-md-4 text-center d-block mr-auto ml-auto mt-8"
                         style="margin-bottom:40px">
                        <a class="btn btn-primary" href="<?php echo e(url('register')); ?>"
                           style="font-family: unitext_bold_cursive; width:100%; padding: 15px">Acepto el reto</a>
                    </div>
                </div>
            </div>
            <div class="container section">
                <h4 class="text-uppercase font-weight-bold text-center">¿Qué pasa después </h4>
                <h4 class="text-uppercase font-weight-bold text-center">de registrarme al reto?</h4>
                <br>
                <br>
                <div id="monitores" class="d-flex flex-wrap">
                    <div class="col-sm-4 col-12 monitor">
                        <h6 class="text-center font-weight-bold text-uppercase">Paso 1</h6>
                        <img src="<?php echo e(asset('img/monitor1.png')); ?>" width="90%">
                        <h6>Recibirás un correo con tu usuario y contraseña para poder ingresar a tu sesión</h6>
                    </div>
                    <div class="col-sm-4 col-12 monitor">
                        <h6 class="text-center font-weight-bold text-uppercase">Paso 2</h6>
                        <img src="<?php echo e(asset('img/monitor2.png')); ?>" width="90%">
                        <h6>En seguida empezarás un cuestionario en el que nos pasarás tus datos necesarios para hacer
                            tu programa personalizado</h6>
                        <br>
                    </div>
                    <div class="col-sm-4 col-12 monitor">
                        <h6 class="text-center font-weight-bold text-uppercase">Paso 3</h6>
                        <img src="<?php echo e(asset('img/monitor3.png')); ?>" width="90%">
                        <h6>Al finalizar este cuestionario entrarás automáticamente a tu programa y estarás listo para
                            empezar</h6>
                    </div>
                    <div class="col-12 col-sm-4 d-block ml-auto mr-auto">
                        <a class="btn btn-primary" href="<?php echo e(url('register')); ?>"
                           style="width: 100%; font-family: unitext_bold_cursive">Registrarme</a>
                    </div>
                </div>
            </div>
            <div class="section info">
                <div>
                    <h4 class="text-uppercase font-weight-bold">Preguntas frecuentes</h4>
                    <br>
                    <div @click="cambiarFaqs('reto')" class="in-cursor">
                        <h3 class="subtitle">¿QUÉ ES EL RETO ACTON?</h3>
                        <i :class="'fas fa-sort-'+(!faqs.reto?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.reto">
                        Es un programa de transformación de 8 semanas en el cual
                        se te dan las herramientas necesarias para lograr la mejor versión de
                        ti en 56 días.
                    </p>
                </div>
                <hr>
                <div>
                    <div @click="cambiarFaqs('diferente')" class="in-cursor">
                        <h3 class="subtitle">¿QUÉ HACE DIFERENTE AL RETO ACTON DE LOS DEMÁS?</h3>
                        <i :class="'fas fa-sort-'+(!faqs.diferente?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.diferente">
                        A diferencia de otros programas donde se les da una misma dieta a
                        todos los participantes, en el RETO ACTON el plan de alimentación es
                        específicamente planeado para ti, es decir, nunca será igual al de algún otro participante. Además este reto te da la oportunidad de ganar
                        dinero diariamente, es decir, todos pueden obtener premios en efectivo. ¡Aquí todos ganan!
                        También habrá un grupo privado de Facebook donde podrás interactuar con otros participantes y ayudarse unos con otros para mantener
                        la motivación.
                    </p>
                </div>
                <hr>
                <div>
                    <div @click="cambiarFaqs('dinero')" class="in-cursor">
                        <h3 class="subtitle">
                            ¿CUÁNTO DINERO PUEDO GENERAR EN EL RETO ACTON?
                        </h3>
                        <i :class="'fas fa-sort-'+(!faqs.dinero?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.dinero">
                        Lo que tú te propongas, no hay límite.
                        Recuerda que con cada inscrito que viene de tu parte se te premia con $500 MXN
                    </p>
                </div>
                <hr>
                <div>
                    <div @click="cambiarFaqs('finalizar')" class="in-cursor">
                        <h3 class="subtitle">¿QUÉ PASA AL FINALIZAR EL RETO ACTON?</h3>
                        <i :class="'fas fa-sort-'+(!faqs.finalizar?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.finalizar">
                        Otra de las ventajas que tienes en el RETO ACTON es que, una vez
                        que lo finalizas tienes la oportunidad de obtener un seguimiento
                        mensual y así mantener los avances y seguir mejorando
                    </p>
                </div>
                <hr>
                <div>
                    <div @click="cambiarFaqs('dudas')" class="in-cursor">
                        <h3 class="subtitle">¿HABRÁ QUIÉN ME RESUELVA DUDAS?</h3>
                        <i :class="'fas fa-sort-'+(!faqs.dudas?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.dudas">
                        Sí, contamos con soporte para aclarar tus dudas, el cual estará disponible de lunes a viernes de 9:00 am a 6:00 pm y sábados de 10:00
                        am a 2:00 pm
                    </p>
                </div>
                <hr>
                <div>
                    <div @click="cambiarFaqs('mundo')" class="in-cursor">
                        <h3 class="subtitle">
                            ¿PUEDO INSCRIBIRME DESDE CUALQUIER PARTE DEL MUNDO?
                        </h3>
                        <i :class="'fas fa-sort-'+(!faqs.mundo?'down':'up')+' float-right'"></i>
                    </div>
                    <p class="subinfo" v-show="faqs.mundo">
                        Sí, como el programa es 100% en línea puedes empezarlo desde
                        cualquier lugar.
                    </p>
                </div>
            </div>
        </div>
    </template>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://unpkg.com/vue-scroll-loader"></script>
    <script>
        Vue.component('inicio', {
            template: '#inicio-template',
            data: function () {
                return {
                    screen: 0,
                    features: {
                        comidas: true,
                        entrenamiento: true,
                        suplementos: true,
                        videos: true
                    },
                    faqs: {
                        diferente: false,
                        dinero: false,
                        dudas: false,
                        finalizar: false,
                        inscribcion: false,
                        loadMore: false,
                        mundo: false,
                        reto: false,
                        sesion: false,
                    },
                }
            },
            methods: {
                getProduct: function (url) {
                    let cadena = url.split("/");
                    let producto = cadena[cadena.length - 1].split(".");
                    return producto[0];
                },
                cambiarFaqs(nombre) {
                    _.each(this.faqs, function (value, key, obj) {
                        if (key != nombre) {
                            obj[key] = false;
                        } else {
                            obj[key] = !obj[key];
                        }
                    });
                },
                checarFeature: function (feature, primero, segundo) {
                    let top_of_element = $("#" + feature + "Feature").offset().top + 300;
                    let bottom_of_element = top_of_element + $("#" + feature + "Feature").outerHeight() + 300;
                    let bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
                    let top_of_screen = $(window).scrollTop();
                    if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
                        this.features[feature]=false;
                        this.features[primero]=true;
                        this.features[segundo]=true;
                    }
                },
                terminar: function (video) {
                    $("#videosCarousel .carousel-control-next").click();
                    if (video == 1) {
                        let elemento = $('#video2').first();
                        if (elemento.prop("tagName") == "VIDEO") {
                            elemento.get(0).play();
                        }
                    } else {
                        let elemento = $('#video3').first();
                        if (elemento.prop("tagName") == "VIDEO") {
                            elemento.get(0).play();
                        }
                    }
                }
            },
            mounted: function () {
                this.screen = screen.width;
                let vm = this;
                if (this.screen < 600) {
                    $(window).scroll(function () {
                        vm.checarFeature('comidas', 'entrenamiento', 'suplementos');
                        vm.checarFeature('entrenamiento','comidas','suplementos');
                        vm.checarFeature('suplementos', 'entrenamiento','videos');
                        vm.checarFeature('videos','suplementos','entrenamiento');
                    });
                }

                Vue.nextTick(function () {
                    $('#carouselExampleControls').carousel({
                        interval: 1500,
                        wrap: false
                    });

                    $('#carouselVideos').carousel({
                        interval: 10000,
                    });
                });

                this.cambiarFaqs('reto');
                window.onresize = () => {
                    this.screen = window.innerWidth
                }
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/welcome.blade.php ENDPATH**/ ?>