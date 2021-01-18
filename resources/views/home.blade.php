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
    </style>
@endsection
@section('content')
    <div id="vue" class="container flex-center">
        <inicio :usuario="{{ $usuario}}" :referencias="{{$referencias}}" :monto="{{$monto}}" :descuento="{{$descuento}}"
                :original="{{$original}}" :saldo="{{$saldo}}"></inicio>
    </div>

    <template id="inicio-template">
        <div class="container">
            @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                <div class="card">
                    <div class="card-header">Reto concluido</div>
                    <div class="card-body">
                        <h6 style="font-size: 1.4em">Tu reto ha concluido, te invitamos a realizar tu pago para que sigas gozando los beneficios del Reto Acton</h6>
                        <hr>
                        <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                            <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                   estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                   :caracter="true"></money>
                        </label>

                        Semanas: <select class="form-control" v-model="dias"
                                         @change="diasChange()">
                            <option value="14" :selected="dias === 24">2 Semanas</option>
                            <option value="28">4 semanas</option>
                            <option value="56">8 semanas</option>
                            <option value="84">12 semanas</option>
                        </select>
                        <br>

                        <div id="infoPago" v-if="descuento>0">
                            <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                                el </label>
                            <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                            <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive">ÚLTIMO DIA</label>
                        </div>
                        <div id="pagar">
                            <div>a solo</div>
                            <div style="font-size: 1.5rem; margin-left: 5px">
                                <div>
                                    <input
                                            type="checkbox"
                                            :value="saldochk"
                                            id="saldochk"
                                            v-model="saldochk"
                                            @change="check($event)">
                                    Usar saldo<br>
                                </div>
                                <money :cantidad="''+montopago" :caracter="true" :decimales="0"
                                       estilo="font-size:1.5em; font-weight: bold"></money>
                            </div>
                        </div>
                        <br>
                        <div>
                            <button id="pagarceros" @click="pagaRefrendo" class="btn btn-primary col-md-4 offset-4">Pagar</button>
                        </div>
                        <h6 style="color: #000;">Estas son las formas de realizar tu pago de manera segura</h6>
                        <cobro ref="cobro" :cobro="''+montopago" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                               :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                               @terminado="terminado"></cobro>
                    </div>
                </div>
                <hr>
            @endif
            <div class="card">
                <div class="card-header">Hola, @{{ usuario.name }}</div>
                <div class="card-body" style="padding: 0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="display: flex; flex-wrap:wrap;  background-color: #E9E9E9; padding: 20px">
                        <div class="col-12 col-sm-6" align="center" style="border:padding: 5px">
                            <img :src="'{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id.'/'.rand(0,10))}}'"
                                 width="100">
                            <h4>Código personal</h4>
                            <h4 class="acton">{{\Illuminate\Support\Facades\Auth::user()->referencia}}</h4>
                        </div>
                        <div class="col-12 col-sm-6 d-flex" style="align-items: flex-end;">
                            <div class="d-block ml-auto mr-auto text-center">
                                <h4>Saldo a favor</h4>
                                <h4 class="acton">$<money :cantidad="''+usuario.saldo"></money></h4>
                                <a v-if="usuario.inicio_reto==null" class="btn btn-lg btn-primary" href="{{url('/reto/comenzar/')}}">
                                    <span>EMPEZAR RETO</span>
                                </a>
                                <!--a v-else class="btn btn-lg btn-primary" href="{{url('/reto/programa')}}">
                                    <span>Mi programa</span>
                                </a-->
                                <br>
                                <br>
                                <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank">
                                    <!--i class="fa fa-file-pdf"></i> Descarga aquí tu manual de apoyo-->
                                </a>
                            </div>

                        </div>
                    </div>
                    <hr>

                        <div class="card mb-3">
                            <div class="card-header"><i class="far fa-clipboard"></i> Personas en ACTON</div>
                            <div class="card-body">
                                <form  action="/usuarios/seguir/" method="GET">
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
                                            <button class="btn btn-primary" @click="buscar" :disabled=" buscando" class="col-sm-12">
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
                                <div class="col-12 col-sm-12 col-md-6">
                                    <h6>Estas son las personas que han usado tu código de referencia: </h6>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 d-flex justify-content-end">
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
                                <tr v-if="referencias.length==0">
                                    <td>[Todavía no se ha utilizado tu referencia]</td>
                                </tr>
                            </table>
                            <div class="float-right">
                                <paginador ref="paginador" :url="'{{url('/usuarios/referencias')}}'" @loaded="loaded"></paginador>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modal ref="pagosModal" :title="'Reinscripciones de '+nombre" @ok="refs.pagosModal.closeModal()">
                <table class="table table-sm">
                    <tr v-for="(pago, ipago) in pagos" v-if="ipago > 0">
                        <td>@{{ ipago }}</td>
                        <td><fecha :fecha="pago.created_at"></fecha></td>
                        <td v-if="pago.pagado==1">
                            Pagado <i class="fa fa-check"></i>
                        </td>
                        <td v-else>
                            Pendiente <i class="fa fa-minus"></i>
                        </td>
                    </tr>
                </table>
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
                        estado: '0',
                        colonia: '0',
                        tiendagym: '0',
                        conexion: '0',
                        ingresadosReto: '',
                        codigo_personal: ''
                    },
                    buscando: false,
                    montopago: this.monto,
                    saldochk: false,
                    dias: 24,
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],

                    tiendas:[],
                    conexiones:[],
                }},
            methods: {
                loaded: function (referencias) {
                    this.referenciados = referencias;
                },
                buscar: function () {
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
                    this.buscando = false;
                },
                terminado: function () {
                    window.location.href = "{{url('/home')}}";
                },
                verPagos: function (referencia) {
                    let vm = this;
                    this.nombre = referencia.name;
                    axios.get('{{url('/verPagos/')}}/'+referencia.id).then(function (response) {
                        vm.pagos = response.data;
                        vm.$refs.pagosModal.showModal();
                    });
                },
                check: function(e) {
                    console.log(this.saldochk)
                    if (this.dias == 14){
                        if (this.saldochk){
                            if(this.saldo > 500){
                                this.montopago = 0
                            }else{
                                this.montopago = 500-this.saldo
                            }
                        }else{
                            this.montopago = 500
                        }
                    }
                    if (this.dias == 28){
                        if (this.saldochk){
                            if(this.saldo > 1000){
                                this.montopago = 0
                            }else{
                                this.montopago = 1000-this.saldo
                            }
                        }else{
                            this.montopago = 1000
                        }
                    }
                    if (this.dias == 56){
                        if (this.saldochk){
                            if(this.saldo > 1000){
                                this.montopago = 0
                            }else{
                                this.montopago = 2000-this.saldo
                            }
                        }else{
                            this.montopago = 2000
                        }
                    }
                    if (this.dias == 84){
                        if (this.saldochk){
                            if(this.saldo > 3000){
                                this.montopago = 0
                            }else{
                                this.montopago = 3000-this.saldo
                            }
                        }else{
                            this.montopago = 3000
                        }
                    }
                    if(this.montopago == 0){
                        $("#pagarceros").show();
                    }
                    this.saveDiasNuevo();
                },
                diasChange: function () {
                    if (this.dias == 14){
                        if (this.saldochk){
                            if(this.saldo > 500){
                                this.montopago = 0
                            }else{
                                this.montopago = 500-this.saldo
                            }
                        }else{
                            this.montopago = 500
                        }
                    }
                    if (this.dias == 28){
                        if (this.saldochk){
                            if(this.saldo > 1000){
                                this.montopago = 0
                            }else{
                                this.montopago = 1000-this.saldo
                            }
                        }else{
                            this.montopago = 1000
                        }
                    }
                    if (this.dias == 56){
                        if (this.saldochk){
                            if(this.saldo > 1000){
                                this.montopago = 0
                            }else{
                                this.montopago = 2000-this.saldo
                            }
                        }else{
                            this.montopago = 2000
                        }
                    }
                    if (this.dias == 84){
                        if (this.saldochk){
                            if(this.saldo > 3000){
                                this.montopago = 0
                            }else{
                                this.montopago = 3000-this.saldo
                            }
                        }else{
                            this.montopago = 3000
                        }
                    }
                    if(this.montopago == 0){
                        $("#pagarceros").show();
                    }
                    this.saveDiasNuevo();
                },
                saveDiasNuevo: function(){
                    if (this.saldochk) {
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias, {saldo: this.saldo, operacion: 'resta'}).then(function (response) {
                        });
                    }else{
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias, {saldo: this.saldo, operacion: 'suma'}).then(function (response) {
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
                    axios.post('{{url('/refrendar_ceros/')}}/', {dias: this.dias}).then((response) => {
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                }
            },
            mounted: function () {
                $("#pagarceros").hide();
                this.filtros.referencia = this.usuario.referencia;
                this.buscar();
                this.getEstados();
                this.getTiendas();
                @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                    this.$refs.cobro.configurar(
                    this.usuario.name,
                    this.usuario.last_name,
                    this.usuario.email,
                    this.usuario.telefono,
                    this.usuario.codigo,
                    this.usuario.referenciado
                );
                @endif
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
