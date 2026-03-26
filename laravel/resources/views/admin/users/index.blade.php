@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1>Users</h1>
            <ul class="list-group mb-4">
                @foreach($users as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $user->name }} ({{ $user->uuid }})
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h2>Add User</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
