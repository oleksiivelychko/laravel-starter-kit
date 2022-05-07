@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.add-user'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.add-user') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.dashboard', $locale) }}">{{ __('dashboard.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.users', $locale) }}">{{ __('dashboard.users') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.add-user') }}</li>
        </ol>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.user._form')
                        @slot('user', $user)
                        @slot('title', trans('dashboard.create'))
                        @slot('currentLocale', $locale)
                        @slot('action', route('user.store', $locale))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
