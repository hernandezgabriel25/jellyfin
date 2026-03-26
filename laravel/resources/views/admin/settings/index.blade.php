@extends('layouts.admin')

@section('content')
    <h1>Settings</h1>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">TMDb API Key</label>
            <input type="text" name="tmdb_api_key" class="form-control" value="{{ $tmdb_api_key }}">
        </div>
        <button class="btn btn-primary">Update Settings</button>
    </form>
@endsection
