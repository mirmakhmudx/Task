@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="width:60px;padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Name</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Slug</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Parent</th>
                        <th style="padding:12px 16px;width:100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                    <tr style="border-bottom:1px solid #f0f1f5;">
                        <td style="padding:12px 16px;color:#9ca3af;">{{ $region->id }}</td>
                        <td style="padding:12px 16px;font-weight:500;color:#1a1a2e;">{{ $region->name }}</td>
                        <td style="padding:12px 16px;color:#6b7280;font-family:monospace;font-size:0.82rem;">{{ $region->slug }}</td>
                        <td style="padding:12px 16px;color:#4a4a6a;">
                            @if($region->parent)
                                <a href="{{ route('admin.regions.show', $region->parent) }}"
                                   style="color:#3b82f6;text-decoration:none;">
                                    {{ $region->parent->name }}
                                </a>
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.regions.show', $region) }}" title="Ko'rish"
                                   style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#f0f1f5;border:1px solid #e8eaf0;color:#374151;text-decoration:none;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('admin.regions.edit', $region) }}" title="Tahrirlash"
                                   style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#eff6ff;border:1px solid #dbeafe;color:#3b82f6;text-decoration:none;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.regions.destroy', $region) }}"
                                      onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="O'chirish"
                                            style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#fff5f5;border:1px solid #fecaca;color:#ef4444;cursor:pointer;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color:#9ca3af;">Region topilmadi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($regions->hasPages())
        <div class="p-3 border-top" style="border-color:#e8eaf0!important;">
            {{ $regions->links() }}
        </div>
        @endif
    </div>
@endsection
