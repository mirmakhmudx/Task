@php
    $map = [
        'open'     => ['Open', '#fff', '#dc2626'],
        'approved' => ['Approved', '#fff', '#2563eb'],
        'closed'   => ['Closed', '#fff', '#6b7280'],
    ];
    [$label, $fg, $bg] = $map[$status] ?? [$status, '#374151', '#f3f4f6'];
@endphp
<span style="background:{{ $bg }};color:{{ $fg }};padding:3px 10px;border-radius:6px;font-size:0.78rem;font-weight:600;">{{ $label }}</span>
