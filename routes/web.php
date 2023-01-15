<?php

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


Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\DashboardController@index');

    //Masyarakat
    Route::get('/masyarakat', 'App\Http\Controllers\MasyarakatController@index');
    Route::get('/masyarakat/download', 'App\Http\Controllers\MasyarakatController@download');
    Route::post('/masyarakat/tambah', 'App\Http\Controllers\MasyarakatController@store');
    Route::get('/masyarakat/{masyarakat}/edit', 'App\Http\Controllers\MasyarakatController@edit');
    Route::put('/masyarakat/{masyarakat}', 'App\Http\Controllers\MasyarakatController@update');
    Route::delete('/masyarakat/{masyarakat}', 'App\Http\Controllers\MasyarakatController@destroy');

    //Kriteria
    Route::get('/kriteria', 'App\Http\Controllers\KriteriaController@index')->name('kriteria.index');
    Route::get('/kriteria/{kriteria}/pembobotan', 'App\Http\Controllers\KriteriaController@pembobotan')->name('kriteria.pembobotan');
    Route::post('/kriteria/{kriteria}/pembobotan', 'App\Http\Controllers\KriteriaController@storePembobotan')->name('kriteria.storePembobotan');
    Route::post('/kriteria/{kriteria}/pembobotan-lmu', 'App\Http\Controllers\KriteriaController@storePembobotanLmu')->name('kriteria.storePembobotanLmu');
    Route::get('/kriteria/download', 'App\Http\Controllers\KriteriaController@export')->name('kriteria.export');
    Route::post('/kriteria/import', 'App\Http\Controllers\KriteriaController@import')->name('kriteria.import');
    Route::post('/kriteria/tambah', 'App\Http\Controllers\KriteriaController@store')->name('kriteria.store');
    Route::put('/kriteria/{kriteria}', 'App\Http\Controllers\KriteriaController@update')->name('kriteria.update');
    Route::delete('/kriteria/{kriteria}', 'App\Http\Controllers\KriteriaController@destroy')->name('kriteria.destroy');

    //Sub Kriteria
    Route::prefix('kriteria/{kriteria}')->group(function () {
        Route::get('sub-kriteria/export', 'App\Http\Controllers\SubKriteriaController@export')->name('sub-kriteria.export');
        Route::post('sub-kriteria/import', 'App\Http\Controllers\SubKriteriaController@import')->name('sub-kriteria.import');
        Route::resource('sub-kriteria', 'App\Http\Controllers\SubKriteriaController');
    });


    //Data PKH
    Route::get('pkh-record/export', 'App\Http\Controllers\PkhRecordController@export')->name('pkh-record.export');
    Route::post('pkh-record/import', 'App\Http\Controllers\PkhRecordController@import')->name('pkh-record.import');
    Route::resource('pkh-record', 'App\Http\Controllers\PkhRecordController');

    //Pembobotan
    Route::get('pembobotan', 'App\Http\Controllers\PembobotanController@index')->name("pembobotan.index");
    Route::post('pembobotan', 'App\Http\Controllers\PembobotanController@store')->name("pembobotan.store");
    Route::post('pembobotan/lmu', 'App\Http\Controllers\PembobotanController@storeLmu')->name("pembobotan.storeLmu");

    //Alternatif
    Route::get('hasil-akhir', 'App\Http\Controllers\HasilAkhirController@index')->name('hasil-akhir.index');
    Route::post('hasil-akhir', 'App\Http\Controllers\HasilAkhirController@store')->name('hasil-akhir.store');
    Route::delete('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('login', 'App\Http\Controllers\AuthController@login')->name('login');
    Route::post('login', 'App\Http\Controllers\AuthController@authenticate')->name('authenticate');
});
