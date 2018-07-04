<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('account', 'AccountController');
    $router->post('account/status', 'AccountController@status');
    $router->post('account/copy', 'AccountController@copy');
    $router->resource('account_normal', 'AccountNormalController');
    $router->post('account_normal/status', 'AccountNormalController@status');
    $router->post('account_normal/copy', 'AccountNormalController@copy');
});
