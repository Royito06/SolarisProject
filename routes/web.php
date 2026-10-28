<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('/');
//Route::redirect('/', '/register')->middleware('guest')->name('/');

/* Route::get('send', function () {
    mail('uncorreomasparapruebas@gmail.com', "orale", "aloooooo");
})->name('send'); */

Auth::routes(['verify' => true]);
Route::get('/register/verify/{code}', 'HomeController@verify');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{code}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::resource('asistentes', 'AsistenteController');
Route::get('/documentos-rechazados', 'AsistenteController@indexRechazados')->name('asistentes.rechazado');
Route::put('/documentos-rechazados/{asistente}', 'DocumentosController@actualizarDocumentosRechazados')->name('asistentes.actualizarRechazados');


// Route::prefix('asistentes')->group(function(){
//     Route::get('/', 'AsistenteController@index')->name('asistentes.index');
//     Route::post('/', 'AsistenteController@store')->name('asistentes.store');
//     Route::get('/create', 'AsistenteController@create')->name('asistentes.create');
//     Route::put('/{asistente}', 'AsistenteController@update')->name('asistentes.update');
//     Route::get('/{asistente}', 'AsistenteController@show')->name('asistentes.show');
//     Route::get('/{asistente}/edit', 'AsistenteController@edit')->name('asistentes.edit');
//     Route::get('/documentos-rechazados', 'AsistenteController@actualizarDocumentosRechazados')->name('asistentes.rechazado');
// });


//Route::resource('revisores', 'RevisoresController');

Route::prefix('revisores')->middleware('auth:revisor')->group(function () {

    // Solo Administrador: gestión de revisores
    Route::middleware('rol:Administrador')->group(function () {
        Route::get('/', 'RevisoresController@index')->name('revisores.index');
        Route::post('/cargar-correos', 'RevisoresController@store')->name('revisores.store');
    });

    // Administrador y Revisor: validar asistentes
    Route::middleware('rol:Administrador,Revisor')->group(function () {
        Route::get('/{asistente}/validacion', 'ValidacionesController@edit')->name('validaciones.edit');
        Route::put('/{asistente}', 'ValidacionesController@update')->name('validaciones.update');
    });

    // Todos los roles: solo lectura
    Route::get('/lista-espera', 'RevisoresController@listaEspera')->name('revisores.lista-espera');
    Route::get('/lista-asistentes', 'RevisoresController@listaAsistentes')->name('revisores.lista-asistentes');
    Route::get('/lista-inscritos', 'RevisoresController@listaInscritos')->name('revisores.lista-inscritos');
});
Route::get('wsgetuserdata/{userID}', 'RevisoresController@wsGetUserData');

Route::get('admin', 'Auth\LoginRevisorController@showLoginForm')->middleware('guest:revisor');
Route::post('admin/login', 'Auth\LoginRevisorController@login')->name('admin.login');
Route::post('admin/logout', 'Auth\LoginRevisorController@logout')->name('admin.logout');

