@extends('layouts.guest')

@section('title', __('auth.forgot-password'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ route('password.email', $appLocale) }}">
            @csrf

            <div class="mb-4">
                <a href="{{ route('home', $appLocale) }}">
                    @component('components.application-logo')
                        @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                    @endcomponent
                </a>
            </div>

            <h6 class="mb-3 fw-normal">{{ __('auth.forgot-password-text') }}</h6>

            <div class="form-floating">
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="name@example.com"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="email"
                >
                <label for="email">Email</label>
                @error('email')
                <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button class="mt-2 w-100 btn btn-primary" type="submit">{{ __('auth.reset-link-button') }}</button>

            <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>
        </form>
    </main>
@endsection
