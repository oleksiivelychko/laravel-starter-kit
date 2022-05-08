<div class="tab-pane" id="order-items" role="tabpanel" aria-labelledby="order-items-tab">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>{{ __('dashboard.product') }}</th>
                <th>{{ __('dashboard.price') }}</th>
                <th>{{ __('dashboard.quantity') }}</th>
                <th>{{ __('dashboard.created_at') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php
                /** @var $order \App\Models\Order */
                /** @var $orderItem \App\Models\OrderItem */
            @endphp
            @foreach ($order->items as $orderItem)
                <tr class="text-center">
                    <td>{{ $orderItem->id }}</td>
                    <td>{{ $orderItem->product->translate('name') }}</td>
                    <td>${{ number_format($orderItem->price, 2, '.', ' ') }}</td>
                    <td>{{ $orderItem->quantity }}</td>
                    <td>{{ date('d.m.Y', strtotime($order->created_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('order-item.edit', ['order_item' => $orderItem, 'order_id' => $order->id, 'locale' => $currentLocale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
