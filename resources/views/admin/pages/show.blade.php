@extends('layouts.app')

@section('content')
    @include('admin.pages._nav')
    <style>.page-content img { max-width: 100%; height: auto; }</style>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">Edit</a>
        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('O\'chirilsinmi?')">@csrf @method('DELETE')
            <button class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ url($page->getPath()) }}" class="btn btn-outline-secondary" target="_blank">Ochiq ko'rish</a>
    </div>

    <table class="table">
        <tr><th style="width:200px;">ID</th><td>{{ $page->id }}</td></tr>
        <tr><th>Title</th><td>{{ $page->title }}</td></tr>
        <tr><th>Menu Title</th><td>{{ $page->menu_title }}</td></tr>
        <tr><th>Slug</th><td>{{ $page->slug }}</td></tr>
        <tr><th>Path</th><td>/{{ $page->getPath() }}</td></tr>
        <tr><th>Description</th><td>{{ $page->description }}</td></tr>
    </table>

    <div class="card"><div class="card-body page-content">{!! $page->content !!}</div></div>
@endsection
