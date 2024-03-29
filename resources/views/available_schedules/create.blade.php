@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Available Schedule</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class='mb-0'>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Form for creating a new available schedule -->
    <form action="{{ route('available_schedules.store') }}" method="POST">
        @csrf

        
        <!-- Container for dynamically added input fields -->
        <div id="scheduleFields">
            <!-- Input field for the first schedule date -->
            <div class="mb-3">
                <label for="schedule1">Schedule Date 1:</label>
                <input type="datetime-local" id="schedule1" name="schedules[]" class="form-control">
            </div>
        </div>

        <!-- Button to add new input fields -->
        <div class="buttons d-flex flex-column gap-3 col-12">
            <button type="button" class="btn btn-success col-3" id="addSchedule">Add Schedule</button>
    
            <!-- Submit button -->
            <div class="text-end col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript to add new input fields -->
<script>
    // Counter to track the number of added input fields
    let scheduleCounter = 1;

    // Function to add new input fields for schedule dates
    function addScheduleField() {
        scheduleCounter++;
        const scheduleField = `
            <div class="mb-3">
                <label for="schedule${scheduleCounter}">Schedule Date ${scheduleCounter}:</label>
                <input type="datetime-local" id="schedule${scheduleCounter}" name="schedules[]" class="form-control">
            </div>
        `;
        document.getElementById('scheduleFields').insertAdjacentHTML('beforeend', scheduleField);
    }

    // Event listener for the addSchedule button
    document.getElementById('addSchedule').addEventListener('click', addScheduleField);
</script>
@endsection
