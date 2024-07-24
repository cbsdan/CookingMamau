@extends('layouts.app')

@section('content')
    <style>
        .orders-container .header {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
            color: white;
            background-color: rgb(179, 54, 179);
            font-size: 16px;
            font-weight: 600
        }
        .filter-btn {
            padding: 10px;
            text-align: center;
            width: 100%;
            cursor: pointer;
            position: relative;
        }
        .active-filter {
            border-bottom: 2px solid lightgoldenrodyellow;
        }
        .dot-label {
            position: absolute;
            top: -7px;
            right: -7px;
            left: unset !important;
            width: 25px;
            height: 25px;
        }

        .body-orders {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .order {
            margin-top: 5px;
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 255, 201, 0.4);
        }
        .baked-good-info-cont {
            display: flex;
            border-bottom: 1px solid rgba(105, 105, 105, 0.5);

        }
        .prod-image-cont, .order-img {
            width: 35%;
        }
        .order-img {
            width: 200px;
            height: 200px;
        }
        .prod-details {
            width: 65%;
            display: flex;
            flex-direction: column;
            justify-content: space-between
        }
        .order-status {
            padding: 5px;
            border-bottom: 1px solid rgba(105, 105, 105, 0.5);

        }
        .order-qty {
            margin-top: auto;
        }
        .order-qty, .order-price {
            margin-left: auto;
            text-align: end
        }
        .item-no-total-cont, .order-date-view-cont  {
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(105, 105, 105, 0.5);

        }

    </style>
    <h1>My Orders</h1>
    <hr>
    <div class="orders-container">
        <div class="header">
            <div class="filter-btn active-filter" data-status="all">All <span class="dot-label" id="count-all"></span></div>
            <div class="filter-btn" data-status="pending">Pending <span class="dot-label" id="count-pending"></span></div>
            <div class="filter-btn" data-status="preparing">Preparing <span class="dot-label" id="count-preparing"></span></div>
            <div class="filter-btn" data-status="out_for_delivery">Out For Delivery <span class="dot-label" id="count-out_for_delivery"></span></div>
            <div class="filter-btn" data-status="delivered">Delivered <span class="dot-label" id="count-delivered"></span></div>
            <div class="filter-btn" data-status="canceled">Canceled <span class="dot-label" id="count-canceled"></span></div>
        </div>
        <div class="body-orders">
            <!-- Orders will be rendered here -->
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                </div>
                <div class="modal-body">
                    <!-- Order details will be dynamically inserted here by viewOrderDetails function -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active-filter');

                $(this).addClass('active-filter');
            });

            var buyerId = {{ auth()->user()->buyer->id }};
            var statusCounts = {
                all: 0,
                pending: 0,
                preparing: 0,
                out_for_delivery: 0,
                delivered: 0,
                canceled: 0
            };

            function fetchOrders(status = '') {
                let url = `/api/order/${buyerId}/user`;
                if (status && status !== 'all') {
                    url += `?order_status=${status}`;
                }
                console.log(url);
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        renderOrders(data);
                    },
                    error: function(error) {
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: "Orders failed to fetch.",
                            timer: 2000,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            function renderOrders(orders) {
                $(".body-orders").empty(); // Clear previous orders
                if (Array.isArray(orders) && orders.length > 0) {
                    orders.forEach(order => {
                        console.log(order);
                        let $order = $('<div class="order"></div>'); // Create a new order element

                        //Calculation
                        let total = parseFloat(order.ordered_goods.reduce((sum, item) => sum + (item.pivot.qty * parseFloat(item.pivot.price_per_good)), 0)).toFixed(2);
                        let shippingCost = parseFloat(order.shipping_cost).toFixed(2);
                        let discountAmount = order.discount_code
                            ? parseFloat((order.discount.percent / 100) * parseFloat(total)).toFixed(2)
                            : 0;
                        let grandTotal = parseFloat(total) + parseFloat(shippingCost) - parseFloat(discountAmount);

                        //Images
                        let thumbnail = 'uploaded_files/default-product.png';
                        let firstBakedGood = order.ordered_goods[0];
                        if (firstBakedGood && firstBakedGood.images && firstBakedGood.images.length > 0) {
                            let thumbnailImage = firstBakedGood.images.find(image => image.is_thumbnail);
                            thumbnail = thumbnailImage ? `/uploaded_files/${thumbnailImage.file_name}` : `/uploaded_files/${firstBakedGood.images[0].file_name}`;
                        }

                        $order.html(`
                            <div class='baked-good-info-cont'>
                                <div class='prod-image-cont'>
                                    <img src="${thumbnail}" class="order-img" width="200px" height="200px" style="object-fit: cover">
                                </div>
                                <div class='prod-details'>
                                    <h3 class="baked-good-name mt-2">${firstBakedGood ? firstBakedGood.name : ''}</h3>
                                    <p class="baked-good-description"><strong>Description: </strong><br>${firstBakedGood ? firstBakedGood.description : ''}</p>
                                    <div class='mt-auto'>
                                        <h5 class="order-qty">${firstBakedGood ? `x${firstBakedGood.pivot.qty}` : ''}</h5>
                                        <h5 class="order-price">${firstBakedGood ? `Price: ₱${firstBakedGood.price}` : ''}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class='item-no-total-cont'>
                                <h5 class="order-count mb-0">${order.ordered_goods.length} Item/s</h5>
                                <h5 class="order-grand-total mb-0">Grand Total: ₱${grandTotal.toFixed(2)}</h5>
                            </div>
                            <div class='status-cont'>
                                <h5 class="order-status">Status: ${order.order_status}</h5>
                            </div>
                            <div class='order-date-view-cont'>
                                <h5 class="order-date">Order At: ${new Date(order.created_at).toLocaleString()}</h5>
                                <button class="view-details-btn btn btn-primary">View Details</button>
                            </div>
                        `);

                        $order.find('.view-details-btn').on('click', function() {
                            viewOrderDetails(order);
                        });

                        $(".body-orders").append($order); // Append the new order element
                    });
                } else {
                    $(".body-orders").append(`<h3 class='text-center w-100 mt-5'>No Orders Yet</h3>`); // Append the new order element

                }
            }

            function viewOrderDetails(order) {
                console.log('View Order Details');
                console.log(order);
                // Prepare discount details
                let discountDetails = order.discount ? `
                    <hr>
                    <p class='mt-1'><strong>Discount Code:</strong> ${order.discount.discount_code}</p>
                    <p class='mt-1'><strong>Discount Percent:</strong> ${order.discount.percent || 'N/A'}%</p>
                ` : `<p class='mt-1'><strong>Discount Code:</strong> None</p>`;

                // Prepare ordered goods table
                let orderedGoodsHtml = order.ordered_goods.map(item => `
                    <tr>
                        <td>${item.pivot.id_baked_goods}</td>
                        <td>${item.name}</td>
                        <td>${item.pivot.qty}</td>
                        <td>₱${parseFloat(item.pivot.price_per_good).toFixed(2)}</td>
                        <td>₱${(item.pivot.qty * parseFloat(item.pivot.price_per_good)).toFixed(2)}</td>
                    </tr>
                `).join('');

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

                // Prepare payments details
                let paymentsHtml = order.payments.map(payment => `
                    <p class="d-flex align-items-center justify-content-between "><strong>Payment Method:</strong> <span>${payment.mode}</span></p>
                    <p class="d-flex align-items-center justify-content-between "><strong>Payment Amount:</strong> <span>₱${payment.amount}</span></p>
                `).join('');

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
                            <p class='mt-1'>
                                <strong>
                                    ${['Delivered', 'Canceled'].includes(order.order_status)
                                        ? order.order_status + ' At: '
                                        : 'Last Updated At:'}
                                </strong>
                                ${new Date(order.updated_at).toLocaleString()}
                            </p>
                        </div>
                        <div class='w-100 p-2'>
                            <h5>Ordered Goods</h5>
                            <hr>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                            <p class="d-flex align-items-center justify-content-between "><strong>Shipping Cost:</strong> <span>₱${shippingCost}</span></p>
                            <p class="d-flex align-items-center justify-content-between "><strong>Discount:</strong> <span>- ₱${discountAmount}</span></p>
                            <hr>
                            <p class="d-flex align-items-center justify-content-between "><strong>Grand Total:</strong> <span>₱${grandTotal}</span></p>
                            ${paymentsHtml}
                        </div>
                    </div>
                `;
                $('#orderDetailsModal .modal-body').html(detailsHtml);
                $("#orderDetailsModal").modal('show');
            }

            function updateStatusCounts() {
                $.ajax({
                    type: "GET",
                    url: `/api/order/${buyerId}/status_counts`,
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        statusCounts = data;
                        $("#count-all").text(statusCounts.all);
                        $("#count-pending").text(statusCounts.Pending);
                        $("#count-preparing").text(statusCounts.Preparing);
                        $("#count-out_for_delivery").text(statusCounts.Out_for_Delivery);
                        $("#count-delivered").text(statusCounts.Delivered);
                        $("#count-canceled").text(statusCounts.Canceled);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            fetchOrders();
            updateStatusCounts();

            $('.filter-btn').on('click', function() {
                const status = $(this).data('status');
                fetchOrders(status);
            });
        });
    </script>
@endsection
