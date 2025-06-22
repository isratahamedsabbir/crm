<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset(settings()->logo ?? 'default/logo.svg') }}" id="header-brand-logo" alt="logo" width="60" height="60">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu mt-2">
                <li>
                    <h3>Menu</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('dashboard') ? 'has-link active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-house side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <li class="slide {{ auth()->user()->role == 'admin' ? '' : 'd-none' }}">
                    <a class="side-menu__item {{  request()->routeIs('admin.category.*') ? 'has-link active' : '' }}" href="{{ route('admin.category.index') }}">
                        <i class="fa-solid fa-list side-menu__icon"></i>
                        <span class="side-menu__label">Category</span>
                    </a>
                </li>
                <li class="slide {{ auth()->user()->role == 'admin' ? '' : 'd-none' }}">
                    <a class="side-menu__item {{  request()->routeIs('admin.type.*') ? 'has-link active' : '' }}" href="{{ route('admin.type.index') }}">
                        <i class="fa-solid fa-layer-group side-menu__icon"></i>
                        <span class="side-menu__label">Type</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('admin.client.*') ? 'has-link active' : '' }}" href="{{ route('admin.client.index') }}">
                        <i class="fa-solid fa-chalkboard-user side-menu__icon"></i>
                        <span class="side-menu__label">Client</span>
                    </a>
                </li>
                <li class="slide {{ auth()->user()->role == 'admin' ? '' : 'd-none' }}">
                    <a class="side-menu__item {{  request()->routeIs('admin.users.*') ? 'has-link active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fa-solid fa-users side-menu__icon"></i>
                        <span class="side-menu__label">Users</span>
                    </a>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->