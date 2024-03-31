@extends('layouts.app')

@section('content')
    <h1>My Orders</h1>
    @if($userOrders->isEmpty())
        <p>No orders found.</p>
    @else
        <div class="row">
            @foreach($userOrders as $order)
                <div class="col-md-6 mb-4 p-2">
                    <div class="card">
                        <div class="card-header">
                            Order ID: {{ $order->id }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Order Details</h5>
                            <p class="card-text mb-0"><span class='fw-bold'>Buyer Name: </span> {{ $order->buyer_name }}</p>
                            <p class="card-text mb-0"><span class='fw-bold'>Email Address: </span> {{ $order->email_address }}</p>
                            <p class="card-text mb-0"><span class='fw-bold'>Delivery Address: </span> {{ $order->delivery_address }}</p>
                            <p class="card-text mb-0"><span class='fw-bold'>Buyer Note: </span> {{ $order->buyer_note ? $order->buyer_note : 'N/A' }}</p>
                            <p class="card-text mb-0"><span class='fw-bold'>Shipping Cost: </span> {{ $order->shipping_cost }}</p>
                            <p class="card-text mb-0"><span class='fw-bold'>Order Date: </span> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                            <!-- Add more order details as needed -->
                            

                            <h5 class="card-title mt-2 fw-bold">Payment Details</h5>
                            @if($order->payment)
                                <p class="card-text mb-0"><span class='fw-bold'>Payment Mode: </span> {{ $order->payment->mode }}</p>
                                <p class="card-text mb-0"><span class='fw-bold'>Payment Amount: </span> {{ $order->payment->amount }}</p>
                                <!-- Add more payment details as needed -->
                            @else
                                <p class="card-text mb-0">Payment details not available.</p>
                            @endif

                            <!-- Add display for ordered goods and discounts if needed -->

                            <h5 class="card-title py-2 fw-bold">Ordered Goods</h5>
                            @if($order->orderedGoods->isNotEmpty())
                                    @foreach($order->orderedGoods as $orderedGood)
                                        <div class='d-flex flex-row gap-2 px-3 py-1 align-items-center'>
                                            <img src="{{ asset($orderedGood->meal->thumbnailImage->image_path) ?? asset('uploaded_files/default-profile.png')}}" alt='{{ $orderedGood->meal->name }}' style='width: 50px; height: 50px'>
                                            
                                            {{ $orderedGood->meal->name }} - Price: {{ $orderedGood->price_per_good }}, Quantity: {{ $orderedGood->qty }}
                                        </div>
                                        <!-- Add more details as needed -->
                                    @endforeach
                            @else
                                <p>No items ordered.</p>
                            @endif
                        </div>
                        <div class="card-footer">
                            Order Status: {{ $order->order_status }}
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
