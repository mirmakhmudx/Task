@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-bold" style="color:#111827;letter-spacing:-0.3px;">Mening e'lonlarim</h5>
            <span style="background:#f3f4f6;color:#6b7280;font-size:0.78rem;font-weight:600;padding:3px 10px;border-radius:20px;">
            {{ $adverts->total() }}
        </span>
        </div>
        <a href="{{ route('cabinet.adverts.create.category') }}"
           class="btn btn-sm d-flex align-items-center gap-2"
           style="background:#111827;color:#fff;border-radius:10px;padding:8px 16px;font-size:0.85rem;font-weight:600;border:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Yangi e'lon
        </a>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;font-size:0.875rem;color:#15803d;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        @if($adverts->isEmpty())
            <div class="text-center py-5">
                <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3 d-block mx-auto">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="mb-1 fw-medium" style="color:#374151;">E'lonlar yo'q</p>
                <p class="mb-3" style="font-size:0.85rem;color:#9ca3af;">Birinchi e'loningizni yarating</p>
                <a href="{{ route('cabinet.adverts.create.category') }}"
                   class="btn btn-sm" style="background:#111827;color:#fff;border-radius:8px;font-size:0.85rem;">
                    E'lon yaratish
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:0.875rem;">
                    <thead>
                    <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                        <th style="padding:11px 20px;font-weight:600;color:#6b7280;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;border:none;">Sarlavha</th>
                        <th style="padding:11px 16px;font-weight:600;color:#6b7280;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;border:none;">Kategoriya</th>
                        <th style="padding:11px 16px;font-weight:600;color:#6b7280;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;border:none;">Region</th>
                        <th style="padding:11px 16px;font-weight:600;color:#6b7280;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;border:none;">Status</th>
                        <th style="padding:11px 16px;font-weight:600;color:#6b7280;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;border:none;">Narx</th>
                        <th style="padding:11px 16px;border:none;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($adverts as $advert)
                        <tr style="border-bottom:1px solid #f3f4f6;"
                            onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                            <td style="padding:14px 20px;border:none;">
                                <a href="{{ route('cabinet.adverts.show', $advert) }}"
                                   style="color:#111827;font-weight:600;text-decoration:none;font-size:0.9rem;">
                                    {{ $advert->title }}
                                </a>
                                <div style="font-size:0.78rem;color:#9ca3af;margin-top:2px;">
                                    {{ $advert->created_at->format('d.m.Y') }}
                                </div>
                            </td>
                            <td style="padding:14px 16px;color:#4b5563;border:none;vertical-align:middle;">{{ $advert->category->name }}</td>
                            <td style="padding:14px 16px;color:#4b5563;border:none;vertical-align:middle;">{{ $advert->region?->name ?? '—' }}</td>
                            <td style="padding:14px 16px;border:none;vertical-align:middle;">
                                @if($advert->isActive())
                                    <span style="background:#dcfce7;color:#15803d;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;">● Aktiv</span>
                                @elseif($advert->isModeration())
                                    <span style="background:#fef9c3;color:#854d0e;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;">● Moderatsiya</span>
                                @elseif($advert->isClosed())
                                    <span style="background:#fee2e2;color:#b91c1c;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;">● Yopiq</span>
                                @else
                                    <span style="background:#f3f4f6;color:#6b7280;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;">● Qoralama</span>
                                @endif
                            </td>
                            <td style="padding:14px 16px;color:#4b5563;border:none;vertical-align:middle;font-weight:500;">
                                {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                            </td>
                            <td style="padding:14px 16px;border:none;vertical-align:middle;text-align:right;">
                                <a href="{{ route('cabinet.adverts.show', $advert) }}"
                                   style="color:#6b7280;font-size:0.8rem;text-decoration:none;padding:5px 12px;border:1px solid #e5e7eb;border-radius:8px;white-space:nowrap;">
                                    Ko'rish →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($adverts->hasPages())
                <div class="px-4 py-3" style="border-top:1px solid #f3f4f6;">
                    {{ $adverts->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
