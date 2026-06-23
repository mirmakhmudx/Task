@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Bannerlarim</h5>
        <a href="{{ route('cabinet.banners.create.category') }}" class="btn"
           style="background:#16a34a;color:#fff;border-radius:10px;font-weight:600;padding:9px 18px;">
            + Banner qo'shish
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    <div class="card" style="border:1px solid #e5e7eb;border-radius:14px;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f9fafb;">
                    <tr>
                        <th class="ps-4">ID</th><th>Nomi</th><th>Region</th>
                        <th>Kategoriya</th><th>Holati</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="ps-4">{{ $banner->id }}</td>
                            <td>
                                <a href="{{ route('cabinet.banners.show', $banner) }}"
                                   style="color:#2563eb;text-decoration:none;font-weight:500;">{{ $banner->name }}</a>
                            </td>
                            <td>{{ $banner->region?->name ?? '—' }}</td>
                            <td>{{ $banner->category->name }}</td>
                            <td>
                                @php
                                    $map = [
                                        'draft'      => ['Draft', '#6b7280', '#f3f4f6'],
                                        'moderation' => ['On Moderation', '#2563eb', '#dbeafe'],
                                        'wait_pay'   => ['Waiting for Payment', '#b45309', '#fef3c7'],
                                        'active'     => ['Active', '#15803d', '#dcfce7'],
                                        'closed'     => ['Closed', '#fff', '#374151'],
                                    ];
                                    [$label, $fg, $bg] = $map[$banner->status] ?? [$banner->status, '#374151', '#f3f4f6'];
                                @endphp
                                <span style="background:{{ $bg }};color:{{ $fg }};padding:3px 10px;border-radius:6px;font-size:0.78rem;font-weight:600;">
                                    {{ $label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Hali banner yo'q</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $banners->links() }}</div>
@endsection
