<?php

Use App\Http\Controllers\CategoryController;
Use App\Http\Controllers\LoginRegisterController;
Use App\Http\Controllers\PortalController;
Use App\Http\Controllers\UserController;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    // Login
    $router->post('login', 'LoginRegisterController@Login');
    $router->post('logout', 'LoginRegisterController@Logout');
    $router->post('refresh', 'LoginRegisterController@Refresh');
    $router->post('me', 'LoginRegisterController@Me');

    // CRUD Category
    $router->post('categories', 'CategoryController@CreateCategory');
    $router->get('categories', 'CategoryController@GetAllCategory');
    $router->get('categories/{id}', 'CategoryController@GetCategoryById');
    $router->put('categories/{id}', 'CategoryController@UpdateCategoryById');
    $router->delete('categories/{id}', 'CategoryController@DeleteCategoryById');

    // CRUD Portal
    $router->post('portals', 'PortalController@CreatePortal');
    $router->get('portals', 'PortalController@GetAllPortal');
    $router->get('portals/{id}', 'PortalController@GetPortalById');
    $router->put('portals/{id}', 'PortalController@UpdatePortalById');
    $router->delete('portals/{id}', 'PortalController@DeletePortalById');
    $router->get('dashboard/{id}', 'PortalController@GetPortalByUserID');
    $router->get('portals/category/{id}', 'PortalController@GetPortalByCategoryID');

    // CRUD User
    $router->post('users', 'UserController@CreateUser');
    $router->get('users', 'UserController@GetAllUser');
    $router->get('users/{id}', 'UserController@GetUserById');
    $router->put('users/{id}', 'UserController@UpdateUserById');
    $router->delete('users/{id}', 'UserController@DeleteUserById');

    // Global Endpoint
    $router->get('/hw', 'Controller@HelloWorld');
    Route::get('portals/image/{filename}', 'Controller@getImage');
    Route::get('portals/file/{filename}', 'Controller@getFile');
});