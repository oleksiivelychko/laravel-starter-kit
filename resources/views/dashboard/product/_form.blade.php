@php
    /** @var \App\Models\Product $product */
@endphp

@extends('components.modal')

<form action="{{ $action }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    @if ($product->exists)
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
                                value="{{ $product->translate('name', $locale) ?: old('name__'.$locale) }}"
                            >
                            @error('name__'.$locale)
                            <div class="invalid-feedback">
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description__{{ $locale }}">{{ __('dashboard.description') }}</label>
                            <textarea
                                type="text"
                                name="description__{{ $locale }}"
                                id="description__{{ $locale }}"
                                class="form-control wysiwyg @error('description__'.$locale) is-invalid @enderror"
                            >
                                {{ $product->translate('description', $locale) ?: old('description__'.$locale) }}
                            </textarea>
                            @error('description__'.$locale)
                            <div class="invalid-feedback">
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label class="form-label" for="slug">Slug ({{ __('dashboard.optional') }})</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control @error('slug') is-invalid @enderror"
                    value="{{ $product->slug ?: old('slug') }}"
                >
                @error('slug')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="price">{{ __('dashboard.price') }}</label>
                <input
                    type="number"
                    step="0.1"
                    placeholder="0.1"
                    name="price"
                    id="price"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ $product->price ?: old('price') }}"
                >
                @error('price')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="categoriesSelect"></label>
                <select
                    id="categoriesSelect"
                    class="multiselect"
                    name="categories[]"
                    multiple="multiple"
                >
                    @foreach($categories as $category)
                        <option {{ in_array($category->id, $product->categories_ids) ? 'selected' : '' }} value="{{ $category->id }}">
                            {{ $category->translate('name', $currentLocale) }}
                        </option>
                    @endforeach
                </select>
                @error('categories')
                <div class="invalid-feedback d-block">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>

    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="mb-3">
                <label class="form-label">{{ __('dashboard.choose-file') }}</label>
                <input type="file" name="images[]" class="form-control @error('images.*'.$currentLocale) is-invalid @enderror" multiple>
            </div>
            @foreach($errors->get('images.*') as $messages)
                <div class="invalid-feedback d-block">
                    @foreach($messages as $message)
                        <span>{{ $message }}</span><br>
                    @endforeach
                </div>
            @endforeach
            @if($product->images)
                <div class="row" id="gallery">
                    @foreach($product->images_array as $image)
                        @php
                            /** @var \App\Models\Product $product */
                            /** @var string $image */
                            $showImage = ImageHelper::showImage($image, $product->getImagesFolder().'/'.$product->id, '200x150');
                        @endphp
                        <div class="col-md-3 col-sm-6 col-12 gy-4 image-wrapper">
                            @if ($showImage['exists'])
                                <i class="bi bi-x-circle" data-bs-toggle="modal" data-bs-target="#modalWindow"></i>
                                @section('modal_title'){{ __('dashboard.modal.delete') }}@endsection
                                @section('modal_body'){{ __('dashboard.modal.delete-message') }}@endsection
                                @section('modal_url'){{ route('product.delete-image', ['locale' => $currentLocale, 'product_id' => $product->id, 'image' => $image]) }}@endsection
                            @endif
                            <img class="rounded w-100" src="{{ asset($showImage['path']) }}" alt="{{ $image }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $product->exists ? $product->id : 0 }}">

    <div class="row mt-4 mb-2 justify-content-end">
        <div class="col-12 col-md-3 text-end">
            @component('components.buttons.save')@endcomponent
        </div>
    </div>
</form>

<div class="row gy-2">
    <div class="col-12 col-md-3">
        @component('components.buttons.back')
            @slot('route', route('dashboard.products', $currentLocale))
        @endcomponent
    </div>
    <div class="col-12 col-md-3">
        @if ($product->exists)
            <form action="{{ route('product.destroy', ['product' => $product, 'locale' => $currentLocale]) }}" method="post">
                @csrf
                @method('delete')
                @component('components.buttons.delete')@endcomponent
            </form>
        @endif
    </div>
</div>
