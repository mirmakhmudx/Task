@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('cabinet.adverts.show', $advert) }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#111827;">Rasmlar</h5>
            <span style="font-size:0.78rem;color:#9ca3af;">{{ $advert->title }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;font-size:0.875rem;color:#15803d;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Existing photos --}}
    @if($advert->photos->count())
        <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">
            Yuklangan rasmlar ({{ $advert->photos->count() }})
        </span>
            </div>
            <div class="card-body" style="padding:20px;">
                <div class="row g-3">
                    @foreach($advert->photos as $photo)
                        <div class="col-6 col-md-3">
                            <div style="position:relative;border-radius:10px;overflow:hidden;aspect-ratio:1;background:#f3f4f6;">
                                <img src="{{ Storage::url($photo->file) }}"
                                     alt="photo"
                                     style="width:100%;height:100%;object-fit:cover;">
                                <form method="POST"
                                      action="{{ route('cabinet.adverts.photos.destroy', [$advert, $photo->id]) }}"
                                      style="position:absolute;top:6px;right:6px;">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,0.6);border:none;color:#fff;font-size:0.75rem;display:flex;align-items:center;justify-content:center;cursor:pointer;"
                                            title="O'chirish">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M18 6L6 18M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Upload form --}}
    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Yangi rasmlar qo'shish</span>
        </div>
        <div class="card-body" style="padding:20px;">
            <form method="POST"
                  action="{{ route('cabinet.adverts.photos.update', $advert) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">
                        Rasmlarni tanlang
                        <span style="color:#9ca3af;font-weight:400;">(jpg, png, webp — max 5MB)</span>
                    </label>
                    <input type="file" name="files[]" multiple accept="image/*"
                           class="form-control @error('files') is-invalid @enderror @error('files.*') is-invalid @enderror"
                           style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                    @error('files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @error('files.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn"
                        style="background:#111827;color:#fff;border-radius:10px;font-size:0.875rem;font-weight:600;padding:10px 20px;">
                    Yuklash
                </button>
            </form>
        </div>
    </div>

@endsection
