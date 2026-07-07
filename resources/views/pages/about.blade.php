@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .ab-hero{ background:linear-gradient(135deg,#1a1a2e 0%,#2d2d4e 55%,#3b3b66 100%); color:#fff; border-radius:22px; padding:3.5rem 2.5rem; position:relative; overflow:hidden; }
        .ab-hero::after{ content:""; position:absolute; right:-60px; top:-60px; width:240px; height:240px; background:rgba(255,255,255,.06); border-radius:50%; }
        .ab-hero::before{ content:""; position:absolute; right:80px; bottom:-90px; width:200px; height:200px; background:rgba(255,255,255,.04); border-radius:50%; }
        .ab-hero h1{ font-weight:600; font-size:2.4rem; letter-spacing:-0.6px; position:relative; }
        .ab-hero p{ color:#c7c9e0; font-size:1.08rem; max-width:620px; position:relative; }
        .ab-stat{ text-align:center; padding:1.4rem .5rem; }
        .ab-stat .num{ font-size:2rem; font-weight:600; color:#1a1a2e; letter-spacing:-0.5px; }
        .ab-stat .lbl{ color:#6b7280; font-size:.88rem; }
        .ab-ico{ width:48px; height:48px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; font-size:1.35rem; background:#eef0f8; color:#1a1a2e; }
        .ab-step-num{ width:42px; height:42px; border-radius:50%; background:#1a1a2e; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:600; }
        .feature{ height:100%; transition:transform .12s, box-shadow .12s; }
        .feature:hover{ transform:translateY(-3px); box-shadow:0 10px 26px rgba(0,0,0,.07); }
        .ab-prose{ color:#2b2b3d; line-height:1.75; }
        .ab-prose p{ margin-bottom:1rem; }
        .ab-cta{ background:linear-gradient(135deg,#1a1a2e,#3b3b66); color:#fff; border-radius:20px; padding:2.5rem; }
        .crumb{ font-size:.82rem; } .crumb a{ color:#6b7280; text-decoration:none; } .crumb a:hover{ color:#1a1a2e; }
    </style>

    @php
        $activeAdverts = \App\Entity\Adverts\Advert::where('status', \App\Entity\Adverts\Advert::STATUS_ACTIVE)->count();
        $usersCount    = \App\Models\User::count();
        $catsCount     = \App\Entity\Adverts\Category::count();
        $regionsCount  = \App\Entity\Region\Region::count();
        $fmt = fn ($n) => $n >= 1000 ? number_format($n/1000, 1).'k' : (string) $n;
    @endphp

    <nav class="crumb mb-3">
        <a href="{{ route('home') }}">Home</a> <span class="text-muted">/ {{ $page->menu_title }}</span>
    </nav>

    <div class="ab-hero mb-4">
        <h1>{{ $page->title }}</h1>
        <p class="mt-3">{{ $page->description ?: "Adverts — O'zbekiston uchun zamonaviy onlayn e'lonlar platformasi. Sotish, sotib olish va sotuvchilar bilan to'g'ridan-to'g'ri bog'lanish — barchasi bir joyda." }}</p>
        <div class="d-flex flex-wrap gap-2 mt-4 position-relative">
            <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-light btn-sm px-3 fw-semibold">+ E'lon berish</a>
            <a href="{{ route('adverts.index') }}" class="btn btn-outline-light btn-sm px-3">E'lonlarni ko'rish</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="row g-0 text-center">
                <div class="col-6 col-md-3 ab-stat border-end"><div class="num">{{ $fmt($activeAdverts) }}+</div><div class="lbl">Aktiv e'lonlar</div></div>
                <div class="col-6 col-md-3 ab-stat border-end"><div class="num">{{ $fmt($usersCount) }}+</div><div class="lbl">Foydalanuvchilar</div></div>
                <div class="col-6 col-md-3 ab-stat border-end"><div class="num">{{ $catsCount }}</div><div class="lbl">Kategoriyalar</div></div>
                <div class="col-6 col-md-3 ab-stat"><div class="num">{{ $regionsCount }}</div><div class="lbl">Hududlar</div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="ab-ico"><i class="bi bi-building"></i></span>
                        <h5 class="fw-semibold mb-0">Biz haqimizda</h5>
                    </div>
                    <div class="ab-prose">
                        {!! $page->content ?: "<p>Bizning maqsadimiz — oldi-sotdini imkon qadar tez, qulay va xavfsiz qilish. Ro'yxatdan o'ting, bir necha daqiqada e'loningizni joylang va minglab xaridorlarga yeting.</p>" !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Qadriyatlarimiz</h5>
                    <div class="d-flex gap-3 mb-3">
                        <span class="ab-ico"><i class="bi bi-shield-check"></i></span>
                        <div><div class="fw-semibold">Xavfsizlik</div><div class="text-muted small">Har bir e'lon moderatsiyadan o'tadi.</div></div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <span class="ab-ico"><i class="bi bi-lightning-charge"></i></span>
                        <div><div class="fw-semibold">Tezlik</div><div class="text-muted small">E'lon joylash bir necha daqiqa.</div></div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="ab-ico"><i class="bi bi-chat-dots"></i></span>
                        <div><div class="fw-semibold">To'g'ridan-to'g'ri aloqa</div><div class="text-muted small">Sotuvchi bilan ichki chat orqali.</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-semibold mt-5 mb-3">Qanday ishlaydi?</h5>
    <div class="row g-3">
        @php
            $steps = [
                ['1', 'Ro\'yxatdan o\'ting', 'Bir daqiqada hisob yarating va telefoningizni tasdiqlang.'],
                ['2', 'E\'lon joylang', 'Kategoriya, hudud, rasm va narxni kiriting — tayyor.'],
                ['3', 'Xaridor toping', 'Xaridorlar siz bilan bevosita bog\'lanadi.'],
            ];
        @endphp
        @foreach($steps as $s)
            <div class="col-md-4">
                <div class="card h-100"><div class="card-body">
                    <span class="ab-step-num mb-3">{{ $s[0] }}</span>
                    <div class="fw-semibold mt-2">{{ $s[1] }}</div>
                    <div class="text-muted small mt-1">{{ $s[2] }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <h5 class="fw-semibold mt-5 mb-3">Nega Adverts?</h5>
    <div class="row g-3">
        @php
            $features = [
                ['bi-search', 'Kuchli qidiruv', 'Kategoriya, hudud va xususiyatlar bo\'yicha aniq filtr.'],
                ['bi-geo-alt', 'Hududlar bo\'yicha', 'O\'zingizga yaqin e\'lonlarni toping.'],
                ['bi-heart', 'Sevimlilar', 'Yoqqan e\'lonlarni saqlab qo\'ying.'],
                ['bi-patch-check', 'Tasdiqlangan e\'lonlar', 'Faqat moderatsiyadan o\'tgan e\'lonlar.'],
                ['bi-phone', 'Telefon tasdiqlash', 'Ishonchli foydalanuvchilar jamoasi.'],
                ['bi-megaphone', 'Bannerlar', 'E\'loningizni reklama orqali ko\'taring.'],
            ];
        @endphp
        @foreach($features as $f)
            <div class="col-md-6 col-lg-4">
                <div class="card feature"><div class="card-body">
                    <span class="ab-ico mb-3"><i class="bi {{ $f[0] }}"></i></span>
                    <div class="fw-semibold mt-2">{{ $f[1] }}</div>
                    <div class="text-muted small mt-1">{{ $f[2] }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="ab-cta mt-5 d-md-flex align-items-center justify-content-between">
        <div class="mb-3 mb-md-0">
            <div class="fs-4 fw-semibold">Bugun birinchi e'loningizni joylang</div>
            <div style="color:#c7c9e0;">Bepul, tez va minglab xaridorga ko'rinadi.</div>
        </div>
        <a href="{{ route('cabinet.adverts.create.category') }}" class="btn btn-light fw-semibold px-4">Boshlash</a>
    </div>
@endsection
