@extends('layouts.app')

@section('content')
    <div class="container">
        <a class='btn btn-primary' href="{{route('user.orders')}}">Back</a>
        <h1>Review Details</h1>                
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h3>Review ID: {{ $orderReview->id }}<h3>
                <a href="{{ route('order_reviews.edit', $orderReview->id) }}" class="btn btn-primary">Edit</a>
            </div>
            <div class="card-body">
                <h5 class="card-title">Rating: {{ $orderReview->rating }}</h5>
                <p class="card-text">Comment: {{ $orderReview->comment }}</p>
                <!-- Add display for review images if needed -->
                @if($orderReview->reviewImages->isNotEmpty())
                    <h5 class="card-title">Review Images</h5>
                    <div class="row">
                        @foreach($orderReview->reviewImages as $image)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset($image->image_path) }}" class="img-fluid" alt="Review Image">
                            </div>
                        @endforeach
                    </div>
                @endif
                
            </div>
        </div>
    </div>
@endsection
