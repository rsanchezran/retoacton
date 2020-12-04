@extends('layouts.app')
@section('header')
    <style>
    </style>
@endsection
@section('content')

<div id="vue">
    <div class="container">
        <contactos :medios="{{$medios}}"></contactos>
    </div>
</div>
<template id="contactos-template">
    <div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap">
                  <input class="form-control col-6" v-model="informacion.codigo" placeholder="REFERENCIA" maxlength="7">
                  <form-error name="codigo" :errors="errors"></form-error>
                  <input type="email" class="form-control" placeholder="Correo electrónico" v-model="informacion.email"
                  @blur="saveContactoTienda" @keypress.enter="saveContactoTienda">
                  <form-error name="email" :errors="errors"></form-error>
                  <div class="mt-4 text-left">
                      <button class="btn btn-primary acton" @click="saveContactoTienda" :disabled="loading">
                          Continuar
                          <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                          <i v-else class="fa fa-shopping-cart"></i>
                      </button>
                  </div>
                </div>
            </div>
        </div>

        <div class="card">
          <table class="table">
              <tr class="table-header">
                  <th></th>
                  <th>Codigo</th>
                  <th>Correo electrónico</th>
                  <th>Fecha de registro</th>
              </tr>
          @forelse ($codigos as $u)
          <tr>
              <th>
                  <i v-if="contacto.contacto" class="fa fa-user text-info"></i>
                  <i v-else class="fa fa-user text-default"></i>
              </th>
              <td>{{ $u->codigo }}</td>
              <td>{{ $u->email }}</td>
              <td class="text-center">{{ $u->created_at }}</td>
          </tr>
          @empty
          <tr v-if="contactos.data.length==0">
              <td colspan="7">[No hay datos que mostrar]</td>
          </tr>
          @endforelse
          </table>
        </div>
      </div>
  </template>



@endsection
@section('scripts')
    <script>

        Vue.component('contactos', {
            template: '#contactos-template',
            props:['medios'],
            data:function () {
              return {
                  errors: [],
                  buscando:false,
                  filtros:{
                      email:'',
                      nombres:'',
                      medio:''
                  },
                  contactos:{
                      data:[]
                  },
                  loading: false,
                  informacion: {
                      nombres: '',
                      apellidos: '',
                      email: '',
                      telefono: '',
                      medio: '',
                      tipo: ''
                  },
                  mensaje:'',
                  contacto:{},
                  sent: false,
                  srcVideo: '',
                  features: {
                      comidas: true,
                      entrenamiento: true,
                      suplementos: true,
                      videos: true
                  },
                  encontrado: null,
                  referencia: '',
                  original: '0',
                  monto: '0',
                  descuento: '0',
                  mensaje: ''
                }

            },
            methods: {
              terminado: function () {
                  window.location.href = "{{url('/login')}}";
              },
              saveContactoTienda: function () {
                  let vm = this;
                  this.loading = true;
                  this.errors = {};
                  this.informacion.email = this.informacion.email.trim();
                  this.informacion.codigo = this.informacion.codigo.trim();
                  if (this.informacion.email==''){
                      this.errors.email = ['El correo electrónico es obligatorio'];
                  }
                  if (this.informacion.codigo==''){
                      this.errors.codigo = ['El codigo de referencia es obligatorio'];
                  }
                  if (Object.keys(this.errors).length == 0) {
                      axios.post("{{url("configuracion/saveContactoTienda")}}", this.informacion).then(function (response) {
                          vm.sent = true;
                          vm.loading = false;

                          vm.mensaje = response.data.mensaje;
                      }).catch(function (error) {
                          vm.sent = false;
                          vm.loading = false;
                          vm.errors = error.response.data.errors;
                      });
                  }else{
                      this.sent = false;
                      this.loading = false;
                  }
              }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
