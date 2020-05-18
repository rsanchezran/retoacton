<?php $__env->startSection('header'); ?>
    <style>
        label {
            display: block;
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div id="vue" class="container">
        <temp-encuesta :usuario="<?php echo e($usuario); ?>"></temp-encuesta>
    </div>
    <template id="temp">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Encuesta: <?php echo e($usuario->name.' '.$usuario->last_name); ?></h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column">
                    <div v-for="(pregunta,index) in usuario.encuesta">
                        <label>{{ index+1 }}.- {{ pregunta.pregunta }}:</label>
                        <span v-if="pregunta.multiple==0 || pregunta.multiple==null">{{ pregunta.respuesta}}</span>
                        <div v-else>
                            <span v-for="(respuesta, index) in pregunta.respuesta" >
                                {{ respuesta + ((pregunta.respuesta.length-1)==index?'.':',')}}
                            </span>
                        </div>
                        <hr>
                    </div>
                    <div v-if="usuario.encuesta.length==0" align="center">
                        <h5>[No hay datos para mostrar]</h5>
                    </div>
                </div>
            </div>
        </div>
    </template>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>

        Vue.component('temp-encuesta', {
            template: '#temp',
            props: ['usuario'],
        })

        var vue = new Vue({
            el: '#vue'
        });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/users/encuesta.blade.php ENDPATH**/ ?>