@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.orders'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.orders') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.orders') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @component('components.buttons.create')
                    @slot('route', route('order.create', $locale))
                    @slot('title', __('dashboard.add-order'))
                @endcomponent
            </div>
        </div>
    </div>

    @component('components.alert')
        @slot('alert', 'alert-success')
    @endcomponent

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="text-center">
                <th>{!! UrlHelper::sortable('id') !!}</th>
                <th>{!! UrlHelper::sortable('status') !!}</th>
                <th>{!! UrlHelper::sortable('total_price') !!}</th>
                <th>{!! UrlHelper::sortable('created_at') !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php
                /** @var $order \App\Models\Order */
                $statuses = [
                    \App\Models\Order::STATUSES['NEW_ORDER']    => 'primary',
                    \App\Models\Order::STATUSES['IN_PROGRESS']  => 'info',
                    \App\Models\Order::STATUSES['PAID']         => 'secondary',
                    \App\Models\Order::STATUSES['DELIVERY']     => 'warning',
                    \App\Models\Order::STATUSES['COMPLETED']    => 'success',
                    \App\Models\Order::STATUSES['CANCELLED']    => 'danger',
                ];
            @endphp
            @foreach ($orders as $order)
                <tr class="text-center">
                    <td>{{ $order->id }}</td>
                    <td>
                        <span class="badge bg-{{ $statuses[$order->status] }}">
                            {{ str_replace('_', ' ', array_keys(\App\Models\Order::STATUSES, $order->status))[0] }}
                        </span>
                    </td>
                    <td>${{ number_format($order->total_price, 2, '.', ' ') }}</td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($order->created_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('order.edit', ['order' => $order, 'locale' => $locale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $orders->links('components.pagination') }}
    </div>
@endsection
