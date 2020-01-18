@extends('layouts.mail')
@section('styles')
    <style>
        .imagen{
            position: absolute;
        }
        p{
            position: absolute;
            z-index: 2;
        }
    </style>
@endsection
@section('content')

    <div style="display: inline;">
        <div class="opps" v-if="pago=='oxxo'&&response.referencia!=''">
            <div class="opps-header">
                <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                <div class="opps-info">
                    <div class="opps-brand">
                        <img src="{{asset("img/$orden->origen.png")}}" alt="{{$orden->origen}}" width="100">
                    </div>
                    <div class="opps-ammount">
                        <h3>Monto a pagar</h3>
                        <h2>$ {{$orden->monto}} <sup>MXN</sup></h2>
                    </div>
                </div>
                <div class="opps-reference">
                    <h3>Referencia</h3>
                    <h1 class="reference">{{ $orden->referencia }}</h1>
                    <div>
                        <img alt="referencia" align="center"  width="300" style="display: block; margin:auto; float: none !important;"
                            src="{{ $message->embedData(base64_decode(Milon\Barcode\DNS1D::getBarcodePNG($orden->referencia, "C128")), 'logo.png') }}">
                    </div>
                </div>
                <p>Este código es válido las siguientes 24 horas.</p>
            </div>
            <div class="opps-instructions">
                <h3>Instrucciones</h3>
                <ol>
                    @if($orden->origen=='oxxo')
                    <li>Acude a la tienda OXXO de tu preferencia. <a
                                href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala
                            aquí</a>.
                    </li>
                    <li>Indica en caja que quieres ralizar un pago de <strong>OXXOPay</strong>.</li>
                    <li>Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la
                        pantalla de venta.
                    </li>
                    <li>Realiza el pago correspondiente con dinero en efectivo.</li>
                    @else
                        <li>Accede a tu banca en línea.</li>
                        <li>Da de alta la CLABE en esta ficha. El banco deberá de ser STP.</li>
                        <li>Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, de lo
                        contrario se rechazará el cargo.
                    </li>
                    @endif
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
@endsection