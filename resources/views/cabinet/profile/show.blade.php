@extends('layouts.app')

@section('content')
    @include('cabinet.profile._nav')

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-semibold mb-0" style="color:#1a1a2e;">Profile</h6>
                <a href="{{ route('cabinet.profile.edit') }}"
                   class="btn btn-primary btn-sm">Edit</a>
            </div>

            <table class="table mb-0" style="font-size:0.9rem;">
                <tbody>
                    <tr style="background:#f8f9fb;">
                        <td style="width:30%;font-weight:600;color:#6b7280;border:none;padding:12px 16px;">First Name</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Last Name</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">
                            {{ $user->last_name ?: '—' }}
                        </td>
                    </tr>
                    <tr style="background:#f8f9fb;">
                        <td style="font-weight:600;color:#6b7280;border:none;padding:12px 16px;">Email</td>
                        <td style="color:#1a1a2e;border:none;padding:12px 16px;">{{ $user->email }}</td>
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
