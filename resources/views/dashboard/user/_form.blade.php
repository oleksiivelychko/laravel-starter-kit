@php
    /** @var \App\Models\User $user */
@endphp

<form action="{{ $action }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    @if ($user->exists)
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
                    value="{{ $user->name ?: old('name') }}"
                >
                @error('name')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ $user->email ?: old('email') }}"
                    {{ $user->email ? 'readonly' : '' }}
                >
                @error('email')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">{{ __('dashboard.password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="********"
                >
                @error('password')
                <div class="invalid-feedback">
                    <span>{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label class="form-label">{{ __('dashboard.choose-file') }}</label>
                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
            </div>
            @error('avatar')
            <div class="invalid-feedback d-block">
                <span>{{ $message }}</span>
            </div>
            @enderror
            <div class="input-group mt-3">
                @if ($user->avatar)
                    <img src="{{ asset($user->showImage('150x150')) }}" alt="{{ $user->avatar }}">
                @endif
            </div>
        </div>
        <div class="col-md-6 mt-3 col-12">
            <h6>{{ __('dashboard.roles') }}</h6>
            <table class="table table-bordered">
                <tbody>
                @foreach(\App\Models\Role::all(['name','slug']) as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="form-group text-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->slug }}"
                                       @if($user->hasRole($role->slug) || (old('roles') && in_array($role->slug, old('roles')))) checked @endif
                                       class="form-check-input" id="{{ $role->slug }}">
                                <label class="form-check-label" for="{{ $role->slug }}"></label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6 mt-3 col-12">
            <h6>{{ __('dashboard.permissions') }}</h6>
            <table class="table table-bordered">
                <tbody>
                @foreach(\App\Models\Permission::all(['name','slug']) as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <div class="form-group text-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}"
                                       @if($user->hasPermission($permission) || (old('permissions') && in_array($permission->slug, old('permissions')))) checked @endif
                                       class="form-check-input" id="{{ $permission->slug }}"
                                >
                                <label class="form-check-label" for="{{ $permission->slug }}"></label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $user->exists ? $user->id : 0 }}">

    <div class="row mt-4 mb-2 justify-content-end">
        <div class="col-12 col-md-3 text-end">
            @component('components.buttons.save')@endcomponent
        </div>
    </div>
</form>

<div class="row gy-2">
    <div class="col-12 col-md-3">
        @component('components.buttons.back')
            @slot('route', route('dashboard.users', $currentLocale))
        @endcomponent
    </div>
    <div class="col-12 col-md-3">
        @if ($user->exists)
            <form action="{{ route('user.destroy', ['user' => $user, 'locale' => $currentLocale]) }}" method="post">
                @csrf
                @method('delete')
                @component('components.buttons.delete')@endcomponent
            </form>
        @endif
    </div>
</div>
