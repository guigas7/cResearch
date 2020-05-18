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

Route::get('/cl/pacientes', 'ClpatientsController@index')->name('clpatients.index')->middleware('auth');
Route::post('/cl/pacientes', 'ClpatientsController@store')->name('clpatients.store');
Route::get('/cl/pacientes/criar', 'ClpatientsController@create')->name('clpatients.create');
Route::get('/cl/pacientes/{patient}', 'ClpatientsController@show')->name('clpatients.show')->middleware('auth');

Route::get('/pr/pacientes', 'PrpatientsController@index')->name('prpatients.index')->middleware('auth');
Route::post('/pr/pacientes', 'PrpatientsController@store')->name('prpatients.store');
Route::get('/pr/pacientes/criar', 'PrpatientsController@create')->name('prpatients.create');
Route::get('/pr/pacientes/{patient}', 'PrpatientsController@show')->name('prpatients.show')->middleware('auth');

Auth::routes(['verify' => false, 'reset' => false, 'register' => false]);
// , 'register' => false

// admin => view all, randomize, search, edit, undo
// plantonista => randomiza, search