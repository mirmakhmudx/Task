@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.9rem;">
                <thead style="background:#f8f9fb;border-bottom:1px solid #e8eaf0;">
                    <tr>
                        <th style="width:70px;padding:12px 16px;font-weight:600;color:#6b7280;">ID</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Name</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Email</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;">Status</th>
                        <th style="padding:12px 16px;font-weight:600;color:#6b7280;width:110px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom:1px solid #f0f1f5;">
                        <td style="padding:12px 16px;color:#9ca3af;">{{ $user->id }}</td>
                        <td style="padding:12px 16px;font-weight:500;color:#1a1a2e;">{{ $user->name }}</td>
                        <td style="padding:12px 16px;color:#4a4a6a;">{{ $user->email }}</td>
                        <td style="padding:12px 16px;">
                            @if($user->status === 'active')
                                <span style="background:#dcfce7;color:#16a34a;font-weight:500;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Active</span>
                            @else
                                <span style="background:#f3f4f6;color:#6b7280;font-weight:500;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Waiting</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- View --}}
                                <a href="{{ route('admin.users.show', $user) }}"
                                   title="Ko'rish"
                                   style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#f0f1f5;border:1px solid #e8eaf0;color:#374151;text-decoration:none;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   title="Tahrirlash"
                                   style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#eff6ff;border:1px solid #dbeafe;color:#3b82f6;text-decoration:none;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="O'chirish"
                                            style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#fff5f5;border:1px solid #fecaca;color:#ef4444;cursor:pointer;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
