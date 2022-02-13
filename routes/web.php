<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\ClientController;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* cities */
Route::resource('cities', CityController::class); //cities resource routes
Route::post( '/cities/table', [CityController::class, 'table'] )->name( 'cities.table' ); //cities asynchronous table
Route::post( '/cities/edit/modal', [CityController::class, 'editModal'] )->name('cities.edit.modal'); //cities asynchronous modal

/* countries */
Route::resource('countries', CountryController::class); //countries resource routes
Route::post( '/countries/table', [CountryController::class, 'table'] )->name( 'countries.table' ); //countries asynchronous table
Route::post( '/countries/edit/modal', [CountryController::class, 'editModal'] )->name('countries.edit.modal'); //countries asynchronous modal

/* contacts */
Route::resource('contacts', ContactController::class); //contact asynchronous table
Route::post( '/contacts/table', [ContactController::class, 'table'] )->name( 'contacts.table' ); //contact asynchronous table
Route::post( '/contacts/edit/modal', [ContactController::class, 'editModal'] )->name('contacts.edit.modal'); //contact asynchronous modal

/* industry */
Route::resource('industries', IndustryController::class); //industries asynchronous table
Route::post( '/industries/table', [IndustryController::class, 'table'] )->name( 'industries.table' ); //industries asynchronous table
Route::post( '/industries/edit/modal', [IndustryController::class, 'editModal'] )->name('industries.edit.modal'); //industries asynchronous modal

/* clients */
Route::resource('clients', ClientController::class); //clients asynchronous table
Route::post( '/clients/table', [ClientController::class, 'table'] )->name( 'clients.table' ); //clients asynchronous table
Route::post( '/clients/edit/modal', [ClientController::class, 'editModal'] )->name('clients.edit.modal'); //clients asynchronous modal

/* home */
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');





