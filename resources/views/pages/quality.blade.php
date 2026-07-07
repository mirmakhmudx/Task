@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .ql-hero{ background:linear-gradient(135deg,#7c2d12 0%,#b45309 55%,#d97706 100%); color:#fff; border-radius:22px; padding:3rem 2.5rem; position:relative; overflow:hidden; }
        .ql-deco{ position:absolute; right:1.8rem; top:50%; transform:translateY(-50%); font-size:6.5rem; opacity:.13; }
        .ql-hero h1{ font-weight:600; font-size:2.2rem; letter-spacing:-0.5px; position:relative; }
        .ql-hero p{ color:#fde7c8; max-width:580px; margin-top:.6rem; position:relative; }
        .ql-badge{ display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.16); color:#fff; padding:.3rem .8rem; border-radius:30px; font-size:.8rem; }
        .ql-ico{ width:50px; height:50px; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; font-size:1.4rem; background:#fdf0db; color:#b45309; }
        .ql-card{ height:100%; transition:transform .12s, box-shadow .12s; }
        .ql-card:hover{ transform:translateY(-3px); box-shadow:0 10px 26px rgba(0,0,0,.07); }
        .ql-prose{ color:#2b2b3d; line-height:1.75; } .ql-prose p{ margin-bottom:1rem; }
        .crumb{ font-size:.82rem; } .crumb a{ color:#6b7280; text-decoration:none; } .crumb a:hover{ color:#1a1a2e; }
    </style>

    <nav class="crumb mb-3">
        <a href="{{ route('home') }}">Home</a> <span class="text-muted">/ {{ $page->menu_title }}</span>
    </nav>

    <div class="ql-hero mb-4">
        <i class="bi bi-patch-check ql-deco"></i>
        <span class="ql-badge mb-3"><i class="bi bi-award"></i> Sifat kafolati</span>
        <h1>{{ $page->title }}</h1>
        <p>{{ $page->description ?: "Platformada faqat haqiqiy va foydali e'lonlar qolishi uchun har bir e'lon moderatsiyadan o'tadi. Bizning sifat standartlarimiz bilan tanishing." }}</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="ql-prose">
                {!! $page->content ?: "<p>Bizning maqsadimiz — ishonchli va xavfsiz muhit yaratish. Har bir e'lon ko'rib chiqiladi, qoidabuzarliklar bartaraf etiladi.</p>" !!}
            </div>
        </div>
    </div>

    <h5 class="fw-semibold mb-3">Bizning standartlarimiz</h5>
    <div class="row g-3 mb-4">
        @php
            $standards = [
                ['bi-clipboard-check', "Moderatsiya", "Har bir e'lon e'lon qilinishidan oldin ko'rib chiqiladi."],
                ['bi-phone-vibrate',   "Tasdiqlangan foydalanuvchilar", "Telefon tasdiqlash orqali ishonchli jamoa."],
                ['bi-flag',            "Shikoyat tizimi", "Qoidabuzar e'lonlar haqida xabar berish mumkin."],
                ['bi-card-text',       "Aniq ma'lumot", "E'lonlarda kategoriya, hudud va xususiyatlar to'liq."],
                ['bi-shield-lock',     "Xavfsizlik", "Ma'lumotlar himoyalangan, parollar shifrlangan."],
                ['bi-headset',         "Qo'llab-quvvatlash", "Murojaatlar tezkor ko'rib chiqiladi."],
            ];
        @endphp
        @foreach($standards as $s)
            <div class="col-md-6 col-lg-4">
                <div class="card ql-card"><div class="card-body">
                    <span class="ql-ico mb-3"><i class="bi {{ $s[0] }}"></i></span>
                    <div class="fw-semibold mt-2">{{ $s[1] }}</div>
                    <div class="text-muted small mt-1">{{ $s[2] }}</div>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body d-md-flex align-items-center justify-content-between">
            <div class="d-flex gap-3 align-items-center mb-3 mb-md-0">
                <span class="ql-ico"><i class="bi bi-hand-thumbs-up"></i></span>
                <div>
                    <div class="fw-semibold">Sifatsiz e'lonni ko'rdingizmi?</div>
                    <div class="text-muted small">Bizga xabar bering — tezda ko'rib chiqamiz.</div>
                </div>
            </div>
            <a href="{{ route('cabinet.tickets.create') }}" class="btn btn-primary btn-sm px-4">Xabar berish</a>
        </div>
    </div>
@endsection
