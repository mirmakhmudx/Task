@extends('layouts.app')

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('cabinet.index') }}">Dashboard</a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        <p>Salom, {{ Auth::user()->name }}! Bu sizning shaxsiy kabinetingiz.</p>
    </div>
</div>
@endsection
