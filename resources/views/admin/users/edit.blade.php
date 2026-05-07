@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        <a href="{{ route('admin.users.show', $user) }}"
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

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Ism</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Rol</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('status', $user->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Saqlash</button>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">Bekor qilish</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
