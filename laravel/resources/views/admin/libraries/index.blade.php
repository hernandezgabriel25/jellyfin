@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1>Libraries</h1>
            <ul class="list-group mb-4">
                @foreach($libraries as $library)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.media.index', $library->id) }}">{{ $library->name }} ({{ $library->type }})</a>
                        <form action="{{ route('admin.libraries.destroy', $library->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h2>Add Library</h2>
            <form action="{{ route('admin.libraries.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="movies">Movies</option>
                        <option value="tvshows">TV Shows</option>
                        <option value="mixed">Mixed</option>
                    </select>
                </div>
                <button class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
