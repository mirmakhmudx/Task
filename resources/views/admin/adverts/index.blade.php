@extends('layouts.app')

@section('content')
    @include('admin.adverts._nav')

    @if(session('success'))
        <div class="alert alert-success" style="border-radius:10px;">{{ session('success') }}</div>
    @endif

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3" style="color:#6b7280;">Filter</h6>
            <form method="GET" action="{{ route('admin.adverts.index') }}" class="row g-3 align-items-end">
                <div class="col-md-1">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">ID</label>
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">Title</label>
                    <input type="text" name="title" value="{{ request('title') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">User</label>
                    <input type="text" name="user" value="{{ request('user') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">Region</label>
                    <input type="text" name="region" value="{{ request('region') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">Category</label>
                    <input type="text" name="category" value="{{ request('category') }}" class="form-control" style="border-radius:10px;">
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="font-size:0.82rem;color:#6b7280;">Status</label>
                    <select name="status" class="form-select" style="border-radius:10px;">
                        <option value="">— All —</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.adverts.index') }}" class="btn btn-outline-primary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Updated</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Title</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">User</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Region</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Category</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adverts as $advert)
                        <tr style="border-bottom:1px solid #f0f1f5;">
                            <td style="padding:12px 16px;">{{ $advert->id }}</td>
                            <td style="padding:12px 16px;color:#6b7280;">{{ $advert->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td style="padding:12px 16px;">
                                <a href="{{ route('admin.adverts.show', $advert) }}"
                                   style="color:#2563eb;text-decoration:none;font-weight:500;">{{ $advert->title }}</a>
                            </td>
                            <td style="padding:12px 16px;color:#374151;">{{ $advert->user_id }} - {{ $advert->user?->name }}</td>
                            <td style="padding:12px 16px;color:#374151;">{{ $advert->region ? $advert->region_id . ' - ' . $advert->region->name : '—' }}</td>
                            <td style="padding:12px 16px;color:#374151;">{{ $advert->category_id }} - {{ $advert->category?->name }}</td>
                            <td style="padding:12px 16px;">
                                @php
                                    $map = [
                                        'draft'      => ['Draft', '#6b7280', '#f3f4f6'],
                                        'moderation' => ['On Moderation', '#2563eb', '#dbeafe'],
                                        'active'     => ['Active', '#15803d', '#dcfce7'],
                                        'closed'     => ['Closed', '#dc2626', '#fee2e2'],
                                    ];
                                    [$label, $fg, $bg] = $map[$advert->status] ?? [$advert->status, '#374151', '#f3f4f6'];
                                @endphp
                                <span style="background:{{ $bg }};color:{{ $fg }};padding:3px 10px;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ $label }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4" style="color:#9ca3af;">No adverts found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $adverts->links() }}</div>
@endsection
