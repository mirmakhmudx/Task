@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-4" style="color:#1a1a2e;">Edit Profile</h6>

                    @if($errors->any())
                        <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cabinet.profile.update') }}">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">First Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                                Last Name <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                            </label>
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;">
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">
                                Phone <span style="color:#9ca3af;font-weight:400;">(ixtiyoriy)</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                                   placeholder="+998901234567">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Saqlash</button>
                            <a href="{{ route('cabinet.profile.show') }}" class="btn btn-outline-primary">Bekor qilish</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
