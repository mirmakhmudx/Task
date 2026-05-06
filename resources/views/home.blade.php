@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body text-center py-5">
        <h1 class="mb-4">{{ config('app.name', 'Laravel') }}</h1>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        @endauth
    </div>
</div>
@endsection
