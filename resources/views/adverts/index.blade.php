@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 style="font-size:1.1rem;font-weight:700;color:#111827;margin:0;">E'lonlar</h5>
        <a href="{{ route('cabinet.adverts.create.category') }}"
           style="background:#111827;color:#fff;border-radius:8px;padding:8px 16px;font-size:0.85rem;font-weight:600;text-decoration:none;">
            + E'lon berish
        </a>
    </div>

    {{-- ── SEARCH ───────────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('adverts.index') }}" id="search-form" class="mb-4">
        <div class="d-flex gap-2" style="position:relative;">
            <div style="position:relative;flex:1;">
                <input type="text"
                       name="text"
                       id="search-input"
                       value="{{ request('text') }}"
                       placeholder="Qidirish... (2 harf yozing)"
                       autocomplete="off"
                       class="form-control"
                       style="border-radius:10px;border-color:#e5e7eb;font-size:0.9rem;padding:10px 16px 10px 42px;">
                <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2"
                     viewBox="0 0 24 24"
                     style="position:absolute;left:14px;top:50%;transform:translateY(-50%);pointer-events:none;">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <div id="search-spinner"
                     style="display:none;position:absolute;right:14px;top:50%;transform:translateY(-50%);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"
                         style="animation:spin 0.8s linear infinite;">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                </div>
                <div id="autocomplete-dropdown"
                     style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;
                            background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                            box-shadow:0 8px 24px rgba(0,0,0,0.12);z-index:1000;overflow:hidden;
                            max-height:360px;overflow-y:auto;">
                </div>
            </div>
            <button type="submit"
                    style="background:#111827;color:#fff;border:none;border-radius:10px;
                           padding:10px 20px;font-size:0.88rem;font-weight:600;white-space:nowrap;cursor:pointer;">
                Qidirish
            </button>
            @if(request('text'))
                <a href="{{ route('adverts.index') }}"
                   style="display:inline-flex;align-items:center;padding:10px 16px;
                          background:#f3f4f6;border-radius:10px;color:#6b7280;
                          text-decoration:none;font-size:0.88rem;white-space:nowrap;">
                    ✕ Tozalash
                </a>
            @endif
        </div>
    </form>

    {{-- ── KATEGORIYALAR + SONI ─────────────────────────────────────────────── --}}
    <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:12px;">
        <div class="card-body p-3">
            <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:12px;">
                Kategoriyalar
            </p>
            <div class="row g-1">
                @foreach($categoryList as $cat)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('adverts.index', adverts_path($path->region, $cat)) }}"
                           style="display:block;padding:5px 8px;border-radius:8px;font-size:0.84rem;
                                  color:{{ ($path->category && $path->category->id === $cat->id) ? '#2563eb' : '#374151' }};
                                  text-decoration:none;
                                  background:{{ ($path->category && $path->category->id === $cat->id) ? '#eff6ff' : 'transparent' }};
                                  transition:background 0.15s;"
                           onmouseover="this.style.background='#f9fafb'"
                           onmouseout="this.style.background='{{ ($path->category && $path->category->id === $cat->id) ? '#eff6ff' : 'transparent' }}'">
                            {{ $cat->name }}
                            <span style="color:#9ca3af;font-size:0.78rem;">({{ $cat->adverts_count }})</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── REGIONLAR + SONI ─────────────────────────────────────────────────── --}}
    <div class="card mb-4" style="border:1px solid #e5e7eb;border-radius:12px;">
        <div class="card-body p-3">
            <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:12px;">
                @if($path->region)
                    Regions of {{ $path->region->name }}
                @else
                    Regionlar
                @endif
            </p>
            @if($regions->count())
                <div class="row g-1">
                    @foreach($regions as $reg)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('adverts.index', adverts_path($reg, $path->category)) }}"
                               style="display:block;padding:5px 8px;border-radius:8px;font-size:0.84rem;
                                      color:{{ ($path->region && $path->region->id === $reg->id) ? '#2563eb' : '#374151' }};
                                      text-decoration:none;
                                      background:{{ ($path->region && $path->region->id === $reg->id) ? '#eff6ff' : 'transparent' }};
                                      transition:background 0.15s;"
                               onmouseover="this.style.background='#f9fafb'"
                               onmouseout="this.style.background='{{ ($path->region && $path->region->id === $reg->id) ? '#eff6ff' : 'transparent' }}'">
                                {{ $reg->name }}
                                <span style="color:#9ca3af;font-size:0.78rem;">({{ $reg->adverts_count }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="font-size:0.85rem;color:#9ca3af;margin:0;">Sub-regionlar yo'q</p>
            @endif
        </div>
    </div>

    {{-- ── QIDIRUV NATIJASI ─────────────────────────────────────────────────── --}}
    @if(request('text'))
        <p style="font-size:0.85rem;color:#6b7280;margin-bottom:16px;">
            "<strong>{{ request('text') }}</strong>" bo'yicha
            <strong>{{ $adverts->total() }}</strong> ta natija
        </p>
    @endif

    {{-- ── E'LONLAR RO'YXATI ───────────────────────────────────────────────── --}}
    @forelse($adverts as $advert)
        <a href="{{ route('adverts.show', $advert) }}"
           style="text-decoration:none;display:block;margin-bottom:12px;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                        overflow:hidden;display:flex;
                        box-shadow:0 1px 3px rgba(0,0,0,0.05);transition:box-shadow 0.2s;"
                 onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)'">

                {{-- Rasm --}}
                <div style="width:200px;flex-shrink:0;background:#f3f4f6;overflow:hidden;">
                    @if($advert->photos->first())
                        <img src="{{ Storage::url($advert->photos->first()->file) }}"
                             alt="{{ $advert->title }}"
                             style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                        <div style="width:100%;height:140px;display:flex;align-items:center;justify-content:center;">
                            <svg width="36" height="36" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Ma'lumot --}}
                <div style="padding:16px 18px;flex:1;min-width:0;">
                    {{-- Sarlavha + narx --}}
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <p style="font-size:1.05rem;font-weight:600;color:#2563eb;margin:0;
                                   white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:70%;">
                            {{ $advert->title }}
                        </p>
                        <span style="font-size:1rem;font-weight:700;color:#111827;white-space:nowrap;">
                            {{ $advert->price ? number_format($advert->price, 0, '.', ' ') : '—' }}
                        </span>
                    </div>

                    {{-- Meta: region, category, date --}}
                    <div class="d-flex flex-wrap gap-3 mb-2" style="font-size:0.8rem;color:#6b7280;">
                        @if($advert->region)
                            <span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;">
                                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Region: <a href="{{ route('adverts.index', adverts_path($advert->region)) }}"
                                           style="color:#2563eb;text-decoration:none;">{{ $advert->region->name }}</a>
                            </span>
                        @endif
                        @if($advert->category)
                            <span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;">
                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Category: <a href="{{ route('adverts.index', adverts_path(null, $advert->category)) }}"
                                             style="color:#2563eb;text-decoration:none;">{{ $advert->category->name }}</a>
                            </span>
                        @endif
                        @if($advert->published_at)
                            <span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                Date: {{ $advert->published_at->format('Y-m-d H:i:s') }}
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    @if($advert->content)
                        <p style="font-size:0.83rem;color:#6b7280;margin:0;
                                   display:-webkit-box;-webkit-line-clamp:2;
                                   -webkit-box-orient:vertical;overflow:hidden;line-height:1.55;">
                            {{ $advert->content }}
                        </p>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div style="text-align:center;padding:60px 20px;color:#9ca3af;">
            <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5"
                 viewBox="0 0 24 24" style="margin-bottom:12px;">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p style="font-size:0.9rem;">
                @if(request('text'))
                    "{{ request('text') }}" bo'yicha hech narsa topilmadi
                @else
                    Hozircha e'lonlar yo'q
                @endif
            </p>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $adverts->appends(request()->query())->links() }}
    </div>

    {{-- ── AUTOCOMPLETE JS ──────────────────────────────────────────────────── --}}
    <style>
        @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        .ac-item { display:flex;align-items:center;gap:12px;padding:10px 16px;text-decoration:none;color:#111827;border-bottom:1px solid #f3f4f6;transition:background 0.15s; }
        .ac-item:last-child { border-bottom:none; }
        .ac-item:hover, .ac-item.ac-active { background:#f9fafb; }
        .ac-item-img { width:44px;height:44px;object-fit:cover;border-radius:8px;flex-shrink:0; }
        .ac-item-no-img { width:44px;height:44px;border-radius:8px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .ac-title { font-size:0.88rem;font-weight:600;color:#111827; }
        .ac-meta  { font-size:0.78rem;color:#9ca3af;margin-top:2px; }
        .ac-price { font-size:0.85rem;font-weight:700;color:#2563eb;margin-left:auto;white-space:nowrap; }
        .ac-all   { display:block;text-align:center;padding:10px;font-size:0.85rem;color:#2563eb;font-weight:600;text-decoration:none;background:#f9fafb; }
        .ac-all:hover { background:#eff6ff; }
        .ac-empty { padding:16px;text-align:center;font-size:0.85rem;color:#9ca3af; }
    </style>

    <script>
        (function () {
            const input    = document.getElementById('search-input');
            const dropdown = document.getElementById('autocomplete-dropdown');
            const spinner  = document.getElementById('search-spinner');
            const baseUrl  = '{{ route('adverts.index') }}';

            let timer = null, xhr = null;

            input.addEventListener('input', function () {
                const q = this.value.trim();
                clearTimeout(timer);
                closeDropdown();
                if (q.length < 2) return;
                timer = setTimeout(() => fetch(q), 300);
            });

            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) closeDropdown();
            });

            input.addEventListener('keydown', function (e) {
                const items  = dropdown.querySelectorAll('.ac-item');
                let   active = dropdown.querySelector('.ac-item.ac-active');

                if (e.key === 'Escape') { closeDropdown(); return; }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!active) { items[0]?.classList.add('ac-active'); }
                    else { active.classList.remove('ac-active'); active.nextElementSibling?.classList.contains('ac-item') && active.nextElementSibling.classList.add('ac-active'); }
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (active) { active.classList.remove('ac-active'); active.previousElementSibling?.classList.contains('ac-item') && active.previousElementSibling.classList.add('ac-active'); }
                }
                if (e.key === 'Enter') {
                    active = dropdown.querySelector('.ac-item.ac-active');
                    if (active) { e.preventDefault(); active.click(); }
                }
            });

            function fetch(q) {
                if (xhr) xhr.abort();
                spinner.style.display = 'block';

                xhr = new XMLHttpRequest();
                xhr.open('GET', baseUrl + '?text=' + encodeURIComponent(q) + '&autocomplete=1', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.onload = function () {
                    spinner.style.display = 'none';
                    if (xhr.status === 200) {
                        try { render(JSON.parse(xhr.responseText), q); } catch(e) { closeDropdown(); }
                    }
                };
                xhr.onerror = function () { spinner.style.display = 'none'; };
                xhr.send();
            }

            function render(data, q) {
                dropdown.innerHTML = '';

                if (!data.items || !data.items.length) {
                    dropdown.innerHTML = '<div class="ac-empty">Hech narsa topilmadi</div>';
                    dropdown.style.display = 'block';
                    return;
                }

                data.items.forEach(function (item) {
                    const a = document.createElement('a');
                    a.className = 'ac-item';
                    a.href = item.url;

                    const img = item.photo
                        ? `<img class="ac-item-img" src="${item.photo}" alt="">`
                        : `<div class="ac-item-no-img"><svg width="18" height="18" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>`;

                    const meta = [item.region, item.category].filter(Boolean).join(' · ');

                    a.innerHTML = img +
                        `<div style="flex:1;min-width:0;">
                        <div class="ac-title">${hl(item.title, q)}</div>
                        ${meta ? `<div class="ac-meta">${meta}</div>` : ''}
                    </div>` +
                        (item.price ? `<div class="ac-price">${item.price}</div>` : '');

                    dropdown.appendChild(a);
                });

                if (data.total > data.items.length) {
                    const all = document.createElement('a');
                    all.className = 'ac-all';
                    all.href = baseUrl + '?text=' + encodeURIComponent(q);
                    all.textContent = `Barcha ${data.total} ta natijani ko'rish →`;
                    dropdown.appendChild(all);
                }

                dropdown.style.display = 'block';
            }

            function hl(text, q) {
                return text.replace(new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&') + ')', 'gi'),
                    '<mark style="background:#fef9c3;padding:0;border-radius:2px;">$1</mark>');
            }

            function closeDropdown() { dropdown.style.display = 'none'; dropdown.innerHTML = ''; }
        })();
    </script>

@endsection
