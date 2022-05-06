@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.categories'))

@section('content')
    @php
        /** @var $category \App\Models\Category **/
       $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.categories') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.categories') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @component('components.buttons.create')
                    @slot('route', route('category.create', $locale))
                    @slot('title', __('dashboard.add-category'))
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
                <th>{!! UrlHelper::sortable('parent') !!}</th>
                <th>{!! UrlHelper::sortable('created_at') !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr class="text-center">
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->parent }}</td>
                    <td>{{ date('d.m.Y', strtotime($category->created_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('category.edit', ['category' => $category, 'locale' => $locale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $categories->links('components.pagination') }}
    </div>
@endsection
