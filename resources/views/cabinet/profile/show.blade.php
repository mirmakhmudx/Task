@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-semibold mb-0" style="color:#1a1a2e;">Profile</h6>
                <a href="{{ route('cabinet.profile.edit') }}" class="btn btn-primary btn-sm">Edit</a>
            </div>

            <table class="table mb-0" style="font-size:0.9rem;">
                <tbody>
                    <tr style="background:#f8f9fb;">
                        <td style="width:30%;font-weight:600;color:#6b7280;border:none;padding:12px 16px;">First Name</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Last Name</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->last_name ?: '—' }}</td>
                    </tr>
                    <tr style="background:#f8f9fb;">
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Email</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Phone</td>
                        <td style="border:none;padding:12px 16px;">
                            @if($user->phone)
                                {{ $user->phone }}
                                @if($user->isPhoneVerified())
                                    <span style="background:#dcfce7;color:#16a34a;font-size:0.75rem;padding:2px 8px;border-radius:20px;margin-left:6px;">verified</span>
                                @else
                                    <span style="color:#9ca3af;font-size:0.82rem;margin-left:4px;">(is not verified)</span>
                                    <form method="POST" action="{{ route('cabinet.profile.phone.request') }}" class="d-inline ms-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                    </form>
                                @endif
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="background:#f8f9fb;">
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Two Factor Auth</td>
                        <td style="border:none;padding:12px 16px;">
                            @if($user->isTwoFactorEnabled())
                                <form method="POST" action="{{ route('cabinet.profile.two_factor.disable') }}" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            style="min-width:48px;padding:4px 12px;border-radius:6px;background:#16a34a;color:#fff;border:none;font-size:0.85rem;font-weight:600;cursor:pointer;">
                                        On
                                    </button>
                                </form>
                            @else
                                @if($user->isPhoneVerified())
                                    <form method="POST" action="{{ route('cabinet.profile.two_factor.enable') }}" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                style="min-width:48px;padding:4px 12px;border-radius:6px;background:#ef4444;color:#fff;border:none;font-size:0.85rem;font-weight:600;cursor:pointer;">
                                            Off
                                        </button>
                                    </form>
                                @else
                                    <span style="color:#9ca3af;font-size:0.82rem;">Phone verification required</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Status</td>
                        <td style="border:none;padding:12px 16px;">
                            @if($user->isActive())
                                <span style="background:#dcfce7;color:#16a34a;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Active</span>
                            @else
                                <span style="background:#fef9c3;color:#ca8a04;font-size:0.78rem;padding:3px 10px;border-radius:20px;">Waiting</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
