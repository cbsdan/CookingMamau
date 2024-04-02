@extends('layouts.app')

@section('content')
    <h1>{{(auth()->check() && auth()->user()->is_admin) ? "Orders" : "My Orders"}}</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
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
                            <!-- Add display for ordered goods and discounts if needed -->

                            <h5 class="card-title py-2 fw-bold">Ordered Goods</h5>
                            @if($order->orderedGoods->isNotEmpty())
                                    @foreach($order->orderedGoods as $orderedGood)
                                        <div class='d-flex flex-row gap-2 px-3 py-1 align-items-center'>
                                            <img src="{{ asset($orderedGood->meal->thumbnailImage->image_path ?? 'uploaded_files/default-profile.png')}}" alt='{{ $orderedGood->meal->name }}' style='width: 50px; height: 50px'>
                                            
                                            {{ $orderedGood->meal->name }} - Price: {{ $orderedGood->price_per_good }}, Quantity: {{ $orderedGood->qty }}
                                        </div>
                                        <!-- Add more details as needed -->
                                    @endforeach
                            @else
                                <p>No items ordered.</p>
                            @endif

                            

                            <h5 class="card-title mt-2 fw-bold">Payment Details</h5>
                            @if($order->payment)
                                <p class="card-text mb-0"><span class='fw-bold'>Payment Mode: </span> {{ $order->payment->mode }}</p>
                                <p class="card-text mb-0"><span class='fw-bold'>Payment Amount: </span> {{ $order->payment->amount }}</p>
                                <!-- Add more payment details as needed -->
                            @else
                                <p class="card-text mb-0">Payment details not available.</p>
                            @endif

                        </div>
                        <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                            <div>
                                Order Status: {{ $order->order_status }}
                            </div>
                            <div>
                                <a class="btn btn-primary" href="{{route('user.order.show', $order->id)}}">
                                    View Details
                                </a>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
