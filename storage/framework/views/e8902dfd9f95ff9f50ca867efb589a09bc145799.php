<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div style="display: inline;  "  >
        <div style="background-color: #edebec; padding: 60px 20px 20px 20px; font-size: 1.4em">
            <label><?php echo e($contacto->nombres." ".$contacto->apellidos); ?> tiene la siguiente duda</label>
            <p><?php echo e($contacto->mensaje); ?></p>
            <p><?php echo e($contacto->email." ".$contacto->telefono); ?></p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/correo/duda.blade.php ENDPATH**/ ?>