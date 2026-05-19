@extends('layouts.app')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <ul class="nav nav-tabs" style="border-bottom:none;">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cabinet.index') ? 'active' : '' }}"
                   href="{{ route('cabinet.index') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cabinet.adverts.index') }}">Adverts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('cabinet.adverts.favorites.index') }}">Favorites</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cabinet.profile.show') }}">Profile</a>
            </li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                <tr>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Updated</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Title</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Region</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Category</th>
                    <th style="padding:12px 16px;font-weight:600;color:#6b7280;"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($adverts as $advert)
                    <tr style="border-bottom:1px solid #f0f1f5;">
                        <td style="padding:12px 16px;color:#6b7280;">{{ $advert->id }}</td>
                        <td style="padding:12px 16px;color:#6b7280;font-size:0.85rem;">
                            {{ $advert->updated_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td style="padding:12px 16px;">
                            <a href="{{ route('adverts.show', $advert) }}"
                               style="color:#2563eb;text-decoration:none;font-weight:500;">
                                {{ $advert->title }}
                            </a>
                        </td>
                        <td style="padding:12px 16px;color:#6b7280;font-size:0.85rem;">
                            {{ $advert->region?->name ?? '—' }}
                        </td>
                        <td style="padding:12px 16px;color:#6b7280;font-size:0.85rem;">
                            {{ $advert->category->name }}
                        </td>
                        <td style="padding:12px 16px;text-align:right;">
                            <form method="POST"
                                  action="{{ route('cabinet.adverts.favorites.remove', $advert) }}"
                                  style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm">
                                    ✕ Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color:#9ca3af;font-size:0.9rem;">
                            Sevimlilar ro'yxati bo'sh
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($adverts->hasPages())
            <div class="p-3 border-top">
                {{ $adverts->links() }}
            </div>
        @endif
    </div>

@endsection
