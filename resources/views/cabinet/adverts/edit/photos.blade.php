@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <a href="{{ route('adverts.show', $advert) }}"
           class="d-inline-flex align-items-center gap-1 mb-4 text-decoration-none"
           style="font-size:0.85rem;color:#6b7280;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            {{ $advert->title }}
        </a>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-2" style="color:#1a1a2e;">Rasmlar yuklash</h5>
                <p style="font-size:0.85rem;color:#9ca3af;margin-bottom:1.5rem;">
                    JPG, PNG, WEBP — maksimal 5MB har biri
                </p>

                @if($errors->any())
                    <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        @foreach($errors->all() as $error)
                            <p class="mb-1" style="font-size:0.85rem;color:#dc2626;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Mavjud rasmlar --}}
                @if($advert->photos->count())
                <div class="row g-2 mb-4">
                    @foreach($advert->photos as $photo)
                    <div class="col-4 col-md-3">
                        <img src="{{ Storage::url($photo->file) }}"
                             class="img-fluid w-100"
                             style="border-radius:10px;object-fit:cover;height:100px;">
                    </div>
                    @endforeach
                </div>
                @endif

                <form method="POST"
                      action="{{ route('cabinet.adverts.photos.update', $advert) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Rasmlarni tanlang
                        </label>
                        <input type="file" name="files[]" multiple accept="image/*"
                               class="form-control @error('files') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('files') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Yuklash</button>
                        <a href="{{ route('adverts.show', $advert) }}" class="btn btn-outline-primary">Bekor qilish</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
