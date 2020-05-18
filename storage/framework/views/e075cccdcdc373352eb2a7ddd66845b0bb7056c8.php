<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <!-- Scripts -->
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('js/multiitemcarousel.js')); ?>"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo e(asset('img/favicon.png')); ?>"/>
    <style>
        @font-face {
            font-family: unitext;
            src: url("<?php echo e(asset('fonts/unitext.ttf')); ?>");

        }

        @font-face {
            font-family: unitext_light;
            src: url("<?php echo e(asset('fonts/unitext thin.ttf')); ?>");

        }

        @font-face {
            font-family: unitext_bold_cursive;
            src: url("<?php echo e(asset('fonts/unitext bold cursive.ttf')); ?>");
        }

        @font-face {
            font-family: unitext_cursive;
            src: url("<?php echo e(asset('fonts/unitext cursive.ttf')); ?>");
        }

        #app {
            min-height: 100%;
            font-family: unitext;
        }

        .thin {
            font-family: unitext_light;
        }

        footer {
            background-color: #007fdc;
        }

        .links > a {
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
        }

        input[type=text], input[type=email] {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
            width: 90%;
            box-sizing: border-box;
            color: #2C3E50;
            font-size: 13px;
        }

        #logo {
            position: absolute;
            top: 10px;
            left: 40px;
        }

        .navbar {
            padding: 40px;
            box-shadow: none;
            background-image: url('<?php echo e(asset('/img/header_back2.png')); ?>');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        main {
            margin-top: 0 !important;
        }

        .navbar-toggler {
            display: block;
            margin: 20px auto 0 auto;
        }

        #_op_data_r, #_op_data_antifraud {
            display: none;
        }

        .navbar-toggler {
            border: 0 !important;
        }

        #onda {
            width: 100%;
            position: absolute;
            top: 10px;
            left: 20px;
            z-index: 4;
        }

        @media  only screen and (max-width: 420px) {
            .navbar {
                padding: 0;
                height: 110px;
            }

            .navbar-brand {
                padding: 0;
                height: 60px;
            }

            .navbar-nav {
                padding: 0;
            }

            .navbar-toggler {
                position: absolute;
                top: 50px;
                right: 2px;
            }

            .nav-item a {
                padding: 1px;
            }
        }
    </style>
    <?php echo $__env->yieldContent('header'); ?>
</head>
<body class="h-100">
<div id="app" class="d-flex flex-column">
    
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel d-flex justify-content-between">
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <img src="<?php echo e(asset('img/header.png')); ?>" width="250" id="logo">
        </a>
        <div class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item links">
                    <a href="<?php echo e(route('login')); ?>">Acceso a miembros</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if(auth()->guard()->guest()): ?>
                    <li class="nav-item links">
                        <a href="<?php echo e(route('login')); ?>">Acceso a miembros</a>
                    </li>
                    <?php else: ?>
                        <li class="nav-item links">
                            <a href="<?php echo e(url('/home')); ?>">Acceso a miembros</a>
                        </li>
                        <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="d-flex flex-column flex-grow-1 position-relative">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
<footer>
    <div class="container">
        <div class="d-flex flex-wrap">
            <div class="col-sm-3">
                <h5>ATENCIÓN A CLIENTES</h5>
                <ul class="list-unstyled">
                    <?php if(\Illuminate\Support\Facades\Auth::guest()): ?>
                        <li><a href="<?php echo e(url('contacto')); ?>"><i class="fa fa-pencil"></i> Dudas</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo e(url('dudas')); ?>"><i class="fa fa-pencil"></i> Dudas</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>SÍGUENOS EN</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com/FitnessPipoLandin"><i class="fab fa-facebook-square"></i>
                            Facebook</a></li>
                    <li><a href="https://www.instagram.com/pipolandin"><i class="fab fa-instagram"></i> Instagram</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>MÉTODOS DE PAGO</h5>
                <ul class="list-unstyled">
                    <li><i class="fab fa-cc-visa"></i> Visa</li>
                    <li><i class="fab fa-cc-mastercard"></i> Mastercard</li>
                    <li><i class="fab fa-paypal"></i> Paypal</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <img src="<?php echo e(asset('img/retoactonblanco.png')); ?>" width="150">
            </div>
        </div>
    </div>
</footer>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/resources/views/layouts/welcome.blade.php ENDPATH**/ ?>