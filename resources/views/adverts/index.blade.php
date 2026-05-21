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

    {{-- ── SEARCH FORMA ─────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('adverts.index') }}" id="search-form" class="mb-4">
        <div class="d-flex gap-2" style="position:relative;">
            <div style="position:relative;flex:1;">
                <input type="text"
                       name="text"
                       id="search-input"
                       value="{{ request('text') }}"
                       placeholder="qidirish "
                       autocomplete="off"
                       class="form-control"
                       style="border-radius:10px;border-color:#e5e7eb;font-size:0.9rem;
                              padding:10px 16px 10px 42px;">

                {{-- Search icon --}}
                <svg width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2"
                     viewBox="0 0 24 24"
                     style="position:absolute;left:14px;top:50%;transform:translateY(-50%);pointer-events:none;">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>

                {{-- Loading spinner --}}
                <div id="search-spinner"
                     style="display:none;position:absolute;right:14px;top:50%;transform:translateY(-50%);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"
                         style="animation:spin 0.8s linear infinite;">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                </div>

                {{-- Autocomplete dropdown --}}
                <div id="autocomplete-dropdown"
                     style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;
                            background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                            box-shadow:0 8px 24px rgba(0,0,0,0.12);z-index:1000;overflow:hidden;
                            max-height:360px;overflow-y:auto;">
                </div>
            </div>

            <button type="submit"
                    style="background:#111827;color:#fff;border:none;border-radius:10px;
                           padding:10px 20px;font-size:0.88rem;font-weight:600;
                           white-space:nowrap;cursor:pointer;">
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

    {{-- ── KATEGORIYALAR ────────────────────────────────────────────────────── --}}
    @if($path->category)
        @if(count($categories))
            <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:12px;">
                <div class="card-body p-3">
                    <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">
                        {{ $path->category->name }} bo'limlari
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            <a href="{{ route('adverts.index', adverts_path($path->region, $cat)) }}"
                               style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:12px;">
            <div class="card-body p-3">
                <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">
                    Kategoriyalar
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                        <a href="{{ route('adverts.index', adverts_path($path->region, $cat)) }}"
                           style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── REGIONLAR ────────────────────────────────────────────────────────── --}}
    @if(!$path->region)
        <div class="card mb-4" style="border:1px solid #e5e7eb;border-radius:12px;">
            <div class="card-body p-3">
                <p style="font-size:0.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;margin-bottom:10px;">
                    Regionlar
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($regions as $reg)
                        <a href="{{ route('adverts.index', adverts_path($reg, $path->category)) }}"
                           style="padding:5px 12px;background:#f3f4f6;border-radius:20px;font-size:0.83rem;color:#374151;text-decoration:none;">
                            {{ $reg->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── QIDIRUV NATIJASI ─────────────────────────────────────────────────── --}}
    @if(request('text'))
        <p style="font-size:0.85rem;color:#6b7280;margin-bottom:16px;">
            "<strong>{{ request('text') }}</strong>" bo'yicha
            <strong>{{ $adverts->total() }}</strong> ta natija topildi
        </p>
    @endif

    {{-- ── E'LONLAR RO'YXATI ───────────────────────────────────────────────── --}}
    <div id="adverts-list">
        @forelse($adverts as $advert)
            <a href="{{ route('adverts.show', $advert) }}"
               style="text-decoration:none;display:block;margin-bottom:12px;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;
                            overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);
                            display:flex;transition:box-shadow 0.2s;"
                     onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)'">

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

                    <div style="padding:14px 16px;flex:1;min-width:0;">
                        <p style="font-size:1rem;font-weight:600;color:#2563eb;margin:0 0 6px;
                                   white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $advert->title }}
                        </p>
                        <p style="font-size:0.82rem;color:#6b7280;margin:0 0 10px;
                                   display:-webkit-box;-webkit-line-clamp:2;
                                   -webkit-box-orient:vertical;overflow:hidden;">
                            {{ $advert->content }}
                        </p>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
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

                    <div style="padding:14px;text-align:right;flex-shrink:0;">
                        <span style="font-size:0.78rem;color:#9ca3af;">
                            {{ $advert->published_at?->format('d.m.Y') }}
                        </span>
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
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $adverts->appends(request()->query())->links() }}
    </div>

    {{-- ── AUTOCOMPLETE JS ──────────────────────────────────────────────────── --}}
    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .ac-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            text-decoration: none;
            color: #111827;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.15s;
        }
        .ac-item:last-child { border-bottom: none; }
        .ac-item:hover { background: #f9fafb; }
        .ac-item-img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            background: #f3f4f6;
        }
        .ac-item-no-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ac-title { font-size: 0.88rem; font-weight: 600; color: #111827; }
        .ac-meta  { font-size: 0.78rem; color: #9ca3af; margin-top: 2px; }
        .ac-price { font-size: 0.85rem; font-weight: 700; color: #2563eb; margin-left: auto; white-space: nowrap; }
        .ac-all   {
            display: block;
            text-align: center;
            padding: 10px;
            font-size: 0.85rem;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            background: #f9fafb;
        }
        .ac-all:hover { background: #eff6ff; }
        .ac-empty { padding: 16px; text-align: center; font-size: 0.85rem; color: #9ca3af; }
    </style>

    <script>
        (function () {
            const input      = document.getElementById('search-input');
            const dropdown   = document.getElementById('autocomplete-dropdown');
            const spinner    = document.getElementById('search-spinner');
            const searchUrl  = '{{ route('adverts.index') }}';

            let debounceTimer = null;
            let currentXhr    = null;

            // ── Yozganda ──────────────────────────────────────────────────────────
            input.addEventListener('input', function () {
                const query = this.value.trim();

                clearTimeout(debounceTimer);
                closeDropdown();

                if (query.length < 2) return;   // 2 harfdan kam bo'lsa ishlamaydi

                debounceTimer = setTimeout(() => fetchSuggestions(query), 300);
            });

            // ── Tashqarini bosganida yopish ───────────────────────────────────────
            document.addEventListener('click', function (e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    closeDropdown();
                }
            });

            // ── Klaviatura navigatsiya (↑↓ Esc Enter) ────────────────────────────
            input.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.ac-item');
                let active  = dropdown.querySelector('.ac-item.ac-active');

                if (e.key === 'Escape') { closeDropdown(); return; }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!active) {
                        items[0]?.classList.add('ac-active');
                    } else {
                        active.classList.remove('ac-active');
                        const next = active.nextElementSibling;
                        if (next && next.classList.contains('ac-item')) next.classList.add('ac-active');
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (active) {
                        active.classList.remove('ac-active');
                        const prev = active.previousElementSibling;
                        if (prev && prev.classList.contains('ac-item')) prev.classList.add('ac-active');
                    }
                }

                if (e.key === 'Enter') {
                    active = dropdown.querySelector('.ac-item.ac-active');
                    if (active) { e.preventDefault(); active.click(); }
                }
            });

            // ── AJAX so'rov ───────────────────────────────────────────────────────
            function fetchSuggestions(query) {
                if (currentXhr) currentXhr.abort();

                showSpinner(true);

                currentXhr = new XMLHttpRequest();
                currentXhr.open('GET', searchUrl + '?text=' + encodeURIComponent(query) + '&autocomplete=1', true);
                currentXhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                currentXhr.setRequestHeader('Accept', 'application/json');

                currentXhr.onload = function () {
                    showSpinner(false);
                    if (currentXhr.status === 200) {
                        try {
                            const data = JSON.parse(currentXhr.responseText);
                            renderDropdown(data, query);
                        } catch (e) {
                            closeDropdown();
                        }
                    }
                };

                currentXhr.onerror = function () { showSpinner(false); };
                currentXhr.send();
            }

            // ── Dropdown render ───────────────────────────────────────────────────
            function renderDropdown(data, query) {
                dropdown.innerHTML = '';

                if (!data.items || data.items.length === 0) {
                    dropdown.innerHTML = '<div class="ac-empty">Hech narsa topilmadi</div>';
                    dropdown.style.display = 'block';
                    return;
                }

                data.items.forEach(function (item) {
                    const a = document.createElement('a');
                    a.className = 'ac-item';
                    a.href = item.url;

                    const imgHtml = item.photo
                        ? `<img class="ac-item-img" src="${item.photo}" alt="${item.title}">`
                        : `<div class="ac-item-no-img">
                           <svg width="18" height="18" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                               <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                           </svg>
                       </div>`;

                    const meta = [item.region, item.category].filter(Boolean).join(' · ');

                    a.innerHTML = `
                    ${imgHtml}
                    <div style="flex:1;min-width:0;">
                        <div class="ac-title">${highlight(item.title, query)}</div>
                        ${meta ? `<div class="ac-meta">${meta}</div>` : ''}
                    </div>
                    ${item.price ? `<div class="ac-price">${item.price}</div>` : ''}
                `;

                    dropdown.appendChild(a);
                });

                // "Barchasini ko'rish" linki
                if (data.total > data.items.length) {
                    const all = document.createElement('a');
                    all.className = 'ac-all';
                    all.href = searchUrl + '?text=' + encodeURIComponent(query);
                    all.textContent = `Barcha ${data.total} ta natijani ko'rish →`;
                    dropdown.appendChild(all);
                }

                dropdown.style.display = 'block';
            }

            function highlight(text, query) {
                const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp('(' + escaped + ')', 'gi'),
                    '<mark style="background:#fef9c3;padding:0;border-radius:2px;">$1</mark>');
            }

            function closeDropdown() {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
            }

            function showSpinner(show) {
                spinner.style.display = show ? 'block' : 'none';
            }
        })();
    </script>

@endsection
