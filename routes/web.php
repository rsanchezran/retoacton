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
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

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
Route::get('buscarReferenciaTienda/{referencia}/{email}', 'Auth\RegisterController@buscarReferenciaTienda');//AQUI

Route::post('/nuevopago', 'PagoController@nuevoPago');
Route::get('/etapa1/{email}', 'HomeController@etapa1');
Route::get('/etapa2/{email}', 'HomeController@etapa2');
Route::get('/etapa3/{email}', 'HomeController@etapa3');
Route::get('/terminos', 'HomeController@terminos');
Route::get('/contacto/', 'HomeController@contacto');
Route::post('/contacto', 'HomeController@contactoSave');
Route::get('/home', 'HomeController@home')->middleware('pago');

Route::group(['prefix' => '/', 'middleware' => ['auth', 'pago']], function () {
    Route::get('dudas', 'HomeController@dudas');
    Route::post('dudas', 'HomeController@saveDudas');
    Route::get('verPagos/{user}', 'HomeController@verPagos');
});
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
    Route::post('contactos/enviarCorreos', 'ConfiguracionController@enviarCorreos');
    Route::get('videos', 'ConfiguracionController@videos');
    Route::get('programa', 'ConfiguracionController@programa');
    Route::get('programa/getSemanaEjercicios/{semana}', 'ConfiguracionController@getSemanaEjercicios');
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
    Route::post('categoria', 'ConfiguracionController@saveCategoria');
    Route::get('registro-tiendas', 'ConfiguracionController@agregarTienda');//AQUI
    Route::post('saveContactoTienda', 'ConfiguracionController@saveContactoTienda');//AQUI
    Route::get('registro-usuario', 'ConfiguracionController@agregarUsuarioNuevo');//AQUI
    Route::post('saveContactoUsuarioNuevo', 'ConfiguracionController@saveContactoUsuarioNuevo');//AQUI
    Route::get('generar-codigo', 'ConfiguracionController@generarCodigo');//AQUI
    Route::get('pagar-tienda/{usuario}', 'ConfiguracionController@pagarTienda');//AQUI
});

Route::group(['prefix' => 'suplementos', 'middleware' => ['auth', 'pago']], function (){
    Route::get('/', 'SuplementosController@index');
    Route::post('/save', 'SuplementosController@save');
});

Route::group(['prefix' => 'usuarios', 'middleware'=>['auth', 'pago']], function (){
    Route::get('/','UserController@index');
    Route::get('buscar','UserController@buscar');
    Route::post('seguir/','UserController@listado');
    Route::post('seguir/{id}','UserController@seguir');
    Route::post('dejar_seguir/{id}','UserController@dejar_seguir');
    Route::post('comentarios/{dia}/{id}','UserController@comenatarios');
    Route::post('likes/{dia}/{id}','UserController@likes');
    Route::post('setlikes/{dia}/{id}','UserController@setlikes');
    Route::post('comentario_nuevo/{dia}/{id}','UserController@comentarioNuevo');
    Route::post('getEstados/','UserController@getEstados');
    Route::post('getCiudades/','UserController@getCiudades');
    Route::post('getCP/','UserController@getCPs');
    Route::post('getColonias/','UserController@getColonias');
    Route::post('getTiendas/','UserController@getTiendas');
    Route::post('guardaUbicacion/','UserController@guardaUbicacion');
    Route::get('buscarSeguir','UserController@buscarSeguir');
    Route::get('imagenes/{id}', 'UserController@imagenes');
    Route::get('encuesta/{id}', 'UserController@showEncuesta');
    Route::post('pagar', 'UserController@pagar');
    Route::get('referencias', 'UserController@getReferencias');
    Route::post('bajar', 'UserController@bajar');
    Route::get('verReferencias', 'UserController@verReferencias');
    Route::get('verPagos', 'UserController@verPagos');
    Route::get('verCompras', 'UserController@verCompras');
    Route::get('verComprasByReferencia', 'UserController@verComprasByReferencia');
    Route::post('cambiarDias', 'UserController@cambiarDias');
    Route::get('exportar/{filtros}', 'UserController@exportar');
    Route::get('getSemana/{usuario}/{semana}', 'UserController@getSemana');
    Route::get('actualizar_dias/{dias}', 'UserController@actualizarDias');
});

Route::group(['prefix'=>'reto', 'middleware'=>['auth', 'pago'] ],function (){
    Route::get('comenzar', 'RetoController@comenzar');
    Route::get('cliente', 'RetoController@cliente');
    Route::get('programa', 'RetoController@programa');
    Route::get('getSemanaPrograma/{semana}', 'RetoController@getSemanaPrograma');
    Route::get('dia/{dia}/{genero}/{objetivo}', 'RetoController@dia');
    Route::get('pdf/{dia}/{genero}/{objetivo}/{dieta}/{lugar}', 'RetoController@pdf');
    Route::get('getImagen/{carpeta}/{id}/{imagen}/{otro?}', 'RetoController@getImagen');
    Route::get('getImagen4/{carpeta}/4/{id}/{imagen}/{otro?}', 'RetoController4@getImagen');//AQUI
    Route::get('getAudio/{carpeta}/{id}/{imagen}/{otro?}', 'RetoController@getAudio');
    Route::post('saveImagen', 'RetoController@saveImagen');
    Route::post('saveAudio', 'RetoController@saveAudio');
    Route::post('quitarAudio', 'RetoController@quitarAudio');
    Route::post('comentar', 'RetoController@comentar');
    Route::post('anotar', 'RetoController@anotar');
    Route::post('correo', 'RetoController@correo');
    Route::get('getDia/{dia}', 'RetoController@getDia');
    Route::get('configuracion', 'RetoController@index');
    Route::get('configuracion_4', 'RetoController4@index');//AQUI
    Route::get('getSemana/{usuario}/{semana}', 'RetoController@getSemana');
    Route::get('getSemanaCliente/{semana}', 'RetoController@getSemanaCliente');
});

Route::group(['prefix'=>'pago'], function (){
    Route::get('/', 'PagoController@index');
    Route::post('openpay', 'PagoController@openpay');
    Route::post('oxxo', 'PagoController@oxxo');
    Route::post('spei', 'PagoController@spei');
    Route::post('paypal', 'PagoController@paypal');
    Route::post('validarOpenpay', 'PagoController@validarOpenpay');
    Route::post('tarjeta', 'PagoController@tarjeta');
});

Route::group(['prefix'=>'encuesta', 'middleware'=>['auth']], function (){
    Route::get('/', 'HomeController@encuesta');
    Route::get('/pago', 'HomeController@encuesta');
    Route::post('/validarAbiertas', 'HomeController@validarAbiertas');
    Route::post('/save', 'HomeController@save');
});
