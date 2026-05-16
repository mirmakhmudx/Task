@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <a href="{{ route('cabinet.adverts.index') }}"
           class="d-inline-flex align-items-center gap-1 mb-4 text-decoration-none"
           style="font-size:0.85rem;color:#6b7280;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Orqaga
        </a>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">Yangi e'lon</h5>

                @if($errors->any())
                    <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('cabinet.adverts.create.category') }}">
                    @csrf

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Kategoriya</label>
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            <option value="">— Tanlang —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    @for($i = 0; $i < $category->depth; $i++) — @endfor
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Region — Ajax dinamik --}}
                    <div class="mb-3"
                         data-region-selector
                         data-source="{{ route('ajax.regions') }}"
                         data-selected="{{ old('region_id') }}">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Region</label>
                        <select name="region_id" id="region-root"
                                class="form-select @error('region_id') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            <option value="">— Barcha regionlar —</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('region_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div id="region-children"></div>
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Sarlavha</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                               placeholder="E'lon sarlavhasi">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Tavsif</label>
                        <textarea name="content" rows="5"
                                  class="form-control @error('content') is-invalid @enderror"
                                  style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;resize:vertical;"
                                  placeholder="E'lon tavsifi">{{ old('content') }}</textarea>
                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Narx (UZS)
                            <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               class="form-control @error('price') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                               min="0" placeholder="0">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Manzil
                            <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                        </label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="form-control @error('address') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                               placeholder="Ko'cha, uy raqami">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                        <a href="{{ route('cabinet.adverts.index') }}"
                           class="btn btn-outline-primary">Bekor qilish</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rootSelect = document.getElementById('region-root');
    const childrenContainer = document.getElementById('region-children');
    const ajaxUrl = '{{ route('ajax.regions') }}';

    function buildSelect(parentId, selectedId) {
        fetch(ajaxUrl + '?parent=' + parentId)
            .then(r => r.json())
            .then(regions => {
                if (!regions.length) return;

                const wrapper = document.createElement('div');
                wrapper.style.marginTop = '8px';

                const select = document.createElement('select');
                select.name = 'region_id';
                select.className = 'form-select';
                select.style.cssText = 'border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;';

                const emptyOption = document.createElement('option');
                emptyOption.value = parentId;
                emptyOption.textContent = '— ' + (rootSelect.options[rootSelect.selectedIndex]?.text || '') + ' (hammasi) —';
                select.appendChild(emptyOption);

                regions.forEach(region => {
                    const opt = document.createElement('option');
                    opt.value = region.id;
                    opt.textContent = region.name;
                    if (region.id == selectedId) opt.selected = true;
                    select.appendChild(opt);
                });

                wrapper.appendChild(select);
                childrenContainer.appendChild(wrapper);

                select.addEventListener('change', function () {
                    // Remove selects after this one
                    const selects = childrenContainer.querySelectorAll('select');
                    let found = false;
                    selects.forEach(s => {
                        if (found) s.closest('div').remove();
                        if (s === select) found = true;
                    });

                    if (this.value) buildSelect(this.value, null);
                });

                if (selectedId) {
                    const found = Array.from(select.options).find(o => o.value == selectedId);
                    if (found) buildSelect(selectedId, null);
                }
            });
    }

    rootSelect.addEventListener('change', function () {
        childrenContainer.innerHTML = '';
        if (this.value) buildSelect(this.value, null);
    });

    // Restore old value
    @if(old('region_id'))
    // Can't fully restore nested without knowing chain, just show root
    @endif

    if (rootSelect.value) {
        buildSelect(rootSelect.value, null);
    }
});
</script>
@endsection
