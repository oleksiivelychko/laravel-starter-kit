<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Support\Facades\Session;

class OrderItemsController extends Controller
{
    public function create(): Factory|View|Application
    {
        return view('dashboard.order.item.create')
            ->with('orderItem', new OrderItem())
            ->with('order', Order::findOrFail(FacadeRequest::get('order_id')))
        ;
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $orderId = $request->get('order_id');
        $request->request->add(['order_id' => $orderId]);

        $orderItem = new OrderItem();
        $validatedData = $request->validate($orderItem->rules());

        if ($validatedData) {
            if ($orderItem->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-create-success'));

                return redirect(route('order.edit', ['order' => $orderId, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(OrderItem $orderItem): Factory|View|Application
    {
        return view('dashboard.order.item.edit')
            ->with('orderItem', $orderItem)
            ->with('order', Order::findOrFail(FacadeRequest::get('order_id')))
        ;
    }

    public function update(Request $request, OrderItem $orderItem, string $locale): Redirector|Application|RedirectResponse
    {
        $orderId = $request->get('order_id');
        $request->request->add(['order_id' => $orderId]);

        $validatedData = $request->validate($orderItem->rules());
        if ($validatedData) {
            if ($orderItem->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('dashboard.messages.model-update-success'));
            }
        }

        return redirect(route('order.edit', ['order' => $orderId, 'locale' => $locale]));
    }

    public function destroy(OrderItem $orderItem, string $locale): Redirector|Application|RedirectResponse
    {
        if ($orderItem->delete()) {
            Session::put('status', trans('dashboard.messages.model-delete-success'));
        }

        return redirect(route('order.edit', ['order' => $orderItem->order_id, 'locale' => $locale]));
    }
}
