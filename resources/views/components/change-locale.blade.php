<div class="nav-item dropdown">
    <a id="navbarDropdownLanguage" class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
        {{ strtoupper(app()->getLocale()) }}
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        @foreach(config('settings.languages') as $language => $localeName)
            <a class="dropdown-item @if($localeName === app()->getLocale()) active @endif" href="{{ route('change-locale', $localeName) }}">
                {{ $language }}
            </a>
        @endforeach
    </div>
</div>
