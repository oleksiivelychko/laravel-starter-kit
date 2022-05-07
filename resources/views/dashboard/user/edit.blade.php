@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.edit-user'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.edit-user') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.dashboard', $locale) }}">{{ __('dashboard.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.users', $locale) }}">{{ __('dashboard.users') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.edit-user') }}</li>
        </ol>
    </div>

    @component('components.alert')
        @slot('alert', 'alert-success')
    @endcomponent

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.user._form')
                        @slot('user', $user)
                        @slot('title', trans('dashboard.edit'))
                        @slot('currentLocale', $locale)
                        @slot('action', route('user.update', ['user' => $user, 'locale' => $locale]))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
