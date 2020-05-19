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

Route::get('/cl/pacientes', 'ClpatientsController@index')->name('clpatients.index')->middleware('can:view_list');
Route::post('/cl/pacientes', 'ClpatientsController@store')->name('clpatients.store');
Route::get('/cl/pacientes/criar', 'ClpatientsController@create')->name('clpatients.create');
Route::post('/cl/pacientes/buscar', 'ClpatientsController@find')->name('clpatients.find');
Route::get('/cl/pacientes/buscar', 'ClpatientsController@search')->name('clpatients.search');
Route::post('/cl/pacientes/editar', 'ClpatientsController@update')->name('clpatients.update')->middleware('can:edit');
Route::get('/cl/pacientes/editar/{patient}', 'ClpatientsController@edit')->name('clpatients.edit')->middleware('can:edit');
Route::get('/cl/pacientes/{patient}', 'ClpatientsController@show')->name('clpatients.show')->middleware('can:search');

Route::get('/pr/pacientes', 'PrpatientsController@index')->name('prpatients.index')->middleware('can:view_list');
Route::post('/pr/pacientes', 'PrpatientsController@store')->name('prpatients.store');
Route::get('/pr/pacientes/criar', 'PrpatientsController@create')->name('prpatients.create');
Route::post('/pr/pacientes/busca', 'PrpatientsController@find')->name('prpatients.find');
Route::get('/pr/pacientes/busca', 'PrpatientsController@search')->name('prpatients.search');
Route::post('/pr/pacientes/editar', 'PrpatientsController@update')->name('prpatients.update')->middleware('can:edit');
Route::get('/pr/pacientes/editar/{patient}', 'PrpatientsController@edit')->name('prpatients.edit')->middleware('can:edit');
Route::get('/pr/pacientes/{patient}', 'PrpatientsController@show')->name('prpatients.show')->middleware('can:search');

Auth::routes(['verify' => false, 'reset' => false, 'register' => false]);
// , 'register' => false

// admin => view all, randomize, search, edit, undo
// plantonista => randomiza, search
