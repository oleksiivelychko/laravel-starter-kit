@php
    $appLocale = app()->getLocale();
@endphp

<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="{{ route('home', $appLocale) }}" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                @component('components.application-logo')
                    @slot('attributes', 'class="bi me-2" width="40" height="32"')
                @endcomponent
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">{{ __('site.home') }}</a></li>
            </ul>

            @include('components.change-locale')

            @guest
                <ul class="nav">
                    <li class="nav-item">
                        <a href="{{ route('login', $appLocale) }}" class="nav-link link-dark px-2">{{ __('auth.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register', $appLocale) }}" class="nav-link link-dark px-2">{{ __('auth.register') }}</a>
                    </li>
                </ul>
            @else
                <div class="dropdown text-end">
                    <a href="{{ route('home', $appLocale) }}" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->showImage('150x150') }}" alt="mdo" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser">
                        <li>
                            @role('administrator')
                                <a class="dropdown-item" href="{{ route('dashboard', $appLocale) }}">{{ __('dashboard.title') }}</a>
                            @endrole
                        </li>
                        <li>
                            <form method="post" action="{{ route('logout', $appLocale) }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout', $appLocale) }}"
                                   onclick="event.preventDefault();this.closest('form').submit();">
                                    {{ __('auth.logout') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
</header>
