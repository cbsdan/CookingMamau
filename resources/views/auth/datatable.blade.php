@extends('layouts.app')

@section('title', 'User and Buyer Details')

@section('content')
    <h1 class="text-light">User and Buyer Details</h1>
    <table id="usersTable" class="display table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Barangay</th>
                <th>City</th>
                <th>Landmark</th>
                <th>Role</th>
                <th>Active Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table body will be populated by DataTables -->
        </tbody>
    </table>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="admin">Admin</option>
                                <option value="customer">Customer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="active_status" class="form-label">Active Status</label>
                            <select class="form-select" id="active_status" name="active_status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endpush
