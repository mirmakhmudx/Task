@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <p>Xush kelibsiz, {{ Auth::user()->name }}!</p>
    </div>
</div>
@endsection
