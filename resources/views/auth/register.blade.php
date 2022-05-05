@extends('layouts.guest')

@section('title', __('auth.sign-up'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form form-signup">
        <form method="post" action="{{ route('register', $appLocale) }}">
            @csrf

            <div class="mb-4">
                <a href="{{ route('home', $appLocale) }}">
                    @component('components.application-logo')
                        @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                    @endcomponent
                </a>
            </div>

            <h1 class="h3 mb-3 fw-normal">{{ __('auth.sign-up') }}</h1>

            <div class="form-floating">
                <input type="text"
                       id="name"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="John Dou"
                       value="{{ old('name') }}"
                       required
                       autofocus
                >
                <label for="name">{{ __('auth.name') }}</label>
                @error('name')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

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
                    autocomplete="new-password"
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

            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ __('auth.register') }}</button>

            <p class="mt-2">
                <a href="{{ route('login', $appLocale) }}">{{ __('auth.already-registered') }}</a>
            </p>

            <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>
        </form>
    </main>
@endsection
