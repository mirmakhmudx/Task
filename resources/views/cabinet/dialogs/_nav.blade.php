<ul class="nav nav-tabs" style="border-bottom:none;">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.index') ? 'active' : '' }}"
           href="{{ route('cabinet.index') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.adverts.index') ? 'active' : '' }}"
           href="{{ route('cabinet.adverts.index') }}">Adverts</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.adverts.favorites.*') ? 'active' : '' }}"
           href="{{ route('cabinet.adverts.favorites.index') }}">Favorites</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.banners.*') ? 'active' : '' }}"
           href="{{ route('cabinet.banners.index') }}">Banners</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.tickets.*') ? 'active' : '' }}"
           href="{{ route('cabinet.tickets.index') }}">Tickets</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.dialogs.*') ? 'active' : '' }}"
           href="{{ route('cabinet.dialogs.index') }}">Dialogs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cabinet.profile.*') ? 'active' : '' }}"
           href="{{ route('cabinet.profile.show') }}">Profile</a>
    </li>
</ul>
