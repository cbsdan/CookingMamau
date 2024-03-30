@extends('layouts.app')

@section('content')
    <style>
        /* Add custom styles here */
        .product-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        input {
            outline: none;
        }
        p {
            margin: 0;
        }
        .product-image {
            max-width: 100%;
            height: auto;
        }
        .quantity-input {
            text-align: center;
        }
        .cart {
            display: flex;
            position: fixed;
            top: 0;
            right: 0;
            width: 350px; /* Adjust the width as needed */
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
        }
        .cart h2 {
            margin-bottom: 20px;
        }
        .cart-item {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .cart-total {
            margin-top: 20px;
            font-weight: bold;
        }
        .checkout-btn {
            margin-top: 20px;
            width: 100%;
        }
        .cart-toggle-visibility {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
        }
    </style>
    <h1>Welcome to Cooking Mamau Shop</h1>
    <div class="container">
        <div class="row">
            <!-- Product Cards -->
            @if (isset($bakedGoods))
                @foreach($bakedGoods as $bakedGood) 
                    <form class="col-3 mb-4" action='{{route('cart.add', $bakedGood->id)}}' method='POST'>
                        @csrf
                        <div class="product-card">
                            @php
                                $thumbnail = $bakedGood->thumbnailImage;
                                $image_path = $thumbnail ? $thumbnail->image_path : "uploaded_files/default-profile.png";
                            @endphp
                            <img src="{{asset($image_path)}}" alt="{{$bakedGood->name}}" class="product-image" style='max-height: 200px; height: 200px; width: 100%;'>
                            <h5 class="mt-2">{{$bakedGood->name}}</h5>
                            <p class='mb-1'>Price: P{{$bakedGood->price}}</p>
                            <p class='mb-1'>Weight: {{$bakedGood->weight_gram}} gram</p>
                            <p class='mb-1'>Availability: {{$bakedGood->is_available ? "Available" : "Not Available"}}</p>
                            <input type='hidden' name='id' value='{{$bakedGood->id}}'>
                            <input type='hidden' name='name' value='{{$bakedGood->name}}'>
                            <input type='hidden' name='price' value='{{$bakedGood->price}}'>
                            <input type='hidden' name='image_path' value='{{$image_path}}'>
                            <div class="d-flex flex-column align-items-center gap-2">
                                <div class="counter d-flex flex-row col-12">
                                    <div class="btn btn-primary mr-2 col-3 quantity-toggler" {{$bakedGood->is_available ? "" : "disabled"}}>-</div>
                                    <input type="number" name='qty' class="form-control border-0 w-50 quantity-input col-8" min=1 value="{{$bakedGood->is_available ? 1 : 0}}" {{$bakedGood->is_available ? "" : "readonly"}}>
                                    <div class="btn btn-primary ml-2 col-3 quantity-toggler" {{$bakedGood->is_available ? "" : "disabled"}}>+</div>
                                </div>
                                <button type='submit' class="btn btn-success ml-auto col-12 add-to-cart" {{$bakedGood->is_available ? "" : "disabled"}} data-product-id="{{$bakedGood->id}}" data-product-name="{{$bakedGood->name}}" data-product-price="{{$bakedGood->price}}">Add to Cart</button>
                            </div>
                        </div>
                    </form>
                @endforeach
            @else
                <h3>No Baked Goods Available</h3>
            @endif
        </div>

        <!-- Shopping Cart -->
        @php
            $cartItems = session()->get('cart', []);
            $grandTotal = 0;
        @endphp
        <div class="cart flex-column justify-content-between" style="display:none;" >
            <div id="cartItems">
                <h2>My Cart</h2>
                @foreach ($cartItems as $id => $details)
                    @php 
                        $total = $details['quantity'] * $details['price']; 
                        $grandTotal += $total;
                    @endphp
                    <div class='cartItem d-flex flex-row ailgn-items-center p-1 mb-1' style="background: rgb(230, 255, 250);">
                        <div class="col-3 px-1">
                            <img src="{{$details['image_path']}}" alt="{{$details['name']}}" class="img-thumbnail" style='height: 70px; width: 70px;'>
                        </div>
                        <div class="col-3 px-1 d-flex flex-column ailgn-items-center justify-content-center">
                            <p class='mb-0'>{{$details['name']}}<p>
                            <p class='mb-0'>P{{$details['price']}}<p>
                        </div>
                        
                        <div class=" d-flex align-items-center col-3 px-1 text-center">
                            <div class="quantity-toggler btn p-1">-</div>
                            <input type="text" name='qty' class="quantity-input border-0 d-flex align-items-center justify-content-center" style="width:30px; background: transparent;" min=1 value="{{$bakedGood->is_available ? $details['quantity'] : 0}}" {{$bakedGood->is_available ? "" : "readonly"}}>
                            <div class="quantity-toggler btn p-1">+</div>
                        </div>
                        <div class=" d-flex align-items-center col-2 px-1 text-start">
                            <p class="mb-0 w-100 text-start">P{{$total}}</p>
                        </div>
                        <form action="{{route('cart.remove')}}" method="POST" class=" d-flex align-items-center col-1 px-1 text-center">
                            @csrf
                            <input type="hidden" name='id' value='{{$id}}'>
                            <button type="submit" name='submit' class="btn mb-0 p-0 text-start ">{{"D"}}</button>
                        </form>
                    </div>

                @endforeach
            </div>
            <div>
                <div class="cart-total text-end">
                    Total: P{{$grandTotal}} 
                </div>
                <button class="btn btn-success checkout-btn">Checkout</button>
            </div>
            <!-- Open button -->
        </div>
        <button class="btn btn-primary open-btn cart-toggle-visibility">Open Cart</button>
        <!-- Close button -->
        <button class="btn btn-danger close-btn cart-toggle-visibility" style="display: none;">Close Cart</button>
    </div>

    <!-- Modal for Checkout -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for Checkout -->
                    <form id="checkoutForm">
                        <!-- Display the list of products to be ordered here -->
                        <div id="checkoutItems"></div>
                        <!-- Delivery Address -->
                        <div class="form-group">
                            <label for="deliveryAddress">Delivery Address</label>
                            <input type="text" class="form-control" id="deliveryAddress">
                        </div>
                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="emailAddress">Email Address</label>
                            <input type="email" class="form-control" id="emailAddress">
                        </div>
                        <!-- Note for Admin -->
                        <div class="form-group">
                            <label for="adminNote">Note</label>
                            <textarea class="form-control" id="adminNote" rows="3"></textarea>
                        </div>
                        <!-- Discount Code -->
                        <div class="form-group">
                            <label for="discountCode">Discount Code</label>
                            <input type="text" class="form-control" id="discountCode">
                        </div>
                        <!-- Delivery Date Time Picker -->
                        <div class="form-group">
                            <label for="deliveryDateTime">Delivery Date and Time</label>
                            <input type="datetime-local" class="form-control" id="deliveryDateTime">
                        </div>
                        <!-- Payment Method -->
                        <div class="form-group">
                            <label for="paymentMethod">Payment Method</label>
                            <select class="form-control" id="paymentMethod">
                                <option value="GCash">GCash</option>
                                <option value="COD">Cash on Delivery (COD)</option>
                            </select>
                        </div>
                        <!-- Total Price -->
                        <div class="form-group">
                            <label for="grandTotal">Grand Total</label>
                            <input type="text" class="form-control" id="grandTotal" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="placeOrderBtn">Place Order</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Increment and Decrement Quantity
            var buttons = document.querySelectorAll('.quantity-toggler');
            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var input = this.parentNode.querySelector('input');
                    var value = parseInt(input.value);
                    if (this.textContent === '+') {
                        input.value = value + 1;
                    } else {
                        input.value = Math.max(value - 1, 1);
                    }
                });
            });

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get the open and close button elements
            var openButton = document.querySelector('.open-btn');
            var closeButton = document.querySelector('.close-btn');
            var cart = document.querySelector('.cart');

            // Add event listener for open button click event
            openButton.addEventListener('click', function() {
                // Show the cart
                cart.style.display = 'flex';
                // Hide the open button and show the close button
                openButton.style.display = 'none';
                closeButton.style.display = 'block';
                closeButton.style.right = '370px';
            });

            // Add event listener for close button click event
            closeButton.addEventListener('click', function() {
                // Hide the cart
                cart.style.display = 'none';
                // Hide the close button and show the open button
                closeButton.style.display = 'none';
                openButton.style.display = 'block';
                openButton.style.display = '1rem';
            });
        });


        // Function to display the checkout modal
        function displayCheckoutModal() {
            // Add your logic to display the checkout modal here
            console.log("Displaying checkout modal");
            $('#checkoutModal').modal('show');
        }

        // Function to place order
        function placeOrder() {
            // Add your logic to place the order here
            console.log("Placing order...");
        }
    </script>
@endsection
