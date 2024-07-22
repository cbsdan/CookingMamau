// $(document).ready(function() {
//     // Initialize DataTable
//     let table = $('#usersTable').DataTable({
//         ajax: {
//             url: '/api/users',
//             type: 'GET',
//             dataSrc: 'users'
//         },
//         columns: [
//             { data: 'id', title: 'User ID' },
//             { data: 'buyer.fname', title: 'First Name' },
//             { data: 'buyer.lname', title: 'Last Name' },
//             { data: 'buyer.contact', title: 'Contact' },
//             { data: 'buyer.address', title: 'Address' },
//             { data: 'buyer.barangay', title: 'Barangay' },
//             { data: 'buyer.city', title: 'City' },
//             { data: 'buyer.landmark', title: 'Landmark' },
//             {
//                 data: 'is_admin',
//                 title: 'Role',
//                 render: function(data) {
//                     return data ? 'Admin' : 'User';
//                 }
//             },
//             {
//                 data: 'is_activated',
//                 title: 'Active Status',
//                 render: function(data) {
//                     return data ? 'Active' : 'Deactivated';
//                 }
//             },
//             {
//                 data: null,
//                 title: 'Actions',
//                 render: function(data, type, row) {
//                     return '<i class="fas fa-edit edit-icon" onclick="editUser('+ row.id +')"></i>';
//                 },
//                 orderable: false
//             }
//         ],
//         createdRow: function(row, data, dataIndex) {
//             $(row).attr('data-id', data.id);
//         },
//         order: [[0, "desc"]]
//     });
// });
