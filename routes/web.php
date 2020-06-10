<?php

use App\Provinsi;
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
});

Route::get('/about', function () {
    return view('about');
});

Route::group(['prefix'=>'admin','as'=>'admin.'],function ()
{
    Auth::routes(['register'=>false]);
    Route::group(['middleware'=>['web','auth']],function(){
        Route::get('/home', 'HomeController@index')->name('home');
        Route::resource('provinsi','ProvinsiController');
        Route::resource('kota','KotaController');
        Route::resource('kecamatan','KecamatanController');
        Route::resource('kelurahan','KelurahanController');
        Route::resource('klaster','KlasterController');
        Route::resource('pasien','PasienController');
        Route::resource('user','UserController');
        Route::get('laporan/{menu}','LaporanController@index')->name('laporan.index');
        Route::get('laporan/{menu}/export','LaporanController@export')->name('laporan.export');
        Route::post('laporan/{menu}/export','LaporanController@export')->name('laporan.export');
    });
    
    
});

Route::group(['prefix'=>'select2'],function(){
    Route::get('kota/{provinsi_id}','KotaController@searchSelect2');
    Route::get('kecamatan/{kota_id}','KecamatanController@searchSelect2');
    Route::get('kelurahan/{kecamatan_id}','KelurahanController@searchSelect2');
    Route::get('pasien','PasienController@searchSelect2');
});


