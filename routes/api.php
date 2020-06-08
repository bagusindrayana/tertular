<?php

use App\Helpers\Helper;
use App\Provinsi;
use App\Helpers\Turf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('map-provinsi-geojson','ApiController@getProvinsiMap');
Route::get('map-kabkota-geojson','ApiController@getKotaMap');
Route::get('map-sebaran-geojson','ApiController@getSebaranPoint');
Route::get('/cek-koordinat/{latitude}/{longitude}', 'ApiController@cekKoordinat');


