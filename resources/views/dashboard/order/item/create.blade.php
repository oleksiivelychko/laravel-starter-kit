@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.add-order'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.add-order') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.dashboard', $locale) }}">{{ __('dashboard.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.orders', $locale) }}">{{ __('dashboard.orders') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.add-order') }}</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.order._form')
                        @slot('order', $order)
                        @slot('currentLocale', $locale)
                        @slot('title', trans('dashboard.create'))
                        @slot('action', route('order.store', $locale))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
