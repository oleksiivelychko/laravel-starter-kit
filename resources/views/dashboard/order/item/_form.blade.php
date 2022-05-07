@php
    /** @var \App\Models\Order $order */
@endphp

<div class="row mt-2">
    <div class="col-md-6 col-12">
        <ul class="nav nav-tabs" id="nav-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active"
                   id="order-tab"
                   data-bs-toggle="tab"
                   data-bs-target="#order"
                   role="tab"
                   aria-controls="order"
                   aria-selected="true"
                >{{ __('dashboard.order') }}</a>
            </li>
            @if ($order->exists)
                <li class="nav-item">
                    <a class="nav-link"
                       id="order-items-tab"
                       data-bs-toggle="tab"
                       data-bs-target="#order-items"
                       role="tab"
                       aria-controls="order-items"
                       aria-selected="true"
                    >{{ __('dashboard.order-items') }}</a>
                </li>
            @endif
        </ul>
    </div>
    @if ($order->exists)
        <div class="col-md-6 col-12">
            <div class="d-flex justify-content-end flex-wrap flex-md-nowrap">
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        @component('components.buttons.create')
                            @slot('route', route('order-item.create', ['order_id' => $order->id, 'locale' => $currentLocale]))
                            @slot('title', __('dashboard.add-order-item'))
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane active" id="order" role="tabpanel" aria-labelledby="order-tab">
                <form action="{{ $action }}" method="post">
                    {{ csrf_field() }}

                    @if ($order->exists)
                        {{ method_field('put') }}
                    @endif

                    <div class="row mt-2">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" for="customer_name">{{ __('dashboard.name') }}</label>
                                <input
                                    type="text"
                                    class="form-control @error('customer_name') is-invalid @enderror"
                                    id="customer_name"
                                    name="customer_name"
                                    value="{{ $order->customer_name ?: old('customer_name') }}"
                                >
                                @error('customer_name')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer_email">Email</label>
                                <input
                                    type="text"
                                    class="form-control @error('customer_email') is-invalid @enderror"
                                    id="customer_email"
                                    name="customer_email"
                                    value="{{ $order->customer_email ?: old('customer_email') }}"
                                >
                                @error('customer_email')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="user_id">{{ __('dashboard.registered-user') }}</label>
                                <select
                                    class="live-search @error('user_id') is-invalid @enderror"
                                    id="user_id"
                                    name="user_id"
                                    data-entity-name="user"
                                    data-entity-value="{{ $order->user_id ?: 0 }}"
                                    data-entity-text="{{ $order->user_id ? $order->user->email : '' }}"
                                ></select>
                                @error('user_id')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" for="status">{{ __('dashboard.status') }}</label>
                                <select
                                    class="form-control @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                >
                                    @foreach(\App\Models\Order::STATUSES as $key=>$value)
                                        <option value="{{ $value }}"
                                                @if($value == old('status') || $value == $order->status) selected="selected" @endif>
                                            {{ str_replace('_', ' ', $key) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="total_price">{{ __('dashboard.total_price') }}</label>
                                <input
                                    type="number"
                                    step="0.1"
                                    class="form-control @error('total_price') is-invalid @enderror"
                                    id="total_price"
                                    name="total_price"
                                    value="{{ $order->total_price ?: old('total_price') }}"
                                    disabled
                                >
                                @error('total_price')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                @php
                                    /** @var \App\Models\Order $order */
                                    $shipping_address_as_billing_address = $order->shipping_address_as_billing_address ?: true;
                                @endphp
                                @if (old('shipping_address_as_billing_address_toggle', 'none') === 'none' &&
                                   ((
                                       $errors->get('shipping_address')
                                    ) || (
                                       ($order->shipping_address || old('shipping_address'))
                                    ))
                                )
                                    @php
                                        $shipping_address_as_billing_address = false;
                                    @endphp
                                @endif
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="shipping_address_as_billing_address"
                                        {{ $shipping_address_as_billing_address ? 'checked' : '' }}
                                        id="shipping_address_as_billing_address">
                                    <label class="form-label" for="shipping_address_as_billing_address">
                                        {{ __('dashboard.same_address') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mt-4">
                            <div class="mb-3">
                                <label class="form-label" for="billing_address">{{ __('dashboard.billing-address') }}</label>
                                <input
                                    type="text"
                                    class="form-control @error('billing_address') is-invalid @enderror"
                                    id="billing_address"
                                    name="billing_address"
                                    value="{{ $order->billing_address ?: old('billing_address') }}"
                                >
                                @error('billing_address')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mt-4">
                            <div class="mb-3">
                                <label class="form-label" for="shipping_address">{{ __('dashboard.shipping-address') }}</label>
                                <input
                                    type="text"
                                    class="form-control @error('shipping_address') is-invalid @enderror"
                                    id="shipping_address"
                                    name="shipping_address"
                                    value="{{ $order->shipping_address ?: old('shipping_address') }}"
                                >
                                @error('shipping_address')
                                <div class="invalid-feedback">
                                    <span>{{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="{{ $order->exists ? $order->id : 0 }}">

                    <div class="row mt-4 mb-2 justify-content-end">
                        <div class="col-12 col-md-3 text-end">
                            @component('components.buttons.save')@endcomponent
                        </div>
                    </div>
                </form>

                <div class="row gy-2">
                    <div class="col-12 col-md-3">
                        @component('components.buttons.back')
                            @slot('route', route('dashboard.orders', $currentLocale))
                        @endcomponent
                    </div>
                </div>

            </div>

            @if ($order->exists)
                @component('dashboard.order.item._table')
                    @slot('order', $order)
                    @slot('currentLocale', $currentLocale)
                @endcomponent
            @endif

        </div>
    </div>
</div>
