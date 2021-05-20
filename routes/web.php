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
    Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['admin', 'stack']], function () {
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
        Route::post('/tests.back/{key?}/{message?}', 'TestController@back')->name('tests.back');
        // Наборы вопросов
        Route::resource('/sets', 'QuestionSetController');
        Route::get('/sets.data', 'QuestionSetController@getData')->name('sets.index.data');
        Route::get('/sets.copy/{set}', 'QuestionSetController@copy')->name('sets.copy');
        Route::post('/sets.filterbytest', 'QuestionSetController@filterByTest')->name('sets.filterbytest');
        Route::post('/sets.back/{key?}/{message?}', 'QuestionSetController@back')->name('sets.back');
        //  Вопросы
        Route::resource('/questions', 'QuestionsController');
        Route::get('/questions.data', 'QuestionsController@getData')->name('questions.index.data');
        // Блоки описания ФМП
        Route::resource('/blocks', 'BlockController');
        Route::get('/blocks.data/{profile_id?}', 'BlockController@getData')->name('blocks.index.data');
        Route::post('/blocks.back/{key?}/{message?}', 'BlockController@back')->name('blocks.back');
        // Типы описаний ФМП
        Route::resource('/fmptypes', "FMPTypeController");
        Route::get('/fmptypes.data', 'FMPTypeController@getData')->name('fmptypes.index.data');
        Route::post('/fmptypes.back/{key?}/{message?}', 'FMPTypeController@back')->name('fmptypes.back');
        Route::get('/fmtypes.copy/{fmptype}', 'FMPTypeController@copy')->name('fmptypes.copy');
        // Нейропрофили
        Route::resource('/neuroprofiles', "NeuroprofileController");
        Route::get('/neuroprofiles.data/{fmptype_id}', 'NeuroprofileController@getData')->name('neuroprofiles.index.data');
        Route::post('/neuroprofiles.back/{key?}/{message?}', 'NeuroprofileController@back')->name('neuroprofiles.back');
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
        Route::get('/player.index', 'PlayerController@index')->name('player.index');
        Route::get('/player.play/{mkey}', 'PlayerController@play')->name('player.play');
        Route::get('/player.card/{mkey}', 'PlayerController@card')->name('player.card');
        Route::get('/player.pkey', 'PlayerController@store_pkey')->name('player.pkey');
        // TODO Реализовать маршрут сохранения полной карты

        Route::get('/player.body/{mkey}/{question?}', 'PlayerController@body')->name('player.body');
        Route::get('/player.result/{mkey}', 'PlayerController@result')->name('player.result');
    });
