@extends('layouts.app')

@section('content')

    <nav style="margin-bottom:18px;">
        <ol class="breadcrumb mb-0" style="font-size:0.82rem;background:none;padding:0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#2563eb;text-decoration:none;">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('adverts.index') }}" style="color:#2563eb;text-decoration:none;">Adverts</a></li>
            @if($advert->region)
                <li class="breadcrumb-item"><span style="color:#6b7280;">{{ $advert->region->name }}</span></li>
            @endif
            @if($advert->category)
                <li class="breadcrumb-item"><span style="color:#6b7280;">{{ $advert->category->name }}</span></li>
            @endif
            <li class="breadcrumb-item active" style="color:#374151;">{{ $advert->title }}</li>
        </ol>
    </nav>

    @if($advert->isDraft())
        <div style="background:#fff8e1;border:1px solid #fde68a;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#92400e;">
            Qoralama — hali nashr etilmagan.
        </div>
    @elseif($advert->isModeration())
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 18px;margin-bottom:18px;font-size:0.88rem;color:#1d4ed8;">
            Moderatsiyada — tekshirilmoqda.
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

    {{-- E'LON EGASIGA: Edit / Photos / Publish / Delete --}}
    @auth
        @if(Auth::id() === $advert->user_id)
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('cabinet.adverts.attributes.edit', $advert) }}"
                   style="display:inline-flex;align-items:center;padding:6px 16px;background:#fff;border:1px solid #d1d5db;border-radius:8px;color:#374151;font-size:0.84rem;text-decoration:none;">Edit</a>
                <a href="{{ route('cabinet.adverts.photos.edit', $advert) }}"
                   style="display:inline-flex;align-items:center;padding:6px 16px;background:#fff;border:1px solid #d1d5db;border-radius:8px;color:#374151;font-size:0.84rem;text-decoration:none;">Photos</a>
                @if($advert->isDraft())
                    <form method="POST" action="{{ route('cabinet.adverts.send-to-moderation', $advert) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="display:inline-flex;align-items:center;padding:6px 16px;background:#16a34a;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                            Moderatsiyaga yuborish
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" style="display:inline;"
                      onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="display:inline-flex;align-items:center;padding:6px 16px;background:#ef4444;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                        Delete
                    </button>
                </form>
            </div>
        @endif

        {{-- ADMINGA: Tasdiqlash / Rad etish --}}
        @can('admin-panel')
            @if($advert->isModeration())
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <form method="POST" action="{{ route('admin.adverts.moderate', $advert) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="display:inline-flex;align-items:center;padding:6px 16px;background:#16a34a;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                            ✓ Tasdiqlash
                        </button>
                    </form>
                    <a href="{{ route('admin.adverts.reject.form', $advert) }}"
                       style="display:inline-flex;align-items:center;padding:6px 16px;background:#ef4444;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;text-decoration:none;">
                        ✕ Rad etish
                    </a>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.adverts.destroy', $advert) }}" style="display:inline;"
                  onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                @csrf @method('DELETE')
                <button type="submit" style="display:inline-flex;align-items:center;padding:6px 16px;background:#ef4444;border:none;border-radius:8px;color:#fff;font-size:0.84rem;font-weight:600;cursor:pointer;">
                    Admin: Delete
                </button>
            </form>
        @endcan
    @endauth

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <h2 style="font-size:1.6rem;font-weight:700;color:#111827;letter-spacing:-0.4px;margin:0;">{{ $advert->title }}</h2>
                <div style="font-size:1.6rem;font-weight:700;color:#111827;white-space:nowrap;margin-left:16px;">
                    {{ $advert->price ? number_format($advert->price, 0, '.', ' ') : '—' }}
                </div>
            </div>

            @if($advert->photos->count())
                <div style="display:grid;grid-template-columns:1fr 120px;grid-template-rows:repeat(2,100px);gap:6px;margin-bottom:24px;border-radius:12px;overflow:hidden;">
                    <div style="grid-row:1/3;">
                        <img src="{{ Storage::url($advert->photos->first()->file) }}"
                             alt="{{ $advert->title }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    @foreach($advert->photos->skip(1)->take(2) as $photo)
                        <div>
                            <img src="{{ Storage::url($photo->file) }}" alt="photo"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @endforeach
                </div>
                @if($advert->photos->count() > 3)
                    <div style="display:flex;gap:6px;margin-bottom:24px;flex-wrap:wrap;">
                        @foreach($advert->photos->skip(3) as $photo)
                            <img src="{{ Storage::url($photo->file) }}" alt="photo"
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

            @if($advert->content)
                <div style="margin-bottom:24px;color:#374151;font-size:0.95rem;line-height:1.75;">
                    {!! nl2br(e($advert->content)) !!}
                </div>
            @endif

            @if($advert->address)
                <div style="margin-bottom:24px;">
                    <p style="font-size:0.88rem;color:#374151;margin-bottom:10px;"><strong>Address:</strong> {{ $advert->address }}</p>
                    <div style="border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
                        <iframe width="100%" height="300" frameborder="0" scrolling="no"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=&layer=mapnik&query={{ urlencode($advert->address) }}"
                            style="display:block;"></iframe>
                        <div style="padding:7px 14px;background:#f9fafb;border-top:1px solid #e5e7eb;">
                            <a href="https://www.openstreetmap.org/search?query={{ urlencode($advert->address) }}"
                               target="_blank" style="font-size:0.78rem;color:#2563eb;text-decoration:none;">
                                Kattaroq xaritada ko'rish
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div style="margin-bottom:32px;">
                <p style="font-size:0.9rem;color:#374151;margin-bottom:14px;">
                    Seller: <strong>{{ $advert->user->name }}</strong>
                </p>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @auth
                        @if(Auth::id() !== $advert->user_id)
                            <a href="{{ route('cabinet.dialogs.write', $advert) }}"
                               style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#16a34a;color:#fff;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;">
                                Send Message
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#16a34a;color:#fff;border-radius:8px;text-decoration:none;font-size:0.88rem;font-weight:600;">
                            Send Message
                        </a>
                    @endauth

                    @if($advert->user->phone)
                        <button onclick="this.textContent='{{ $advert->user->phone }}';this.style.background='#1d4ed8';"
                                style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                            Show Phone Number
                        </button>
                    @endif

                    @auth
                        @php
                            $isFavorite = \Illuminate\Support\Facades\DB::table('advert_user_favorites')
                                ->where('user_id', Auth::id())
                                ->where('advert_id', $advert->id)->exists();
                        @endphp
                        @if($isFavorite)
                            <form method="POST" action="{{ route('cabinet.adverts.favorites.remove', $advert) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#6b7280;color:#fff;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                                    ★ Remove from Favorites
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('cabinet.adverts.favorites.add', $advert) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="display:inline-flex;align-items:center;gap:7px;padding:10px 20px;background:#f59e0b;color:#fff;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;">
                                    ★ Add to Favorites
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

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
                            {{ $advert->price ? number_format($advert->price, 0, '.', ' ').' UZS' : '—' }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;border-bottom:1px solid #f9fafb;">
                        <span style="font-size:0.85rem;color:#6b7280;">Manzil</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;text-align:right;max-width:55%;">{{ $advert->address ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:11px 20px;{{ $advert->published_at ? 'border-bottom:1px solid #f9fafb;' : '' }}">
                        <span style="font-size:0.85rem;color:#6b7280;">Yaratilgan</span>
                        <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->created_at->format('d.m.Y') }}</span>
                    </div>
                    @if($advert->published_at)
                        <div style="display:flex;justify-content:space-between;padding:11px 20px;">
                            <span style="font-size:0.85rem;color:#6b7280;">Nashr etilgan</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $advert->published_at->format('d.m.Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($advert->values->count())
                <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-top:16px;">
                    <div style="padding:12px 20px;background:#f9fafb;border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Xususiyatlar</span>
                    </div>
                    @foreach($advert->values as $value)
                        <div style="display:flex;justify-content:space-between;padding:10px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.84rem;color:#6b7280;">{{ $value->attribute->name }}</span>
                            <span style="font-size:0.84rem;font-weight:600;color:#111827;">{{ $value->value }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($advert->reject_reason)
                <div style="border:1px solid #fecaca;border-radius:14px;background:#fff5f5;padding:16px 20px;margin-top:16px;">
                    <p style="font-size:0.75rem;font-weight:700;color:#b91c1c;text-transform:uppercase;margin-bottom:8px;">Rad etish sababi</p>
                    <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $advert->reject_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    @if(isset($similar) && $similar->count())
        <div style="margin-top:48px;">
            <h5 style="font-size:1.1rem;font-weight:700;color:#111827;margin-bottom:20px;">Shunga o'xshash e'lonlar</h5>
            <div class="row g-3">
                @foreach($similar as $item)
                    <div class="col-md-4">
                        <a href="{{ route('adverts.show', $item) }}" style="text-decoration:none;display:block;height:100%;">
                            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;height:100%;box-shadow:0 1px 4px rgba(0,0,0,0.05);">
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
                                    <p style="font-size:0.95rem;font-weight:600;color:#2563eb;margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->title }}</p>
                                    <p style="font-size:0.92rem;font-weight:700;color:#111827;margin:0;">
                                        {{ $item->price ? number_format($item->price, 0, '.', ' ').' UZS' : '—' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @include('adverts._write_seller')

@endsection
