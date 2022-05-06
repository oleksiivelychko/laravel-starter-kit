@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.add-product'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.add-product') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.dashboard', $locale) }}">{{ __('dashboard.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.products', $locale) }}">{{ __('dashboard.products') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.add-product') }}</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.product._form')
                        @slot('product', $product)
                        @slot('categories', $categories)
                        @slot('currentLocale', $locale)
                        @slot('title', trans('dashboard.create'))
                        @slot('action', route('product.store', $locale))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
