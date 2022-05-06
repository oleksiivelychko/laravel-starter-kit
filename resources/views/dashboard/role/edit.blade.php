@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.edit-role'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __('dashboard.edit-role') }}</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.dashboard', $locale) }}">{{ __('dashboard.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard.roles', $locale) }}">{{ __('dashboard.roles') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.edit-role') }}</li>
        </ol>
    </div>

    @component('components.alert')
        @slot('alert', 'alert-success')
    @endcomponent

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @component('dashboard.role._form')
                        @slot('role', $role)
                        @slot('title', trans('dashboard.edit'))
                        @slot('currentLocale', $locale)
                        @slot('action', route('role.update', ['role' => $role, 'locale' => $locale]))
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
