@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .dl-hero{ background:linear-gradient(135deg,#0b3d2e 0%,#125c43 55%,#1c8c63 100%); color:#fff; border-radius:22px; padding:3rem 2.5rem; position:relative; overflow:hidden; }
        .dl-hero::after{ content:"\F2D5"; font-family:"bootstrap-icons"; position:absolute; right:1.6rem; top:50%; transform:translateY(-50%); font-size:7rem; opacity:.10; }
        .dl-hero h1{ font-weight:600; font-size:2.2rem; letter-spacing:-0.5px; }
        .dl-hero p{ color:#cfe8df; max-width:580px; margin-top:.6rem; }
        .dl-badge{ display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.15); color:#fff; padding:.3rem .8rem; border-radius:30px; font-size:.8rem; }
        .dl-num{ width:42px; height:42px; border-radius:50%; background:#125c43; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:600; }
        .dl-prose{ color:#2b2b3d; line-height:1.75; } .dl-prose p{ margin-bottom:1rem; }
        .do-item, .dont-item{ display:flex; gap:.6rem; align-items:flex-start; margin-bottom:.7rem; }
        .do-item i{ color:#16a34a; } .dont-item i{ color:#dc2626; }
        .crumb{ font-size:.82rem; } .crumb a{ color:#6b7280; text-decoration:none; } .crumb a:hover{ color:#1a1a2e; }
    </style>

    <nav class="crumb mb-3">
        <a href="{{ route('home') }}">Home</a> <span class="text-muted">/ {{ $page->menu_title }}</span>
    </nav>

    <div class="dl-hero mb-4">
        <span class="dl-badge mb-3"><i class="bi bi-truck"></i> Xavfsiz bitim</span>
        <h1>{{ $page->title }}</h1>
        <p>{{ $page->description ?: "Adverts — e'lonlar platformasi. Yetkazib berish shartlari sotuvchi va xaridor o'rtasida bevosita kelishiladi. Quyida xavfsiz uchrashuv va tovarni qabul qilish bo'yicha tavsiyalar." }}</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="dl-prose">
                {!! $page->content ?: "<p>Tovarni olishdan oldin uni shaxsan ko'rib chiqishni va xavfsiz, ko'p odam bo'ladigan joyda uchrashishni tavsiya qilamiz. Oldindan to'lov qilishda ehtiyot bo'ling.</p>" !!}
            </div>
        </div>
    </div>

    <h5 class="fw-semibold mb-3">Xavfsiz uchrashuv bosqichlari</h5>
    <div class="row g-3 mb-4">
        @php
            $steps = [
                ['1', 'bi-chat-dots',  "Kelishib oling", "Sotuvchi bilan ichki chat orqali narx, vaqt va joyni aniqlang."],
                ['2', 'bi-geo-alt',    "Ochiq joyda uchrashing", "Ko'p odam bo'ladigan, yorug' joyni tanlang."],
                ['3', 'bi-box-seam',   "Tovarni tekshiring", "To'lashdan oldin mahsulotni shaxsan ko'rib chiqing."],
                ['4', 'bi-cash-coin',  "To'lovni amalga oshiring", "Tovarni qo'lga olgach to'lang."],
            ];
        @endphp
        @foreach($steps as $s)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100"><div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="dl-num">{{ $s[0] }}</span>
                        <i class="bi {{ $s[1] }}" style="font-size:1.2rem; color:#125c43;"></i>
                    </div>
                    <div class="fw-semibold mt-1">{{ $s[2] }}</div>
                    <div class="text-muted small mt-1">{{ $s[3] }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-check-circle-fill text-success me-1"></i> Tavsiya etiladi</h6>
                @foreach([
                    "Tovarni shaxsan ko'rib, tekshirib oling",
                    "Ko'p odamli, xavfsiz joyda uchrashing",
                    "Narx va shartlarni oldindan kelishing",
                    "Sotuvchi reytingi va e'lon tarixini ko'ring",
                ] as $item)
                    <div class="do-item"><i class="bi bi-check2"></i><span class="text-muted">{{ $item }}</span></div>
                @endforeach
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Ehtiyot bo'ling</h6>
                @foreach([
                    "Tovarni ko'rmasdan oldindan to'lov qilmang",
                    "Shaxsiy/karta ma'lumotlaringizni bermang",
                    "Shubhali \"juda arzon\" takliflardan ehtiyot bo'ling",
                    "Notanish havolalar orqali to'lov qilmang",
                ] as $item)
                    <div class="dont-item"><i class="bi bi-x-lg"></i><span class="text-muted">{{ $item }}</span></div>
                @endforeach
            </div></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body d-md-flex align-items-center justify-content-between">
            <div class="mb-3 mb-md-0">
                <div class="fw-semibold">Muammoga duch keldingizmi?</div>
                <div class="text-muted small">Shubhali e'lon yoki firibgarlik bo'lsa, bizga xabar bering.</div>
            </div>
            <a href="{{ route('cabinet.tickets.create') }}" class="btn btn-primary btn-sm px-4">Murojaat yuborish</a>
        </div>
    </div>
@endsection
