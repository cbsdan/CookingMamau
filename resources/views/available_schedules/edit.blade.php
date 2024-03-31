@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Available Schedule</h1>

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Update form -->
    <form action="{{ route('available_schedules.update', $availableSchedule->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Schedule Date input -->
        <div class="mb-3">
            <label for="schedule" class="form-label">Schedule Date</label>
            <input type="datetime-local" class="form-control" id="schedule" name="schedule" value="{{ old('schedule', $availableSchedule->schedule) }}">
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Update Schedule</button>
    </form>
</div>
@endsection
