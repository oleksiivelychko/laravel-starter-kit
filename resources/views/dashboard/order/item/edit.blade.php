@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.edit-order-item'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.add-order-item') }} #{{ $orderItem->id }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('dashboard.orders', $locale) }}">{{ __('dashboard.orders') }}</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('order.edit', ['order' => $order, 'locale' => $locale]) }}">{{ __('dashboard.edit-order') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('dashboard.edit-order-item') }}</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.order.item._form')
                        @slot('order', $order)
                        @slot('orderItem', $orderItem)
                        @slot('currentLocale', $locale)
                        @slot('title', trans('dashboard.edit-order-item').' #'.$orderItem->id)
                        @slot('action', route('order-item.update', ['order_item' => $orderItem, 'order_id' => $order->id, 'locale' => $locale]))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
