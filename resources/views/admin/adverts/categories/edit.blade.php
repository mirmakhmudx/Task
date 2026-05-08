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
            Orqaga
        </a>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">Tahrirlash</h5>

                @if($errors->any())
                    <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.adverts.categories.update', $category) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Name</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                               class="form-control @error('slug') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;font-family:monospace;">
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
@endsection
