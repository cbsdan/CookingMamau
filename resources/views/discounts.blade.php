@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    <style>
        .copyDiscountCode {
            cursor: pointer;
            /* Changes the cursor to a hand when hovering */
        }
        .discount-code {
            color: rgb(34, 34, 34);
            background: rgb(255, 243, 154);
            font-weight: 600;
            padding: 5px;
        }
        .view-discount {
            position: relative;
        }
        .usage-label {
            position: absolute;
            top: 0;
            right: 0;
            padding: 5px;
            color: rgb(55, 54, 54);
            border-bottom-left-radius: 5px;
            border-top-right-radius: 5px;
            font-weight: 600;
        }
    </style>
    <h1>Discounts</h1>
    <hr>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Product Cards -->
        <div class="row g-3" id="discounts-container">
            <!-- Discounts will be appended here by AJAX -->
        </div>

        <!-- Loader -->
        <div id="loader" style="display:none; text-align: center; width: 100%;">
            Loading...
        </div>
        <div id="end" style="display:none; text-align: center; width: 100%;">
            You have reached the end...
        </div>
    </div>

    <!-- Modal for baked good -->
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Discount Details</h5>
                </div>
                <div class="modal-body">
                    <!-- Discount details will be loaded here -->
                    <div id="discountDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Function to fetch discounts
            function fetchDiscounts() {
                $.ajax({
                    url: 'api/discounts', // Ensure this route returns the discounts in JSON
                    type: 'GET',
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data) {
                        $('#loader').hide();
                        $('#discounts-container').empty(); // Clear previous discounts

                        data.forEach(function(discount) {
                            var discountCard = `
                                <div class="col-md-4 product-card">
                                    <div class="card mb-4 view-discount" data-bs-toggle="modal" data-bs-target="#discountModal" data-discount='${JSON.stringify(discount)}'>
                                        <img src="${discount.image_path || 'uploaded_files/default-profile.png'}" class="card-img-top" alt="Discount Image" style="object-fit: cover; width: 100%; min-height: 300px; max-height: 300px">
                                        <div class="card-body" style="border-top: 1px solid grey;">
                                            <h5 class='display-flex align-items-center justify-content-between row'>
                                                <span class="col-9"><strong>Code: </strong> <span class="discount-code">${discount.discount_code}</span></span>
                                                <span class="col-3 copyDiscountCode text-end">
                                                    <img class="ml-auto" src="uploaded_files/copy-regular.svg" width=20px height=20px>
                                                </span>
                                            </h5>
                                            <p class="card-text">From <strong>${discount.discount_start}</strong> to <strong>${discount.discount_end}</strong> only!</p>
                                            <h5 class="card-text mt-2"><strong>${discount.percent}</strong>% off</h5>
                                            <p class="card-text usage-label bg-warning">${discount.is_one_time_use ? 'One time use only' : 'Multiple uses'}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#discounts-container').append(discountCard);
                        });

                        // Attach click event to cards for opening modal
                        $('#discounts-container').on('click', '.view-discount', function() {
                            var discount = $(this).data('discount');
                            console.log(discount);
                            console.log('clicked');
                            $('#discountDetails').html(`
                                <img src="${discount.image_path || 'uploaded_files/default-profile.png'}" class="img-fluid" alt="Discount Image" style="object-fit: cover; width: 100%; min-height: 250px; max-height: 250px">
                                <h5 class='mt-3 display-flex align-items-center justify-content-between row'>
                                    <span class="col-9"><strong>Discount Code: </strong> <span class="discount-code">${discount.discount_code}</span></span>
                                    <span class="col-3 copyDiscountCode text-end">
                                        <img class="ml-auto" src="uploaded_files/copy-regular.svg" width=20px height=20px>
                                    </span>
                                </h5>
                                <hr>
                                <h5 class='text-center'><strong>Discount Information</strong></h5>
                                <p><strong>Percent:</strong> ${discount.percent}%</p>
                                <p><strong>Max Number of Buyers:</strong> ${discount.max_number_buyer || 'N/A'}</p>
                                <p><strong>Min Order Price:</strong> ${discount.min_order_price || 'N/A'}</p>
                                <p><strong>One Time Use:</strong> ${discount.is_one_time_use ? 'Yes' : 'No'}</p>
                                <p><strong>Discount Start:</strong> ${discount.discount_start}</p>
                                <p><strong>Discount End:</strong> ${discount.discount_end}</p>
                                <p><strong>Max Discount Amount:</strong> ${discount.max_discount_amount || 'N/A'}</p>
                            `);
                        });

                    },
                    error: function(error) {
                        $('#loader').hide();
                        console.error('Error fetching discounts:', error);
                    }
                })
            }
            // Attach click event to copyDiscountCode
            $('#discountDetails').on('click', '.copyDiscountCode', function(e) {
                e.stopPropagation();
                e.preventDefault();
                var discountCode = $(this).siblings('span').find('.discount-code')
                    .text().trim();
                navigator.clipboard.writeText(discountCode).then(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Discount code copied to clipboard.',
                        confirmButtonText: 'OK'
                    });
                }, function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to copy discount code.',
                        confirmButtonText: 'OK'
                    });
                });
            });

            // Fetch discounts on page load
            fetchDiscounts();
        });
    </script>
@endsection
