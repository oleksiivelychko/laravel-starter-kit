@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.edit-category'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.edit-category') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.categories', $locale) }}">{{ __('dashboard.categories') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.edit-category') }}</li>
        </ol>
    </div>

    @component('components.alert')
        @slot('alert', 'alert-success')
    @endcomponent

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.category._form')
                        @slot('category', $category)
                        @slot('currentLocale', $locale)
                        @slot('title', trans('dashboard.edit-category'))
                        @slot('action', route('category.update', ['category' => $category, 'locale' => $locale]))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
