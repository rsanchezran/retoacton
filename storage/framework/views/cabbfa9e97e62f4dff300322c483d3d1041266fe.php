Ficha digital. No es necesario imprimir.
Monto a pagar : $ <?php echo e($orden->monto); ?> MXN
Referencia : <?php echo e($orden->referencia); ?>

Este código es válido las siguientes 24 horas.
Instrucciones
<?php if($orden->origen=='oxxo'): ?>
    Acude a la tienda OXXO de tu preferencia.
    Indica en caja que quieres ralizar un pago de OXXOPay.
    Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la pantalla de venta.
    Realiza el pago correspondiente con dinero en efectivo.
<?php else: ?>
    Accede a tu banca en línea.
    Da de alta la CLABE en esta ficha. El banco deberá de ser STP.
    Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, de lo
        contrario se rechazará el cargo.
<?php endif; ?>
Al confirmar tu pago, el cajero te entregará un comprobante impreso. En el podrás verificar que se haya realizado correctamente. Conserva este comprobante de pago para cualquier aclaración.
Al completar estos pasos recibirás un correo de soporte@retoacton.com confirmando tu pago.ç
Una vez efectuado el pago, inmediatamente recibirás un correo con tu usuario y contraseña para que puedas acceder a tu cuenta, no es necesario enviar el comprobante de pago a ningún lado.
<?php /**PATH /var/www/resources/views/correo/ficha_plano.blade.php ENDPATH**/ ?>