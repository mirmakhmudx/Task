@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-4" style="color:#1a1a2e;">Phone Verification</h6>

                    @if(session('info'))
                        <div class="mb-4 p-3" style="background:#eff6ff;border:1px solid #dbeafe;border-radius:10px;font-size:0.85rem;color:#3b82f6;">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3" style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;">
                            <ul class="mb-0 ps-3" style="font-size:0.85rem;color:#dc2626;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cabinet.profile.phone.verify') }}">
                        @csrf @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-medium" style="font-size:0.875rem;color:#374151;">SMS Code</label>
                            <input type="text" name="token"
                                   class="form-control @error('token') is-invalid @enderror"
                                   style="border-radius:10px;font-size:0.9rem;border-color:#e8eaf0;"
                                   placeholder="12345" autofocus>
                            @error('token') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Verify</button>
                        <a href="{{ route('cabinet.profile.show') }}" class="btn btn-outline-secondary ms-2">Orqaga</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
