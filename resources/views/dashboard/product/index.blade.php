@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.products'))

@section('content')
    @php
        /** @var $product \App\Models\Product */
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.products') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.products') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @component('components.buttons.create')
                    @slot('route', route('product.create', $locale))
                    @slot('title', __('dashboard.add-product'))
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
                <th>{!! UrlHelper::sortable('name') !!}</th>
                <th>{!! UrlHelper::sortable('slug') !!}</th>
                <th>{!! UrlHelper::sortable('price') !!}</th>
                <th>{!! UrlHelper::sortable('created_at') !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr class="text-center">
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->slug }}</td>
                    <td>${{ $product->price }}</td>
                    <td>{{ date('d.m.Y', strtotime($product->created_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('product.edit', ['product' => $product, 'locale' => $locale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $products->links('components.pagination') }}
    </div>
@endsection
