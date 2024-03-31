@extends('layouts.app')

@section('content')
    <a class="btn btn-primary" href="{{route('order_reviews.show', $orderReview->id)}}">Back</a>
    <h1 class='mt-2'>Edit Order Review</h1>
    <hr>
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <form action="{{ route('order_reviews.update', $orderReview->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="rating">Rating:</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" value="{{ $orderReview->rating }}" required>
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ $orderReview->comment }}</textarea>
        </div>
        <div class="form-group">
            <label for="review_images">Review Images:</label>
            <input type="file" class="form-control" id="review_images" name="review_images[]" accept="image/*" multiple>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>

    <!-- Delete review button -->
    <form action="{{ route('order_reviews.destroy', $orderReview->id) }}" method="POST" class='mt-2'>
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Review</button>
    </form>
    <h3 class='mt-3'>Review Images</h3>
    <hr>
    <!-- Delete image buttons -->
    <div class="d-flex flex-row reviewImages">
        @foreach($orderReview->reviewImages as $image)
            <div class="reviewImage d-flex align-items-center flex-column">
                <img src="{{asset($image->image_path)}}" alt="{{$image->id}}" style='width: 200px'>
                <form action="{{ route('review_images.destroy', $image->id) }}" method="POST" class='mt-3'>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Image</button>
                </form>
            </div>
        @endforeach

    </div>
@endsection
