// Initialize DataTable
var table = $('#discountTable').DataTable({
    ajax: {
        url: "/api/discounts",
        dataSrc: ""
    },
    dom: 'lBfrtip',
    buttons: [
        'pdf',
        'excel',
        {
            text: 'Add Discount',
            className: 'btn btn-primary buttons-add',
            action: function (e, dt, node, config) {
                $("#discountForm").trigger("reset");
                $('#discountModal').modal('show');
                $('#discountUpdate').hide();
                $('#discountSubmit').show();
                $('#discountImage').remove();
                updateFeedbackClasses();
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
                    <a href='#' class='editBtn' data-id=${data.discount_code}><i class='fas fa-edit' style='font-size:24px'></i></a>
                    <a href='#' class='deleteBtn' data-id=${data.discount_code}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>
                `;
            }
        }
    ],
    "order": [[0, "desc"]],
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

// Add custom validation method for image files
$.validator.addMethod("validImage", function(value, element) {
    var files = element.files;
    if (files.length === 0) {
        return true; // Allow empty image field
    }
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if (!file.type.match('image.*')) {
            return false;
        }
    }
    return true;
}, "Please upload a valid image.");

// Add custom validation method for date comparison
$.validator.addMethod("endDateAfterStartDate", function(value, element) {
    var startDate = $('#discount_start').val();
    var endDate = $('#discount_end').val();
    if (!startDate || !endDate) {
        return true; // Skip this validation if either date is not provided
    }
    return new Date(endDate) >= new Date(startDate);
}, "End date must be after the start date");

$(document).ready(function (){
    // Validate the form
    $('#discountForm').validate({
        rules: {
            discount_code: {
                required: true
            },
            percent: {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            max_number_buyer: {
                number: true,
                min: 0
            },
            min_order_price: {
                number: true,
                min: 0
            },
            max_discount_amount: {
                number: true,
                min: 0
            },
            discount_start: {
                required: true,
                date: true
            },
            discount_end: {
                required: true,
                date: true,
                endDateAfterStartDate: true // Apply the custom date comparison rule
            },
            image: {
                validImage: true
            }
        },
        messages: {
            discount_code: "Please enter a discount code",
            percent: {
                required: "Please enter the discount percent",
                number: "Please enter a valid number",
                min: "Percent must be at least 0",
                max: "Percent cannot exceed 100"
            },
            max_number_buyer: {
                number: "Please enter a valid number",
                min: "Number of buyers cannot be negative"
            },
            min_order_price: {
                number: "Please enter a valid number",
                min: "Order price cannot be negative"
            },
            max_discount_amount: {
                number: "Please enter a valid number",
                min: "Discount amount cannot be negative"
            },
            discount_start: {
                required: "Please enter the start date",
                date: "Please enter a valid date"
            },
            discount_end: {
                required: "Please enter the end date",
                date: "Please enter a valid date",
                endDateAfterStartDate: "End date must be after the start date" // Custom error message
            },
            image: "Please upload a valid image"
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function() {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        timer: 2000,
                        text: "Discount added successfully",
                        confirmButtonText: 'OK'
                    });
                    $("#discountModal").modal("hide");
                    var $discountTable = $('#discountTable').DataTable();
                    $discountTable.ajax.reload();
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        timer: 2000,
                        text: "Discount failed to add",
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

    });
})


// Edit discount
function updateFeedbackClasses() {
    $('.invalid-feedback').each(function() {
        $(this).remove();
    });
    $('.is-invalid').removeClass('is-invalid');

}

$('#discountTable tbody').on('click', 'a.editBtn', function (e) {
    e.preventDefault();
    updateFeedbackClasses();
    $('#discountImage').remove();
    $('#discountId').remove();
    $("#discountForm").trigger("reset");
    var id = $(this).data('id');
    $('<input>').attr({ type: 'hidden', id: 'discountId', name: 'discount_id', value: id }).appendTo('#discountForm');
    $('#discountModal').modal('show');
    $('#discountSubmit').hide();
    $('#discountUpdate').show();
    $('.invalid-feedback')
    $.ajax({
        type: "GET",
        url: `/api/discounts/${id}`,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "json",
        success: function (data) {
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
    var form = $('#discountForm')[0];
    var formData = new FormData(form);
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
            $('#discountModal').modal("hide");

            Swal.fire({
                icon: 'success',
                title: 'Success',
                timer: 2000,
                text: "Discount updated successfully",
                confirmButtonText: 'OK'
            });

            // Add a slight delay before reloading the table
            setTimeout(function () {
                table.ajax.reload();
            }, 500);
        },
        error: function (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                timer: 2000,
                text: "Discount failed to update",
                confirmButtonText: 'OK'
            });
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            timer: 2000,
                            text: "Discount deleted successfully",
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function (error) {
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            timer: 2000,
                            text: "Discount failed to delete",
                            confirmButtonText: 'OK'
                        });
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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "Excel import successfully.",
                timer: 2000,
                confirmButtonText: 'OK'
            });

            var table = $('#discountTable').DataTable();
            table.ajax.reload();
        },
        error: function(xhr) {
            // Handle error response
            var errorMsg = 'An error occurred: ' + xhr.responseJSON.message;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Excel failed to import.",
                timer: 2000,
                confirmButtonText: 'OK'
            });
        }
    });
});


