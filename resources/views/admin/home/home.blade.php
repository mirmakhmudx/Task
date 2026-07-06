@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="row g-3 mb-2">
        @php
            $cards = [
                ['label' => 'Foydalanuvchilar', 'value' => $stats['users']['total'],   'sub' => $stats['users']['active'].' aktiv / '.$stats['users']['wait'].' kutilmoqda', 'route' => 'admin.users.index',    'color' => 'primary'],
                ['label' => "E'lonlar",         'value' => $stats['adverts']['total'], 'sub' => $stats['adverts']['active'].' aktiv',                                                    'route' => 'admin.adverts.categories.index', 'color' => 'success'],
                ['label' => 'Moderatsiyada',    'value' => $stats['adverts']['moderation'], 'sub' => 'korib chiqish kerak',                                                              'route' => null,                   'color' => 'warning'],
                ['label' => 'Tiketlar',         'value' => $stats['tickets']['total'], 'sub' => $stats['tickets']['open'].' ochiq',                                                     'route' => 'admin.tickets.index',  'color' => 'info'],
                ['label' => 'Kategoriyalar',    'value' => $stats['categories'],       'sub' => null,                                                                                   'route' => 'admin.adverts.categories.index', 'color' => 'secondary'],
                ['label' => 'Regionlar',        'value' => $stats['regions'],          'sub' => null,                                                                                   'route' => 'admin.regions.index',  'color' => 'secondary'],
                ['label' => 'Sahifalar',        'value' => $stats['pages'],            'sub' => null,                                                                                   'route' => 'admin.pages.index',    'color' => 'secondary'],
                ['label' => 'Bannerlar',        'value' => $stats['banners']['total'], 'sub' => $stats['banners']['active'].' aktiv',                                                   'route' => null,                   'color' => 'dark'],
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
                        @if($card['route'])
                            <a href="{{ route($card['route']) }}" class="stretched-link"></a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="text-muted text-uppercase small mb-3">E'lonlar holati bo'yicha</h6>
            <div class="d-flex flex-wrap gap-4">
                <div><span class="badge text-bg-secondary">Draft</span> <strong>{{ $stats['adverts']['draft'] }}</strong></div>
                <div><span class="badge text-bg-warning">Moderation</span> <strong>{{ $stats['adverts']['moderation'] }}</strong></div>
                <div><span class="badge text-bg-success">Active</span> <strong>{{ $stats['adverts']['active'] }}</strong></div>
                <div><span class="badge text-bg-dark">Closed</span> <strong>{{ $stats['adverts']['closed'] }}</strong></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold">Moderatsiya navbati</div>
                <div class="card-body p-0">
                    @forelse($moderationAdverts as $advert)
                        <a href="{{ route('admin.adverts.show', $advert) }}"
                           class="d-flex justify-content-between align-items-center px-3 py-2 border-top text-decoration-none text-dark">
                            <div>
                                <div class="fw-semibold">{{ $advert->title }}</div>
                                <div class="text-muted small">
                                    {{ $advert->user->name ?? '-' }} &middot; {{ $advert->category->name ?? '-' }}
                                </div>
                            </div>
                            <span class="badge text-bg-warning">Moderation</span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">Moderatsiya kutayotgan e'lon yo'q</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 fw-semibold">Ochiq tiketlar</div>
                <div class="card-body p-0">
                    @forelse($openTickets as $ticket)
                        <a href="{{ route('admin.tickets.show', $ticket) }}"
                           class="d-flex justify-content-between align-items-center px-3 py-2 border-top text-decoration-none text-dark">
                            <div>
                                <div class="fw-semibold">#{{ $ticket->id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->subject, 30) }}</div>
                                <div class="text-muted small">{{ $ticket->user->name ?? '-' }}</div>
                            </div>
                            <span class="badge text-bg-info">Open</span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-4">Ochiq tiket yo'q</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
