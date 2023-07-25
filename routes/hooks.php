<?php

use App\Http\Controllers\Hooks\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('payment', [PaymentController::class, 'index']);
