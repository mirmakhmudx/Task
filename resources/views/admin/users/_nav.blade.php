<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="nav-item ms-auto">
        <a class="btn btn-success btn-sm" href="{{ route('admin.users.create') }}">+ Create</a>
    </li>
</ul>
