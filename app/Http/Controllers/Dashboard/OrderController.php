<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;


class OrderController extends Controller
{
    public function index(Request $request, Order $orders): View|Factory|Application
    {
        return view('admin.order.index', [
            'orders' => $orders->pagination($request)
        ]);
    }

    public function create(): Factory|View|Application
    {
        return view('admin.order.create')->with('order', new Order);
    }

    public function store(StoreOrderRequest $request): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $order = new Order;
            if ($order->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-create-success'));
                return redirect(route('order.edit', ['order' => $order, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, Order $order): View|Factory|Application
    {
        return view('admin.order.edit')->with('order', $order);
    }

    public function update(StoreOrderRequest $request, string $locale, Order $order): Redirector|Application|RedirectResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($order->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-update-success'));
            }
        }

        return redirect(route('order.edit', ['order' => $order, 'locale' => $locale]));
    }
}
