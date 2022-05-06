@php
    /** @var \App\Models\Category $category */
@endphp

<form action="{{ $action }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    @if ($category->exists)
        {{ method_field('put') }}
    @endif

    <div class="row mt-2">
        <div class="col-md-6 col-12">
            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                @foreach(config('settings.languages') as $language => $locale)
                    <li class="nav-item">
                        <a class="nav-link {{ $locale === $currentLocale ? 'active' : '' }} {{ BladeHelper::isLocaleErrors($errors, $locale) ? 'error' : '' }}"
                           id="{{ $locale }}-tab"
                           data-bs-toggle="tab"
                           data-bs-target="#{{ $locale }}"
                           role="tab"
                           aria-controls="{{ $locale }}"
                           aria-selected="{{ $locale ? 'true' : 'false' }}"
                        >{{ $language }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6 col-12">
            <div class="tab-content" id="nav-tabContent">
                @foreach(config('settings.languages') as $language => $locale)
                    <div class="tab-pane {{ $locale === $currentLocale ? 'show active' : '' }}" id="{{ $locale }}" role="tabpanel" aria-labelledby="{{ $locale }}-tab">
                        <div class="mb-3">
                            <label class="form-label" for="name__{{ $locale }}">{{ __('dashboard.name') }}<span class="required">*</span></label>
                            <input
                                type="text"
                                name="name__{{ $locale }}"
                                id="name__{{ $locale }}"
                                class="form-control @error('name__'.$locale) is-invalid @enderror"
                                value="{{ $category->translate('name', $locale) ?: old('name__'.$locale) }}"
                            >
                            @error('name__'.$locale)
                            <div class="invalid-feedback">
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <label class="form-label" for="slug">Slug ({{ __('dashboard.optional') }})</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control @error('slug') is-invalid @enderror"
                    value="{{ $category->slug ?: old('slug') }}"
                >
                @error('slug')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="parent_id">{{ __('dashboard.parent') }}</label>
                <select name="parent_id"
                        class="live-search @error('parent_id') is-invalid @enderror"
                        id="parent_id"
                        data-entity-name="category"
                        data-entity-value="{{ $category->parent_id ?: 0 }}"
                        data-entity-text="{{ $category->parent_id ? $category->translate('name', false, true) : '' }}"
                ></select>
                @error('parent_id')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $category->exists ? $category->id : 0 }}">

    <div class="row mt-4 mb-2 justify-content-end">
        <div class="col-12 col-md-3 text-end">
            @component('components.buttons.save')@endcomponent
        </div>
    </div>
</form>

<div class="row gy-2">
    <div class="col-12 col-md-3">
        @component('components.buttons.back')
            @slot('route', route('dashboard.categories', $currentLocale))
        @endcomponent
    </div>
    <div class="col-12 col-md-3">
        @if ($category->exists)
            <form action="{{ route('category.destroy', ['category' => $category, 'locale' => $currentLocale]) }}" method="post">
                @csrf
                @method('delete')
                @component('components.buttons.delete')@endcomponent
            </form>
        @endif
    </div>
</div>
