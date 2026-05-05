@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    {{-- Back --}}
    <a href="{{ route('admin.users.index') }}"
       class="d-inline-flex align-items-center gap-1 mb-3 text-decoration-none"
       style="font-size:0.85rem;color:#6b7280;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Orqaga
    </a>

    <div class="card">
        <div class="card-body">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-semibold mb-0" style="color:#1a1a2e;font-size:1rem;">{{ $user->name }}</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="d-inline-flex align-items-center gap-1"
                       style="padding:5px 14px;font-size:0.82rem;border-radius:8px;background:#eff6ff;color:#3b82f6;border:1px solid #dbeafe;text-decoration:none;font-weight:500;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="d-inline-flex align-items-center gap-1"
                                style="padding:5px 14px;font-size:0.82rem;border-radius:8px;background:#fff5f5;color:#ef4444;border:1px solid #fecaca;font-weight:500;cursor:pointer;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <table class="table mb-0" style="font-size:0.9rem;">
                <tbody>
                    <tr style="background:#f8f9fb;">
                        <td style="width:30%;font-weight:600;color:#6b7280;border:none;padding:12px 16px;">ID</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Name</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->name }}</td>
                    </tr>
                    <tr style="background:#f8f9fb;">
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Email</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Status</td>
                        <td style="border:none;padding:12px 16px;">
                            @if($user->status === 'active')
                                <span style="background:#dcfce7;color:#16a34a;font-weight:500;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Active</span>
                            @else
                                <span style="background:#f3f4f6;color:#6b7280;font-weight:500;padding:3px 10px;border-radius:20px;font-size:0.78rem;">Waiting</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
