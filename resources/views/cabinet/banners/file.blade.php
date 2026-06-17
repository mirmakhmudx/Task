@extends('layouts.app')

@section('content')
    <h5 class="fw-bold mb-4">Rasmni almashtirish</h5>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="{{ route('cabinet.banners.file.update', $banner) }}">
        @csrf
        @method('PUT')

        <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;max-width:560px;">
            <div class="card-body" style="padding:20px;">
                @if($banner->file)
                    <p style="font-size:0.82rem;color:#9ca3af;">Hozirgi rasm:</p>
                    <img src="{{ Storage::url($banner->file) }}" style="max-width:240px;border-radius:10px;margin-bottom:16px;">
                @endif
                <div class="mb-4">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;">Yangi rasm</label>
                    <input type="file" name="file" accept="image/*" class="form-control" style="border-radius:10px;">
                </div>
                <button type="submit" class="btn" style="background:#111827;color:#fff;border-radius:10px;font-weight:600;padding:11px 22px;">Yuklash</button>
                <a href="{{ route('cabinet.banners.show', $banner) }}" class="btn" style="background:#f3f4f6;color:#374151;border-radius:10px;padding:11px 22px;">Bekor qilish</a>
            </div>
        </div>
    </form>
@endsection
