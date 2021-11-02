@extends('layouts.app')
@section('header')
    <style>
        hr{
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .money{
            margin-left: 5px;
        }
        .contenedor{
            width: 100% !important;
        }
        .estas_listo img{
            margin-top: -55px;
            margin-left: 20px;
        }
        .estas_listo div{
            padding-top: 10px;
            display: inline-block;
        }
        .estas_listo{
            background-color: rgba(0, 159, 227, 1);
            color: white;
            height: 150px;
            font: caption;
        }
        .planes .col-sm-3{
            padding: 10px;
        }
        .planes{
            background-color: rgba(0, 93, 156, 1);
            width: 100%;
            height: auto;
        }
        #pagar{
            width: 25% !important;
        }
        .card-img-top {
            width: 70% !important;
            margin-left: 15% !important;
            padding-top: 25px !important;
            padding-bottom: 42px !important;
        }

        .imagenpersonal img {
            display:block;
            margin:0 -10px;
        }

        .card {
            border: 0px solid rgba(0, 0, 0, 0.125) !important;
        }

        .modal-body-reto .card{
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .cardbusqueda {
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .usuario {
            padding: 10px;
            border-bottom: 1px solid #c2c2c2;
        }

        .subir_foto{
            background: #007FDC;
            color: white;
            border: 0px solid;
            border-radius: 5px;
            padding: 5px;
        }
        .mialbum_card{
            border: 1px solid #c2c2c2 !important;
            padding: 5px;
            border-radius: 5px;
        }
        .flechas-right{
            background: transparent !important;
            border: 0px solid;
            margin-top: 45%;
            position: absolute;
            margin-left: 82%;
            z-index: 1000;
            color: white;
        }
        .flechas-left{
            background: transparent !important;
            border: 0px solid;
            margin-top: 45%;
            z-index: 1000;
            position: absolute;
            margin-left: 0%;
            color: white;
        }
        .modal-body {
            padding: 0 !important;
        }
        .modal-body img {
            padding: 0 !important;
        }
        .trespuntos{
            position: absolute;
            z-index: 9999999999999999999;
            margin-top: 5%;
            width: 7% !important;
            margin-left: -10%;
        }
        .modal_mini {
            position: absolute;
            z-index: 9999999999999999999;
            margin-top: -90%;
            width: 100%;
        }
        #accordionExample2 {
            position: absolute;
            margin-top: -100%;
            width: 100%;
            background: #CCCCCC;
        }
        #accordionExample2 .card-header {
            color: #fff;
            background-color: #cccccc;
            border: 1px solid;
        }
        @media only screen and (max-width: 800px) {
            #pagar {
                width: 80% !important;
            }
            .estas_listo{
                height: 300px;
            }
            .estas_listo img{
                margin-top: -5px;
            }
            .controls-top{
                display: none;
            }
            .carousel-indicators li {
                width: 8px !important;
                height: 6px !important;
                border-radius: 50% !important;
            }
            .copa{
                width: 60% !important;
            }

            .imagenpersonal img {
                display:block;
                margin:0 0px;
                width: 400px !important;
            }
            .lunes-proximo{
                border: 0.6px solid #c2c2c2;
                border-radius: 5px;
                min-height: 100px;
                color: #c2c2c2;
            }
        }

    </style>
