<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
               href="{{ route('admin.home') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}"
               href="{{ route('admin.regions.index') }}">Regions</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">Users</a>
        </li>
    </ul>

    @if(request()->routeIs('admin.regions.*') && !request()->routeIs('admin.regions.create'))
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary btn-sm">+ Create</a>
    @endif
</div>
