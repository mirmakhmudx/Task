@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="{{ route('admin.adverts.categories.show', $category) }}"
           class="d-inline-flex align-items-center gap-1 mb-4 text-decoration-none"
           style="font-size:0.85rem;color:#6b7280;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            {{ $category->name }}
        </a>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">Atributni tahrirlash</h5>

                @if($errors->any())
                    <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('admin.adverts.categories.attributes.update', [$category, $attribute]) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Name</label>
                        <input type="text" name="name" value="{{ old('name', $attribute->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Sort</label>
                        <input type="number" name="sort" value="{{ old('sort', $attribute->sort) }}"
                               class="form-control @error('sort') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;" min="0">
                        @error('sort') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Type</label>
                        <select name="type" id="type"
                                class="form-select @error('type') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                                onchange="toggleVariants(this.value)">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('type', $attribute->type) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="variantsBlock">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Variants
                            <span style="color:#9ca3af;font-weight:400;">(har bir qiymat yangi qatorda)</span>
                        </label>
                        <textarea name="variants" rows="4"
                                  class="form-control"
                                  style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;resize:vertical;">{{ old('variants', $attribute->variants ? implode("\n", $attribute->variants) : '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Default
                            <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                        </label>
                        <input type="text" name="default" value="{{ old('default', $attribute->default) }}"
                               class="form-control"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="hidden" name="required" value="0">
                            <input type="checkbox" name="required" value="1" id="required"
                                   class="form-check-input"
                                   {{ old('required', $attribute->required) ? 'checked' : '' }}>
                            <label for="required" class="form-check-label fw-medium"
                                   style="font-size:0.875rem;color:#374151;">
                                Majburiy maydon
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                        <a href="{{ route('admin.adverts.categories.show', $category) }}"
                           class="btn btn-outline-primary">Bekor qilish</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleVariants(type) {
    document.getElementById('variantsBlock').style.display =
        type === 'string' ? 'block' : 'none';
}
toggleVariants(document.getElementById('type').value);
</script>
@endsection
