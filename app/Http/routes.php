<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', ['as' => 'main.index', 'uses' => 'MainController@index']);
Route::any('dashboard/index', ['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);
Route::any('dashboard/test', ['as' => 'dashboard.test', 'uses' => 'DashboardController@test']);
