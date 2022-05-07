@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.permissions'))

@section('content')
    @php
        /** @var $permission \App\Models\Permission */
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.permissions') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.permissions') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                @component('components.buttons.create')
                    @slot('route', route('permission.create', $locale))
                    @slot('title', __('dashboard.add-permission'))
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
                <th>{!! UrlHelper::sortable('updated_at') !!}</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="text-center">
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->slug }}</td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($permission->updated_at)) }}</td>
                    <td>
                        @component('components.buttons.edit')
                            @slot('route', route('permission.edit', ['permission' => $permission, 'locale' => $locale]))
                        @endcomponent
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $permissions->links('components.pagination') }}
    </div>
@endsection
