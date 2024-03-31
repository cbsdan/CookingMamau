@extends('layouts.app')

@section('content')
    <!-- Display the list of products to be ordered here -->
    <h1>Checkout page</h1>
    <!-- Form for Checkout -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p class='mb-0'>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form id="checkoutForm" action='{{route('user.orders.create')}}' action="POST">
        @csrf
        <h5>Order Form</h5>
        <hr>        
        <input type='hidden' name='order_status' value='Pending'>
        <input type='hidden' name='id_buyer' value='{{Auth::user()->id}}'>
        <div class="form-group">
            <label for="buyerName">Buyer Name</label>
            <input type="text" class="form-control" id="buyerName" name="buyer_name" value='{{$user->buyer->fname . " " . $user->buyer->lname}}'>
        </div>
        <div class="form-group">
            <label for="emailAddress">Email Address</label>
            <input type="email" class="form-control" id="emailAddress" name="email_address" value='{{$user->email}}'>
        </div>
        <!-- Delivery Address -->
        <div class="form-group">
            <label for="deliveryAddress">Delivery Address</label>
            <input type="text" class="form-control" id="deliveryAddress" name="delivery_address" value='{{$user->buyer->address . " " . $user->buyer->barangay . " " . $user->buyer->city}}'>
        </div>
        <!-- Note for Admin -->
        <div class="form-group">
            <label for="buyer_note">Note (Optional)</label>
            <textarea class="form-control" id="buyer_note" name="buyer_note" rows="3"></textarea>
        </div>
        
        <!-- Discount Code -->
        <div class="form-group">
            <label for="discountCode">Discount Code</label>
            <div class='d-flex flex-row align-items-center'>
                <input type="text" class="form-control" id="discountCode" >
                <button type="button" class="btn btn-primary" id="checkDiscountBtn">Apply</button>
            </div>
            <div id="discountDetails"></div> <!-- Display discount details here -->
        </div>
        
        <!-- Delivery Date Time Picker -->
        <div class="form-group">
            <label for="deliveryDateTime">Delivery Date and Time</label>
            <select class="form-control" id="deliveryDateTime" name="id_schedule">
                <option value="">Select date schedule</option>
                @foreach($availableSchedules as $availableSchedule)
                    <option value="{{ $availableSchedule->id }}">{{  date('l, Y-m-d H:i', strtotime($availableSchedule->schedule)) }}</option>
                @endforeach
            </select>
        </div>
        <hr>
        <div id="checkoutItems" class='d-flex flex-column gap-1 mb-3'>
            <h5 class='bg-primary text-light p-2'>Ordered Baked Goods</h5>
            @php $total=0; $grandTotal = 0; $shippingCost = 50; $discountPercent = 0; $off = 0; $amount = 0;@endphp
            @foreach($cartItems as $id => $item)
                <div class='d-flex flex-row justify-content-between gap-2 align-items-center border-1 c-white p-2'>
                    <img src="{{ $item['image_path'] }}" alt="{{ $item['name'] }}" style="width: 100px; height: auto;">
                    <p class='m-0'>{{ $item['name'] }}</p>
                    <div>
                        <p class='m-0'>P{{ $item['price'] }}</p>
                        <p class='m-0'>x{{ $item['quantity'] }}</p>
                    </div>
                    <p class='m-0'>Total Price: P{{ $item['price'] * $item['quantity'] }}</p>
                    <input type="hidden" name="bakedGoods[]" value="{{ $id }}">
                    <input type="hidden" name="bakedGoodPrices[]" value="{{ $item['price'] }}">
                    <input type="hidden" name="bakedGoodQtys[]" value="{{ $item['quantity'] }}">
                    @php
                        $total = $item['price'] * $item['quantity'];
                        $grandTotal += $total;
                    @endphp
                </div>
            @endforeach
            <h5 class=' p-2 d-flex justify-content-between mb-0'>
                <span>Total:</span>
                <span id="grandTotal">P{{$grandTotal}}</span>
            </h5>
            <h5 class='p-2 d-flex justify-content-between mb-0'>
                <span>Shipping Cost: </span>
                <span id='shippingCost'>P{{$shippingCost}}</span>
            </h5>
            <h5 class='p-2 d-flex justify-content-between mb-0'>
                <span>Discount: </span>
                <span id='discountOff'>-P{{$off}}</span>
            </h5>
            <h5 class='bg-danger text-light p-2 d-flex justify-content-between mb-0'>
                <span>Grand Total: </span>
                <span id='totalAmount'>P{{$amount = $grandTotal - $off + $shippingCost}}</span>
            </h5>
        </div>
        
        <!-- Payment Method -->
        <div class="form-group">
            <label for="paymentMethod">Payment Method</label>
            <select class="form-control" id="paymentMethod" name="mode">
                <option value="GCash">GCash</option>
                <option value="COD">Cash on Delivery (COD)</option>
            </select>
        </div>  
        <div class="form-group">
            <label for="amount">Payment Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" min={{$amount}}>
            <span>Please Pay amount of <strong id='paymentAmount'>{{$amount}}</strong></span>
        </div>
        <!-- Submit Button -->
        <button type="button" class="btn btn-primary" id="placeOrderBtn" data-bs-toggle="modal" data-bs-target="#confirmationModal">Place Order</button>
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
           <div class="modal-dialog">
             <div class="modal-content">
               <div class="modal-header">
                 <h5 class="modal-title" id="confirmationModalLabel">Confirm Order</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                 Are you sure you want to place the order?
               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                 <button type="submit" class="btn btn-primary">Confirm</button>
               </div>
             </div>
           </div>
         </div>
    </form>
     <!-- Modal -->
    <script>
        // Add this script to your view or a separate JavaScript file
        $(document).ready(function() {
            $('#checkDiscountBtn').click(function() {
                var discountCode = $('#discountCode').val();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('check.discount') }}",
                    data: {
                        discountCode: discountCode
                    },
                    success: function(response) {
                        console.log('Received data:', response);

                        if (response) {
                            // Extract discount details from the response
                            var discountPercent = response.percent;
                            var minOrderPrice = parseFloat(response.min_order_price);
                            var maxNumberBuyer = parseInt(response.max_number_buyer);
                            // var isOneTimeUse = response.is_one_time_use;

                            // Check if the conditions for applying the discount are met
                            var isValidDiscount = ({{$grandTotal}} >= minOrderPrice) && (maxNumberBuyer > 0 );
                            //|| isOneTimeUse == 0

                            if (isValidDiscount) {
                                // Calculate discount
                                var off = (discountPercent / 100) * {{$grandTotal}};

                                // Update discount and total amount
                                $('#discountDetails').html('<p>Discount: -P' + off.toFixed(2) + ' ('+discountPercent +'%OFF)'+'</p>');
                                var amount = {{$grandTotal}} - off + {{$shippingCost}};
                                $('#discountOff').html('-P' + off + ' (' + discountPercent + '% OFF)');
                                $('#totalAmount').html('P' + amount);
                                $('#paymentAmount').html('P' + amount);
                                $('#amount').attr('min', amount);
                                $('#discountCode').attr('name', 'discount_code');
                            } else {
                                $('#discountDetails').html('<p>Invalid</p>');
                                $('#discountCode').attr('name', ''); //unset the name of discount code input to not read after submission

                            }
                        } else {
                            $('#discountDetails').html('<p>Invalid</p>');
                            $('#discountCode').attr('name', ''); //unset the name of discount code input to not read after submission

                        }
                    },
                    error: function() {
                        console.log('Error occurred while checking discount code.');
                    }
                });
            });
            const cartToggle = document.querySelector('.cart-toggle-visibility');
            cartToggle.style.display = 'none';
        });

    </script>
@endsection
