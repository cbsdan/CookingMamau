@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by ID or Email" value="{{ request()->input('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create New User</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Activation Status</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @if (!$user->is_admin)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td class='col-1'>
                            @php
                                $profileImagePath = $user->profile_image_path ?? 'uploaded_files/default-profile.png';
                            @endphp
                            <img src="{{ asset($profileImagePath) }}" class="img-thumbnail" alt="Profile" >   
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->buyer->fname }}</td>
                        <td>{{ $user->buyer->lname }}</td>
                        <td>{{ ($user->is_activated ? "Activated" : "Deactivated") }}</td>
                        <td>{{ $user->buyer->contact }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                            <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
