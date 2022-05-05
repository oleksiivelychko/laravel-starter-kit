@extends('layouts.guest')

@section('title', __('auth.confirm-password'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form">
        <form method="post" action="{{ route('password.confirm', $appLocale) }}">
            @csrf

            <div class="mb-4">
                <a href="{{ route('home', $appLocale) }}">
                    @component('components.application-logo')
                        @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                    @endcomponent
                </a>
            </div>

            <h6 class="mb-3 fw-normal">{{ __('auth.confirm-password-text') }}</h6>

            <div class="form-floating">
                <input
                    type="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="********"
                    name="password"
                    required
                    autocomplete="current-password"
                >
                <label for="password">{{ __('auth.password-confirm') }}</label>
                @error('password')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button class="mt-2 w-100 btn btn-primary" type="submit">{{ __('auth.confirm-password') }}</button>

            <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>
        </form>
    </main>
@endsection
