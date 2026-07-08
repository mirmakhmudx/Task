@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.adverts.index') }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#111827;">{{ $advert->title }}</h5>
            <span style="font-size:0.78rem;color:#9ca3af;">E'lon #{{ $advert->id }} &middot; {{ $advert->user->name ?? '—' }}</span>
        </div>
        <div class="ms-auto">
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

    @if(session('success'))
        <div class="mb-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;color:#15803d;font-size:0.875rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            @foreach($errors->all() as $error)
                <div style="font-size:0.85rem;color:#dc2626;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ADMIN AMALLAR --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        @if($advert->isModeration())
            <form method="POST" action="{{ route('admin.adverts.moderate', $advert) }}" style="display:inline;">
                @csrf
                <button type="submit" style="padding:8px 20px;background:#16a34a;border:none;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;cursor:pointer;">
                    ✓ Tasdiqlash
                </button>
            </form>
            <a href="{{ route('admin.adverts.reject.form', $advert) }}"
               style="display:inline-flex;align-items:center;padding:8px 20px;background:#f59e0b;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;text-decoration:none;">
                Rad etish
            </a>
        @endif

        <form method="POST" action="{{ route('admin.adverts.destroy', $advert) }}" style="display:inline;"
              onsubmit="return confirm('E\'lonni o\'chirishni tasdiqlaysizmi?')">
            @csrf @method('DELETE')
            <button type="submit" style="padding:8px 20px;background:#ef4444;border:none;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;cursor:pointer;">
                O'chirish
            </button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            @if($advert->photos->count())
                <div class="card mb-3" style="border-radius:14px;">
                    <div class="card-body p-0">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Rasmlar</span>
                        </div>
                        <div class="p-3">
                            <div class="row g-2">
                                @foreach($advert->photos as $photo)
                                    <div class="col-4 col-md-3">
                                        <img src="{{ Storage::url($photo->file) }}" alt="photo"
                                             style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mb-3" style="border-radius:14px;">
                <div class="card-body p-0">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Tavsif</span>
                    </div>
                    <div style="padding:20px;color:#374151;font-size:0.9rem;line-height:1.7;">
                        {{ $advert->content ?: '—' }}
                    </div>
                </div>
            </div>

            @if($advert->values->count())
                <div class="card" style="border-radius:14px;">
                    <div class="card-body p-0">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Xususiyatlar</span>
                        </div>
                        @foreach($advert->values as $value)
                            <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                                <span style="font-size:0.85rem;color:#6b7280;">{{ $value->attribute->name }}</span>
                                <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $value->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-3" style="border-radius:14px;">
                <div class="card-body p-0">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Ma'lumotlar</span>
                    </div>
                    <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Egasi</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->user->name ?? '—' }}</span>
                    </div>
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
                            {{ $advert->price ? number_format($advert->price, 0, '.', ' ').' UZS' : '—' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Status</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ ucfirst($advert->status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="padding:11px 20px;">
                        <span style="font-size:0.85rem;color:#6b7280;">Yaratilgan</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>

            @if($advert->reject_reason)
                <div class="card" style="border:1px solid #fecaca;border-radius:14px;background:#fff5f5;">
                    <div class="card-body" style="padding:16px 20px;">
                        <p style="font-size:0.78rem;font-weight:700;color:#b91c1c;text-transform:uppercase;margin-bottom:8px;">Rad etish sababi</p>
                        <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $advert->reject_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
