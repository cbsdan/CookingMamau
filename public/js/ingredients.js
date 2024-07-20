// $("#addModalBtn").on('click', function () {
//     console.log('clicked');
//     $('#ingredientModal').modal('show');
// })
$(document).ready(function () {
    // Initialize DataTable

    $('#ingredientTable').DataTable({
        ajax: {
            url: "/api/ingredients",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'excel',
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn-pdf',
            },
            {
                text: 'Add Ingredient',
                className: 'btn-add-ingredient',
                action: function (e, dt, node, config) {
                    $("#ingredientForm").trigger("reset");
                    $('#ingredientModal').modal('show');
                    $('#ingredientUpdate').hide();
                    $('#ingredientSubmit').show();
                    $('#ingredientImage').remove();
                }
            }
        ],
        columns: [
            { data: 'id' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<img src="${data.image_path ? data.image_path : '/uploaded_files/default-product.png'}" width="50" height="60">`;
                }
            },
            { data: 'name' },
            { data: 'unit' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a href='#' class='editBtn animated-icon' data-id=${data.id}><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>
                        <a href='#' class='deleteBtn animated-icon' data-id=${data.id}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>
                    `;
                }
            }
        ],
        "order": [[0, "desc"]],
        scrollY: 400,
        scroller: {
            loadingIndicator: true
        },
        initComplete: function () {
            $('.btn-add-ingredient, .btn-pdf').css({
                'border-radius': '20px',
                'width': '150px',
                'height': '40px',
                'margin': '5px',
                'background-color': '#da95da',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'padding': '10px 20px',
                'font-size': '15px',
                'cursor': 'pointer',
                'margin-top': '20px'
            });

            $('.dt-buttons').css({
                'display': 'flex',
                'gap': '10px'
            });

            $('.dataTables_filter input').css({
                'width': '250px',
                'margin-top': '20px',
                'margin-left': '10px',
                'border-radius': '20px',
                'padding': '5px 10px',
                'border': '1px solid #ccc'
            });

            $('.dataTables_filter label').css({
                'margin-top': '20px',
                'margin-left': '10px',

                'align-items': 'center'
            });
        }

    });

    // Submit new ingredient
    $("#ingredientSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#ingredientForm')[0];
        let formData = new FormData(data);
        $.ajax({
            type: "POST",
            url: "/api/ingredients",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#name').val("")
                $('#unit').val("")
                if (window.location.pathname == "/bakedgood-all") {
                    $('#ingredientsList option:eq(0)').after(`<option value='${data.ingredient.id}'>${data.ingredient.name}</option>`)
                }

                $("#ingredientModal").modal("hide");
                var $ingredientTable = $('#ingredientTable').DataTable();
                $ingredientTable.ajax.reload();

            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Edit ingredient
    $('#ingredientTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#ingredientImage').remove();
        $('#ingredientId').remove();
        $("#ingredientForm").trigger("reset");
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'ingredientId', name: 'id', value: id }).appendTo('#ingredientForm');
        $('#ingredientModal').modal('show');
        $('#ingredientSubmit').hide();
        $('#ingredientUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/ingredients/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#name').val(data.name);
                $('#unit').val(data.unit);
                $("#ingredientForm").append(`<img src="${data.image_path ? data.image_path : '/uploaded_files/default-product.png'}" width='200px' height='200px' id="ingredientImage" />`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update ingredient
    $("#ingredientUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#ingredientId').val();
        var table = $('#ingredientTable').DataTable();
        var data = $('#ingredientForm')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/ingredients/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#ingredientModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Delete ingredient
    $('#ingredientTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#ingredientTable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        bootbox.confirm({
            message: "Do you want to delete this ingredient?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/ingredients/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });
                            bootbox.alert(data.success);
                        },
                        error: function (error) {
                            console.log(error);
                            bootbox.alert('Error deleting ingredient.');
                        }
                    });
                }
            }
        });
    });

    $('#ingredientImportForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            url: 'api/ingredient/import', // API endpoint
            type: 'POST',
            data: formData,
            contentType: false, // Important
            processData: false, // Important
            success: function(response) {
                // Handle success response
                alert(response.message); // Or use a more sophisticated notification system

                var table = $('#ingredientTable').DataTable();
                table.ajax.reload();
            },
            error: function(xhr) {
                // Handle error response
                var errorMsg = 'An error occurred: ' + xhr.responseJSON.message;
                alert(errorMsg);
            }
        });
    });

    //Infinite Scroll
    $('#usersTable_wrapper .dataTables_scrollBody').on('scroll', function() {
        let tbody = $(this);
        if (tbody.scrollTop() + tbody.innerHeight() >= tbody[0].scrollHeight && !loadingData) {
            fetchData(currentPage);
        }
    });
});
