@php
    use \Illuminate\Support\Facades\Request;

    $locale = app()->getLocale();
    $route = Request::route()->getName();
@endphp

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link {{ Request::is('*dashboard') ? 'active' : '' }}" aria-current="page" href="{{ route('dashboard', $locale) }}">
                    <i class="bi bi-shop-window"></i>
                    &nbsp;{{ __('dashboard') }}
                </a>
            </li>

            @php
                $show = Request::is('*dashboard/goods*');
            @endphp
            <li class="nav-item">
                <button class="btn btn-toggle align-items-center rounded {{ $show ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#goods-collapse"
                        aria-expanded="false"
                        aria-controls="goods-collapse"
                >
                    <i class="bi bi-cart4"></i>
                    &nbsp;{{ __('dashboard.goods') }}
                </button>
                <div class="collapse {{ $show ? 'show' : '' }}" id="goods-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li>
                            @php
                                $show = Request::is('*dashboard/goods/order*');
                            @endphp
                            <a href="{{ route('dashboard.orders', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.orders') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*dashboard/goods/categor*');
                            @endphp
                            <a href="{{ route('dashboard.categories', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.categories') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*dashboard/goods/product*');
                            @endphp
                            <a href="{{ route('dashboard.products', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.products') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            @php
                $show = Request::is('*dashboard/acl*');
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
                                $show = Request::is('*dashboard/acl/user*');
                            @endphp
                            <a href="{{ route('dashboard.users', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.users') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*dashboard/acl/role*');
                            @endphp
                            <a href="{{ route('dashboard.roles', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.roles') }}
                            </a>
                        </li>
                        <li>
                            @php
                                $show = Request::is('*dashboard/acl/permission*');
                            @endphp
                            <a href="{{ route('dashboard.permissions', $locale) }}" class="nav-link {{ $show ? 'active' : '' }}">
                                {{ __('dashboard.permissions') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                @php
                    $show = Request::is('*dashboard/import');
                @endphp
                <a class="nav-link {{ $show ? 'active' : '' }}" aria-current="page" href="{{ route('dashboard.import', $locale) }}">
                    <i class="bi bi-upload"></i>
                    &nbsp;{{ __('dashboard.import') }}
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
