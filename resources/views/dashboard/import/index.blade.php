@extends('layouts.dashboard.layout')

@section('title', trans('dashboard.import'))

@section('content')
    @php
        $locale = app()->getLocale();
    @endphp

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard', $locale) }}">{{ __('dashboard.title') }}</a></li>
            <li class="breadcrumb-item active">{{ __('dashboard.import') }}</li>
        </ol>
        <h1 class="h2">{{ __('dashboard.import') }}</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group">

                <form action="{{ route('dashboard.import.post', app()->getLocale()) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label" for="importEntity">{{ __('dashboard.select-entity') }}</label>
                            <select class="form-control" name="entity" id="importEntity">
                                @foreach(\App\Models\Import::ALLOWED_ENTITIES as $entity)
                                    <option value="{{ $entity }}">{{ __('dashboard.'.$entity) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <input class="form-control form-control-sm" type="file" name="import">
                            @error('import')
                            <div class="invalid-feedback d-block">
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-6">
                            <button type="submit" class="btn btn-sm btn-outline-dark w-100">{{ __('dashboard.import') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>{{ __('dashboard.entity') }}</th>
                <th>{{ __('dashboard.state') }}</th>
                <th>{{ __('dashboard.received') }}</th>
                <th>{{ __('dashboard.created') }}</th>
                <th>{{ __('dashboard.updated') }}</th>
                <th>{{ __('dashboard.created_at') }}</th>
                <th>{{ __('dashboard.finished_at') }}</th>
            </tr>
            </thead>
            <tbody>
            @php /** @var $import \App\Models\Import */ @endphp
            @foreach ($imports as $import)
                <tr class="text-center">
                    <td>{{ $import->id }}</td>
                    <td>{{ $import->entity}}</td>
                    <td>{{ $import->state }}</td>
                    <td>{{ $import->received }}</td>
                    <td>{{ $import->created }}</td>
                    <td>{{ $import->updated }}</td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($import->created_at)) }}</td>
                    <td>{{ date('d.m.Y H:i:s', strtotime($import->updated_at)) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="clearfix float-end">
        {{ $imports->links('components.pagination') }}
    </div>

    <input type="hidden" id="userId" value="{{ auth()->id() }}">
@endsection
