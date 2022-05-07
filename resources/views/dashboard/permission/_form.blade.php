@php
    /** @var \App\Models\Permission $permission */
@endphp

<form action="{{ $action }}" method="post">
    {{ csrf_field() }}

    @if ($permission->exists)
        {{ method_field('put') }}
    @endif

    <div class="row mt-2">
        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label class="form-label" for="name">{{ __('dashboard.name') }}</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    placeholder="Manage ACL"
                    value="{{ $permission->name ?: old('name') }}"
                >
                @error('name')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="slug">Slug</label>
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control @error('slug') is-invalid @enderror"
                    placeholder="manage-acl"
                    value="{{ $permission->slug ?: old('slug') }}"
                    @if($permission->id) readonly @endif
                >
                @error('slug')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $permission->exists ? $permission->id : 0 }}">

    <div class="row mt-4 mb-2 justify-content-end">
        <div class="col-12 col-md-3 text-end">
            @component('components.buttons.save')@endcomponent
        </div>
    </div>
</form>

<div class="row gy-2">
    <div class="col-12 col-md-3">
        @component('components.buttons.back')
            @slot('route', route('dashboard.permissions', $currentLocale))
        @endcomponent
    </div>
    <div class="col-12 col-md-3">
        @if ($permission->exists)
            <form action="{{ route('permission.destroy', ['permission' => $permission, 'locale' => $currentLocale]) }}" method="post">
                @csrf
                @method('delete')
                @component('components.buttons.delete')@endcomponent
            </form>
        @endif
    </div>
</div>
