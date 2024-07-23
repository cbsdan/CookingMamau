$(document).ready(function () {
    function convertObjectToArray(obj) {
        return Object.keys(obj).map(key => obj[key]);
    }

    // Initialize DataTable for upcoming schedules
    let upcomingTable = $('#upcomingScheduleTable').DataTable({
        ajax: {
            url: "/api/available_schedules",
            dataSrc: function (json) {
                console.log("Raw upcomingSchedules response:", json.upcomingSchedules);
                return convertObjectToArray(json.upcomingSchedules);
            }
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Available Schedule',
                className: 'btn btn-primary add-sched-btn',
                action: function (e, dt, node, config) {
                    $("#scheduleForm").trigger("reset");
                    $('#scheduleModal').modal('show');
                    $('#scheduleUpdate').hide();
                    $('#scheduleSubmit').show();
                }
            }
        ],
        columns: [
            { data: 'id' },
            { data: 'schedule' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class='editBtn' data-id=${data.id}><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></button>
                        <button class='deleteBtn' data-id=${data.id}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></button>
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

    let pastTable = $('#pastScheduleTable').DataTable({
        ajax: {
            url: "/api/available_schedules",
            dataSrc: "pastSchedules"
        },
        columns: [
            { data: 'id' },
            { data: 'schedule' }
        ],
        order: [[0, "desc"]]
    });


    // Submit new schedule
    $("#scheduleSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#scheduleForm')[0];
        let formData = new FormData(data);
        $.ajax({
            type: "POST",
            url: "/api/available_schedules",
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
                    text: "Added schedule successfully.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });

                $('#schedule').val("")

                $("#scheduleModal").modal("hide");
                var $availableScheduleTable = $('#upcomingScheduleTable').DataTable();
                $availableScheduleTable.ajax.reload();

            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Failed to add schedule.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
})
    // Edit upcoming schedule
    $('#upcomingScheduleBody').on('click', 'button.editBtn', function (e) {
        e.preventDefault();
        $("#scheduleForm").trigger("reset");
        var id = $(this).data('id');
        $('#scheduleId').remove();
        $('<input>').attr({ type: 'hidden', id: 'scheduleId', name: 'id', value: id }).appendTo('#scheduleForm .form-group');
        $('#scheduleModal').modal('show');
        $('#scheduleSubmit').hide();
        $('#scheduleUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/available_schedules/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('#scheduleDate').val(data.schedule);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update ingredient
    $("#scheduleUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#scheduleId').val();
        var table = $('#upcomingScheduleTable').DataTable();
        var data = $('#scheduleForm')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/available_schedules/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#scheduleModal').modal("hide");
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "Updated schedule successfully.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });

                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Failed to update schedule.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Delete Upcoming Schedule
    $('#upcomingScheduleBody').on('click', 'button.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#upcomingScheduleTable').DataTable();
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
                        url: `/api/available_schedules/${id}`,
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
                                text: "Deleted Schedule successfully.",
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });
                        },
                        error: function (error) {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: "Failed to delete schedule.",
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });                        }
                    });
                }
            }
        });
    });