@endsection
@section('content')
    <div id="vue" class="flex-center">
        <inicio :usuario="{{ $usuario}}" :fotos="{{$fotos}}" :seguidos="{{$seguidos}}" :siguen="{{$siguen}}" ></inicio>
    </div>

    <template id="inicio-template">
        <div class="">


            <!--div class="card-header">Hola, @{{ usuario.name }}</div-->
            <div class="" style="">
            @if (session('status'))
                <!--div class="alert alert-success" role="alert">
                                {{ session('status') }}
                        </div-->
                @endif
                <div style="">
                    <div class="" align="center" style="">
                        <div :src="'/cuenta/getFotografia/'+usuario.id+'/4353'"
                             width="100%" style=" min-height: 300px;" :style="{
                                    height: '100px',
                                    backgroundColor: '#323232',
                                    backgroundImage: 'url(\'' + imagen_perfil + '\')',
                                    backgroundPosition: 'center center',
                                    backgroundSize: 'cover'
                                    }"></div>
                        <br>
                    </div>

                </div>
                <div class="row col-12">
                    <div class="col-10">
                        <h2>@{{ usuario.name }} @{{ usuario.last_name }}</h2>
                    </div>
                    <div class="col-2" style="margin-top: 10px;">
                        <i class="fas fa-pen"></i>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <div class="col-12">
                        <br>
                        <h6>Tu código es: @{{usuario.referencia}}</h6>
                        <qrcode :value="'https://retoacton.com/registro/gratis/?codigo='+usuario.referencia" :options="{ width: 200 }" @ready="onReady"></qrcode>
                        <br>
                    </div>
                    <br>
                    <div class="col-12 text-center mt-3">
                        <a href="#" class="col-6"><img src="{{asset('images/2021/btn_seguir.png')}}" class="col-8"></a>
                    </div>
                    <br>
                    <div class="col-12 text-center mt-3">
                        <a href="#" data-toggle="modal" data-title=""
                           data-target="#ponerreto"  class="col-6"><img src="{{asset('images/2021/ponme_reto.png')}}" class="col-10"></a>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-sort-down mr-1"></i> Mi información
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-6">
                                    <h2><a href="usuarios/seguir/?q=siguen#lstUsuarios">@{{siguen}}</a></h2>
                                    Seguidores
                                </div>
                                <div class="col-6 mb-4">
                                    <h2><a href="usuarios/seguir/?q=seguidos#lstUsuarios">@{{seguidos}}</a></h2>
                                    Seguidos
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.edad_publico">
                                    <img src="{{asset('images/2021/edad_info.png')}}" width="30px" class="mr-2">@{{ usuario.edad }} Años
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.idiomas_publico">
                                    <img src="{{asset('images/2021/idiomas.png')}}" width="30px" class="mr-2">@{{ usuario.idiomas }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.gym_publico">
                                    <img src="{{asset('images/2021/gym_info.png')}}" width="30px" class="mr-2">@{{ usuario.gym }}
                                </div>
                                <div class="col-12 mb-2">
                                    <img src="{{asset('images/2021/info_ubicacion.png')}}" width="30px" class="mr-2">@{{ usuario.estado }}, @{{ usuario.ciudad }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.idiomas_publico">
                                    <img src="{{asset('images/2021/intereses_info.png')}}" width="30px" class="mr-2">@{{ usuario.idiomas }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.situacion_actual_publico">
                                    <img src="{{asset('images/2021/estado_info.png')}}" width="30px" class="mr-2">@{{ usuario.situacion_actual }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-sort-down mr-1"></i> Mi álbum
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body text-center">
                            <div class="row text-center">
                                <div v-for="f in fotos" class="text-center col-4 ">
                                    <a class="thumbnail" href="#" :data-image-id="f.id" data-toggle="modal" data-title=""
                                       :data-image="'/'+f.archivo"
                                       :id="f.id"
                                       :data-descripcion="'/'+f.descripcion"
                                       data-target="#image-gallery"
                                       @click="interacciones"
                                    >
                                        <img :src="'/'+f.archivo" class="col-12 mialbum_card">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-sort-down mr-1"></i> Mis retos
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row text-center">
                                <div v-for="f in retos" class="text-center col-4 ">
                                    <a class="thumbnail" href="#" :data-image-id="f.id" data-toggle="" data-title=""
                                       @click="mostrarmini('usuario.name', f.coins, f.descripcion, f.created_at, f.publico, 'reto', f.id, f.id,  f.aceptado, f.updated_at,  f.video)"
                                    >
                                        <img :src="f.archivo" class="col-12 mialbum_card">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!--br>
                    <br>
                    <h4 class="" style="color:#007FDC;">Edad: @{{ usuario.edad }}</h4>
                    <h4 class="" style="color:#007FDC;">Empleo: @{{ usuario.empleo }}</h4>
                    <h4 class="" style="color:#007FDC;">Estudios: @{{ usuario.estudios }}</h4>
                    <h4 class="" style="color:#007FDC;">Intereses: @{{ usuario.intereses }}</h4>
                    <h4 class="" style="color:#007FDC;">Idiomas: @{{ usuario.idiomas }}</h4>
                    <h4 class="" style="color:#007FDC;">Gym: @{{ usuario.gym }}</h4>
                    <br>
                    <h4 class="" style="color:#007FDC;">@{{ usuario.estado }}, @{{ usuario.ciudad }}, @{{ usuario.colonia }}</h4>
                    @if(\Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::TIENDA && \Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::COACH && \Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::ENTRENADOR)
            <a-- v-if="usuario.inicio_reto==null" class="btn btn-lg btn-primary" href="{{url('/reto/comenzar/')}}">
                        <span>EMPEZAR RETO</span>
                    </a-->
        @endif
        <!--a v-else class="btn btn-lg btn-primary" href="{{url('/reto/programa')}}">
                                <span>Mi programa</span>
                            </a-->
        <!--br>
                    <br>
                    <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank"-->
            <!--i class="fa fa-file-pdf"></i> Descarga aquí tu manual de apoyo-->
            <!--/a-->





        @if(\Illuminate\Support\Facades\Auth::user()->vencido)
            <!--div class="card col-md-5 d-block ml-auto mr-auto text-center">
                        <img src="{{asset('/images/imagesremodela/copa.png')}}" width="45%" class="copa">
                </div-->
            @endif


            <div class="card">
            @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                <!--div class="">
                                <div class="">
                                    <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                                        <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                               estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                               :caracter="true"></money>
                                    </label>



                                    <div class="estas_listo text-center">
                                        <div class="row" style="width: 100%;">
                                            <div class="col-sm-12">
                                                <div>
                                                    <h1>¿Estas listo para elegir<br>
                                                        tu plan de seguimiento?</h1>
                                                </div>
                                                <div>
                                                    <img src="{{asset('/images/imagesremodela/tablaok.png')}}" width="50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="planes text-center flex-column d-none d-md-block d-lg-block">
                                        <div class="row col-sm-11" style="margin-left: 4%;padding-top: 20px;padding-bottom: 20px;">
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/2semanasrenovar.png')}}" width="95%" @click="diasChange(14)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/4semanasrenovar.png')}}" width="95%" @click="diasChange(28)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/8semanasrenovar.png')}}" width="95%" @click="diasChange(56)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/12semanasrenovar.png')}}" width="95%" @click="diasChange(84)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="planes text-center flex-column d-none d-sm-block d-block d-md-none">


                                        <div id="multi-item-example" class="carousel slide carousel-multi-item" data-ride="carousel">

                                            <div class="controls-top">
                                                <a class="btn-floating" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                                                <a class="btn-floating" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right"></i></a>
                                            </div>

                                            <ol class="carousel-indicators">
                                                <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
                                                <li data-target="#multi-item-example" data-slide-to="1"></li>
                                                <li data-target="#multi-item-example" data-slide-to="2"></li>
                                                <li data-target="#multi-item-example" data-slide-to="3"></li>
                                            </ol>

                                            <div class="carousel-inner" role="listbox">

                                                <div class="carousel-item active">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/2semanasrenovar.png')}}" width="50%" @click="diasChange(14)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/4semanasrenovar.png')}}" width="50%" @click="diasChange(28)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/8semanasrenovar.png')}}" width="50%" @click="diasChange(56)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/12semanasrenovar.png')}}" width="50%" @click="diasChange(84)">
                                                    </div>

                                                </div>



                                            </div>

                                        </div>


                                    </div>
                                    <br>


                                    <div id="infoPago" v-if="descuento>0">
                                        <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                                            el </label>
                                        <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                                        <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive">ÚLTIMO DIA</label>
                                    </div>
                                    <div id="pagar" class="text-center" style="widows: 13% !important; color: black;">
                                        <div>
                                            <img src="{{asset('/images/imagesremodela/medalla.png')}}" width="95%">
                                        </div>
                                        <div style="font-size: 1.5rem;margin-left: 10%;">
                                                <input
                                                        type="checkbox"
                                                        :value="saldochk"
                                                        id="saldochk"
                                                        v-model="saldochk"
                                                        @change="check($event)">
                                                Usar saldo<br>
                                                a sólo<br>
                                            <money :cantidad="''+montopago" :caracter="true" :decimales="0"
                                                   estilo="font-size:1.5em; font-weight: bold"></money>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <button id="pagarceros" @click="pagaRefrendo" class="btn btn-primary col-md-4 offset-4">Pagar</button>
                                    </div>
                                    <cobro ref="cobro" :cobro="''+montopago" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                                           :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                                           @terminado="terminado"></cobro>
                                </div>
                            </div>
                            <hr-->
                @endif


                <hr>



                <modal ref="seguir" title="Nueva foto" @ok="GuardarNuevaFoto">
                    <div class="row col-12">
                        <clipper-upload v-model="imgURL" class="subir_foto col-4 text-center offset-1">Subir foto</clipper-upload>
                        <button @click="getResult" class="subir_foto col-4 offset-3">Cortar</button>
                    </div>
                    <div class="col-12 text-center">
                        <clipper-basic class="my-clipper" ref="clipper" :src="imgURL" ratio="1" class="col-12">
                            <div class="placeholder" slot="placeholder">Sin foto</div>
                        </clipper-basic>
                        <div>Resultado:</div>
                        <img class="result" :src="resultURL" alt="" class="col-12">
                    </div>
                    <div class="col-12 text-center mt-3">
                        <textarea rows="2" cols="40" id="txtDescripcion" v-model="descripcion" placeholder="Descripción"></textarea>
                    </div>

                </modal>


                <div class="modal fade" id="ponerreto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body modal-body-reto">

                                <div class="card mt-4" style="padding: 20px;">
                                    <div class="text-center col-12 mb-3">
                                        Describe tu reto
                                    </div>
                                    <textarea class="form-control" v-model="descripcion_reto" placeholder="Describe tu reto"></textarea>
                                </div>

                                <div class="card mt-4" style="padding: 20px;">
                                    <div class="text-center col-12 mb-3">
                                        Ofrezco por este reto
                                    </div>
                                    <div class="col-12 text-center row ml-2">
                                        <img src="{{asset('images/2021/moneda_mini.png')}}" class="col-1 offset-1 mt-1 ml-4 mr-2" style="width: 78% !important; height: 25px;">
                                        <input type="number" class="form-control col-4" min="0" v-model="pago">
                                        <span class="col-5">Acton Coins</span>
                                    </div>
                                </div>

                                <div class="card mt-4 mb-3" style="padding: 20px;">
                                    <div class="text-center col-12 mb-3">
                                        ESTE RETO SERÁ
                                    </div>
                                    <div class="row" style="margin-left: -10.7%">
                                        <div class="col-6">
                                            <img src="{{asset('images/2021/btn_publico.png')}}" style="width: 115% !important;"><br>
                                            <img src="{{asset('images/2021/ad_publico.png')}}" style="width: 115% !important;" class="mt-2">
                                        </div>
                                        <div class="col-6">
                                            <img src="{{asset('images/2021/btn_privado.png')}}" style="width: 115% !important;"><br>
                                            <img src="{{asset('images/2021/ad_privado.png')}}" style="width: 115% !important;" class="mt-2">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button class="btn btn-success mb-3" @click="enviarReto">Enviar Reto</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



                <!--modal ref="nuevasfoto" title="Nueva foto">
                </modal-->

                <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button @click="interacciones" type="button" class=" float-left flechas-left" id="show-previous-image"><img src="{{asset('images/2021/izquierda.png')}}" class="w-25">
                                </button>
                                <button @click="interacciones" type="button" id="show-next-image" class=" float-right flechas-right"><img src="{{asset('images/2021/derecha.png')}}"  class="w-25">
                                </button>
                                <img id="image-gallery-image" class="img-responsive col-md-12" src="" data-id="">
                                <img id="" class="trespuntos" src="{{asset('images/2021/puntos.png')}}" data-id="" @click="mostrarmenumodal">
                                <div v-if="mostrarAcordion" class="accordion" id="accordionExample2" style="border: 1px solid white;">
                                    <div class="card" style="    border-bottom: 1px solid white;">
                                        <div class="card-header" id="uno">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" @click="mostrarmini('privacidad')">
                                                    Privacidad
                                                </button>
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('conteo')">
                                                    Conteo de reacciones
                                                </button>
                                            </h5>
                                        </div>
                                    </div>



                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('administrar')">
                                                    Administrar comentarios
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('editar')">
                                                    Editar descripción
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('eliminar')">
                                                    Eliminar foto/video
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal_mini">



                                    <div v-if="privacidad_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Esta publicación la pueden ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarPrivacidad('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarPrivacidad('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="conteo_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            El conteo de reacciones de esta publicacion lo puede ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarConteo('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarConteo('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="administrar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Los comentarios de esta publicacion lo puede ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarComentarios('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarComentarios('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="eliminar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            ¿Estas seguro que deseas eliminar este archivo?
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="eliminarElemento()">Eliminar</button>
                                            <button class="btn btn-danger col-4 offset-1" @click="ocultarmini()">Cancelar</button>
                                        </div>
                                    </div>

                                    <div v-if="editar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Editar descripción
                                        </div>
                                        <div class="text-center col-12">
                                            <textarea v-model="descripcion_edicion"></textarea>
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarDescripcion()">Guardar</button>
                                            <button class="btn btn-success col-4 offset-1" @click="ocultarmini()">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-12 row text-center mt-3 mb-3 ml-1">
                                    <div class="col-3">
                                        <img v-if="!dar_acton" src="{{asset('images/2021/dar_acton.png')}}" @click="darCoinsLike()" class="w-50">
                                        <img v-else src="{{asset('images/2021/dar_acton_color.png')}}" @click="darCoinsLike()" class="w-50">
                                    </div>
                                    <div class="col-3">
                                        <img v-if="!like_acton" src="{{asset('images/2021/like_acton.png')}}" @click="darLike('like')" class="w-50">
                                        <img v-else src="{{asset('images/2021/like_acton_color.png')}}" @click="darLike('like')" class="w-50">
                                    </div>
                                    <div class="col-3">
                                        <img  v-if="!flor_acton" src="{{asset('images/2021/flor_acton.png')}}" class="" @click="darLike('flor')" width="35%">
                                        <img v-else src="{{asset('images/2021/flor_acton_color.png')}}" @click="darLike('flor')" class="" width="35%">
                                    </div>
                                    <div class="col-3">
                                        <img  v-if="!fuego_acton" src="{{asset('images/2021/fuego_acton.png')}}" @click="darLike('fuego')" class="" width="35%">
                                        <img v-else src="{{asset('images/2021/fuego_acton_color.png')}}" @click="darLike('fuego')" class="" width="35%">
                                    </div>
                                </div>
                                <div v-if="darCoins" class="row col-12 text-center">
                                    <input type="number" id="txtactoncoins" min="1" class="darcoins col-8 offset-2" placeholder="Acton coins"><br>
                                    <button class="btn btn-success col-6 offset-3 mt-2  " @click="enviarCoins">Enviar</button>
                                </div>
                                <div id="descripcion" class="row col-10 offset-1 mb-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </template>
@endsection

@section('scripts')
    @if(\Illuminate\Support\Facades\Auth::user()->vencido)
        <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
        <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
    @endif
    <script>

        Vue.component(VueQrcode.name, VueQrcode);

        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['usuario', 'referencias','monto','original','descuento','saldo', 'fotos', 'seguidos', 'siguen'],
            data: function(){
                return{
                    nombre:'',
                    referenciados: [],
                    pagos: [],
                    filtros:{
                        referencia: '',
                        estado: '0',
                        ciudad: '0',
                        cp: '0',
                        colonia: '0',
                        tiendagym: '0',
                        conexion: '0',
                        ingresadosReto: '',
                        codigo_personal: '',
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: ''
                    },
                    buscando: false,
                    descripcion: '',
                    montopago: 0,
                    dias_hasta: 0,
                    horas: 0,
                    minutos: 0,
                    segundos: 0,
                    saldochk: false,
                    dias: 24,
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],
                    tiendas:[],
                    conexiones:[],
                    frmBusqueda: false,
                    usuariosSeguir: [],
                    compras:[],
                    reacciones:[],
                    imgURL: '',
                    resultURL: '',
                    dar_acton: false,
                    flor_acton: false,
                    fuego_acton: false,
                    like_acton: false,
                    darCoins: false,
                    mostrarAcordion: false,
                    eliminar_mini: false,
                    editar_mini: false,
                    administrar_mini: false,
                    privacidad_mini: false,
                    conteo_mini: false,
                    descripcion_edicion: '',
                    descripcion_reto: '',
                    pago: 0,
                    imagen_perfil: '/cuenta/getFotografia/'+this.usuario.id+'/33434',
                }},
            methods: {
                sigue: function () {
                    this.$refs.seguir.showModal();
                },
                obtenercoins: function () {
                    this.$refs.obtenercoins.showModal();
                },
                upload: function(e){
                    if (e.target.files.length !== 0) {
                        if(this.imgURL) URL.revokeObjectURL(this.imgURL)
                        this.imgURL = window.URL.createObjectURL(e.target.files[0]);
                    }
                },
                getResult: function () {
                    const canvas = this.$refs.clipper.clip();//call component's clip method
                    this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image
                },
                GuardarNuevaFoto: function() {
                    let vm = this;
                    let fm = new FormData();
                    fm.append('imagen', this.resultURL);
                    fm.append('descripcion', this.descripcion);
                    if(this.descripcion != '' && this.imgURL != ''){
                        axios.post('{{url('cuenta/mialbum/nuevaFoto/')}}', fm).then(function (response) {
                            vm.loadingFoto = false;
                            if (response.data.status == 'ok'){
                            }
                        }).catch(function (error) {
                            console.log(error.response.data.errors);
                        });
                    }
                },
                galeria: function(){
                    let current_image,
                        selector,
                        counter = 0;
                    var setIDs = true;
                    var setClickAttr = 'a.thumbnail';

                    function disableButtons(counter_max, counter_current) {
                        $('#show-previous-image, #show-next-image')
                            .show();
                        if (counter_max === counter_current) {
                            $('#show-next-image')
                                .hide();
                        } else if (counter_current === 1) {
                            $('#show-previous-image')
                                .hide();
                        }
                    }

                    $('#show-next-image, #show-previous-image')
                        .click(function () {
                            if ($(this)
                                .attr('id') === 'show-previous-image') {
                                current_image--;
                            } else {
                                current_image++;
                            }

                            selector = $('[data-image-id="' + current_image + '"]');
                            updateGallery(selector);
                        });

                    function updateGallery(selector) {
                        let $sel = selector;
                        current_image = $sel.data('image-id');
                        $('#image-gallery-title')
                            .text($sel.data('title'));
                        $('#image-gallery-image')
                            .attr('src', $sel.data('image'));
                        $('#image-gallery-image')
                            .attr('data-id', current_image);
                        $('#descripcion')
                            .html($sel.data('descripcion'));
                        disableButtons(counter, $sel.data('image-id'));
                    }

                    if (setIDs == true) {
                        $('[data-image-id]')
                            .each(function () {
                                counter++;
                                $(this)
                                    .attr('data-image-id', counter);
                            });
                    }
                    $(setClickAttr)
                        .on('click', function () {
                            updateGallery($(this));
                        });

                },
                darLike: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    axios.post('{{url('cuenta/mialbum/darlike/')}}', {'tipo': tipo, 'id': dataID}
                    ).then(function (response) {
                        vm.loadingFoto = false;
                        console.log(response.data);
                        if (response.data.status == 'agregado'){
                            if(tipo == 'fuego'){
                                vm.fuego_acton = true;
                            }
                            if(tipo == 'flor'){
                                vm.flor_acton = true;
                            }
                            if(tipo == 'like'){
                                vm.like_acton = true;
                            }
                        }else{
                            if(tipo == 'fuego'){
                                vm.fuego_acton = false;
                            }
                            if(tipo == 'flor'){
                                vm.flor_acton = false;
                            }
                            if(tipo == 'like'){
                                vm.like_acton = false;
                            }
                        }
                    });
                },
                interacciones: function(){
                    var vm = this;
                    setTimeout(function(){
                        vm.fuego_acton = false;
                        vm.flor_acton = false;
                        vm.like_acton = false;
                        vm.dar_acton = false;
                        var element = document.getElementById('image-gallery-image');
                        var dataID = element.getAttribute('data-id');
                        axios.post('{{url('cuenta/mialbum/reacciones/')}}', {'id': dataID}
                        ).then(function (response) {
                            vm.loadingFoto = false;
                            //console.log(response.data);
                            Object.keys(response.data).forEach(function(key) {
                                console.log(key, response.data[key].tipo)
                                if(response.data[key].tipo_like == 'fuego'){
                                    vm.fuego_acton = true;
                                }
                                if(response.data[key].tipo_like == 'flor'){
                                    vm.flor_acton = true;
                                }
                                if(response.data[key].tipo_like == 'like'){
                                    vm.like_acton = true;
                                }
                                if(response.data[key].tipo_like == 'coins'){
                                    vm.dar_acton = true;
                                }
                            })
                        });
                    }, 500);
                },
                guardarPrivacidad: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'privacidad'}
                    ).then(function (response) {
                    });
                    vm.privacidad_mini = false;

                },
                guardarComentarios: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    vm.administrar_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'comentarios'}
                    ).then(function (response) {
                    });

                },
                eliminarElemento: function(){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    vm.administrar_mini = false;
                    axios.post('{{url('cuenta/eliminarElemento/')}}', {'id': dataID}
                    ).then(function (response) {
                        window.location.reload();
                    });

                },
                guardarDescripcion: function(){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    vm.editar_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': vm.descripcion_edicion, 'tipo': 'descripcion'}
                    ).then(function (response) {
                        $('#descripcion')
                            .html(vm.descripcion_edicion);
                        $('#'+dataID).attr('data-descripcion', vm.descripcion_edicion);
                    });

                },
                guardarConteo: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-id');
                    vm.conteo_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'conteo'}
                    ).then(function (response) {
                    });

                },
                darCoinsLike: function(){
                    this.darCoins = true;
                },
                enviarCoins: function(){
                    var vm = this;
                    this.darCoins = true;
                    var element = document.getElementById('image-gallery-image');
                    var coins = document.getElementById('txtactoncoins').value;
                    var dataID = element.getAttribute('data-id');
                    axios.post('{{url('cuenta/mialbum/enviarcoins/')}}', {'id': dataID, 'coins': coins}
                    ).then(function (response) {
                        if (response.data.status == 'agregado'){
                            vm.dar_acton = true;
                            vm.darCoins = false;
                        }else{
                            alert('No cuentas con suficiente acton coins');
                            vm.dar_acton = false;
                            vm.darCoins = true;
                        }

                    });
                },
                terminado: function () {
                    window.location.href = 'home';
                },
                mostrarmenumodal: function(){
                    var vm = this;
                    if(vm.mostrarAcordion){
                        vm.mostrarAcordion = false;
                    }else {
                        vm.mostrarAcordion = true;
                    }
                },
                ocultarmini: function(){
                    var vm = this;
                    vm.eliminar_mini = false;
                    vm.editar_mini = false;
                    vm.administrar_mini = false;
                    vm.privacidad_mini = false;
                    vm.conteo_mini = false;
                },
                enviarReto: function(){
                    var vm = this;
                    axios.post('{{url('cuenta/enviarreto/')}}', {'id': vm.usuario.id, 'pago': vm.pago, 'descripcion': vm.descripcion_reto}
                    ).then(function (response) {
                        if (response.data.status == 'No cuenta con saldo suficiente'){
                            alert('No cuenta con saldo suficiente');
                        }
                    });
                }
            },
            mounted: function () {
                this.countdown();
                $("#pagarceros").hide();
                //this.diasChange(14);
                /*this.filtros.referencia = this.usuario.referencia;
                this.getEstados();
                this.getTiendas();
                this.buscar();
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                if(params.get("_token")) {
                    this.buscar();
                }*/
                this.galeria();
                @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                /*this.$refs.cobro.configurar(
                this.usuario.name,
                this.usuario.last_name,
                this.usuario.email,
                this.usuario.telefono,
                this.usuario.codigo
            );*/
                @endif

            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
