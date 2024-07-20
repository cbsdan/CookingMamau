$(document).ready(function () {
    // Initialize DataTable
    $('#discountTable').DataTable({
        ajax: {
            url: "/api/discounts",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Discount',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#discountForm").trigger("reset");
                    $('#discountModal').modal('show');
                    $('#discountUpdate').hide();
                    $('#discountImage').remove();
                }
            }
        ],
        columns: [
            { data: 'discount_code' },
            {
                data: null,
                render: function (data, type, row) {
                    return `<img src="${data.image_path ? data.image_path : '/uploaded_files/default-profile.png'}" width="50" height="60">`;
                }
            },
            { data: 'percent' },
            { data: 'max_number_buyer' },
            { data: 'min_order_price' },
            { data: 'max_discount_amount' },
            { data: 'is_one_time_use' },
            { data: 'discount_start' },
            { data: 'discount_end' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a href='#' class='editBtn' data-id=${data.discount_code}><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>
                        <a href='#' class='deleteBtn' data-id=${data.discount_code}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>
                    `;
                }
            }
        ],
        "order": [[0, "desc"]]
    });

    // Submit new discount
    $("#discountSubmit").on('click', function (e) {
        e.preventDefault();
        console.log('clicked');
        var data = $('#discountForm')[0];
        let formData = new FormData(data);
        $.ajax({
            type: "POST",
            url: "/api/discounts",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#discountModal").modal("hide");
                var $discountTable = $('#discountTable').DataTable();
                $discountTable.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Edit discount
    $('#discountTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#discountImage').remove();
        $('#discountId').remove();
        $("#discountForm").trigger("reset");
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'discountId', name: 'discount_code', value: id }).appendTo('#discountForm');
        $('#discountModal').modal('show');
        $('#discountSubmit').hide();
        $('#discountUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/discounts/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#discount_code').val(data.discount_code);
                $('#percent').val(data.percent);
                $('#max_number_buyer').val(data.max_number_buyer);
                $('#min_order_price').val(data.min_order_price);
                $('#max_discount_amount').val(data.max_discount_amount);
                $('#is_one_time_use').val(data.is_one_time_use);
                $('#discount_start').val(data.discount_start);
                $('#discount_end').val(data.discount_end);
                $("#discountForm").append(`<img src="${data.image_path ? data.image_path : '/uploaded_files/default-profile.png'}" width='200px' height='200px' id="discountImage" />`);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update discount
    $("#discountUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#discountId').val();
        var table = $('#discountTable').DataTable();
        var data = $('#discountForm')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/discounts/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#discountModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Delete discount
    $('#discountTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#discountTable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        bootbox.confirm({
            message: "Do you want to delete this discount?",
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
                        url: `/api/discounts/${id}`,
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
                            bootbox.alert('Error deleting discount.');
                        }
                    });
                }
            }
        });
    });

    $('#discountImportForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            url: 'api/discount/import', // API endpoint
            type: 'POST',
            data: formData,
            contentType: false, // Important
            processData: false, // Important
            success: function(response) {
                // Handle success response
                alert(response.message); // Or use a more sophisticated notification system

                var table = $('#discountTable').DataTable();
                table.ajax.reload();
            },
            error: function(xhr) {
                // Handle error response
                var errorMsg = 'An error occurred: ' + xhr.responseJSON.message;
                alert(errorMsg);
            }
        });
    });
});


