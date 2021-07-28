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

        .cardbusqueda {
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .usuario {
            padding: 10px;
            border-bottom: 1px solid #c2c2c2;
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
        }

    </style>
@endsection
@section('content')
    <div id="vue" class="flex-center">
        <inicio :usuario="{{ $usuario}}" :referencias="{{$referencias}}" :monto="{{$monto}}" :descuento="{{$descuento}}"
                :original="{{$original}}" :saldo="{{$saldo}}"></inicio>
    </div>

    <template id="inicio-template">
        <div class="contenedor">

            <div class="row" style="width: 104%;">
                <div class="card col-md-3" style="border: solid 0px; margin-left: 3%;">

                    <div class="card-header">Hola, @{{ usuario.name }}</div>
                    <div class="card-body" style="padding: 0">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div style="display: flex; flex-wrap:wrap;  background-color: #E9E9E9; padding: 20px">
                            <div class="col-12 col-sm-8 imagenpersonal d-block ml-auto mr-auto text-center" align="center" style="border:padding: 5px; min-height: 160px;">
                                <img :src="'{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id.'/'.rand(0,10))}}'"
                                     width="200px">
                                <br>
                                <h4>Código personal</h4>
                                <h4 class="">{{\Illuminate\Support\Facades\Auth::user()->referencia}}</h4>
                                <h4 class="acton" style="color:#007FDC;">Dinero Acton: $<money :cantidad="''+usuario.saldo"></money></h4>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card col-md-3" style="color:#007FDC;font-weight: bold;margin-left: 3%;">
                    <br>
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
                    <a v-if="usuario.inicio_reto==null" class="btn btn-lg btn-primary" href="{{url('/reto/comenzar/')}}">
                        <span>EMPEZAR RETO</span>
                    </a>
                    @endif
                <!--a v-else class="btn btn-lg btn-primary" href="{{url('/reto/programa')}}">
                                <span>Mi programa</span>
                            </a-->
                    <br>
                    <br>
                    <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank">
                    <!--i class="fa fa-file-pdf"></i> Descarga aquí tu manual de apoyo-->
                    </a>


                </div>



                    @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                <div class="card col-md-5 d-block ml-auto mr-auto text-center">
                        <img src="{{asset('/images/imagesremodela/copa.png')}}" width="45%" class="copa">
                </div>
                    @endif

            </div>

            <div class="card">
                        @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                            <div class="">
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

                                            <!--Controls-->
                                            <div class="controls-top">
                                                <a class="btn-floating" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                                                <a class="btn-floating" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right"></i></a>
                                            </div>
                                            <!--/.Controls-->

                                            <!--Indicators-->
                                            <ol class="carousel-indicators">
                                                <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
                                                <li data-target="#multi-item-example" data-slide-to="1"></li>
                                                <li data-target="#multi-item-example" data-slide-to="2"></li>
                                                <li data-target="#multi-item-example" data-slide-to="3"></li>
                                            </ol>
                                            <!--/.Indicators-->

                                            <!--Slides-->
                                            <div class="carousel-inner" role="listbox">

                                                <!--First slide-->
                                                <div class="carousel-item active">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/2semanasrenovar.png')}}" width="50%" @click="diasChange(14)">
                                                    </div>

                                                </div>
                                                <!--/.First slide-->
                                                <!--First slide-->
                                                <div class="carousel-item">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/4semanasrenovar.png')}}" width="50%" @click="diasChange(28)">
                                                    </div>

                                                </div>
                                                <!--/.First slide-->
                                                <!--First slide-->
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/8semanasrenovar.png')}}" width="50%" @click="diasChange(56)">
                                                    </div>

                                                </div>
                                                <!--/.First slide-->
                                                <!--First slide-->
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/12semanasrenovar.png')}}" width="50%" @click="diasChange(84)">
                                                    </div>

                                                </div>
                                                <!--/.First slide-->



                                            </div>
                                            <!--/.Slides-->

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
                            <hr>
                        @endif

                        <div class="card mb-3">
                            <div class="card-header" @click="BusquedaBlock()" style="cursor: pointer">
                                <i class="far fa-clipboard"></i> Personas que han usado tu referencia
                            </div>
                            <div class="card-body"  v-if="this.frmBusqueda">
                                <form  action="/usuarios/seguir/" method="GET" id="formBusqueda">
                                    @csrf
                                    <div style="display: flex; flex-wrap: wrap">
                                        <div class="col-sm-3">
                                            <label>Nombre</label>
                                            <input class="form-control" v-model="filtros.nombre" name="nombre">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Codigo Personal</label>
                                            <input class="form-control" v-model="filtros.codigo_personal" name="codigo_personal">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Conexiones</label>
                                            <select class="form-control" v-model="filtros.conexion" name="conexion">
                                                <option></option>
                                                <option>Siguiendo</option>
                                                <option>Me siguen</option>
                                                <option>Sin conexión</option>
                                                <option>Tiendas</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Tiendas/GYM</label>
                                            <select class="form-control" v-model="filtros.tiendagym" name="tienda">
                                                <option></option>
                                                <option v-for="p in this.tiendas[0]">@{{ p.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Estado</label>
                                            <select class="form-control" v-model="filtros.estado" @change="getCiudades()" name="estado">
                                                <option></option>
                                                <option v-for="p in this.estados[0]">@{{ p.estado }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Ciudad</label>
                                            <select class="form-control" v-model="filtros.ciudad" @change="getCPs()" name="ciudad">
                                                <option></option>
                                                <option v-for="p in this.ciudades[0]">@{{ p.ciudad }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Codigo Postal</label>
                                            <select class="form-control" v-model="filtros.cp" @change="getColonias()" name="cp">
                                                <option></option>
                                                <option v-for="p in this.cps[0]">@{{ p.cp }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Colonias</label>
                                            <select class="form-control" v-model="filtros.colonia" name="colonia">
                                                <option></option>
                                                <option  v-for="p in this.colonias[0]">@{{ p.colonia }}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>&nbsp;</label>
                                            <br>
                                            <button class="btn btn-primary" @click="buscarSeguir" :disabled=" buscando" class="col-sm-12">
                                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                                <i v-else class="fas fa-search"></i>&nbsp;Buscar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    <hr>

                    <div class="dash">
                        <div class="table-responsive">
                            <div class="d-flex flex-wrap">
                                <div class="col-12 col-sm-12 col-md-6 d-flex justify-content-end">
                                    <div v-if="referencias.length>1" class="col-12 col-sm-12 col-md-6">
                                        <h6>Estas son las personas que han usado tu código de referencia: </h6>
                                    </div>
                                    <!--span class="badge badge-light money" v-tooltip="{content:'Total generado'}">TG <money :caracter="true" :cantidad="''+usuario.total"></money></span>
                                    <span class="badge badge-light money" v-tooltip="{content:'Total transferido'}">TT <money :caracter="true" :cantidad="''+usuario.depositado"></money></span>
                                    <span class="badge badge-light money" v-tooltip="{content:'Pendiete por pagar'}">PP <money :caracter="true" :cantidad="''+usuario.saldo"></money></span-->
                                </div>
                            </div>
                            <table class="table" style="margin: 0px;">
                                <tr v-for="referencia in referenciados.data">
                                    <td>
                                        <div>
                                            <span>@{{ referencia.name }}</span>
                                        </div>
                                        <div>
                                            <span>@{{ referencia.email }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div>
                                            <fecha :fecha="referencia.created_at"></fecha>
                                        </div>
                                        <button v-if="(referencia.num_inscripciones-1) > 0" class="btn btn-sm btn-light" @click="verPagos(referencia)">
                                            <i class="far fa-calendar-edit"></i> Reinscripciones
                                        </button>
                                    </td>
                                </tr>
                                <!--tr v-if="referencias.length==0">
                                    <td>[Todavía no se ha utilizado tu referencia]</td>
                                </tr-->
                            </table>
                            <div class="float-right">
                                <paginador ref="paginador" :url="'{{url('/usuarios/referencias')}}'" @loaded="loaded"></paginador>
                                <br>
                            </div>
                        </div>
                    </div>

            <div class="card mb-3">
                <div class="card-header"><i class="far fa-clipboard"></i> Personas en ACTON</div>
                <div class="card-body">
                    <div style="display: flex; flex-wrap: wrap">
                        <div class="col-sm-3">
                            <label>Nombre</label>
                            <input class="form-control" v-model="filtros.nombre" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-3">
                            <label>Codigo Personal</label>
                            <input class="form-control" v-model="filtros.codigo_personal" name="codigo_personal">
                        </div>
                        <div class="col-sm-3">
                            <label>Conexiones</label>
                            <select class="form-control" v-model="filtros.conexion" @keyup.enter="buscar">
                                <option></option>
                                <option>Siguiendo</option>
                                <option>Me siguen</option>
                                <option>Sin conexión</option>
                                <option>Tiendas</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Tiendas/GYM</label>
                            <select class="form-control" v-model="filtros.tiendagym" @keyup.enter="buscar">
                                <option></option>
                                <option v-for="p in this.tiendas[0]" :selected="p == filtros.tiendagym">@{{ p.name }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Estado</label>
                            <select class="form-control" v-model="filtros.estado" @keyup.enter="buscar" @change="getCiudades()">
                                <option></option>
                                <option v-for="p in this.estados[0]">@{{ p.estado }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Ciudad</label>
                            <select class="form-control" v-model="filtros.ciudad" @keyup.enter="buscar" @change="getCPs()">
                                <option></option>
                                <option v-for="p in this.ciudades[0]">@{{ p.ciudad }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Codigo Postal</label>
                            <select class="form-control" v-model="filtros.cp" @keyup.enter="buscar" @change="getColonias()">
                                <option></option>
                                <option v-for="p in this.cps[0]">@{{ p.cp }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Colonias</label>
                            <select class="form-control" v-model="filtros.colonia" @keyup.enter="buscar">
                                <option></option>
                                <option  v-for="p in this.colonias[0]">@{{ p.colonia }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <br>
                            <button class="btn btn-primary" @click="buscarSeguir" :disabled=" buscando" class="col-sm-12">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-search"></i>&nbsp;Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card cardbusqueda mb-3" style="border:1px solid #d2d2d2">
                <div class="card-body">
                    <div v-for="usuario in usuariosSeguir.data" class="d-flex usuario">
                        <div class="col-4 d-flex flex-column align-items-start">
                            <span>
                                <i v-if="usuario.vigente" class="fa fa-user text-info"></i>
                                <i v-else class="fa fa-user text-default"></i>
                                @{{ usuario.name+' '+usuario.last_name }}
                            </span>
                            <!--span>@{{ usuario.medio }}</span-->
                        </div>
                        <div class="col-4 d-flex flex-column text-center">
                        </div>
                        <div class="col-4 d-flex flex-column align-items-end">
                            <div class="d-flex settings">
                                <div>
                                    <a v-tooltip="{content:'Ver perfil'}" class="btn btn-sm btn-default" :href="'{{ url('/cuenta/') }}/' + usuario.id">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                                <div v-if="usuario.amistad=='si'">
                                    <a v-tooltip="{content:'Ver reto'}" class="btn btn-sm btn-default" :href="'{{ url('/usuarios/imagenes') }}/' + usuario.id">
                                        <i class="fas fa-running"></i>
                                    </a>
                                </div>
                                <div v-if="usuario.amistad=='si'">
                                    <button v-tooltip="{content:'Dejar de Seguir'}" class="btn btn-sm btn-default" @click="dejar(usuario)">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </div>
                                <div v-if="usuario.amistad=='no'">
                                    <button v-tooltip="{content:'Seguir'}" class="btn btn-sm btn-default" @click="sigue(usuario)">
                                        <i class="fas fa-handshake"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="usuariosSeguir.length == 0 || usuariosSeguir.data.length == 0" align="center">
                        <h6 colspan="6">[No hay datos para mostrar]</h6>
                    </div>
                    <div class="float-right">
                        <paginador ref="paginadorBusquedaSeguir" :url="'{{url('/usuarios/buscarSeguir')}}'" @loaded="loadedSeguir"></paginador>
                    </div>
                </div>
            </div>

            <modal ref="baja" title="Baja de usuario" @ok="bajar">
                <h5>¿Quiere desactivar dar de baja a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="seguir" title="Seguir" @ok="seguir">
                <h5>¿Quiere seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="dejarseguir" title="DejarSeguir" @ok="dejarseguir">
                <h5>¿Quiere dejar de seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>

        </div>
    </template>
@endsection

@section('scripts')
    @if(\Illuminate\Support\Facades\Auth::user()->vencido)
        <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
        <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
    @endif
    <script>
        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['usuario', 'referencias','monto','original','descuento','saldo'],
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
                    montopago: 0,
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
                    usuario: {
                        id: '',
                        nombre: '',
                        tarjeta: '',
                        saldo: '',
                        referencia: '',
                        dias_reto:''
                    },
                    referencias:{
                        data:[]
                    },
                    compras:[]
                }},
            methods: {
                BusquedaBlock: function (){
                    if (this.frmBusqueda == true){
                        this.frmBusqueda = false;
                    }else{
                        this.frmBusqueda = true;
                    }
                },
                loaded: function (referencias) {
                    this.referenciados = referencias;
                    this.usuarios = usuarios;
                    this.buscando = false;
                },
                loadedSeguir: function (usuarios) {
                    this.usuariosSeguir = usuarios;
                    this.buscando = false;
                },
                buscar: function () {
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
                    this.buscando = false;
                },
                buscarSeguir: function () {
                    this.buscando = true;
                    this.$refs.paginadorBusquedaSeguir.consultar(this.filtros);
                    this.buscando = false;
                },
                terminado: function () {
                    window.location.href = "{{url('/home')}}";
                },
                check: function(e) {
                    if(this.saldo > 0) {
                        console.log(this.saldochk)
                        if (this.dias == 14) {
                            if (this.saldochk) {
                                if (this.saldo > 500) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 500 - this.saldo
                                }
                            } else {
                                this.montopago = 500
                            }
                        }
                        if (this.dias == 28) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 1000 - this.saldo
                                }
                            } else {
                                this.montopago = 1000
                            }
                        }
                        if (this.dias == 56) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 2000 - this.saldo
                                }
                            } else {
                                this.montopago = 2000
                            }
                        }
                        if (this.dias == 84) {
                            if (this.saldochk) {
                                if (this.saldo > 3000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 3000 - this.saldo
                                }
                            } else {
                                this.montopago = 3000
                            }
                        }
                        if (this.montopago == 0) {
                            $("#pagarceros").show();
                        } else {
                            $("#pagarceros").hide();

                        }
                    }
                    this.saveDiasNuevo();
                },
                diasChange: function (d) {
                    this.dias = d;
                    if(this.saldo >= 0) {
                        if (this.dias == 14) {
                            if (this.saldochk) {
                                if (this.saldo > 500) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 500 - this.saldo
                                }
                            } else {
                                this.montopago = 500
                            }
                        }
                        if (this.dias == 28) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 1000 - this.saldo
                                }
                            } else {
                                this.montopago = 1000
                            }
                        }
                        if (this.dias == 56) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 2000 - this.saldo
                                }
                            } else {
                                this.montopago = 2000
                            }
                        }
                        if (this.dias == 84) {
                            if (this.saldochk) {
                                if (this.saldo > 3000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 3000 - this.saldo
                                }
                            } else {
                                this.montopago = 3000
                            }
                        }
                        if (this.montopago == 0) {
                            $("#pagarceros").show();
                        } else {
                            $("#pagarceros").hide();

                        }
                    }
                    this.saveDiasNuevo();
                },
                saveDiasNuevo: function(){
                    if (this.saldochk) {
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias+'001', {saldo: this.saldo, operacion: 'resta', usa: 1}).then(function (response) {
                        });
                    }else{
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias+'000', {saldo: this.saldo, operacion: 'suma', usa: 0}).then(function (response) {
                        });
                    }
                },
                getTiendas: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getTiendas')}}').then((response) => {
                        this.tiendas=[];
                        this.tiendas.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getEstados: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getEstados')}}').then((response) => {
                        this.estados=[];
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.estados.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCiudades: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCiudades')}}', {estado:this.filtros.estado}).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCPs: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCP')}}', {ciudad:this.filtros.ciudad}).then((response) => {
                        this.cps=[];
                        this.colonias=[];
                        this.cps.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getColonias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getColonias')}}', {cp:this.filtros.cp}).then((response) => {
                        this.colonias=[];
                        this.colonias.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                pagaRefrendo: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/refrendar_ceros')}}', {dias: this.dias}).then((response) => {
                        location.reload();
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                sigue: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.seguir.showModal();
                },
                dejar: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.dejarseguir.showModal();
                },
                bajar: function () {
                    axios.post('{{url('/usuarios/bajar')}}', this.usuario).then(function (response) {
                        if (response.data.status=='ok'){
                            window.location.href = response.data.redirect;
                        }
                    }).catch(function () {

                    });
                },
                seguir: function () {
                    axios.post('{{url('/usuarios/seguir')}}/'+this.usuario.id).then(function (response) {
                        location.reload();
                    }).catch(function () {
                        window.location.href = '{{ url('/usuarios/imagenes') }}/' + this.usuario.id;

                    });
                },
                dejarseguir: function () {
                    axios.post('{{url('/usuarios/dejar_seguir')}}/'+this.usuario.id).then(function (response) {
                        location.reload();
                    }).catch(function () {

                        location.reload();
                    });
                },
            },
            mounted: function () {
                $("#pagarceros").hide();
                this.diasChange(14);
                this.filtros.referencia = this.usuario.referencia;
                this.getEstados();
                this.getTiendas();
                this.buscar();
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                if(params.get("_token")) {
                    this.buscar();
                }
                @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                    this.$refs.cobro.configurar(
                    this.usuario.name,
                    this.usuario.last_name,
                    this.usuario.email,
                    this.usuario.telefono,
                    this.usuario.codigo
                );
                @endif
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
