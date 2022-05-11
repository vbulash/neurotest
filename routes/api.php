<?php

    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    // Утилиты
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::get('/get.password', 'HelperController@generatePassword')->name('api.get.password');
        Route::get('/down', function() {
            Artisan::call('down');
            echo('Сервер остановлен и переведен в режим обслуживания');
        })->name('api.down');
//        Route::get('/up', function() {
//            $exitcode = Artisan::call('up');
//            echo("Сервер выведен из режима обслуживания и может работать как обычно ($exitcode)");
//        })->name('api.up');
        Route::get('/get.block.typename/{type}', 'Admin\BlockController@getTypeName')->name('api.get.block.typename');
        // Оплата
        Route::get('/payment.result', 'PlayerController@paymentResult')->name('payment.result');
        Route::get('/payment.success', 'PlayerController@paymentSuccess')->name('payment.success');
        Route::get('/payment.fail', 'PlayerController@paymentFail')->name('payment.fail');
        // Окружение
        Route::get('/get.phpinfo', 'HelperController@getPhpInfo')->name('api.get.phpinfo');
    });
