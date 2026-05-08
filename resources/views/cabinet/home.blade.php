@extends('layouts.app')

@section('content')
    <ul class="nav nav-tabs mb-4" style="border-bottom:none;">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('cabinet.index') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cabinet.profile.show') }}">Profile</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body text-center py-5" style="color:#9ca3af;">
            Cabinet Dashboard
        </div>
    </div>
@endsection
