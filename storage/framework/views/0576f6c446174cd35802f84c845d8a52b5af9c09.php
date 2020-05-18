<?php $__env->startSection('styles'); ?>
    <style>
        .imagen{
            position: absolute;
        }
        p{
            position: absolute;
            z-index: 2;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div style="display: inline;">
        <div style="background-color: #edebec; padding: 60px 20px 20px 20px; font-size: 1.4em">
            Bienvenido al Reto ACTON <br><?php echo e($usuario->name); ?> <?php echo e($usuario->last_name); ?> <br>
            Ya puedes iniciar sesión en Acton:<br>
            correo: <?php echo e($usuario->email); ?> <br> <br>
            <?php if($usuario->pass!=''): ?>
                contraseña: <?php echo e($usuario->pass); ?>

                <br>
                <br>
                <span style="color: red;" >NOTA:</span>Recuerda que tu contraseña se escribe con mayusculas, si deseas asignar una nueva contraseña, lo podrás hacer en la seccion "Mi cuenta"
            <?php endif; ?>
            <br>
            <div style="padding-top:10px; margin: auto;">
                <a style=" padding: 10px; background-color: #007fdc; color:#FFF;" href="<?php echo e(env("APP_URL")."/login"); ?>">Ingresa aquí</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/correo/registro.blade.php ENDPATH**/ ?>