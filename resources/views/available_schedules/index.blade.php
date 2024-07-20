


@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Available Schedules</h1>
    <hr>
    <!-- Display upcoming schedules -->
    <h2>Upcoming Schedules</h2>
    <div class="table-responsive">
        <table id="upcomingScheduleTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Schedule Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="upcomingScheduleBody">
                <!-- Schedules will be dynamically added here -->
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Display past schedules -->
    <h2>Past Schedules</h2>
    <div class="table-responsive">
        <table id="pastScheduleTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Schedule Date</th>
                </tr>
            </thead>
            <tbody id="pastSchedulesBody">
                <!-- Schedules will be dynamically added here -->
            </tbody>
        </table>
    </div>
</div>

<!-- Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Create New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <div class="form-group">
                        <label for="scheduleDate" class="control-label">Schedule Date</label>
                        <input type="datetime-local" class="form-control" id="scheduleDate" name="schedule_date">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="scheduleSubmit" type="button" class="btn btn-primary">Save</button>
                <button id="scheduleUpdate" type="button" class="btn btn-primary" style="display: none;">Update</button>
            </div>
        </div>
    </div>
</div>

<script src='{{ asset('js/scheduleCrud.js') }}'></script>

@endsection
