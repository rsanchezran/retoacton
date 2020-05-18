<?php $__env->startSection('header'); ?>
    <style>
        #imagenes img {
            cursor: pointer;
            width:  180px;
            height: 180px;
            object-fit:scale-down;
        }

        #informacionModal .modal-content {
            min-width: 700px;
        }

        input[type="file"] {
            width: 120px;
        }

        #fotoModal .modal-content {
            background: none;
            text-align: center;
        }

        #fotoModal .modal-header {
            border-bottom: 0;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        audio {
            width: 100%;
        }

        #panel {
            position: fixed;
            padding: 10px;
            right: 20px;
            bottom: 120px;
            display: flex;
            flex-direction: column;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div id="vue">
        <div class="container">
            <dias :p_dias="<?php echo e($dias); ?>" :p_semana="<?php echo e($semana); ?>" :maximo="<?php echo e($maximo); ?>"
                :teorico="<?php echo e($teorico); ?>"></dias>
        </div>
    </div>

    <template id="dias-template">
        <div class="card">
            <div class="card-header">
                <span><i class="far fa-running"></i> Actividades del Reto Acton</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between col-10 col-sm-6 m-auto">
                    <button v-if="semana>1" class="btn btn-sm btn-light" @click="mostrarSemana(semana-1)">
                        <i v-if="semana>1" class="fa fa-arrow-left"></i>
                        <i v-else></i>
                    </button>
                    <i v-else></i>
                    <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana)">
                        <option v-for="s in p_semana" :value="s">Semana {{ s }}</option>
                    </select>
                    <button v-if="maximo>=semana * dias.length" class="btn btn-sm btn-light" @click="mostrarSemana(semana+1)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <i v-else></i>
                </div>
                <hr>
                <div class="d-flex flex-wrap" id="imagenes">
                    <div v-for="(dia, index) in dias" class="col-3">
                        <div class="card-header d-flex justify-content-between" style="padding: 5px 10px;">
                            <span>Día {{ dia.dia }}</span>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-default" @click="mostrarModal(dia)">
                                    <i class="fas fa-comment-lines"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="dia.subir" align="center">
                                <div>
                                    <label :for="'file'+index" class="custom-file-upload">
                                        <i class="fa fa-image"></i> Subir foto
                                    </label>
                                    <input :id="'file'+index" type="file" accept="image/png,image/jpg,image/jpeg"
                                           @change="agregarImagen(index, $event)" :disabled="loading">
                                </div>
                                <div>
                                    <label :for="'audio'+index" class="custom-file-upload">
                                        <i class="fa fa-microphone"></i> Subir audio
                                    </label>
                                    <input :id="'audio'+index" type="file" accept="audio/mp3"
                                           @change="agregarAudio(index, $event)" :disabled="loading">
                                </div>
                                <form-error :name="'imagen'+index" :errors="errors"></form-error>
                                <form-error :name="'audio'+index" :errors="errors"></form-error>
                            </div>
                            <div align="center">
                                <div v-if="dia.loading">
                                    <span >Estamos procesando el archivo, espera un momento porfavor...</span>
                                    <br>
                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                </div>
                                <img v-if="dia.imagen!='' && !dia.loading" :id="'img'+index" :src="dia.imagen"
                                     width="160"
                                     @click="mostrarImagen(dia.imagen)"/>
                                <br>
                                <br>
                                <audio controls v-if="dia.audio!=''">
                                    <source :src="dia.audio"/>
                                </audio>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="panel" v-if="maximo >= teorico && semana==p_semana">
                <button class="btn btn-sm btn-success" @click="agregarDia">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
            <modal id="informacionModal" ref="informacionModal" title="Recomendaciones del día" @ok="anotar()">
                <div>
                    <wysiwyg v-model="dia.comentarios"/>
                </div>
            </modal>
            <modal id="fotoModal" ref="fotoModal" :showfooter="false" :btncerrar="true" title="Foto">
                <div style="padding-top: 15px;">
                    <img :src="imagen" style="margin: auto; display: block" width="400">
                </div>
            </modal>
        </div>
    </template>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        Vue.component('dias', {
            template: '#dias-template',
            props: ['p_dias', 'p_semana','maximo','teorico'],
            data: function () {
                return {
                    loading: false,
                    semana: 1,
                    ultimo: 1,
                    dias: [],
                    imagen: '',
                    errors: [],
                    dia: {
                        comentarios: ''
                    }
                }
            },
            methods: {
                agregarImagen: function (index, event) {
                    let vm = this;
                    let fm = new FormData();
                    let file = event.target.files[0];
                    let dia = this.dias[index];
                    vm.loading = true;
                    dia.loading = true;
                    fm.append("imagen", file);
                    fm.append("dia", (7*(this.semana-1))+(index+1));
                    vm.errors = [];
                    vm.dias[index].error = false;
                    axios.post("<?php echo e(url('/reto/saveImagen')); ?>", fm).then(function (response) {
                        vm.loading = false;
                        dia.loading = false;
                        Vue.nextTick(function () {
                            dia.imagen = response.data.imagen;
                        });
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                        dia.loading = false;
                    });
                },
                agregarAudio: function (index, event) {
                    let vm = this;
                    let fm = new FormData();
                    let file = event.target.files[0];
                    let dia = this.dias[index];
                    vm.loading = true;
                    dia.loading = true;
                    fm.append("audio", file);
                    fm.append("dia", (7*(this.semana-1))+(index+1));
                    vm.errors = [];
                    vm.dias[index].error = false;
                    axios.post("<?php echo e(url('/reto/saveAudio')); ?>", fm).then(function (response) {
                        dia.loading = false;
                        vm.loading = false;
                        Vue.nextTick(function () {
                            dia.audio = response.data.audio;
                        });
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                        dia.loading = false;
                    });
                },
                mostrarModal: function (dia) {
                    this.dia = dia;
                    this.$refs.informacionModal.showModal();
                },
                mostrarImagen: function (imagen) {
                    this.imagen = imagen;
                    this.$refs.fotoModal.showModal();
                },
                anotar: function () {
                    let vm = this;
                    axios.post("<?php echo e(url('/reto/anotar')); ?>", {
                        dia: this.dia.dia,
                        comentarios: this.dia.comentarios
                    }).then(function (response) {
                        vm.$refs.informacionModal.working = false;
                        vm.$refs.informacionModal.closeModal();
                    }).catch(function (error) {
                        vm.$refs.informacionModal.working = false;
                        vm.errors = error.response.data.errors;
                    });
                },
                agregarDia: function () {
                    let ultimoDia=(this.semana-1)*7+this.dias.length+1;
                    this.dias.push({
                        dia:ultimoDia,
                        mostrar: true,
                        subir: true,
                        loading: false,
                        comentarios: '',
                        imagen: '',
                        audio: '',
                    });
                },
                mostrarSemana: function (semana) {
                    let vm = this;
                    axios.get('<?php echo e(url('/reto/getSemana/')); ?>/<?php echo e(\Illuminate\Support\Facades\Auth::user()->id); ?>/' + semana).then(function (response) {
                        vm.dias = response.data;
                        vm.semana = semana;
                        Vue.nextTick(function () {
                            $('.selectpicker').selectpicker('refresh');
                        });
                    });
                }
            },
            created: function () {
                this.dias = this.p_dias;
                this.semana = this.p_semana;
            }
        });

        let vue = new Vue({
            el: '#vue'
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/reto/configuracion.blade.php ENDPATH**/ ?>