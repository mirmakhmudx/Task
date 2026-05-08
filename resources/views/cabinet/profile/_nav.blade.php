<ul class="nav nav-tabs mb-4" style="border-bottom:none;">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.index') ? 'active' : '' }}"
           href="{{ route('cabinet.index') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.profile.*') ? 'active' : '' }}"
           href="{{ route('cabinet.profile.show') }}">Profile</a>
    </li>
</ul>
