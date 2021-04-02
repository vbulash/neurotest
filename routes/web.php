<?php

    use Illuminate\Support\Facades\Route as Route;

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

    Route::redirect('/', '/admin')->name('home');

    // Маршруты объектов платформы
    Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'admin'], function () {
        Route::get('/', 'MainController@index')->name('admin.index');
        // Клиенты
        Route::resource('/clients', 'ClientController');
        Route::get('/clients.data', 'ClientController@getData')->name('clients.index.data');
        // Контракты
        Route::resource('/contracts', 'ContractController');
        Route::get('/contracts.create/{client}', 'ContractController@create')->name('client.contracts.create');
        Route::get('/contracts.data/{client?}', 'ContractController@getData')->name('contracts.index.data');
        // Тесты
        Route::resource('/tests', 'TestController');
        Route::get('/tests.data', 'TestController@getData')->name('tests.index.data');
        Route::get('/tests.status.change/{test}/{newType}', 'TestController@changeStatus')->name('tests.status.change');
        // Модули тестов
        Route::resource('/blocks', 'BlockController');
        Route::get('/blocks.create.embedded/{type}', 'BlockController@create')->name('blocks.create.embedded');
        Route::get('/blocks.data', 'BlockController@getData')->name('blocks.index.data');
        Route::get('/blocks.up/{block}', 'BlockController@up')->name('blocks.up');
        Route::get('/blocks.down/{block}', 'BlockController@down')->name('blocks.down');
    });

    // Безопасность
    Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'auth'], function () {
        // Пользователи
        Route::resource('/users', 'UserController');
        Route::get('/users.data', 'UserController@getData')->name('users.index.data');
        // Роли
        Route::resource('/roles', 'RoleController');
        Route::get('/roles.data', 'RoleController@getData')->name('roles.index.data');
    });

    // Аутентификация
    Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'guest'], function () {
        Route::get('/login', 'UserController@loginForm')->name('login.create');
        Route::post('/login', 'UserController@login')->name('login');
    });

    Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'auth'], function () {
        Route::get('/logout', 'UserController@logout')->name('logout');
    });

    // Плеер
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::get('/player/{mkey}', 'PlayerController@play')->name('player');
    });
