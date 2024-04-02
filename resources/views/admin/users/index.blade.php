@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>Users</h1>
    <hr>
    <div class="mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create New User</a>
    </div>

    <table class="table" id="myDataTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Activation Status</th>
                <th>Contact</th>
                <th>Updated At</th>
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
                        <td>{{ $user->updated_at }}</td>
                        <td class=''>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                            <form action="{{ route('admin.users.activation', $user->id) }}" method="POST" class="mt-2">
                                @php
                                    $updateTo = $user->is_activated ? "Deactivate" : "Activate";
                                @endphp
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm {{$user->is_activated ? 'btn-danger' : 'btn-success'}}" onclick="return confirm('Are you sure you want to {{$updateTo}} this user?')">{{$user->is_activated ? "Deactivate" : "Activate"}}</button>
                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
