@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cabinet.index') ? 'active' : '' }}"
               href="{{ route('cabinet.index') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cabinet.adverts.*') ? 'active' : '' }}"
               href="{{ route('cabinet.adverts.index') }}">Adverts</a>
        </li>
    </ul>
    <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-primary btn-sm">+ Create</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.9rem;">
            <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                <tr>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Title</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Category</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Region</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Status</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adverts as $advert)
                <tr style="border-bottom:1px solid #f0f1f5;">
                    <td style="padding:12px 16px;font-weight:500;color:#1a1a2e;">
                        <a href="{{ route('cabinet.adverts.show', $advert) }}"
                           style="color:#1a1a2e;text-decoration:none;">
                            {{ $advert->title }}
                        </a>
                    </td>
                    <td style="padding:12px 16px;color:#4a4a6a;">{{ $advert->category->name }}</td>
                    <td style="padding:12px 16px;color:#4a4a6a;">{{ $advert->region?->name ?? '—' }}</td>
                    <td style="padding:12px 16px;">
                        @if($advert->isActive())
                            <span style="background:#dcfce7;color:#16a34a;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Active</span>
                        @elseif($advert->isModeration())
                            <span style="background:#fef3c7;color:#d97706;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Moderate</span>
                        @elseif($advert->isClosed())
                            <span style="background:#fee2e2;color:#ef4444;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Closed</span>
                        @else
                            <span style="background:#f3f4f6;color:#6b7280;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Draft</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;color:#4a4a6a;">
                        {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4" style="color:#9ca3af;">
                        E'lon topilmadi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($adverts->hasPages())
    <div class="p-3 border-top" style="border-color:#e8eaf0!important;">
        {{ $adverts->links() }}
    </div>
    @endif
</div>
@endsection
