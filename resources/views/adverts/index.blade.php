@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 style="font-size:1.1rem;font-weight:700;color:#111827;margin:0;">E'lonlar</h5>
        <a href="{{ route('cabinet.adverts.create.category') }}"
           style="background:#111827;color:#fff;border-radius:8px;padding:8px 16px;font-size:0.85rem;font-weight:600;text-decoration:none;">
            + E'lon berish
        </a>
    </div>

    {{-- KATEGORIYALAR --}}
    @if($path->category)
        @if($path->category->children->count())
            <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:12px;">
                <div class="card-body p-3">
                    <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">
                        {{ $path->category->name }} bo'limlari
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($path->category->children as $child)
                            <a href="{{ route('adverts.index', adverts_path($path->region, $child)) }}"
                               style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:12px;">
            <div class="card-body p-3">
                <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">Kategoriyalar</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <a href="{{ route('adverts.index', adverts_path($path->region, $category)) }}"
                           style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- REGIONLAR --}}
    @if(!$path->region)
        <div class="card mb-4" style="border:1px solid #e5e7eb;border-radius:12px;">
            <div class="card-body p-3">
                <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">Regionlar</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($regions as $region)
                        <a href="{{ route('adverts.index', adverts_path($region, $path->category)) }}"
                           style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                            {{ $region->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- E'LONLAR RO'YXATI --}}
    @forelse($adverts as $advert)
        <a href="{{ route('adverts.show', $advert) }}" style="text-decoration:none;display:block;margin-bottom:12px;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;
                        box-shadow:0 1px 3px rgba(0,0,0,0.05);display:flex;gap:0;">
                {{-- Rasm --}}
                <div style="width:160px;min-height:120px;flex-shrink:0;background:#f3f4f6;overflow:hidden;">
                    @if($advert->photos->first())
                        <img src="{{ Storage::url($advert->photos->first()->file) }}"
                             alt="{{ $advert->title }}"
                             style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                        <div style="width:100%;height:120px;display:flex;align-items:center;justify-content:center;">
                            <svg width="32" height="32" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Ma'lumot --}}
                <div style="padding:14px 16px;flex:1;">
                    <p style="font-size:1rem;font-weight:600;color:#2563eb;margin:0 0 6px;
                               white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $advert->title }}
                    </p>
                    <p style="font-size:0.82rem;color:#6b7280;margin:0 0 10px;
                               display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $advert->content }}
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <span style="font-size:0.92rem;font-weight:700;color:#111827;">
                            {{ $advert->price ? number_format($advert->price, 0, '.', ' ') . ' UZS' : '—' }}
                        </span>
                        @if($advert->region)
                            <span style="font-size:0.8rem;color:#9ca3af;">{{ $advert->region->name }}</span>
                        @endif
                        @if($advert->category)
                            <span style="font-size:0.8rem;color:#9ca3af;">{{ $advert->category->name }}</span>
                        @endif
                    </div>
                </div>

                {{-- Sana --}}
                <div style="padding:14px;text-align:right;flex-shrink:0;">
                    <span style="font-size:0.78rem;color:#9ca3af;">
                        {{ $advert->published_at?->format('d.m.Y') }}
                    </span>
                </div>
            </div>
        </a>
    @empty
        <div style="text-align:center;padding:60px 20px;color:#9ca3af;">
            <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:12px;">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p style="font-size:0.9rem;">Hozircha e'lonlar yo'q</p>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $adverts->links() }}
    </div>

@endsection
