@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <h5 class="fw-bold mb-1" style="color:#111827;letter-spacing:-0.3px;">E'lon yaratish</h5>
        <p style="color:#9ca3af;font-size:0.85rem;margin:0;">3-qadam: E'lon ma'lumotlari</p>
    </div>

    {{-- Progress --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <div style="width:28px;height:28px;border-radius:50%;background:#16a34a;color:#fff;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">
            <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <div style="height:2px;width:60px;background:#16a34a;border-radius:2px;"></div>
        <div style="width:28px;height:28px;border-radius:50%;background:#16a34a;color:#fff;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">
            <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <div style="height:2px;width:60px;background:#16a34a;border-radius:2px;"></div>
        <div style="width:28px;height:28px;border-radius:50%;background:#111827;color:#fff;font-size:0.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;">3</div>
    </div>

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center gap-2 mb-4" style="font-size:0.82rem;color:#9ca3af;">
        <span>Kategoriya: <strong style="color:#374151;">{{ $category->name }}</strong></span>
        @if($region)
            <span>&middot;</span>
            <span>Region: <strong style="color:#374151;">{{ $region->getAddress() }}</strong></span>
        @endif
    </div>

    @if($errors->any())
        <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;">
            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $region
    ? route('cabinet.adverts.create.store.region', [$category, $region])
    : route('cabinet.adverts.create.store', $category) }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-8">

                {{-- Asosiy --}}
                <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Asosiy ma'lumotlar</span>
                    </div>
                    <div class="card-body" style="padding:20px;">

                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">Sarlavha</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="E'lon sarlavhasini kiriting"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">Tavsif</label>
                            <textarea name="content" rows="5"
                                      class="form-control @error('content') is-invalid @enderror"
                                      placeholder="E'lon haqida batafsil yozing..."
                                      style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;resize:vertical;">{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">
                                    Narx (so'm) <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                                </label>
                                <input type="number" name="price" value="{{ old('price') }}" min="0"
                                       class="form-control"
                                       placeholder="0"
                                       style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.85rem;font-weight:600;color:#374151;">
                                    Manzil <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                                </label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                       class="form-control"
                                       placeholder="Shahar, ko'cha..."
                                       style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Atributlar --}}
                @if($category->allAttributes()->count())
                    <div class="card mb-3" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                        <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                            <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Xususiyatlar</span>
                        </div>
                        <div class="card-body" style="padding:20px;">
                            @foreach($category->allAttributes() as $attribute)
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="attribute_{{ $attribute->id }}"
                                           style="font-size:0.85rem;font-weight:600;color:#374151;">
                                        {{ $attribute->name }}
                                        @if($attribute->required)
                                            <span style="color:#ef4444;">*</span>
                                        @endif
                                    </label>

                                    @if($attribute->isSelect())
                                        <select id="attribute_{{ $attribute->id }}"
                                                name="attributes[{{ $attribute->id }}]"
                                                class="form-select @error('attributes.'.$attribute->id) is-invalid @enderror"
                                                style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                                            <option value="">Tanlang...</option>
                                            @foreach($attribute->variants as $variant)
                                                <option value="{{ $variant }}"
                                                    {{ old('attributes.'.$attribute->id) === $variant ? 'selected' : '' }}>
                                                    {{ $variant }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif($attribute->isInteger() || $attribute->isFloat())
                                        <input type="number"
                                               id="attribute_{{ $attribute->id }}"
                                               name="attributes[{{ $attribute->id }}]"
                                               value="{{ old('attributes.'.$attribute->id) }}"
                                               class="form-control @error('attributes.'.$attribute->id) is-invalid @enderror"
                                               style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;"
                                               step="{{ $attribute->isFloat() ? '0.01' : '1' }}">
                                    @else
                                        <input type="text"
                                               id="attribute_{{ $attribute->id }}"
                                               name="attributes[{{ $attribute->id }}]"
                                               value="{{ old('attributes.'.$attribute->id) }}"
                                               class="form-control @error('attributes.'.$attribute->id) is-invalid @enderror"
                                               placeholder="{{ $attribute->name }}..."
                                               style="border-radius:10px;font-size:0.9rem;border-color:#e5e7eb;padding:10px 14px;">
                                    @endif

                                    @error('attributes.'.$attribute->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Side panel --}}
            <div class="col-md-4">
                <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);position:sticky;top:20px;">
                    <div style="padding:14px 20px;border-bottom:1px solid #f3f4f6;background:#f9fafb;">
                        <span style="font-size:0.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Xulosa</span>
                    </div>
                    <div class="card-body" style="padding:16px 20px;">
                        <div class="mb-2 pb-2" style="border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:2px;">Kategoriya</div>
                            <div style="font-size:0.85rem;font-weight:600;color:#111827;">{{ $category->name }}</div>
                        </div>
                        <div class="mb-3 pb-2" style="border-bottom:1px solid #f3f4f6;">
                            <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:2px;">Region</div>
                            <div style="font-size:0.85rem;font-weight:600;color:#111827;">
                                {{ $region ? $region->getAddress() : "Ko'rsatilmaydi" }}
                            </div>
                        </div>

                        <button type="submit"
                                class="btn w-100 mb-2"
                                style="background:#111827;color:#fff;border-radius:10px;font-size:0.875rem;font-weight:600;padding:11px;">
                            E'lon yaratish
                        </button>
                        <a href="{{ route('cabinet.adverts.index') }}"
                           class="btn w-100"
                           style="background:#f3f4f6;color:#374151;border-radius:10px;font-size:0.875rem;font-weight:500;padding:11px;border:none;">
                            Bekor qilish
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection
