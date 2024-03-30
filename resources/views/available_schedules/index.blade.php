@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Available Schedules</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Link to create a new available schedule -->
    <div class="mb-3">
        <a href="{{ route('available_schedules.create') }}" class="btn btn-primary">Create New Schedule</a>
    </div>

    <!-- Display upcoming schedules -->
    <h2>Upcoming Schedules</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Schedule Date</th>
                <th>Actions</th> <!-- Add a new column for actions -->
            </tr>
        </thead>
        <tbody>
            @foreach($upcomingSchedules as $schedule)
            <tr>
                <td>{{ $schedule->id }}</td>
                <td>{{ $schedule->schedule }}</td>
                <td>
                    <!-- Edit action -->
                    <a href="{{ route('available_schedules.edit', $schedule->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <!-- Delete action -->
                    <form action="{{ route('available_schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Display past schedules -->
    <h2>Past Schedules</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Schedule Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pastSchedules as $schedule)
            <tr>
                <td>{{ $schedule->id }}</td>
                <td>{{ $schedule->schedule }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection