@extends('layouts.app')

@section('content')

    {{-- BREADCRUMB --}}
    <nav style="margin-bottom:18px;">
        <ol class="breadcrumb mb-0" style="font-size:0.82rem;background:none;padding:0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#2563eb;text-decoration:none;">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cabinet.adverts.index') }}" style="color:#2563eb;text-decoration:none;">Adverts</a></li>
            @if($advert->region)
                <li class="breadcrumb-item"><span style="color:#6b7280;">{{ $advert->region->name }}</span></li>
            @endif
            @if($advert->category)
                <li class="breadcrumb-item"><span style="color:#6b7280;">{{ $advert->category->name }}</span></li>
            @endif
            <li class="breadcrumb-item active" style="color:#374151;">{{ $advert->title }}</li>
        </ol>
    </nav>

    {{-- STATUS BANNER --}}
    @if($advert->isDraft())
        <div style="background:#fff8e1;border:1px solid #fde68a;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#92400e;">
            It is a draft.
        </div>
    @elseif($advert->isModeration())
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#1d4ed8;">
            Moderatsiyada.
        </div>
    @elseif($advert->isClosed())
        <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#9f1239;">
            Yopiq.
        </div>
    @endif

    @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#15803d;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:10px 18px;margin-bottom:18px;">
            @foreach($errors->all() as $error)
                <div style="font-size:0.85rem;color:#dc2626;">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- ACTION BUTTONS --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('cabinet.adverts.attributes.edit', $advert) }}"
           style="display:inline-flex;align-items:center;padding:6px 16px;background:#fff;border:1px solid #d1d5db;border-radius:8px;color:#374151;font-size:0.84rem;text-decoration:none;">
            Edit
        </a>
        <a href="{{ route('cabinet.adverts.photos.edit', $advert) }}"
           style="display:inline-flex;align-items:center;padding:6px 16px;background:#fff;border:1px solid #d1d5db;border-radius:8px;color:#374151;font-size:0.84rem;text-decoration:none;">
            Photos
        </a>

        @if($advert->isDraft())
            <form method="POST" action="{{ route('cabinet.adverts.send-to-moderation', $advert) }}" style="display:inline;">
                @csrf
                <button type="submit"
                        style="display:inline-flex;align-items:center;padding:6px 16px;background:#16a34a;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                    Publish
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" style="display:inline;"
              onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="display:inline-flex;align-items:center;padding:6px 16px;background:#ef4444;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                Delete
            </button>
        </form>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- Title + Price --}}
            <div class="d-flex align-items-start justify-content-between mb-3">
                <h2 style="font-size:1.6rem;font-weight:700;color:#111827;letter-spacing:-0.4px;margin:0;">
                    {{ $advert->title }}
                </h2>
                <div style="font-size:1.6rem;font-weight:700;color:#111827;white-space:nowrap;margin-left:16px;">
                    {{ $advert->price ? number_format($advert->price, 0, '.', ' ') : '—' }}
                </div>
            </div>

            {{-- PHOTOS --}}
            @if($advert->photos->count())
                <div style="display:grid;grid-template-columns:1fr 120px;grid-template-rows:repeat(2,100px);gap:6px;margin-bottom:24px;border-radius:12px;overflow:hidden;">
                    <div style="grid-row:1/3;">
                        <img src="{{ Storage::url($advert->photos->first()->file) }}"
                             alt="{{ $advert->title }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    @foreach($advert->photos->skip(1)->take(2) as $photo)
                        <div>
                            <img src="{{ Storage::url($photo->file) }}"
                                 alt="photo"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @endforeach
                </div>
                @if($advert->photos->count() > 3)
                    <div style="display:flex;gap:6px;margin-bottom:24px;flex-wrap:wrap;">
                        @foreach($advert->photos->skip(3) as $photo)
                            <img src="{{ Storage::url($photo->file) }}"
                                 alt="photo"
                                 style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
                        @endforeach
                    </div>
                @endif
            @else
                <div style="width:100%;height:260px;background:#f3f4f6;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
                    <svg width="56" height="56" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- DESCRIPTION --}}
            @if($advert->content)
                <div style="margin-bottom:24px;color:#374151;font-size:0.95rem;line-height:1.75;">
                    {!! nl2br(e($advert->content)) !!}
                </div>
            @endif

            {{-- MAP — OpenStreetMap iframe (API key shart emas) --}}
            @if($advert->address)
                <div style="margin-bottom:24px;">
                    <p style="font-size:0.88rem;color:#374151;margin-bottom:10px;">
                        <strong>Address:</strong> {{ $advert->address }}
                    </p>
                    <div style="border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                        <iframe
                            width="100%"
                            height="300"
                            frameborder="0"
                            scrolling="no"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=&layer=mapnik&query={{ urlencode($advert->address) }}"
                            style="display:block;">
                        </iframe>
                        <div style="padding:7px 14px;background:#f9fafb;border-top:1px solid #e5e7eb;">
                            <a href="https://www.openstreetmap.org/search?query={{ urlencode($advert->address) }}"
                               target="_blank"
                               style="font-size:0.78rem;color:#2563eb;text-decoration:none;">
                                🗺 Kattaroq xaritada ko'rish
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- SELLER --}}
            <div style="margin-bottom:32px;">
                <p style="font-size:0.9rem;color:#374151;margin-bottom:14px;">
                    Seller: <strong>{{ $advert->user->name }}</strong>
                </p>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="mailto:"
                       style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#16a34a;color:#fff;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Message
                    </a>
                    @if($advert->user->phone)
                        <button onclick="this.textContent='{{ $advert->user->phone }}';this.style.background='#1d4ed8';"
                                style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Show Phone Number
                        </button>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">
            <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;position:sticky;top:20px;">
                <div style="padding:12px 20px;background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                    <span style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Ma'lumotlar</span>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Kategoriya</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->category->name }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Region</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->region?->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Narx</span>
                        <span style="font-size:0.85rem;font-weight:700;color:#111827;">
                            {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Manzil</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;text-align:right;max-width:55%;">{{ $advert->address ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;{{ $advert->published_at || $advert->expires_at ? 'border-bottom:1px solid #f9fafb;' : '' }}">
                        <span style="font-size:0.85rem;color:#6b7280;">Yaratilgan</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->created_at->format('d.m.Y') }}</span>
                    </div>
                    @if($advert->published_at)
                        <div style="display:flex;justify-content:space-between;padding:11px 20px;{{ $advert->expires_at ? 'border-bottom:1px solid #f9fafb;' : '' }}">
                            <span style="font-size:0.85rem;color:#6b7280;">Nashr etilgan</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->published_at->format('d.m.Y') }}</span>
                        </div>
                    @endif
                    @if($advert->expires_at)
                        <div style="display:flex;justify-content:space-between;padding:11px 20px;">
                            <span style="font-size:0.85rem;color:#6b7280;">Tugaydi</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->expires_at->format('d.m.Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Attributes --}}
            @if($advert->values->count())
                <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-top:16px;">
                    <div style="padding:12px 20px;background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Xususiyatlar</span>
                    </div>
                    <div>
                        @foreach($advert->values as $value)
                            <div style="display:flex;justify-content:space-between;padding:10px 20px;border-bottom:1px solid #f9fafb;">
                                <span style="font-size:0.84rem;color:#6b7280;">{{ $value->attribute->name }}</span>
                                <span style="font-size:0.84rem;font-weight:600;color:#111827;">{{ $value->value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reject reason --}}
            @if($advert->reject_reason)
                <div style="border:1px solid #fecaca;border-radius:14px;background:#fff5f5;padding:16px 20px;margin-top:16px;">
                    <p style="font-size:0.75rem;font-weight:700;color:#b91c1c;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Rad etish sababi</p>
                    <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $advert->reject_reason }}</p>
                </div>
            @endif
        </div>

    </div>

    {{-- SIMILAR ADVERTS --}}
    @if(isset($similar) && $similar->count())
        <div style="margin-top:48px;">
            <h5 style="font-size:1.1rem;font-weight:700;color:#111827;margin-bottom:20px;">
                Similar adverts
            </h5>
            <div class="row g-3">
                @foreach($similar as $item)
                    <div class="col-md-4">
                        <a href="{{ route('cabinet.adverts.show', $item) }}" style="text-decoration:none;display:block;height:100%;">
                            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;height:100%;
                                        box-shadow:0 1px 4px rgba(0,0,0,0.05);"
                                 onmouseover="this.style.boxShadow='0 6px 18px rgba(0,0,0,0.1)'"
                                 onmouseout="this.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'">
                                @if($item->photos->first())
                                    <img src="{{ Storage::url($item->photos->first()->file) }}"
                                         alt="{{ $item->title }}"
                                         style="width:100%;height:190px;object-fit:cover;display:block;">
                                @else
                                    <div style="width:100%;height:190px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                                        <svg width="42" height="42" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div style="padding:14px 16px 16px;">
                                    <p style="font-size:0.95rem;font-weight:600;color:#2563eb;margin:0 0 6px;
                                              white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $item->title }}
                                    </p>
                                    @if($item->content)
                                        <p style="font-size:0.82rem;color:#6b7280;margin:0 0 10px;line-height:1.55;
                                                  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                            {{ $item->content }}
                                        </p>
                                    @endif
                                    <p style="font-size:0.92rem;font-weight:700;color:#111827;margin:0;">
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
