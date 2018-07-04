<?php

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

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

Auth::routes();

Route::group([
    'middleware'    => 'admin2',
], function (Router $router) {
    $router->resource('/home', 'HomeController');
});