@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:600px;margin:0 auto;border:1px solid #e5e7eb;border-radius:14px;">
        <div class="card-body p-4">
            <h5 style="font-weight:700;color:#111827;margin-bottom:20px;">
                E'lonni rad etish: {{ $advert->title }}
            </h5>

            @if($errors->any())
                <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:10px 16px;margin-bottom:16px;">
                    @foreach($errors->all() as $error)
                        <div style="font-size:0.85rem;color:#dc2626;">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.adverts.reject', $advert) }}">
                @csrf
                <div class="mb-3">
                    <label style="font-size:0.85rem;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                        Rad etish sababi
                    </label>
                    <textarea name="reason" rows="5"
                              class="form-control"
                              placeholder="Sababni kiriting..."
                              style="border-radius:10px;border-color:#e5e7eb;font-size:0.9rem;padding:10px 14px;resize:vertical;">{{ old('reason') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit"
                            style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:9px 20px;font-size:0.875rem;font-weight:600;cursor:pointer;">
                        Rad etish
                    </button>
                    <a href="{{ route('adverts.show', $advert) }}"
                       style="background:#f3f4f6;color:#374151;border-radius:8px;padding:9px 20px;font-size:0.875rem;font-weight:500;text-decoration:none;">
                        Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
