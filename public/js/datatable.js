$(document).ready(function() {
    let currentPage = 1;
    let loadingData = false;
    let reachedEnd = false;

    function fetchData(page) {
        if (loadingData || reachedEnd) return;
        loadingData = true;

        $.ajax({
            url: '/api/user/fetch',
            type: "GET",
            data: { page: page },
            success: function(response) {
                console.log('JSON Response:', response);

                if (!response || !response.data || response.data.length === 0) {
                    reachedEnd = true;
                    console.log("No more data to load.");
                    return;
                }

                let table = $('#usersTable').DataTable();
                let existingIds = table.rows().data().pluck('id').toArray();
                let newData = response.data.filter(item => !existingIds.includes(item.id));

                table.rows.add(newData).draw(false);
                currentPage++;
                loadingData = false;
            },
            error: function(xhr, error, thrown) {
                console.error("Error in fetching data: ", xhr.responseText);
                loadingData = false;
            }
        });
    }

    // Initialize DataTable
    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: false,
        paging: false, // Disable built-in pagination
        columns: [
            { data: 'fname', title: 'First Name' },
            { data: 'lname', title: 'Last Name' },
            { data: 'contact', title: 'Contact' },
            { data: 'address', title: 'Address' },
            { data: 'barangay', title: 'Barangay' },
            { data: 'city', title: 'City' },
            { data: 'landmark', title: 'Landmark' },
            {
                data: 'is_admin',
                title: 'Role',
                render: function(data) {
                    return data ? 'Admin' : 'User';
                }
            },
            {
                data: 'is_activated',
                title: 'Active Status',
                render: function(data) {
                    return data ? 'Active' : 'Deactivated';
                }
            },
            {
                data: null,
                title: 'Actions',
                render: function(data, type, row) {
                    return '<i class="fas fa-edit edit-icon" onclick="editUser('+ row.id +')"></i>';
                },
                orderable: false
            }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        searching: true,
        scrollY: '400px',
        scrollCollapse: true,
        language: {
            emptyTable: "No data available in table",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            lengthMenu: "Show _MENU_ entries",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching records found"
        },
        order: [[0, "desc"]]
    });

    // Infinite scroll event
    $('#usersTable_wrapper .dataTables_scrollBody').on('scroll', function() {
        let tbody = $(this);
        if (tbody.scrollTop() + tbody.innerHeight() >= tbody[0].scrollHeight && !loadingData) {
            fetchData(currentPage);
        }
    });

    // Initial data fetch
    fetchData(currentPage);
});
