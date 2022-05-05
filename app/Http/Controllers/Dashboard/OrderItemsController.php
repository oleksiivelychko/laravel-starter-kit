<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\OrderItem;


class OrderItemsController extends Controller
{
    public function create(): Factory|View|Application
    {
        $order = Order::findOrFail(FacadeRequest::get('order_id'));
        return view('admin.order.item.create')
            ->with('orderItem', new OrderItem)
            ->with('order', $order);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $orderId = $request->get('order_id');
        $request->request->add(['order_id' => $orderId]);

        $orderItem = new OrderItem;
        $validatedData = $request->validate($orderItem->rules());

        if ($validatedData) {
            if ($orderItem->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-create-success'));
                return redirect(route('order.edit', ['order' => $orderId, 'locale' => app()->getLocale()]));
            }
        }
    }

    public function edit(string $locale, OrderItem $orderItem): Factory|View|Application
    {
        $order = Order::findOrFail(FacadeRequest::get('order_id'));
        return view('admin.order.item.edit')
            ->with('orderItem', $orderItem)
            ->with('order', $order);
    }

    public function update($locale, Request $request, OrderItem $orderItem): Redirector|Application|RedirectResponse
    {
        $orderId = $request->get('order_id');
        $request->request->add(['order_id' => $orderId]);

        $validatedData = $request->validate($orderItem->rules());
        if ($validatedData) {
            if ($orderItem->calculateAndSave($validatedData)) {
                $request->session()->put('status', trans('admin.messages.model-update-success'));
            }
        }

        return redirect(route('order.edit', ['order' => $orderId, 'locale' => $locale]));
    }

    /**
     * @param $locale
     * @param OrderItem $orderItem
     * @return Redirector|Application|RedirectResponse
     * @throws Exception
     */
    public function destroy($locale, OrderItem $orderItem): Redirector|Application|RedirectResponse
    {
        if ($orderItem->delete()) {
            Session::put('status', trans('admin.messages.model-delete-success'));
        }

        return redirect(route('order.edit', ['order' => $orderItem->order_id, 'locale' => $locale]));
    }
}
