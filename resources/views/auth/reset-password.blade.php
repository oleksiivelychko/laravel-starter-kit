@extends('layouts.guest')

@section('title', __('auth.confirm-password'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form form-reset">
        <form method="post" action="{{ route('password.update', $appLocale) }}">
            @csrf

            <div class="mb-4">
                <a href="{{ route('home', $appLocale) }}">
                    @component('components.application-logo')
                        @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                    @endcomponent
                </a>
            </div>

            <h6 class="mb-3 fw-normal">{{ __('auth.reset-password') }}</h6>

            <div class="form-floating">
                <input
                    type="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="********"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                >
                <label for="email">Email</label>
                @error('email')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-floating">
                <input
                    type="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="********"
                    name="password"
                    required
                >
                <label for="password">{{ __('auth.form-password') }}</label>
                @error('password')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-floating">
                <input
                    type="password"
                    id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="********"
                    name="password_confirmation"
                    required
                >
                <label for="password_confirmation">{{ __('auth.password-confirm') }}</label>
                @error('password_confirmation')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button class="mt-2 w-100 btn btn-primary" type="submit">{{ __('auth.reset-password') }}</button>

            <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>

            <input type="hidden" name="token" value="{{ $request->route('token') }}">
        </form>
    </main>
@endsection
