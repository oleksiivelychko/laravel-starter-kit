<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => '{locale?}',
    'middleware' => 'locale',
    'where' => ['locale' => rtrim(implode('|', array_values(config('settings.languages'))), '|')]
], function () {
    require __DIR__.'/auth.php';

    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});
