@extends('layouts.app')

@section('content')
    @include('admin.pages._nav')
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('admin.pages.store') }}">
        @csrf
        @include('admin.pages._form', ['page' => null])
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    @include('admin.pages._editor')
@endsection
