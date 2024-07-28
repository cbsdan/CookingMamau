$(document).ready(function() {
    let orderTable = $('#orders-table').DataTable({
        ajax: {
            url: 'api/order',
            type: 'GET',
            dataType: 'json',
            dataSrc: '',
            data: function(d) {
                d.order_status = $('#orderStatusFilter').val(); // Add order_status to the request
            }
        },
        dom: 'lBfrtip',
        buttons: [
            'pdf',
        ],
        columns: [
            { data: 'id' },
            { data: 'buyer_name' },
            { data: 'email_address' },
            { data: 'delivery_address' },
            { data: 'buyer_note',
                render: function(data, type, row) {
                    return data ? data : 'None';
                }
            },
            { data: 'discount_code',
                render: function(data, type, row) {
                    return data ? data : 'None';
                }
            },
            { data: 'schedule',
                render: function(data, type, row) {
                    console.log(data);
                    return data.schedule ? data.schedule : 'Error';
                }
            },
            {
                data: 'order_status',
                render: function(data, type, row) {
                    return `
                        <select class="form-control status-select bg-warning" data-id="${row.id}">
                            <option value="Pending" ${data === 'Pending' ? 'selected' : ''}>Pending</option>
                            <option value="Canceled" ${data === 'Canceled' ? 'selected' : ''}>Canceled</option>
                            <option value="Preparing" ${data === 'Preparing' ? 'selected' : ''}>Preparing</option>
                            <option value="Out for Delivery" ${data === 'Out for Delivery' ? 'selected' : ''}>Out for Delivery</option>
                            <option value="Delivered" ${data === 'Delivered' ? 'selected' : ''}>Delivered</option>
                        </select>
                    `;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<div class='d-flex'>
                                <button class="btn bg-info view-order" data-id="${data.id}"><img src='uploaded_files/eye-solid.svg' alt='view' width=20px height=20px></a>
                                <button class="btn btn-danger delete-order" data-id="${data.id}"><img src='uploaded_files/trash-can-solid.svg' alt='delete' width=20px height=20px></button>
                            </div>`;
                }
            }
        ]
    });

    // View order details
    $('#orders-table').on('click', '.view-order', function(e) {
        let orderId = $(this).data('id');

        $.ajax({
            url: `/api/order/${orderId}`,
            type: 'GET',
            success: function(result) {
                console.log(result);
                let order = result.order;
                // Prepare discount details
                let discountDetails = order.discount ? `
                    <hr>
                    <p class='mt-1'><strong>Discount Code:</strong> ${order.discount.discount_code}</p>
                    <p class='mt-1'><strong>Discount Percent:</strong> ${order.discount.percent || 'N/A'}%</p>
                ` : `<p class='mt-1'><strong>Discount Code:</strong> None</p>`;


                // Initialize variables for product images
                let defaultThumbnail = 'uploaded_files/default-product.png'; // Default image

                // Prepare ordered goods table
                let orderedGoodsHtml = order.ordered_goods.map(item => {
                    let thumbnail = defaultThumbnail; // Reset thumbnail to default for each item
                    let firstBakedGood = item; // Current baked good in the order

                    if (firstBakedGood && firstBakedGood.images && firstBakedGood.images.length > 0) {
                        // Try to find the thumbnail image
                        let thumbnailImage = firstBakedGood.images.find(image => image.is_thumbnail);
                        // Use the thumbnail image if found, otherwise use the first image
                        thumbnail = thumbnailImage ? thumbnailImage.image_path : firstBakedGood.images[0].image_path;
                    }

                    return `
                        <tr>
                            <td>${item.pivot.id_baked_goods}</td>
                            <td><img src='${thumbnail}' alt='img' width='30px' height='30px' style='object-fit: cover'></td>
                            <td>${item.name}</td>
                            <td>${item.pivot.qty}</td>
                            <td>₱${parseFloat(item.pivot.price_per_good).toFixed(2)}</td>
                            <td>₱${(item.pivot.qty * parseFloat(item.pivot.price_per_good)).toFixed(2)}</td>
                        </tr>
                    `;
                }).join('');

                let total = parseFloat(order.ordered_goods.reduce((sum, item) => sum + (item.pivot.qty * parseFloat(item.pivot.price_per_good)), 0)).toFixed(2);
                let shippingCost = parseFloat(order.shipping_cost).toFixed(2);
                let discountAmount = order.discount_code
                    ? parseFloat((order.discount.percent / 100) * parseFloat(total)).toFixed(2)
                    : 0;

                // Convert total, shippingCost, and discountAmount to numbers before arithmetic
                total = parseFloat(total);
                shippingCost = parseFloat(shippingCost);
                discountAmount = parseFloat(discountAmount);

                let grandTotal = (total + shippingCost - discountAmount).toFixed(2);

                // Prepare modal details HTML
                let detailsHtml = `
                    <h4 class='mt-1 p-2'><strong>Order Status:</strong> ${order.order_status}</h4>
                    <div class='order-info-container d-flex align-items-start'>
                        <div class='w-100 p-2'>
                            <h5>Buyer Information</h5>
                            <hr>
                            <p class='mt-1'><strong>Order ID:</strong> ${order.id}</p>
                            <p class='mt-1'><strong>Buyer Name:</strong> ${order.buyer_name}</p>
                            <p class='mt-1'><strong>Email Address:</strong> ${order.email_address}</p>
                            <p class='mt-1'><strong>Delivery Address:</strong> ${order.delivery_address}</p>
                            <p class='mt-1'><strong>Buyer Note:</strong> ${order.buyer_note || 'None'}</p>
                            ${discountDetails}
                            <hr>
                            <p class='mt-1'><strong>Schedule ID:</strong> ${order.id_schedule}</p>
                            <p class='mt-1'><strong>Schedule Date:</strong> ${order.schedule.schedule}</p>
                            <hr>
                            <p class='mt-1'><strong>Ordered At:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                            <p class='mt-1'><strong>Last Updated At:</strong> ${new Date(order.updated_at).toLocaleString()}</p>
                        </div>
                        <div class='w-100 p-2'>
                            <h5>Ordered Goods</h5>
                            <hr>
                            <table class="table table-warning" >
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${orderedGoodsHtml}
                                </tbody>
                            </table>
                            <p class="d-flex align-items-center justify-content-between "><strong>Total:</strong> <span>₱${total}</span></p>
                            <p class="d-flex align-items-center justify-content-between "><strong>Shipping Cost:</strong> <span>₱${shippingCost}</span</p>
                            <p class="d-flex align-items-center justify-content-between "><strong>Discount:</strong> <span>- ₱${discountAmount}</span</p>
                            <hr>
                            <p class="d-flex align-items-center justify-content-between "><strong>Grand Total:</strong> <span>₱${grandTotal}</span</p>
                            <p class="d-flex align-items-center justify-content-between "><strong>Mode of Payment:</strong> <span>${order.payments[0].mode}</span</p>
                            <p class="d-flex align-items-center justify-content-between "><strong>Paid Amount:</strong> <span>₱${order.payments[0].amount}</span</p>
                        </div>
                    </div>
                `;

                $('#order-details').html(detailsHtml);
                $('#orderModal').modal('show');
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Failed to retrieve order details.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Filter orders by status
    $('#orderStatusFilter').on('change', function() {
        orderTable.ajax.reload();
    });

    // Handle status change
    $('#orders-table').on('change', '.status-select', function() {
        let orderId = $(this).data('id');
        let newStatus = $(this).val();

        $.ajax({
            url: `/api/order/${orderId}/status`,
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { order_status: newStatus },
            success: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "Order status updated successfully.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
                orderTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Failed to update order status.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Delete order
    $(document).on('click', '.delete-order', function() {
        var orderId = $(this).data('id');
        bootbox.confirm({
            message: 'Are you sure you want to delete this order?',
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-secondary'
                }
            },
            callback: function (result) {
                if (result) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait a moment.',
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    })

                    $.ajax({
                        url: `api/order/${orderId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: "Order deleted successfully.",
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });
                            $('#orders-table').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: "Failed delete order.",
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            }
        });
    });
});
