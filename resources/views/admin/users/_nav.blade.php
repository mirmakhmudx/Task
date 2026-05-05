<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
               href="{{ route('admin.home') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">Users</a>
        </li>
    </ul>

    {{-- Faqat users bo'limida ko'rinadi --}}
    @if(request()->routeIs('admin.users.*'))
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            + Create
        </a>
    @endif
</div>
