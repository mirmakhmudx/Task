@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('cabinet.adverts.show', $advert) }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border:1px solid #e5e7eb;border-radius:10px;color:#6b7280;text-decoration:none;background:#fff;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#111827;">Xususiyatlarni tahrirlash</h5>
            <span style="font-size:0.78rem;color:#9ca3af;">{{ $advert->title }}</span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cabinet.adverts.attributes.update', $advert) }}">
        @csrf @method('PUT')

        <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Xususiyatlar</span>
            </div>
            <div class="card-body" style="padding:20px;">
                @foreach($advert->category->allAttributes() as $attribute)
                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">
                            {{ $attribute->name }}
                            @if($attribute->required)<span style="color:#ef4444;">*</span>@endif
                        </label>

                        @php $val = old('attributes.'.$attribute->id, $advert->values->where('attribute_id', $attribute->id)->first()?->value) @endphp

                        @if($attribute->isSelect())
                            <select name="attributes[{{ $attribute->id }}]"
                                    class="form-select @error('attributes.'.$attribute->id) is-invalid @enderror"
                                    style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                                <option value="">Tanlang...</option>
                                @foreach($attribute->variants as $variant)
                                    <option value="{{ $variant }}" {{ $val === $variant ? 'selected' : '' }}>{{ $variant }}</option>
                                @endforeach
                            </select>
                        @elseif($attribute->isInteger() || $attribute->isFloat())
                            <input type="number" name="attributes[{{ $attribute->id }}]" value="{{ $val }}"
                                   class="form-control @error('attributes.'.$attribute->id) is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;"
                                   step="{{ $attribute->isFloat() ? '0.01' : '1' }}">
                        @else
                            <input type="text" name="attributes[{{ $attribute->id }}]" value="{{ $val }}"
                                   class="form-control @error('attributes.'.$attribute->id) is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                        @endif

                        @error('attributes.'.$attribute->id)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn"
                            style="background:#111827;color:#fff;border-radius:10px;font-size:0.875rem;font-weight:600;padding:10px 20px;">
                        Saqlash
                    </button>
                    <a href="{{ route('cabinet.adverts.show', $advert) }}"
                       class="btn" style="background:#f3f4f6;color:#374151;border-radius:10px;font-size:0.875rem;padding:10px 20px;border:none;">
                        Bekor qilish
                    </a>
                </div>
            </div>
        </div>
    </form>

@endsection
