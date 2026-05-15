@extends('layouts.app')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('cabinet.index') ? 'active' : '' }}"
               href="{{ route('cabinet.index') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('cabinet.adverts.index') }}">Adverts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cabinet.profile.show') }}">Profile</a>
        </li>
    </ul>
    <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-primary btn-sm">
        + Create
    </a>
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
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;text-align:right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adverts as $advert)
                <tr style="border-bottom:1px solid #f0f1f5;cursor:pointer;"
                    onclick="location.href='{{ route('adverts.show', $advert) }}'">
                    <td style="padding:12px 16px;font-weight:500;color:#1a1a2e;">
                        {{ $advert->title }}
                    </td>
                    <td style="padding:12px 16px;color:#6b7280;font-size:0.85rem;">
                        {{ $advert->category->name }}
                    </td>
                    <td style="padding:12px 16px;color:#6b7280;font-size:0.85rem;">
                        {{ $advert->region?->name ?? '—' }}
                    </td>
                    <td style="padding:12px 16px;">
                        @if($advert->isDraft())
                            <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Draft</span>
                        @elseif($advert->isModeration())
                            <span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Moderation</span>
                        @elseif($advert->isActive())
                            <span style="background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Active</span>
                        @else
                            <span style="background:#fee2e2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Closed</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;text-align:right;font-weight:600;color:#1a1a2e;">
                        {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' so\'m' : '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:#9ca3af;font-size:0.9rem;">
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
