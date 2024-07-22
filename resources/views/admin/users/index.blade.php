@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users</h1>
    <hr>
    <table id="usersTable" class="table table-striped table-hover w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Profile</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
                <th>Activation Status</th>
                <th>Contact</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#usersTable').DataTable({
            ajax: {
                url: '/api/users',
                dataSrc: 'users'
            },
            dom: 'Bfrtip',
            columns: [
                { data: 'id' },
                { data: 'profile_image_path', render: function(data) {
                        return '<img src="' + (data ? data : '/uploaded_files/default-profile.png') + '" class="img-thumbnail" width="50" height="50">';
                    }
                },
                { data: 'email' },
                {
                    data: 'buyer.fname',
                    title: 'First Name',
                    render: function(data, type, row) {
                        return data ? data : 'Admin';
                    }
                },
                {
                    data: 'buyer.lname',
                    title: 'Last Name',
                    render: function(data, type, row) {
                        return data ? data : 'Admin';
                    }
                },
                {
                    data: 'is_admin',
                    title: 'Role',
                    render: function(data, type, row) {
                        var userSelected = data == 0 ? 'selected' : '';
                        var adminSelected = data == 1 ? 'selected' : '';
                        return `<select class="role-select" data-id="${row.id}">
                                    <option value="0" ${userSelected}>User</option>
                                    <option value="1" ${adminSelected}>Admin</option>
                                </select>`;
                    }
                },
                {
                    data: 'is_activated',
                    title: 'Active Status',
                    render: function(data, type, row) {
                        var activatedSelected = data ? 'selected' : '';
                        var deactivatedSelected = !data ? 'selected' : '';
                        return `<select class="status-select" data-id="${row.id}">
                                    <option value="1" ${activatedSelected}>Activated</option>
                                    <option value="0" ${deactivatedSelected}>Deactivated</option>
                                </select>`;
                    }
                },
                {
                    data: 'buyer.contact',
                    title: 'Contact',
                    render: function(data, type, row) {
                        return data ? data : 'None';
                    }
                },
                { data: 'updated_at' },
            ]
        });

        // Save user
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var userId = $('#userId').val();
            var url = userId ? '/api/users/' + userId : '/api/users';
            var method = userId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#userModal').modal('hide');
                    table.ajax.reload();
                    alert(data.message);
                },
                error: function(err) {
                    alert('Error: ' + err.responseJSON.message);
                }
            });
        });

        // Event listener for role updates
        $('#usersTable').on('change', '.role-select', function() {
            var userId = $(this).data('id');
            var newRole = $(this).val();

            $.ajax({
                url: `/api/users/${userId}/update-role`,
                type: 'PUT',
                data: { is_admin: newRole },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Role Updated',
                        text: 'User role has been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    console.log('Role updated successfully:', response);
                },
                error: function(xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the user role.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    console.error('Error updating role:', xhr.responseText);
                }
            });
        });

        // Event listener for status updates
        $('#usersTable').on('change', '.status-select', function() {
            var userId = $(this).data('id');
            var newStatus = $(this).val();

            $.ajax({
                url: `/api/users/${userId}/update-status`,
                type: 'PUT',
                data: { is_activated: newStatus },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: 'User status has been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                console.log('Status updated successfully:', response);
                },
                error: function(xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the user status.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    console.error('Error updating status:', xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
