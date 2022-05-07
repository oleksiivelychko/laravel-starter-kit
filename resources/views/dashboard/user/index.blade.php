@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.users'))

@section('content')
    @php
        /** @var $user \App\Models\User */
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.users') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.users') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @component('components.buttons.create')
                    @slot('route', route('user.create', $locale))
                    @slot('title', __('dashboard.add-user'))
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
                <th>{!! UrlHelper::sortable('email') !!}</th>
                <th>{!! UrlHelper::sortable('created_at') !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr class="text-center">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ date('d.m.Y', strtotime($user->created_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('user.edit', ['user' => $user, 'locale' => $locale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $users->links('components.pagination') }}
    </div>
@endsection
