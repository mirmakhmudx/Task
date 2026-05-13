@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <ul class="nav nav-tabs" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cabinet.index') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('cabinet.adverts.index') }}">Adverts</a>
        </li>
    </ul>
</div>

<a href="{{ route('cabinet.adverts.index') }}"
   class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"
   style="font-size:0.85rem;color:#6b7280;">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
    </svg>
    Orqaga
</a>

<div class="card">
    <div class="card-body">
        <h6 class="fw-semibold mb-4" style="color:#1a1a2e;">{{ $advert->title }}</h6>

        <table class="table mb-0" style="font-size:0.9rem;">
            <tbody>
                <tr style="background:#f8f9fb;">
                    <td style="width:25%;font-weight:600;color:#6b7280;border:none;padding:11px 16px;">ID</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $advert->id }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Kategoriya</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $advert->category->name }}</td>
                </tr>
                <tr style="background:#f8f9fb;">
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Region</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $advert->region?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Narx</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">
                        {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                    </td>
                </tr>
                <tr style="background:#f8f9fb;">
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Manzil</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $advert->address ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Status</td>
                    <td style="border:none;padding:11px 16px;">
                        @if($advert->isActive())
                            <span style="background:#dcfce7;color:#16a34a;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Active</span>
                        @elseif($advert->isModerate())
                            <span style="background:#fef3c7;color:#d97706;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Moderate</span>
                        @elseif($advert->isClosed())
                            <span style="background:#fee2e2;color:#ef4444;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Closed</span>
                        @else
                            <span style="background:#f3f4f6;color:#6b7280;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Draft</span>
                        @endif
                    </td>
                </tr>
                <tr style="background:#f8f9fb;">
                    <td style="font-weight:600;color:#6b7280;border:none;padding:11px 16px;">Tavsif</td>
                    <td style="color:#1a1a2e;border:none;padding:11px 16px;">{{ $advert->content }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
