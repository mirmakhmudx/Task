@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-4" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('cabinet.index') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cabinet.profile.show') }}">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cabinet.tickets.*') ? 'active' : '' }}"
               href="{{ route('cabinet.tickets.index') }}">Tickets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cabinet.dialogs.*') ? 'active' : '' }}"
               href="{{ route('cabinet.dialogs.index') }}">Dialogs</a>
        </li>
    </ul>

    <div class="row g-3 mb-2">
        @php
            $cards = [
                ['label' => "Mening e'lonlarim", 'value' => $stats['adverts']['total'], 'sub' => $stats['adverts']['active'].' aktiv', 'route' => 'cabinet.adverts.index',           'color' => 'primary'],
                ['label' => "O'qilmagan xabarlar", 'value' => $stats['unreadMessages'], 'sub' => 'dialoglar',                          'route' => 'cabinet.dialogs.index',           'color' => 'danger'],
                ['label' => 'Sevimlilar',          'value' => $stats['favorites'],       'sub' => null,                               'route' => 'cabinet.adverts.favorites.index', 'color' => 'warning'],
                ['label' => 'Ochiq tiketlar',      'value' => $stats['openTickets'],     'sub' => null,                               'route' => 'cabinet.tickets.index',           'color' => 'info'],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small mb-1">{{ $card['label'] }}</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fs-3 fw-bold">{{ $card['value'] }}</span>
                            <span class="badge text-bg-{{ $card['color'] }} rounded-pill">&nbsp;</span>
                        </div>
                        @if($card['sub'])
                            <div class="text-muted small mt-1">{{ $card['sub'] }}</div>
                        @endif
                        <a href="{{ route($card['route']) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-primary btn-sm">+ E'lon berish</a>
            <a href="{{ route('cabinet.adverts.index') }}" class="btn btn-outline-secondary btn-sm">Mening e'lonlarim</a>
            <a href="{{ route('cabinet.banners.index') }}" class="btn btn-outline-secondary btn-sm">Bannerlar</a>
            <a href="{{ route('cabinet.adverts.favorites.index') }}" class="btn btn-outline-secondary btn-sm">Sevimlilar</a>
            <a href="{{ route('cabinet.profile.show') }}" class="btn btn-outline-secondary btn-sm">Profil</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 fw-semibold">Oxirgi e'lonlaringiz</div>
        <div class="card-body p-0">
            @php
                $badges = [
                    \App\Entity\Adverts\Advert::STATUS_DRAFT      => 'secondary',
                    \App\Entity\Adverts\Advert::STATUS_MODERATION => 'warning',
                    \App\Entity\Adverts\Advert::STATUS_ACTIVE     => 'success',
                    \App\Entity\Adverts\Advert::STATUS_CLOSED     => 'dark',
                ];
            @endphp
            @forelse($recentAdverts as $advert)
                <a href="{{ route('cabinet.adverts.show', $advert) }}"
                   class="d-flex justify-content-between align-items-center px-3 py-2 border-top text-decoration-none text-dark">
                    <div>
                        <div class="fw-semibold">{{ $advert->title }}</div>
                        <div class="text-muted small">
                            {{ $advert->category->name ?? '-' }} &middot; {{ $advert->region->name ?? 'Region tanlanmagan' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge text-bg-{{ $badges[$advert->status] ?? 'secondary' }}">{{ ucfirst($advert->status) }}</span>
                        <div class="text-muted small mt-1">{{ $advert->price ? number_format($advert->price, 0, '.', ' ')." so'm" : '-' }}</div>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-4">
                    Hali e'lon yo'q. <a href="{{ route('cabinet.adverts.create.category') }}">Birinchi e'loningizni bering</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
