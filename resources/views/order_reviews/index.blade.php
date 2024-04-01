@extends('layouts.app')

@section('content')
    <h1>Order Reviews</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($orderReviews->isEmpty())
        <p>No order reviews found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Review Images</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderReviews as $orderReview)
                    <tr>
                        <td>{{ $orderReview->id }}</td>
                        <td>{{ $orderReview->rating }}</td>
                        <td>{{ $orderReview->comment }}</td>
                        <td>
                            @foreach ($orderReview->reviewImages as $image)
                            <img src="{{ asset($image->image_path) }}" alt="Review Image" style="max-width: 100px;">
                            @endforeach
                        </td>
                        <td><a class='btn btn-primary' href="{{ route('user.order.show', $orderReview->id_order) }}">Order Details</href></td>
                        <td>
                            <a href="{{ route('order_reviews.edit', $orderReview->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('order_reviews.destroy', $orderReview->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order review?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
