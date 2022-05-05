@extends('layouts.guest')

@section('title', __('auth.sign-in'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form form-signin">
        <form method="post" action="{{ route('login', $appLocale) }}">
            @csrf

            <div class="mb-4">
                <a href="{{ route('home', $appLocale) }}">
                    @component('components.application-logo')
                        @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                    @endcomponent
                </a>
            </div>

            <h1 class="h3 mb-3 fw-normal">{{ __('auth.sign-in') }}</h1>

            <div class="form-floating">
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="name@example.com"
                       value="{{ old('email') }}"
                       required
                       autocomplete="email"
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
                    autocomplete="current-password"
                >
                <label for="password">{{ __('auth.form-password') }}</label>
                @error('password')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember"{{ old('remember') ? 'checked' : '' }}> {{ __('auth.remember-me') }}
                </label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ __('auth.login') }}</button>

            <p class="mt-2">
                <a href="{{ route('password.request', $appLocale) }}">{{ __('auth.forgot-password') }}</a>
            </p>

            <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>
        </form>
    </main>
@endsection
