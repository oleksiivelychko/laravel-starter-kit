@extends('layouts.guest')

@section('title', __('auth.verify-email'))

@section('content')
    @php
        $appLocale = app()->getLocale();
    @endphp

    <main class="auth-form">

        <div class="mb-4">
            <a href="{{ route('home', $appLocale) }}">
                @component('components.application-logo')
                    @slot('attributes', 'style="margin: 0 auto;text-align: left;" width="72"')
                @endcomponent
            </a>
        </div>

        <h6 class="mb-3 fw-normal">
            @if (session('status') === 'verification-link-sent')
                {{ __('auth.verify-email-sent') }}
            @else
                {{ __('auth.verify-email-text') }}
            @endif
        </h6>

        <form method="post" action="{{ route('verification.send', $appLocale) }}">
            @csrf
            <button class="mt-2 w-100 btn btn-primary" type="submit">{{ __('auth.verify-email-resend') }}</button>
        </form>

        <form method="post" action="{{ route('logout', $appLocale) }}">
            @csrf
            <button type="submit" class="btn btn-link">
                {{ __('auth.logout') }}
            </button>
        </form>

        <p class="mt-4 mb-3 text-muted">&copy; {{ date('Y') }}</p>
    </main>
@endsection
