@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .pm-hero{ background:linear-gradient(135deg,#2e1065 0%,#5b21b6 55%,#7c3aed 100%); color:#fff; border-radius:22px; padding:3rem 2.5rem; position:relative; overflow:hidden; }
        .pm-deco{ position:absolute; right:1.8rem; top:50%; transform:translateY(-50%); font-size:6.5rem; opacity:.12; }
        .pm-hero h1{ font-weight:600; font-size:2.2rem; letter-spacing:-0.5px; position:relative; }
        .pm-hero p{ color:#ddd0f5; max-width:580px; margin-top:.6rem; position:relative; }
        .pm-badge{ display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.15); color:#fff; padding:.3rem .8rem; border-radius:30px; font-size:.8rem; }
        .pm-ico{ width:50px; height:50px; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; font-size:1.4rem; background:#f1ecfb; color:#5b21b6; }
        .pm-method{ height:100%; transition:transform .12s, box-shadow .12s; }
        .pm-method:hover{ transform:translateY(-3px); box-shadow:0 10px 26px rgba(0,0,0,.07); }
        .pm-prose{ color:#2b2b3d; line-height:1.75; } .pm-prose p{ margin-bottom:1rem; }
        .tip{ display:flex; gap:.6rem; align-items:flex-start; margin-bottom:.7rem; }
        .crumb{ font-size:.82rem; } .crumb a{ color:#6b7280; text-decoration:none; } .crumb a:hover{ color:#1a1a2e; }
    </style>

    <nav class="crumb mb-3">
        <a href="{{ route('home') }}">Home</a> <span class="text-muted">/ {{ $page->menu_title }}</span>
    </nav>

    <div class="pm-hero mb-4">
        <i class="bi bi-credit-card-2-front pm-deco"></i>
        <span class="pm-badge mb-3"><i class="bi bi-shield-lock"></i> Xavfsiz to'lov</span>
        <h1>{{ $page->title }}</h1>
        <p>{{ $page->description ?: "To'lov usullari sotuvchi va xaridor o'rtasida kelishiladi. Platforma to'lovda vositachilik qilmaydi — shuning uchun xavfsiz usullarni tanlash muhim." }}</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="pm-prose">
                {!! $page->content ?: "<p>Firibgarlikdan saqlanish uchun tovarni qo'lga olgandan keyin to'lashni yoki ishonchli to'lov tizimlaridan foydalanishni maslahat beramiz.</p>" !!}
            </div>
        </div>
    </div>

    <h5 class="fw-semibold mb-3">To'lov usullari</h5>
    <div class="row g-3 mb-4">
        @php
            $methods = [
                ['bi-cash-stack',        "Naqd pul", "Eng xavfsiz usul — tovarni qo'lga olgan paytda naqd to'lash."],
                ['bi-bank',              "Bank o'tkazmasi", "Ishonchli sotuvchilar bilan rasmiy o'tkazma orqali."],
                ['bi-phone',             "To'lov ilovalari", "Payme, Click va boshqa ishonchli ilovalar orqali."],
            ];
        @endphp
        @foreach($methods as $m)
            <div class="col-md-4">
                <div class="card pm-method"><div class="card-body">
                    <span class="pm-ico mb-3"><i class="bi {{ $m[0] }}"></i></span>
                    <div class="fw-semibold mt-2">{{ $m[1] }}</div>
                    <div class="text-muted small mt-1">{{ $m[2] }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-shield-check text-success me-1"></i> Firibgarlikdan himoya</h6>
                @foreach([
                    "Tovarni ko'rib, tekshirgach to'lang",
                    "Imkon bo'lsa naqd yoki ishonchli ilova orqali",
                    "Sotuvchi ma'lumotlari va reytingini tekshiring",
                ] as $t)
                    <div class="tip"><i class="bi bi-check2 text-success"></i><span class="text-muted">{{ $t }}</span></div>
                @endforeach
            </div></div>
        </div>
        <div class="col-md-6">
            <div class="card h-100"><div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-exclamation-octagon-fill text-danger me-1"></i> Bunga yo'l qo'ymang</h6>
                @foreach([
                    "Tovarni ko'rmasdan oldindan to'lash",
                    "Karta PIN yoki SMS kodini ulashish",
                    "Notanish havola yoki saytga to'lov qilish",
                ] as $t)
                    <div class="tip"><i class="bi bi-x-lg text-danger"></i><span class="text-muted">{{ $t }}</span></div>
                @endforeach
            </div></div>
        </div>
    </div>

    <div class="alert mt-4 mb-0 d-flex gap-2" style="background:#f1ecfb; border:none; border-radius:14px; color:#4c1d95;">
        <i class="bi bi-info-circle-fill"></i>
        <div class="small">Eslatma: Adverts to'lovlarda vositachilik qilmaydi. Barcha to'lov risklari tomonlar zimmasida.</div>
    </div>
@endsection
