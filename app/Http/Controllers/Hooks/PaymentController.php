<?php

namespace App\Http\Controllers\Hooks;

use App\Http\Controllers\Controller;
use App\Jobs\PaymentHandler;
use Illuminate\Support\Facades\Request;

class PaymentController extends Controller
{
    public function index(): void
    {
        PaymentHandler::dispatch(Request::post('order_id', 0), Request::post('status'));
    }
}
