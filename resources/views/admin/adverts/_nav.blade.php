<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
               href="{{ route('admin.home') }}">Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.adverts.*') && !request()->routeIs('admin.adverts.categories.*') ? 'active' : '' }}"
               href="{{ route('admin.adverts.index') }}">Adverts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}"
               href="{{ route('admin.regions.index') }}">Regions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.adverts.categories.*') ? 'active' : '' }}"
               href="{{ route('admin.adverts.categories.index') }}">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
               href="{{ route('admin.pages.index') }}">Pages</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}"
               href="{{ route('admin.tickets.index') }}">Tickets</a>
        </li>
    </ul>
</div>
