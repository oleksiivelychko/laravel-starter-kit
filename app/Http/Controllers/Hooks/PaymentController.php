<?php

namespace App\Http\Controllers\Hooks;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPaymentHook;
use Illuminate\Support\Facades\Request;

class PaymentController extends Controller
{
    public function index(): void
    {
        ProcessPaymentHook::dispatch(Request::post('order_id', 0), Request::post('status'));
    }
}
