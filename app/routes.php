<?php

use Core\Router;

/*
 * routes file dùng để cấu hình điều hướng cho website
 */

$router = new Router();

$router->get('', 'PagesController@index');
$router->get('404', 'PagesController@notFound');
$router->get('login', 'AuthController@index');
$router->get('logout', 'AuthController@logout');
$router->post('login', 'AuthController@login');

$router->get('users', 'UsersController@index');
$router->get('users/one', 'UsersController@show');
$router->post('users', 'UsersController@store');
$router->post('users/update', 'UsersController@update');
$router->post('users/delete', 'UsersController@destroy');

$router->get('customers', 'CustomersController@index');
$router->get('customers/one', 'CustomersController@show');
$router->post('customers', 'CustomersController@store');
$router->post('customers/update', 'CustomersController@update');
$router->post('customers/delete', 'CustomersController@destroy');

$router->get('categories', 'CategoriesController@index');
$router->get('categories/one', 'CategoriesController@show');
$router->post('categories', 'CategoriesController@store');
$router->post('categories/update', 'CategoriesController@update');
$router->post('categories/delete', 'CategoriesController@destroy');

$router->get('products', 'ProductsController@index');
$router->get('products/one', 'ProductsController@show');
$router->post('products', 'ProductsController@store');
$router->post('products/update', 'ProductsController@update');
$router->post('products/delete', 'ProductsController@destroy');

$router->get('orders', 'OrdersController@index');
$router->get('orders/one', 'OrdersController@getOne');
$router->post('orders', 'OrdersController@store');
$router->post('orders/delete', 'OrdersController@destroy');

