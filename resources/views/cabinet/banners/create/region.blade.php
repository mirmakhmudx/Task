@extends('layouts.app')

@section('content')
    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;">
        <div class="card-body">
            <h5 class="fw-semibold mb-4">2-qadam: Regionni tanlang</h5>

            @if($region)
                <p style="color:#6b7280;font-size:0.9rem;">Hozirgi: <strong>{{ $region->getAddress() }}</strong></p>
            @endif

            <div class="list-group mb-3">
                <a href="{{ $region
                    ? route('cabinet.banners.create.banner.region', [$category, $region])
                    : route('cabinet.banners.create.banner', [$category]) }}"
                   class="list-group-item list-group-item-action"
                   style="border-radius:10px;margin-bottom:4px;font-size:0.9rem;">
                    {{ $region ? 'Shu regionni tanlash' : 'Barcha regionlar uchun' }}
                </a>
            </div>

            @if($regions->count())
                <p style="color:#9ca3af;font-size:0.82rem;">Yoki ichki regionni tanlang:</p>
                <div class="list-group">
                    @foreach($regions as $r)
                        <a href="{{ route('cabinet.banners.create.region.region', [$category, $r]) }}"
                           class="list-group-item list-group-item-action"
                           style="border-radius:10px;margin-bottom:4px;font-size:0.9rem;">
                            {{ $r->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
