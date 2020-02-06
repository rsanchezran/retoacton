<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('/', 'HomeController@index')->name('index');

Route::get('/getImagen/{image}', 'HomeController@getImage');
Route::get('/getTestimonio/{image}', 'HomeController@getTestimonio');
Route::get('/getCombo/{image}', 'HomeController@getCombo');
Route::get('/getVideo/{video}/{random}', 'HomeController@getVideo');

Route::get('unsuscribe/{email}', 'Auth\RegisterController@unsuscribe');
Route::post('unsuscribe', 'Auth\RegisterController@unsuscribeSave');
Route::post('create', 'Auth\RegisterController@create');
Route::post('saveContacto', 'Auth\RegisterController@saveContacto');
Route::post('saveObjetivo', 'Auth\RegisterController@saveObjetivo');
Route::post('savePeso', 'Auth\RegisterController@savePeso');
Route::post('webhook', 'Auth\RegisterController@webhook');
Route::get('buscarReferencia/{referencia}', 'Auth\RegisterController@buscarReferencia');

Route::post('/nuevopago', 'PagoController@nuevoPago');
Route::get('/etapa1/{id}', 'HomeController@etapa1');
Route::get('/etapa2/{id}', 'HomeController@etapa2');
Route::get('/etapa3/{id}', 'HomeController@etapa3');
Route::get('/terminos', 'HomeController@terminos');
Route::get('/contacto', 'HomeController@contacto');
Route::post('/contacto', 'HomeController@contactoSave');
Route::get('/home', 'HomeController@home')->middleware('pago');

Route::group(['prefix' => 'cuenta', 'middleware' => ['auth', 'pago']], function () {
    Route::get('/', 'CuentaController@index');
    Route::post('/', 'CuentaController@save');
    Route::post('subirFoto', 'CuentaController@subirFoto');
    Route::get('getFotografia/{id}/{random}', 'CuentaController@getFotografia');
    Route::post('cambiarModo', 'CuentaController@cambiarModo');
});

Route::group(['prefix' => 'configuracion', 'middleware' => ['auth', 'pago']], function () {
    Route::get('contactos', 'ConfiguracionController@contactos');
    Route::get('contactos/buscar', 'ConfiguracionController@buscarContactos');
    Route::post('contactos/enviarCorreo', 'ConfiguracionController@enviarCorreo');
    Route::get('contactos/getMensaje/{contacto}', 'ConfiguracionController@getMensaje');
    Route::post('contactos/quitar', 'ConfiguracionController@quitarContacto');
    Route::get('contactos/exportar/{filtros}', 'ConfiguracionController@exportarContactos');
    Route::get('videos', 'ConfiguracionController@videos');
    Route::get('programa', 'ConfiguracionController@programa');
    Route::get('dia/{dia}/{genero}/{objetivo}', 'ConfiguracionController@dia');
    Route::get('getDia/{dia}/{genero}/{objetivo}', 'ConfiguracionController@getDia');
    Route::get('ejercicio/{categoria}/{ejercicio}', 'ConfiguracionController@getEjercicio');
    Route::post('ejercicios', 'ConfiguracionController@getEjercicios');
    Route::post('video', 'ConfiguracionController@saveVideo');
    Route::post('ejercicio', 'ConfiguracionController@saveEjercicio');
    Route::post('dia', 'ConfiguracionController@saveDia');
    Route::post('suplementos', 'ConfiguracionController@suplementos');
    Route::post('quitarEjercicio', 'ConfiguracionController@quitarEjercicio');
    Route::get('getEjerciciosCategoria/{categoria}', 'ConfiguracionController@getEjerciciosCategoria');
    Route::get('getVideosPendientes', 'ConfiguracionController@getVideosPendientes');
});

Route::group(['prefix' => 'suplementos', 'middleware' => ['auth', 'pago']], function (){
    Route::get('/', 'SuplementosController@index');
    Route::post('/save', 'SuplementosController@save');
});

Route::group(['prefix' => 'usuarios', 'middleware'=>['auth', 'pago']], function (){
    Route::get('/','UserController@index');
    Route::get('buscar','UserController@buscar');
    Route::get('imagenes/{id}', 'UserController@imagenes');
    Route::get('encuesta/{id}', 'UserController@showEncuesta');
    Route::post('pagar', 'UserController@pagar');
    Route::get('referencias', 'UserController@getReferencias');
    Route::post('bajar', 'UserController@bajar');
    Route::post('verReferencias', 'UserController@verReferencias');
    Route::post('verPagos', 'UserController@verPagos');
    Route::post('verCompras', 'UserController@verCompras');
    Route::post('cambiarDias', 'UserController@cambiarDias');
    Route::get('exportar/{filtros}', 'UserController@exportar');
    Route::get('getSemana/{usuario}/{semana}', 'UserController@getSemana');
});

Route::group(['prefix'=>'reto', 'middleware'=>['auth', 'pago'] ],function (){
    Route::get('cliente', 'RetoController@cliente');
    Route::get('programa', 'RetoController@programa');
    Route::get('dia/{dia}/{genero}/{objetivo}', 'RetoController@dia');
    Route::get('pdf/{dia}/{genero}/{objetivo}/{dieta}/{lugar}', 'RetoController@pdf');
    Route::get('getImagen/{carpeta}/{id}/{imagen}/{otro?}', 'RetoController@getImagen');
    Route::get('getAudio/{carpeta}/{id}/{imagen}/{otro?}', 'RetoController@getAudio');
    Route::post('saveImagen', 'RetoController@saveImagen');
    Route::post('saveAudio', 'RetoController@saveAudio');
    Route::post('comentar', 'RetoController@comentar');
    Route::post('anotar', 'RetoController@anotar');
    Route::post('correo', 'RetoController@correo');
    Route::get('getDia/{dia}', 'RetoController@getDia');
    Route::get('configuracion', 'RetoController@index');
    Route::get('getSemana/{semana}', 'RetoController@getSemana');
    Route::get('getSemanaCliente/{semana}', 'RetoController@getSemanaCliente');
});

Route::group(['prefix'=>'pago'], function (){
    Route::get('/', 'PagoController@index');
    Route::post('openpay', 'PagoController@openpay');
    Route::post('oxxo', 'PagoController@oxxo');
    Route::post('spei', 'PagoController@spei');
    Route::post('paypal', 'PagoController@paypal');
    Route::post('validarOpenpay', 'PagoController@validarOpenpay');
});

Route::group(['prefix'=>'encuesta', 'middleware'=>['auth']], function (){
    Route::get('/', 'HomeController@encuesta');
    Route::get('/pago', 'HomeController@encuesta');
    Route::post('/validarAbiertas', 'HomeController@validarAbiertas');
    Route::post('/save', 'HomeController@save');
});
