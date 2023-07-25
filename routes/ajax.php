<?php

use App\Http\Controllers\Ajax\LiveSearchController;
use App\Http\Controllers\Ajax\SearchController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{locale}',
    'middleware' => 'locale',
    'where' => ['locale' => rtrim(implode('|', array_values(config('settings.languages'))), '|')],
], function () {
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('live-search-{entity}', [LiveSearchController::class, 'search']);
        Route::post('search', [SearchController::class, 'search'])->name('search');
    });
});
