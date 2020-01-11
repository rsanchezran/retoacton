@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo enlace de verificacione ha sido enviado a tu dirrección de correo.') }}
                        </div>
                    @endif

                    {{ __('Antes de continuar, por favor verifica tu correo para el link de verificación.') }}
                    {{ __('Si tu no recibes el correo') }}, <a href="{{ route('verification.resend') }}">{{ __('clikc aqui para pedir otro') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
