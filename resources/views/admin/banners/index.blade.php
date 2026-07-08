@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.banners.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Nomi</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Status</label>
                    <select name="status" class="form-select" style="border-radius:10px;">
                        <option value="">— Barchasi —</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Qidirish</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary w-100">Tozalash</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;">
                    <tr>
                        <th style="padding:12px 16px;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;color:#6b7280;">Nomi</th>
                        <th style="padding:12px 16px;color:#6b7280;">Egasi</th>
                        <th style="padding:12px 16px;color:#6b7280;">Kategoriya</th>
                        <th style="padding:12px 16px;color:#6b7280;">Format</th>
                        <th style="padding:12px 16px;color:#6b7280;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td style="padding:12px 16px;">{{ $banner->id }}</td>
                            <td style="padding:12px 16px;">
                                <a href="{{ route('admin.banners.show', $banner) }}" style="color:#2563eb;font-weight:500;text-decoration:none;">{{ $banner->name }}</a>
                            </td>
                            <td style="padding:12px 16px;color:#374151;">{{ $banner->user->name ?? '—' }}</td>
                            <td style="padding:12px 16px;color:#374151;">{{ $banner->category->name ?? '—' }}</td>
                            <td style="padding:12px 16px;color:#374151;">{{ $banner->format }}</td>
                            <td style="padding:12px 16px;">
                                @php
                                    $map = [
                                        'draft'      => ['Draft', '#6b7280', '#f3f4f6'],
                                        'moderation' => ['Moderation', '#2563eb', '#dbeafe'],
                                        'active'     => ['Active', '#15803d', '#dcfce7'],
                                        'closed'     => ['Closed', '#dc2626', '#fee2e2'],
                                    ];
                                    [$lbl,$fg,$bg] = $map[$banner->status] ?? [$banner->status,'#374151','#f3f4f6'];
                                @endphp
                                <span style="background:{{ $bg }};color:{{ $fg }};padding:3px 10px;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ $lbl }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Banner yo'q</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $banners->links() }}</div>
@endsection
