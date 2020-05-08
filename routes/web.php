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

Route::get('/', 'HomeController@welcome')->name('welcome');

Route::get('/cl/pacientes', 'ClpatientsController@index')->name('clpatients.index');
Route::post('/cl/pacientes', 'ClpatientsController@store')->name('clpatients.store');
Route::get('/cl/pacientes/criar', 'ClpatientsController@create')->name('clpatients.create');
Route::get('/cl/pacientes/{patient}', 'ClpatientsController@show')->name('clpatients.show');

Route::get('/pr/pacientes', 'PrPatientsController@index')->name('prpatients.index');
Route::post('/pr/pacientes', 'PrPatientsController@store')->name('prpatients.store');
Route::get('/pr/pacientes/criar', 'PrPatientsController@create')->name('prpatients.create');
Route::get('/pr/pacientes/{patient}', 'PrPatientsController@show')->name('prpatients.show');

Auth::routes(['verify' => false, 'reset' => false]);
// , 'register' => false

Route::get('/home', 'HomeController@index')->name('home');
