// Set CSRF token in AJAX headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Function to fetch users data via AJAX
function fetchUsers() {
    $.ajax({
        url: '/admin/dashboard', // Ensure this URL matches the API endpoint
        method: 'GET',
        dataType: 'json', // Ensure the response is treated as JSON
        success: function(response) {
            if (response && response.users) {
                displayUsers(response.users); // Assuming API returns an object with 'users' key
            } else {
                console.error('Invalid response format:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching users:', error);
        }
    });
}

// Function to display users in the dashboard
function displayUsers(users) {
    if (!Array.isArray(users)) {
        console.error('Invalid data format:', users);
        return;
    }

    let userList = $('#userList'); // Assuming you have a <ul> element with id 'userList'
    userList.empty(); // Clear existing list
    users.forEach(function(user) {
        userList.append(`<li>${user.email}</li>`); // Display each user's email (adjust as per your data structure)
    });
}

// Function to handle the edit action
function editUser(userId) {
    // Fetch the user data
    $.ajax({
        url: `/api/user/${userId}`, // Ensure this URL matches the API endpoint
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response && response.user) {
                const user = response.user;

                // Populate the modal with user data
                $('#username').val(user.email); // Assuming 'email' is used as the username
                $('#role').val(user.is_admin ? 'admin' : 'customer'); // Assuming 'is_admin' determines the role
                $('#active_status').val(user.is_activated ? 'active' : 'inactive'); // Assuming 'is_activated' determines the status

                // Store the user ID in the modal for later use
                $('#editUserForm').data('userId', user.id);

                // Show the modal
                $('#editUserModal').modal('show');
            } else {
                console.error('Invalid response format:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching user:', error);
        }
    });
}

// Document ready function
$(document).ready(function() {
    // Fetch users when the page loads
    fetchUsers();

    // Handle form submission
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        // Get the user ID from the modal data
        const userId = $(this).data('userId');

        // Prepare the data to be sent
        const data = {
            role: $('#role').val(),
            active_status: $('#active_status').val()
        };

        // Send the update request
        $.ajax({
            url: `/api/user/${userId}`,
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response && response.user) {
                    // Update the DataTable with the new data
                    const table = $('#usersTable').DataTable();
                    const row = table.row(`[data-id="${userId}"]`).data();
                    row.is_admin = response.user.is_admin;
                    row.is_activated = response.user.is_activated;
                    table.row(`[data-id="${userId}"]`).data(row).draw();

                    // Hide the modal
                    $('#editUserModal').modal('hide');
                } else {
                    console.error('Invalid response format:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating user:', error);
            }
        });
    });
});
