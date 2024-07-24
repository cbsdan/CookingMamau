@extends('layouts.app')

@section('content')
    <h1>Checkout page</h1>
    <hr>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class='mb-0'>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @php
        $total = 0;
        $grandTotal = 0;
        $shippingCost = 50;
        $discountPercent = 0;
        $off = 0;
        $amount = 0;
    @endphp

    <form id="checkoutForm" action="" method="POST" class='row'>
        @csrf
        <div class='d-flex flex-row gap-5 flex-1 col-7'>
            <div class='d-flex flex-column gap-2 flex-1'>
                <h3>Order Form</h3>
                <input type="hidden" name="id_buyer" value="{{auth()->user()->buyer->id}}">
                <input type="hidden" id="id_user_input" name="id_user" value="{{auth()->user()->id}}">
                <div class="form-group">
                    <label class='fw-semibold' for="buyerName">Buyer Name</label>
                    <input type="text" class="form-control" id="buyerName" name="buyer_name" value="">
                </div>
                <div class="form-group">
                    <label class='fw-semibold' for="emailAddress">Email Address</label>
                    <input type="email" class="form-control" id="emailAddress" name="email_address" value="">
                </div>
                <div class="form-group">
                    <label class='fw-semibold' for="deliveryAddress">Delivery Address</label>
                    <input type="text" class="form-control" id="deliveryAddress" name="delivery_address" value="">
                </div>
                <div class="form-group">
                    <label class='fw-semibold' for="buyer_note">Note (Optional)</label>
                    <textarea class="form-control" id="buyer_note" name="buyer_note" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class='fw-semibold' for="deliveryDateTime">Delivery Date and Time</label>
                    <select class="form-control" id="deliveryDateTime" name="id_schedule">
                        <option value="">Select date schedule</option>
                    </select>
                </div>
                <div class="form-group gap-2">
                    <label class='fw-semibold' for="discountCode">Discount Code (Optional)</label>
                    <div class='d-flex flex-row align-items-center gap-2'>
                        <input type="text" class="form-control" id="discountCode">
                        <button type="button" class="btn btn-primary" id="checkDiscountBtn">Apply</button>
                    </div>
                    <div id="discountDetails" class='fst-italic'></div>
                </div>
                <div class="form-group d-flex flex-column gap-2 mt-2">
                    <label class='fw-semibold' for="paymentMethod">Payment Method</label>
                    <select class="form-control" id="paymentMethod" name="mode">
                        <option value="GCash">GCash</option>
                        <option value="COD">Cash on Delivery (COD)</option>
                    </select>
                </div>
                <div class="form-group d-flex flex-column gap-2 mt-2">
                    <label class='fw-semibold' for="amount">Payment Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value='' min='' readonly>
                    <span class='fst-italic'>Please Pay amount of <strong id='paymentAmount'></strong></span>
                </div>
            </div>
        </div>

        <div class='d-flex flex-column gap-2 flex-1 col-5'>
            <div class='d-flex flex-column gap-2'>
                <div id="checkoutItems" class='d-flex flex-column gap-1'>
                    <h3 class='text-light p-2'>Ordered Baked Goods</h3>
                </div>
                <h6 class='d-flex justify-content-between mb-0'>
                    <span>Total:</span>
                    <span id="grandTotal">P0.00</span>
                </h6>
                <h6 class='d-flex justify-content-between mb-0'>
                    <span>Shipping Cost:</span>
                    <span id='shippingCost'>P{{$shippingCost}}</span>
                </h6>
                <h6 class='d-flex justify-content-between mb-0'>
                    <span>Discount:</span>
                    <span id='discountOff'>-P0.00</span>
                </h6>
                <h4 class='bg-warning text-light p-2 d-flex justify-content-between mb-0'>
                    <span>Grand Total:</span>
                    <span id='totalAmount'>P0.00</span>
                </h4>
            </div>
            <button id="submitOrder" type="submit" class="btn btn-primary ml-auto mt-4 w-50">Confirm Order</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#open-btn").hide();
            const userId = {{ auth()->user()->id }};
            // Fetch checkout data
            $.ajax({
                type: 'GET',
                url: "api/checkout",
                data: { id_user: userId },
                success: function(response) {
                    console.log(response);
                    const cartItems = response.cartItems;
                    const user = response.user;
                    const availableSchedules = response.availableSchedules;

                    $('#buyerName').val(user.buyer.fname + ' ' + user.buyer.lname);
                    $('#emailAddress').val(user.email);
                    $('#deliveryAddress').val(user.buyer.address + ' ' + user.buyer.barangay + ' ' + user.buyer.city + '. Near ' + user.buyer.landmark);

                    let grandTotal = 0;

                    cartItems.forEach(item => {
                        console.log(item);
                        const total = item.baked_good.price * item.qty;
                        grandTotal += total;

                        // Check for the image with is_thumbnail set to true
                        const thumbnailImage = item.baked_good.images.find(image => image.is_thumbnail);
                        const firstImage = item.baked_good.images[0];

                        // Determine the image path
                        const imagePath = thumbnailImage ? thumbnailImage.image_path : (firstImage ? firstImage.image_path : 'uploaded_files/default-profile.png');

                        $('#checkoutItems').append(`
                            <div class='d-flex flex-row justify-content-between gap-2 align-items-center border-1 c-white p-2'>
                                <img class=" flex-1" src="${imagePath}" alt="${item.baked_good.name}" style="width: 50px !important; height: auto;">
                                <p class='m-0 flex-1'>${item.baked_good.name}</p>
                                <div class=" flex-1">
                                    <p class='m-0'>P${item.baked_good.price}</p>
                                    <p class='m-0'>x${item.qty}</p>
                                </div>
                                <p class='m-0 flex-1'>Total Price: P${total}</p>
                                <input type="hidden" name="bakedGoods[]" value="${item.baked_good.id}">
                                <input type="hidden" name="bakedGoodPrices[]" value="${item.baked_good.price}">
                                <input type="number" style="display: none;" name="bakedGoodQtys[]" value="${item.qty}">
                            </div>
                        `);
                    });

                    $('#grandTotal').text('P' + grandTotal.toFixed(2));

                    const amount = grandTotal + {{$shippingCost}};
                    $('#totalAmount').text('P' + amount.toFixed(2));
                    $('#paymentAmount').text('P' + amount.toFixed(2));
                    $('#amount').val(amount.toFixed(2));

                    availableSchedules.forEach(schedule => {
                        $('#deliveryDateTime').append(`
                            <option value="${schedule.id}">${new Date(schedule.schedule).toLocaleString()}</option>
                        `);
                    });
                },
                error: function() {
                    console.log('Error fetching checkout data.');
                }
            });

            $('#checkDiscountBtn').click(function() {
                var discountCode = $('#discountCode').val();
                $.ajax({
                    type: 'GET',
                    url: "api/check-discount",
                    data: {
                        discountCode: discountCode
                    },
                    success: function(response) {
                        if (response) {
                            var discountPercent = response.percent;
                            var minOrderPrice = parseFloat(response.min_order_price);
                            var maxNumberBuyer = parseInt(response.max_number_buyer);

                            var grandTotal = parseFloat($('#grandTotal').text().replace('P', ''));
                            var isValidDiscount = (grandTotal >= minOrderPrice) && (maxNumberBuyer > 0);

                            if (isValidDiscount) {
                                var off = (discountPercent / 100) * grandTotal;
                                var amount = grandTotal - off + {{$shippingCost}};

                                $('#discountDetails').html('<p>Discount: -P' + off.toFixed(2) + ' ('+discountPercent +'%OFF)'+'</p>');
                                $('#discountOff').html('-P' + off.toFixed(2) + ' (' + discountPercent + '% OFF)');
                                $('#totalAmount').text('P' + amount.toFixed(2));
                                $('#paymentAmount').text('P' + amount.toFixed(2));
                                $('#amount').val(amount.toFixed(2));
                                $('#discountCode').attr('name', 'discount_code');
                            } else {
                                var amount = grandTotal + {{$shippingCost}};
                                $('#discountDetails').html('<p>Invalid</p>');
                                $('#discountCode').attr('name', '');
                                $('#discountOff').html('-P0');
                                $('#totalAmount').text('P' + amount.toFixed(2));
                                $('#paymentAmount').text('P' + amount.toFixed(2));
                                $('#amount').val(amount.toFixed(2));
                            }
                        } else {
                            var amount = grandTotal + {{$shippingCost}};
                            $('#discountDetails').html('<p>Invalid</p>');
                            $('#discountCode').attr('name', '');
                            $('#discountOff').html('-P0');
                            $('#totalAmount').text('P' + amount.toFixed(2));
                            $('#paymentAmount').text('P' + amount.toFixed(2));
                            $('#amount').val(amount.toFixed(2));
                        }
                    },
                    error: function() {
                        console.log('Error occurred while checking discount code.');
                    }
                });
            });

            $('#checkoutForm').validate({
                rules: {
                    buyer_name: {
                        required: true,
                        minlength: 3
                    },
                    email_address: {
                        required: true,
                        email: true
                    },
                    delivery_address: {
                        required: true,
                        minlength: 5
                    },
                    id_schedule: {
                        required: true
                    },
                    mode: {
                        required: true
                    }
                },
                messages: {
                    buyer_name: {
                        required: "Please enter your name",
                        minlength: "Your name must be at least 3 characters long"
                    },
                    email_address: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    delivery_address: {
                        required: "Please enter your delivery address",
                        minlength: "Your delivery address must be at least 5 characters long"
                    },
                    id_schedule: {
                        required: "Please select a delivery date and time"
                    },
                    mode: {
                        required: "Please select a payment method"
                    }
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
                    let bakedGoodQtys = $("input[name='bakedGoodQtys[]']").map(function() {
                        return parseInt($(this).val(), 10);
                    }).get();

                    let formData = new FormData(form);

                    // Append bakedGoodQtys to FormData
                    bakedGoodQtys.forEach((qty, index) => {
                        formData.append(`bakedGoodQtys[${index}]`, qty);
                    });

                    // Perform the AJAX request on form submission
                    $.ajax({
                        type: 'POST',
                        url: '/api/order',
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        dataType: "json",
                        success: function(response) {
                            console.log(response);

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: "Order placed successfully!",
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect only after the user clicks "OK"
                                    window.location.href = '{{route('my-orders')}}';
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: "Failed to placed an Order!",
                                timer: 3000,
                                confirmButtonText: 'OK'
                            });
                            console.log(response.responseJSON.errors);
                        }
                    });
                }
            });




        });
    </script>
@endsection
