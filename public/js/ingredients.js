$(document).ready(function () {
    var table = $('#ingredientTable').DataTable({
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
                    updateFeedbackClasses();
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

    // Add custom validation method for unit field
    $.validator.addMethod("validUnit", function(value, element) {
        return this.optional(element) || /^(g|kg|l)$/i.test(value);
    }, "Please enter a valid unit (g, kg, l).");

    // Validate the form
    $('#ingredientForm').validate({
        rules: {
            name: {
                required: true
            },
            unit: {
                required: true,
                validUnit: true
            },
            image: {
                validImage: true
            }
        },
        messages: {
            name: "Please enter the name",
            unit: "Please enter a valid unit (g, kg, l, etc...)",
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
        submitHandler: function(form) {
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                url: "/api/ingredients", // Adjust URL as needed
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $("#ingredientModal").modal("hide");
                    table.ajax.reload(); // Reload the DataTable
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });

    // Trigger form submission
    $("#ingredientSubmit").on('click', function(e) {
        e.preventDefault();
        $('#ingredientForm').submit();
    });

    // Edit ingredient
    $('#ingredientTable tbody').on('click', 'a.editBtn', function (e) {
        updateFeedbackClasses();
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
                table.ajax.reload(); // Reload the DataTable
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Delete ingredient
    $('#ingredientTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
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

    // Edit discount
    function updateFeedbackClasses() {
        $('.invalid-feedback').each(function() {
            $(this).remove();
        });
        $('.is-invalid').removeClass('is-invalid');

    }
});
