<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
    Route::post('/clients.back/{key?}/{message?}', 'ClientController@back')->name('clients.back');
    // Контракты
    Route::resource('/contracts', 'ContractController');
    Route::get('/contracts.create/{client}', 'ContractController@create')->name('client.contracts.create');
    Route::get('/contracts.data/{client?}', 'ContractController@getData')->name('contracts.index.data');
    Route::post('/contracts.regenerate', 'ContractController@regenerateKey')->name('contracts.regenerate');
    Route::post('/contracts.back/{key?}/{message?}', 'ContractController@back')->name('contracts.back');
    // Тесты
    Route::resource('/tests', 'TestController');
    Route::get('/tests.data', 'TestController@getData')->name('tests.index.data');
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
    Route::post('/questions.up', 'QuestionsController@up')->name('questions.up');
    Route::post('/questions.down', 'QuestionsController@down')->name('questions.down');
    Route::post('/questions.back/{key?}/{message?}', 'QuestionsController@back')->name('questions.back');
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
    Route::post('/neuroprofiles.filter/{fmptype_id}', 'NeuroprofileController@filterNew')->name('neuroprofiles.filter');
    // История
    Route::resource('/history', 'HistoryController');
    Route::get('/history.data', 'HistoryController@getData')->name('history.index.data');
    Route::get('/history.detail', 'HistoryController@detail')->name('history.detail');
    Route::post('/history.back/{key?}/{message?}', 'HistoryController@back')->name('history.back');
});

// Безопасность
Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'admin'], function () {
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

Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => 'admin'], function () {
    Route::get('/logout', 'UserController@logout')->name('logout');
});

// Плеер
Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => 'restore.session'], function () {
    // Проверка
    Route::get('/player.iframe', 'PlayerController@iframe')->name('player.iframe');
    //
    Route::get('/player.index', 'PlayerController@index')->name('player.index');
    Route::get('/player.play/{mkey}/{test}', 'PlayerController@play')->name('player.play');
    Route::get('/player.card', 'PlayerController@card')->name('player.card');
    // Маршруты сохранения предварительной информации (из карточек)
    Route::get('/player.pkey', 'PlayerController@store_pkey')->name('player.pkey');
    Route::get('/player.full', 'PlayerController@store_full_card')->name('player.full');

    // TODO обменять body и body2 после полной отладки
    Route::get('/player.body/{question?}', 'PlayerController@body')->name('player.body');
    //
    Route::get('/player.body2', 'PlayerController@body2')->name('player.body2');
    Route::post('/player/body2.store', 'PlayerController@body2_store')->name('player.body2.store');
    //
    Route::get('/player.precalc/{history_id}', 'PlayerController@precalc')->name('player.precalc');
    Route::get('/player.calculate/{history_id}', 'PlayerController@calculate')->name('player.calculate');
    Route::get('/player.mail/{history_id}', 'PlayerController@mail')->name('player.mail');

    Route::get('/player.policy/{document}/{mail?}', 'PlayerController@showDocument')->name('player.policy');
});

// Служебные маршруты
Route::get('/optimize-clear', function () {
    $exitCode = Artisan::call('optimize:clear');
    echo('Сделана полная оптимизация. Код завершения = '. $exitCode);
});
