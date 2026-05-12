@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4" style="color:#1a1a2e;">SMS tasdiqlash</h5>
                @if($errors->any())
                    <div class="mb-3 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                        <p class="mb-0" style="font-size:0.85rem;color:#dc2626;">{{ $errors->first() }}</p>
                    </div>
                @endif
                <form method="POST" action="{{ route('login.phone.verify') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">SMS kodi</label>
                        <input type="text" name="token"
                               class="form-control @error('token') is-invalid @enderror"
                               style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                               autofocus>
                        @error('token') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Tasdiqlash</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
