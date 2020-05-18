Bienvenido al Reto ACTON
<?php echo e($usuario->name); ?> <?php echo e($usuario->last_name); ?>

Ya puedes iniciar sesión en Acton:
correo: <?php echo e($usuario->email); ?>

<?php if($usuario->pass!=''): ?>
    contraseña: <?php echo e($usuario->pass); ?>


    NOTA: Recuerda que tu contraseña se escribe con mayusculas, si deseas asignar una nueva contraseña, lo podrás hacer en la seccion "Mi cuenta"
<?php endif; ?>
Ingresa aquí : "<?php echo e(env("APP_URL")."/login"); ?>"<?php /**PATH /var/www/resources/views/correo/registro_plano.blade.php ENDPATH**/ ?>