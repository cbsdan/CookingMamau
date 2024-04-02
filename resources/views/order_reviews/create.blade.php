@extends('layouts.app')

@section('content')
    <h1>Create Order Review</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ route('order_reviews.store', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name='id_order' value="{{$order->id}}">
        <div class="form-group">
            <label for="rating">Rating:</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" >
        </div>
        <div class="form-group">
            <label for="comment">Comment: (Optional)</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" ></textarea>
        </div>
        <div class="form-group">
            <label for="review_images">Review Images:</label>
            <input type="file" class="form-control-file" id="review_images" name="review_images[]" accept="image/*" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
