@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.banners.index') }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <h5 class="mb-0 fw-bold">{{ $banner->name }}</h5>
            <span style="font-size:0.78rem;color:#9ca3af;">Banner #{{ $banner->id }} &middot; {{ $banner->user->name ?? '—' }}</span>
        </div>
        <div class="ms-auto">
            @php
                $map = [
                    'draft'      => ['Draft',      '#6b7280','#f3f4f6'],
                    'moderation' => ['Moderation', '#2563eb','#dbeafe'],
                    'wait_pay'   => ['To\'lov kutilmoqda', '#b45309','#fef3c7'],
                    'active'     => ['Active',     '#15803d','#dcfce7'],
                    'closed'     => ['Closed',     '#dc2626','#fee2e2'],
                ];
                [$lbl,$fg,$bg] = $map[$banner->status] ?? [$banner->status,'#374151','#f3f4f6'];
            @endphp
            <span style="background:{{ $bg }};color:{{ $fg }};font-size:0.8rem;font-weight:600;padding:5px 14px;border-radius:20px;">● {{ $lbl }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;color:#15803d;font-size:0.875rem;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            @foreach($errors->all() as $e)<div style="font-size:0.85rem;color:#dc2626;">{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="d-flex flex-wrap gap-2 mb-4">
        @if($banner->status === 'moderation')
            <form method="POST" action="{{ route('admin.banners.moderate', $banner) }}" style="display:inline;">
                @csrf
                <button type="submit" style="padding:8px 20px;background:#16a34a;border:none;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;cursor:pointer;">✓ Tasdiqlash</button>
            </form>
            <a href="{{ route('admin.banners.reject.form', $banner) }}"
               style="display:inline-flex;align-items:center;padding:8px 20px;background:#f59e0b;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;text-decoration:none;">Rad etish</a>
        @endif

        @if($banner->status === 'wait_pay')
            <form method="POST" action="{{ route('admin.banners.pay', $banner) }}" style="display:inline;">
                @csrf
                <button type="submit" style="padding:8px 20px;background:#16a34a;border:none;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;cursor:pointer;">✓ Aktiv qilish</button>
            </form>
            <a href="{{ route('admin.banners.reject.form', $banner) }}"
               style="display:inline-flex;align-items:center;padding:8px 20px;background:#f59e0b;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;text-decoration:none;">Rad etish</a>
        @endif

        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" style="display:inline;"
              onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
            @csrf @method('DELETE')
            <button type="submit" style="padding:8px 20px;background:#ef4444;border:none;border-radius:10px;color:#fff;font-size:0.88rem;font-weight:600;cursor:pointer;">O'chirish</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-8">
            @if($banner->file)
                <div class="card mb-3" style="border-radius:14px;">
                    <div class="card-body text-center p-3">
                        <img src="{{ Storage::url($banner->file) }}" alt="{{ $banner->name }}"
                             style="max-width:100%;border-radius:8px;">
                        <div class="text-muted small mt-2">Format: {{ $banner->format }}</div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card" style="border-radius:14px;">
                <div class="card-body p-0">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;">Ma'lumotlar</span>
                    </div>
                    @foreach([
                        ['Egasi',       $banner->user->name ?? '—'],
                        ['Kategoriya',  $banner->category->name ?? '—'],
                        ['Region',      $banner->region?->name ?? 'Barcha regionlar'],
                        ['URL',         $banner->url],
                        ['Limit',       $banner->limit],
                        ["Ko'rsatildi", $banner->views],
                        ['Format',      $banner->format],
                        ['Nashr',       $banner->published_at?->format('d.m.Y H:i') ?? '—'],
                    ] as [$label, $value])
                        <div class="d-flex justify-content-between" style="padding:11px 20px;border-bottom:1px solid #f9fafb;">
                            <span style="font-size:0.85rem;color:#6b7280;">{{ $label }}</span>
                            <span style="font-size:0.85rem;font-weight:600;color:#111827;text-align:right;max-width:60%;">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($banner->reject_reason)
                <div class="card mt-3" style="border:1px solid #fecaca;border-radius:14px;background:#fff5f5;">
                    <div class="card-body" style="padding:16px 20px;">
                        <p style="font-size:0.78rem;font-weight:700;color:#b91c1c;text-transform:uppercase;margin-bottom:8px;">Rad etish sababi</p>
                        <p style="font-size:0.85rem;color:#374151;margin:0;">{{ $banner->reject_reason }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
