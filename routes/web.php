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

Route::get('/', 'PatientsController@create')->name('patients.create');

Auth::routes(['verify' => false, 'reset' => false]);
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pacientes', 'PatientsController@index')->name('patients.index');
Route::post('/pacientes', 'PatientsController@store')->name('patients.store');
Route::get('/pacientes/criar', 'PatientsController@create')->name('patients.create');
Route::get('/pacientes/{patient}', 'PatientsController@show')->name('patients.show');
