@php
    use \Illuminate\Support\Facades\Request;

    $locale = app()->getLocale();
    $route = Request::route()->getName();
@endphp

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link {{ Request::is('*admin') ? 'active' : '' }}" aria-current="page" href="{{ route('admin.dashboard', $locale) }}">
                    <i class="bi bi-shop-window"></i>
                    &nbsp;{{ __('admin.dashboard') }}
                </a>
            </li>

            @php
                $show = Request::is('*admin/goods*');
            @endphp
            <li class="nav-item">
                <button class="btn btn-toggle align-items-center rounded {{ $show ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#goods-collapse"
                        aria-expanded="false"
                        aria-controls="goods-collapse"
                >
                    <i class="bi bi-cart4"></i>
                    &nbsp;{{ __('admin.goods') }}
                </button>
                <div class="collapse {{ $show ? 'show' : '' }}" id="goods-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            @php
                                $show = Request::is('*admin/goods/order*');
                            @endphp
                            <a href="{{ route('admin.orders', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.orders') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*admin/goods/categor*');
                            @endphp
                            <a href="{{ route('admin.categories', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.categories') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*admin/goods/product*');
                            @endphp
                            <a href="{{ route('admin.products', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.products') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @php
                $show = Request::is('*admin/acl*');
            @endphp
            <li class="nav-item">
                <button class="btn btn-toggle align-items-center rounded {{ $show ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#acl-collapse"
                        aria-expanded="{{ $show ? 'true' : 'false' }}"
                >
                    <i class="bi bi-people"></i>
                    &nbsp;ACL
                </button>
                <div class="collapse {{ $show ? 'show' : '' }}" id="acl-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            @php
                                $show = Request::is('*admin/acl/user*');
                            @endphp
                            <a href="{{ route('admin.users', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.users') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*admin/acl/role*');
                            @endphp
                            <a href="{{ route('admin.roles', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.roles') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*admin/acl/permission*');
                            @endphp
                            <a href="{{ route('admin.permissions', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('admin.permissions') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                @php
                    $show = Request::is('*admin/import');
                @endphp
                <a class="nav-link {{ $show ? 'active' : '' }}" aria-current="page" href="{{ route('admin.import', $locale) }}">
                    <i class="bi bi-upload"></i>
                    &nbsp;{{ __('admin.import') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ url('api/documentation') }}" target="_blank">
                    <i class="bi bi-diagram-3"></i>
                    &nbsp;API
                </a>
            </li>

        </ul>
    </div>
</nav>
