@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .pv-hero{ background:linear-gradient(135deg,#0f2027 0%,#203a43 55%,#2c5364 100%); color:#fff; border-radius:22px; padding:3rem 2.5rem; position:relative; overflow:hidden; }
        .pv-hero::after{ content:"\F470"; font-family:"bootstrap-icons"; position:absolute; right:1.5rem; top:50%; transform:translateY(-50%); font-size:7rem; opacity:.10; }
        .pv-hero h1{ font-weight:600; font-size:2.2rem; letter-spacing:-0.5px; }
        .pv-hero p{ color:#cfe0e6; max-width:560px; margin-top:.6rem; }
        .pv-badge{ display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.14); color:#fff; padding:.3rem .8rem; border-radius:30px; font-size:.8rem; }
        .pv-ico{ width:44px; height:44px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; font-size:1.25rem; background:#e7f0f3; color:#203a43; flex:0 0 auto; }
        .pv-sec{ scroll-margin-top:1rem; }
        .pv-prose{ color:#2b2b3d; line-height:1.75; } .pv-prose p{ margin-bottom:1rem; }
        .toc a{ display:block; padding:.45rem .8rem; border-radius:8px; text-decoration:none; color:#4a4a6a; font-size:.9rem; font-weight:500; }
        .toc a:hover{ background:#f0f1f5; color:#1a1a2e; }
        .crumb{ font-size:.82rem; } .crumb a{ color:#6b7280; text-decoration:none; } .crumb a:hover{ color:#1a1a2e; }
        .rights li{ margin-bottom:.5rem; }
    </style>

    @php
        $sections = [
            ['collect',  'bi-clipboard-data', "Qanday ma'lumot to'playmiz", "Ro'yxatdan o'tishda ism, familiya, email va telefon raqamingizni olamiz. E'lon joylashda e'lon matni, rasmlar, narx va hudud ma'lumotlari saqlanadi."],
            ['use',      'bi-gear',           "Ma'lumotlardan qanday foydalanamiz", "Ma'lumotlaringiz faqat platformaning ishlashi, hisobingiz xavfsizligini ta'minlash va siz bilan bog'lanish uchun ishlatiladi. Reklama maqsadida sotilmaydi."],
            ['security', 'bi-shield-lock',    "Ma'lumot xavfsizligi", "Parollar shifrlangan (hash) holatda saqlanadi, ulanishlar himoyalangan kanal orqali amalga oshiriladi. Telefon tasdiqlash qo'shimcha himoya qatlamini beradi."],
            ['third',    'bi-people',         "Uchinchi shaxslar", "Shaxsiy ma'lumotlaringiz uchinchi shaxslarga sotilmaydi va qonun talab qilmagan holatlarda oshkor qilinmaydi."],
            ['rights',   'bi-person-check',   "Sizning huquqlaringiz", "Istalgan vaqtda ma'lumotlaringizni ko'rish, tahrirlash yoki hisobingizni o'chirishni so'rashingiz mumkin."],
            ['cookie',   'bi-cookie',         "Cookie fayllar", "Sayt to'g'ri ishlashi va sizni tizimda saqlab turish uchun cookie fayllaridan foydalanamiz."],
        ];
    @endphp

    <nav class="crumb mb-3">
        <a href="{{ route('home') }}">Home</a> <span class="text-muted">/ {{ $page->menu_title }}</span>
    </nav>

    <div class="pv-hero mb-4">
        <span class="pv-badge mb-3"><i class="bi bi-shield-check"></i> Maxfiylik kafolati</span>
        <h1>{{ $page->title }}</h1>
        <p>{{ $page->description ?: "Sizning shaxsiy ma'lumotlaringizni himoya qilishni jiddiy qabul qilamiz. Quyida ma'lumotlaringiz qanday yig'ilishi va ishlatilishi tushuntirilgan." }}</p>
        <div class="mt-3" style="color:#9fc3cd; font-size:.82rem;">Oxirgi yangilangan: {{ now()->format('d.m.Y') }}</div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="pv-prose">
                        {!! $page->content ?: "<p>Biz sizning shaxsiy ma'lumotlaringizni himoya qilishni jiddiy qabul qilamiz. Ro'yxatdan o'tishda kiritilgan ma'lumotlar faqat platformaning ishlashi va siz bilan bog'lanish uchun ishlatiladi.</p>" !!}
                    </div>
                </div>
            </div>

            @foreach($sections as $s)
                <div id="{{ $s[0] }}" class="card mb-3 pv-sec">
                    <div class="card-body d-flex gap-3">
                        <span class="pv-ico"><i class="bi {{ $s[1] }}"></i></span>
                        <div>
                            <h6 class="fw-semibold mb-1">{{ $s[2] }}</h6>
                            <div class="text-muted" style="line-height:1.7;">{{ $s[3] }}</div>
                            @if($s[0] === 'rights')
                                <ul class="rights text-muted small mt-2 mb-0">
                                    <li>Ma'lumotlaringizga kirish va ularni yuklab olish</li>
                                    <li>Noto'g'ri ma'lumotni tuzatish</li>
                                    <li>Hisob va ma'lumotlarni o'chirishni so'rash</li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="card mb-3" style="position:sticky; top:1rem;">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-semibold mb-2">Bo'limlar</div>
                    <div class="toc">
                        @foreach($sections as $s)
                            <a href="#{{ $s[0] }}">{{ $s[2] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <span class="pv-ico mx-auto mb-2"><i class="bi bi-envelope"></i></span>
                    <div class="fw-semibold mb-1">Savol yoki shikoyat?</div>
                    <p class="text-muted small mb-3">Maxfiylik bo'yicha murojaat qoldiring.</p>
                    <a href="{{ route('cabinet.tickets.create') }}" class="btn btn-primary btn-sm w-100">Murojaat yuborish</a>
                </div>
            </div>
        </div>
    </div>
@endsection
