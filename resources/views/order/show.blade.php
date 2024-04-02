@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('user.orders')}}" class="btn btn-primary">Back</a>
        <h1>Order Details</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                Order ID: {{ $order->id }}
            </div>
            <div class="card-body">
                @php
                    $grandTotal = 0;
                @endphp
                <h5 class="card-title py-2 fw-bold mb-0">Ordered Goods</h5>
                @if($order->orderedGoods->isNotEmpty())
                    @foreach($order->orderedGoods as $orderedGood)
                        <div class='d-flex flex-row gap-2 px-3 py-1 align-items-center'>
                            <img src="{{ asset($orderedGood->meal->thumbnailImage->image_path ?? 'uploaded_files/default-profile.png')}}" alt='{{ $orderedGood->meal->name }}' style='width: 50px; height: 50px'>
                            
                            {{ $orderedGood->meal->name }} - Price: {{ $orderedGood->price_per_good }}, Quantity: {{ $orderedGood->qty }}
                            @php $grandTotal += $orderedGood->price_per_good * $orderedGood->qty; @endphp
                            <a class="btn btn-primary" href="{{route('baked_goods.show', $orderedGood->meal->id)}}">
                                view
                            </a>
                        </div>
                        <!-- Add more details as needed -->
                    @endforeach
                @else
                    <p>No items ordered.</p>
                @endif
                    @php
                        $shippingCost = $order->shipping_cost;
                        $amountCost = $grandTotal + $shippingCost;
                        if ($order->discount_code != null) {
                            $discount = $order->discount;
                            $percent = $discount->percent;
                            $discountAmount = $grandTotal * ($percent/100);
                            $amountCost = $grandTotal + $shippingCost - $discountAmount;
                        }
                    @endphp
                <h5 class="card-title fw-bold mt-3">Order Details</h5>
                <p class="card-text mb-0"><span class='fw-bold'>Buyer Name: </span> {{ $order->buyer_name }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Email Address: </span> {{ $order->email_address }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Delivery Address: </span> {{ $order->delivery_address }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Buyer Note: </span> {{ $order->buyer_note ? $order->buyer_note : 'No notes' }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Total: </span> P{{ $grandTotal }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Shipping Cost: </span> P{{ $order->shipping_cost }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Discount: </span> -P{{ $order->discount_code ? $discountAmount . " (" . $percent. "% OFF)" : 0 }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Grand Total: </span> P{{ $amountCost }}</p>
                <p class="card-text mb-0"><span class='fw-bold'>Order Date: </span> {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                <!-- Add more order details as needed -->
                
                <h5 class="card-title mt-2 fw-bold">Payment Details</h5>
                @if($order->payment)
                    <p class="card-text mb-0"><span class='fw-bold'>Payment Mode: </span> {{ $order->payment->mode }}</p>
                    <p class="card-text mb-0"><span class='fw-bold'>Payment Amount: </span> P{{ $order->payment->amount }}</p>
                    <!-- Add more payment details as needed -->
                @else
                    <p class="card-text mb-0">Payment details not available.</p>
                @endif

            </div>
            <div class="card-footer d-flex flex-row justify-content-between align-items-center">
                @if (auth()->check() && auth()->user()->is_admin )
                <form action="{{ route('order.updateStatus', ['order' => $order->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <label for="order_status">Update Order Status:</label>
                    <select name="order_status" id="order_status" class='py-1'>
                        <option value="Pending" {{ $order->order_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Canceled" {{ $order->order_status === 'Canceled' ? 'selected' : '' }}>Canceled</option>
                        <option value="Preparing" {{ $order->order_status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Out for Delivery" {{ $order->order_status === 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="Delivered" {{ $order->order_status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                
                    <button type="submit" class='btn btn-primary py-1'>Update</button>
                </form>
                @else
                <div>
                    Order Status: {{ $order->order_status }}
                </div>
                @endif
                <div>
                    @if ($order->order_status == "Delivered" && !$order->reviews && !auth()->user()->is_admin)
                        <a class="btn btn-primary" href="{{route('order_reviews.create', $order->id)}}">
                            Add a review
                        </a>
                    @elseif ($order->reviews)
                        @php $orderReview =  $order->reviews;@endphp
                        <a class="btn btn-primary" href="{{route('order_reviews.show', $orderReview->id)}}">
                            View Review
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
