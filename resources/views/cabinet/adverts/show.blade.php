@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('cabinet.adverts.index') }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#111827;letter-spacing:-0.3px;">{{ $advert->title }}</h5>
            <span style="font-size:0.78rem;color:#9ca3af;">E'lon #{{ $advert->id }}</span>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            @if($advert->isActive())
                <span style="background:#dcfce7;color:#15803d;font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:20px;">● Aktiv</span>
            @elseif($advert->isModeration())
                <span style="background:#fef9c3;color:#854d0e;font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:20px;">● Moderatsiya</span>
            @elseif($advert->isClosed())
                <span style="background:#fee2e2;color:#b91c1c;font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:20px;">● Yopiq</span>
            @else
                <span style="background:#f3f4f6;color:#6b7280;font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:20px;">● Qoralama</span>
            @endif
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="mb-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;font-size:0.875rem;color:#15803d;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Action buttons --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('cabinet.adverts.attributes.edit', $advert) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e5e7eb;border-radius:10px;font-size:0.85rem;font-weight:500;color:#374151;text-decoration:none;background:#fff;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Xususiyatlar
        </a>
        <a href="{{ route('cabinet.adverts.photos.edit', $advert) }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #e5e7eb;border-radius:10px;font-size:0.85rem;font-weight:500;color:#374151;text-decoration:none;background:#fff;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Rasmlar ({{ $advert->photos->count() }})
        </a>

        @if($advert->isDraft())
            <form method="POST" action="{{ route('cabinet.adverts.send-to-moderation', $advert) }}">
                @csrf
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:none;border-radius:10px;font-size:0.85rem;font-weight:600;color:#fff;background:#2563eb;cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Moderatsiyaga yuborish
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}"
              onsubmit="return confirm('E\'lonni o\'chirishni tasdiqlaysizmi?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border:1px solid #fecaca;border-radius:10px;font-size:0.85rem;font-weight:500;color:#dc2626;background:#fff;cursor:pointer;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                O'chirish
            </button>
        </form>
    </div>

    <div class="row g-3">

        {{-- Left: photos + description + attributes + map --}}
        <div class="col-md-8">

            {{-- Photos --}}
            @if($advert->photos->count())
                <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                    <div class="card-body p-0">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Rasmlar</span>
                        </div>
                        <div class="p-3">
                            <div class="row g-2">
                                @foreach($advert->photos as $photo)
                                    <div class="col-4 col-md-3">
                                        <img src="{{ Storage::url($photo->file) }}"
                                             alt="photo"
                                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Description --}}
            <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div class="card-body p-0">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Tavsif</span>
                    </div>
                    <div style="padding:20px;color:#374151;font-size:0.9rem;line-height:1.7;">
                        {{ $advert->content }}
                    </div>
                </div>
            </div>

            {{-- Attributes --}}
            @if($advert->values->count())
                <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                    <div class="card-body p-0">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Xususiyatlar</span>
                        </div>
                        <div style="padding:4px 0;">
                            @foreach($advert->values as $value)
                                <div class="d-flex align-items-center justify-content-between"
                                     style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                                    <span style="font-size:0.85rem;color:#6b7280;">{{ $value->attribute->name }}</span>
                                    <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $value->value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Map (Yandex Maps) --}}
            @if($advert->address)
                <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                    <div class="card-body p-0">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;display:flex;align-items:center;gap:8px;">
                            <svg width="14" height="14" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Manzil</span>
                        </div>
                        <div style="padding:14px 20px 10px;">
                            <p style="font-size:0.875rem;color:#374151;margin-bottom:12px;">{{ $advert->address }}</p>
                        </div>
                        {{-- Yandex Maps iframe --}}
                        <div style="height:280px;overflow:hidden;">
                            <iframe
                                src="https://maps.yandex.ru/?text={{ urlencode($advert->address) }}&z=14&l=map&output=embed"
                                width="100%"
                                height="280"
                                frameborder="0"
                                allowfullscreen
                                style="border:none;display:block;">
                            </iframe>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right: info card --}}
        <div class="col-md-4">
            <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div class="card-body p-0">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Ma'lumotlar</span>
                    </div>
                    <div style="padding:4px 0;">
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">Kategoriya</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->category->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">Region</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->region?->name ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">Narx</span>
                            <span style="font-size:0.85rem;font-weight:700;color:#111827;">
                                {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">Manzil</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;text-align:right;max-width:60%;">{{ $advert->address ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">Yaratilgan</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->created_at->format('d.m.Y') }}</span>
                        </div>
                        @if($advert->published_at)
                            <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                                <span style="font-size:0.85rem;color:#6b7280;">Nashr etilgan</span>
                                <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->published_at->format('d.m.Y') }}</span>
                            </div>
                        @endif
                        @if($advert->expires_at)
                            <div class="d-flex justify-content-between" style="padding:11px 20px;">
                                <span style="font-size:0.85rem;color:#6b7280;">Tugaydi</span>
                                <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->expires_at->format('d.m.Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Reject reason --}}
            @if($advert->reject_reason)
                <div class="card" style="border:1px solid #fecaca;border-radius:14px;background:#fff5f5;">
                    <div class="card-body" style="padding:16px 20px;">
                        <p style="font-size:0.78rem;font-weight:700;color:#b91c1c;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Rad etish sababi</p>
                        <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $advert->reject_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Similar Adverts --}}
    @if($similar->count())
        <div class="mt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0" style="color:#111827;letter-spacing:-0.2px;">O'xshash e'lonlar</h6>
                <a href="{{ route('cabinet.adverts.index') }}"
                   style="font-size:0.82rem;color:#6b7280;text-decoration:none;">
                    Hammasini ko'rish →
                </a>
            </div>
            <div class="row g-3">
                @foreach($similar as $item)
                    <div class="col-md-4">
                        <a href="{{ route('cabinet.adverts.show', $item) }}"
                           style="text-decoration:none;display:block;">
                            <div class="card h-100" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.04);transition:box-shadow 0.2s;"
                                 onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'"
                                 onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'">

                                {{-- Image --}}
                                @if($item->photos->count())
                                    <img src="{{ Storage::url($item->photos->first()->file) }}"
                                         alt="{{ $item->title }}"
                                         style="width:100%;height:160px;object-fit:cover;">
                                @else
                                    <div style="width:100%;height:160px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                        <svg width="32" height="32" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div style="padding:14px 16px;">
                                    <p class="mb-1 fw-semibold" style="color:#111827;font-size:0.9rem;line-height:1.3;
                                       display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $item->title }}
                                    </p>
                                    <p class="mb-2" style="font-size:0.8rem;color:#9ca3af;">
                                        {{ $item->category->name }}
                                    </p>
                                    <p class="mb-0 fw-bold" style="color:#111827;font-size:0.9rem;">
                                        {{ $item->price ? number_format($item->price, 0, '.', ' ') . ' UZS' : '—' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection
