$(document).ready(function () {
    // Initialize DataTable
    $('#bakedGoodsTable').DataTable({
        ajax: {
            url: "/api/baked_goods",
            dataSrc: function (json) {
                console.log(json);  // Log all baked good data to the console
                return json;
            }
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add Baked Good',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $('#added-ingredient-container').html("");
                    $("#ingredientsList").html("");
                    $("#ingredientsList").append(`<option value='false'>Select an Ingredient</option>`)

                    $.ajax({
                        type: "GET",
                        url: `/api/ingredients/`,
                        dataType: "json",
                        success: function (ingredients) {
                            const $ingredientsList = $('#ingredientsList');

                            if (ingredients && ingredients.length > 0) {
                                ingredients.forEach(function(ingredient) {
                                    const option = $('<option></option>')
                                        .attr('value', ingredient.id)
                                        .text(ingredient.name);
                                    $ingredientsList.append(option);
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    })

                    $("#bakedGoodForm").trigger("reset");
                    $('#bakedGoodModal').modal('show');
                    $('#bakedGoodUpdate').hide();
                    $('#bakedGoodSubmit').show();
                    $('#bakedGoodImage').remove();

                    //remove the images that was inserted from previous opened baked goods
                    $('.image-container').each(function () {
                        $(this).remove();
                    })
                }
            }
        ],
        columns: [
            { data: 'id' },
            {
                data: null,
                render: function (data, type, row) {
                    let imagePath = '/uploaded_files/default-product.png'; // Default image
                    if (data.images && data.images.length > 0) {
                        let thumbnailImage = data.images.find(image => image.is_thumbnail);
                        imagePath = thumbnailImage ? thumbnailImage.image_path : data.images[0].image_path;
                    }
                    return `<img src="${imagePath}" width="50" height="60">`;
                }
            },
            { data: 'name' },
            { data: 'price' },
            { data: 'weight_gram' },
            {
                data: 'is_available',
                render: function (data, type, row) {
                    return data ? 'Yes' : 'No';
                }
            },
            {
                data: 'updated_at',
                render: function (data, type, row) {
                    return data.split('T')[0]; // This will extract the date part only
                }
            },            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <a href='#' class='editBtn' data-id=${data.id}><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>
                        <a href='#' class='deleteBtn' data-id=${data.id}><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>
                    `;
                }
            },

        ],
        "order": [[0, "desc"]]
    });

    // Submit new baked good
    $("#bakedGoodSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#bakedGoodForm')[0];
        let formData = new FormData(data);
        console.log(data);
        $.ajax({
            type: "POST",
            url: "/api/baked_goods",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);

                $("#bakedGoodModal").modal("hide");
                var $bakedGoodsTable = $('#bakedGoodsTable').DataTable();
                $bakedGoodsTable.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Edit baked good
    $('#bakedGoodsTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $("#ingredientsList").html("");
        $("#ingredientsList").append(`<option value='false'>Select an Ingredient</option>`)

        $('#added-ingredient-container').html("");
        $('#bakedGoodImage').remove();
        $('#bakedGoodId').remove();
        $("#bakedGoodForm").trigger("reset");
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'bakedGoodId', name: 'id', value: id }).appendTo('#bakedGoodForm');
        $('#bakedGoodModal').modal('show');
        $('#bakedGoodSubmit').hide();
        $('#bakedGoodUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/baked_goods/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('.image-container').each(function () {
                    $(this).remove();
                })
                console.log(data);
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#weight_gram').val(data.weight_gram);
                $('#is_available').prop('checked', data.is_available);
                $('#description').val(data.description);
                if (data.images && data.images.length > 0) {
                    data.images.forEach((image, index) => {
                        $("#bakedGoodForm").append(`
                            <div id="imageContainer${index}" class='image-container'>
                                <img src="${image.image_path}" width='200px' height='200px' id="bakedGoodImage${index}" />
                                <button type="button" class="btn btn-danger removeImageBtn" data-id="${image.id}" data-index="${index}">Remove</button>
                                <button type="button" class="btn ${image.is_thumbnail ? 'btn-success' : 'btn-primary'} setImageBtn" data-id="${image.id}" data-index="${index}" ${image.is_thumbnail ? 'disabled' : 'enabled'}>${image.is_thumbnail ? 'Current Thumbnail' : 'Set as Thumbnail'}</button>
                            </div>
                        `);
                    });
                } else {
                    $("#bakedGoodForm").append(`<img src='/uploaded_files/default-product.png' width='200px' height='200px' id="bakedGoodImage" />`);
                }

                if (data.ingredients !== null && data.ingredients.length > 0) {
                    data.ingredients.forEach(function(ingredient){
                        $('#added-ingredient-container').append(
                            `<div class='ingredient-container my-1'><img src='${ingredient.image_path ? ingredient.image_path : '/uploaded_files/default-product.png'}' width=40px height=40px alt="img">
                                <p class='w-100 px-2'>${ingredient.pivot.qty} ${ingredient.unit} ${ingredient.name}</p>
                                <button type="button" class="btn btn-danger deleteIngredient">Delete</button>
                                <input name="ids_ingredient[]" type='hidden' value="${ingredient.id}">
                                <input name="qtys_ingredient[]" type='hidden' value="${ingredient.pivot.qty}">
                            </div>`
                        )
                    })
                }

                $.ajax({
                    type: "GET",
                    url: `/api/ingredients/`,
                    dataType: "json",
                    success: function (ingredients) {
                        const $ingredientsList = $('#ingredientsList');
                        if (ingredients && ingredients.length > 0) {
                            ingredients.forEach(function(ingredient) {
                                // Check if the ingredient is not already in bakedGoodIngredient
                                let isInBakedGood = false;
                                data.ingredients.forEach(function(bakedGoodIngredient) {
                                    if (ingredient.id === bakedGoodIngredient.id) {
                                        isInBakedGood = true;
                                    }
                                });

                                // If ingredient is not in bakedGoodIngredient, add it to $ingredientsList
                                if (!isInBakedGood) {
                                    const option = $('<option></option>')
                                        .attr('value', ingredient.id)
                                        .text(ingredient.name);
                                    $ingredientsList.append(option);
                                }
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                })


            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $(document).on('click', '.removeImageBtn', function () {
        var imageId = $(this).data('id');
        var index = $(this).data('index');
        var imageContainer = $(`#imageContainer${index}`);

        $.ajax({
            type: "DELETE",
            url: `/api/baked_goods/images/${imageId}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                console.log(response);
                imageContainer.remove();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    //Set the thumbnail image of the baked good
    $(document).on('click', '.setImageBtn', function () {
        var imageId = $(this).data('id');
        var index = $(this).data('index');
        var imageContainer = $(`#imageContainer${index}`);

        // First, set all other images' is_thumbnail to false
        $.ajax({
            type: "PUT",
            url: `/api/baked_goods/images/${imageId}/set-thumbnail`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                bootbox.alert({
                    message: "Thumbnail set successfully.",
                    buttons: {
                        ok: {
                            label: 'OK',
                            className: 'btn-primary'
                        }
                    },
                    className: 'bootbox-centered'
                });
                // Reset all other image containers
                $('.image-container').each(function () {
                    var btn = $(this).find('.setImageBtn');
                    if (btn.length > 0) {
                        btn.removeClass('btn-success').addClass('btn-primary').prop('disabled', false).text('Set as Thumbnail');
                    }
                });

                // Update current image container
                imageContainer.find('.setImageBtn').removeClass('btn-primary').addClass('btn-success').prop('disabled', true).text('Current Thumbnail');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update baked good
    $("#bakedGoodUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#bakedGoodId').val();
        var table = $('#bakedGoodsTable').DataTable();
        var data = $('#bakedGoodForm')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/api/baked_goods/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                bootbox.alert({
                    message: "Baked Good updated successfully.",
                    buttons: {
                        ok: {
                            label: 'OK',
                            className: 'btn-primary'
                        }
                    },
                    className: 'bootbox-centered'
                });

                $('#bakedGoodModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Delete baked good
    $('#bakedGoodsTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var table = $('#bakedGoodsTable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        bootbox.confirm({
            message: "Do you want to delete this baked good?",
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
                        url: `/api/baked_goods/${id}`,
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
                            bootbox.alert('Error deleting baked good.');
                        }
                    });
                }
            }
        });
    });

    $('#addNewIngredientBtn').click(function () {
        $('#ingredientModal').modal('show');
    });

    $(document).on('click', '.deleteIngredient', function() {
        // Remove the parent element of the delete button
        console.log('delete ingredien');
        $(this).parent('.ingredient-container').remove();
    });

    $('#editIngredientBtn').click(function () {
        $('#ingredientModal').modal('show');

    });

    $('#ingredientsList').change(function() {
        var selectedValue = $(this).val();
        console.log(selectedValue);
        var $submitButton = $('#addIngredientBtn');
        $.ajax({
            type: "GET",
            url: `/api/ingredients/${selectedValue}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (ingredient) {
                $('#unitIngredient').val(ingredient.unit)
            },
            error: function(error) {
                console.log(error)
            }
        })

        if (selectedValue !== 'false') {
            $submitButton.prop('disabled', false);
        } else {
            $submitButton.prop('disabled', true);
        }
    });

    $('#addIngredientBtn').on('click', function(){
        $('.ingredient_name').val(""); //ingredient name on form
        $('#unit').val(""); //ingredient unit on form

        var selectedValue = $('#ingredientsList').val();
        var qtyIngredient = $('#qtyIngredient').val();

        console.log(qtyIngredient);
        if ($.trim(qtyIngredient) !== '') {
            $.ajax({
                type: "GET",
                url: `/api/ingredients/${selectedValue}`,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function (ingredient) {
                    console.log(ingredient)

                    $('#ingredientsList option[value="' + selectedValue + '"]').remove();
                    $('#ingredientsList').val('false')
                    $('#addIngredientBtn').prop('disabled', true)
                    $('#qtyIngredient').val('');
                    $('#unitIngredient').val('')

                    $('#added-ingredient-container')
                        .append(`<div class='ingredient-container my-1'><img src='${ingredient.image_path ? ingredient.image_path : '/uploaded_files/default-product.png'}' width=40px height=40px alt="img">
                                    <p class='w-100 px-2'>${qtyIngredient} ${ingredient.unit} ${ingredient.name}</p>
                                    <button type="button" class="btn btn-danger deleteIngredient">Delete</button>
                                    <input name="ids_ingredient[]" type='hidden' value="${ingredient.id}">
                                    <input name="qtys_ingredient[]" type='hidden' value="${qtyIngredient}">
                                </div>`)
                },
                error: function(error) {
                    alert(error)
                }
            })

        } else {
            alert('Please input a qty first');
        }
    })

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
});

$('#bakedGoodImportForm').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);

    $.ajax({
        url: 'api/bakedgood/import', // API endpoint
        type: 'POST',
        data: formData,
        contentType: false, // Important
        processData: false, // Important
        success: function(response) {
            // Handle success response
            alert(response.message); // Or use a more sophisticated notification system

            var table = $('#bakedGoodsTable').DataTable();
            table.ajax.reload();
        },
        error: function(xhr) {
            // Handle error response
            var errorMsg = 'An error occurred: ' + xhr.responseJSON.message;
            alert(errorMsg);
        }
    });
});

