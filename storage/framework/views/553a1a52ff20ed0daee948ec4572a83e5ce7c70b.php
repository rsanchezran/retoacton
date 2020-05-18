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
        <div class="opps" v-if="pago=='oxxo'&&response.referencia!=''">
            <div class="opps-header">
                <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                <div class="opps-info">
                    <div class="opps-brand">
                        <img src="<?php echo e(asset("img/$orden->origen.png")); ?>" alt="<?php echo e($orden->origen); ?>" width="100">
                    </div>
                    <div class="opps-ammount">
                        <h3>Monto a pagar</h3>
                        <h2>$ <?php echo e($orden->monto); ?> <sup>MXN</sup></h2>
                    </div>
                </div>
                <div class="opps-reference">
                    <h3>Referencia</h3>
                    <h1 class="reference"><?php echo e($orden->referencia); ?></h1>
                    <div>
                        <img alt="referencia" align="center"  width="300" style="display: block; margin:auto; float: none !important;"
                            src="<?php echo e($message->embedData(base64_decode(Milon\Barcode\DNS1D::getBarcodePNG($orden->referencia, "C128")), 'logo.png')); ?>">
                    </div>
                </div>
                <p>Este código es válido las siguientes 24 horas.</p>
            </div>
            <div class="opps-instructions">
                <h3>Instrucciones</h3>
                <ol>
                    <?php if($orden->origen=='oxxo'): ?>
                    <li>Acude a la tienda OXXO de tu preferencia. <a
                                href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala
                            aquí</a>.
                    </li>
                    <li>Indica en caja que quieres ralizar un pago de <strong>OXXOPay</strong>.</li>
                    <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la
                        pantalla de venta.
                    </li>
                    <li>Realiza el pago correspondiente con dinero en efectivo.</li>
                    <?php else: ?>
                        <li>Accede a tu banca en línea.</li>
                        <li>Da de alta la CLABE en esta ficha. El banco deberá de ser STP.</li>
                        <li>Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, de lo
                        contrario se rechazará el cargo.
                    </li>
                    <?php endif; ?>
                    <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás
                            verificar que se haya realizado correctamente.</strong> Conserva este comprobante de
                        pago para cualquier aclaración.
                    </li>
                </ol>
                <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>soporte@retoacton.com</strong>
                    confirmando tu pago.<br><br>Una vez efectuado el pago, inmediatamente recibirás un correo con tu
                    usuario y contraseña para que puedas acceder a tu cuenta,
                    no es necesario enviar el comprobante de pago a ningún lado.
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/correo/ficha.blade.php ENDPATH**/ ?>