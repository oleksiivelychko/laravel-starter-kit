@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.add-permission'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.add-permission') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.permissions', $locale) }}">{{ __('dashboard.permissions') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.add-permission') }}</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.permission._form')
                        @slot('permission', $permission)
                        @slot('currentLocale', $locale)
                        @slot('title', trans('dashboard.create'))
                        @slot('action', route('permission.store', $locale))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
