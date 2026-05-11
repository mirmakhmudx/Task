@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="card">
        <div class="card-body">
            <p>Hushhh kelibsiz, {{ Auth::user()->name }}!</p>
        </div>
    </div>
@endsection
