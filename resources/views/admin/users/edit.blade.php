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

                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">Foydalanuvchini tahrirlash</h5>

                @if ($errors->any())
                    <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Ism</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                               required autofocus placeholder="Ism kiriting"
                               class="form-control @error('name') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('name')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                               required placeholder="email@example.com"
                               class="form-control @error('email') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('email')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Status</label>
                        <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="waiting" {{ old('status', $user->status) === 'waiting' ? 'selected' : '' }}>Waiting</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                            Yangi parol
                            <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                        </label>
                        <input id="password" type="password" name="password"
                               placeholder="Bo'sh qoldiring — o'zgarmaydi"
                               class="form-control @error('password') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                        @error('password')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
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
