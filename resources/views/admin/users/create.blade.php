@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size:0.85rem;">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}" class="text-decoration-none text-secondary">Admin</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-secondary">Foydalanuvchilar</a>
                </li>
                <li class="breadcrumb-item active text-dark fw-medium">Yangi qo'shish</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">

                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">Yangi foydalanuvchi qo'shish</h5>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    {{-- Ism --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Ism</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="Ism kiriting"
                            class="form-control @error('name') is-invalid @enderror"
                            style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                        >
                        @error('name')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="email@example.com"
                            class="form-control @error('email') is-invalid @enderror"
                            style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                        >
                        @error('email')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Parol --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">Parol</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Parol kiriting"
                            class="form-control @error('password') is-invalid @enderror"
                            style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                        >
                        @error('password')
                            <div class="invalid-feedback" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Saqlash
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-outline-primary">
                            Bekor qilish
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
