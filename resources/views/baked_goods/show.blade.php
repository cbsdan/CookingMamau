@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back button -->
    <div class="mb-3">
        <a href="{{ (auth()->check() && auth()->user()->is_admin) ? route('baked_goods.index') : route('welcome')}}" class="btn btn-primary">Back</a>
    </div>
    <h1>Baked Good Details</h1>

    <div class=' p-2'>
        @if ($bakedGood->thumbnailImage)
        <div class="mb-3">
            <p class="fw-bold">Images:</p>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <img src="{{ asset($bakedGood->thumbnailImage->image_path) }}" alt="Thumbnail" class="img-fluid img-thumbnail">
                </div>
                @foreach($bakedGood->images as $image)
                    @if ($image->id != $bakedGood->thumbnailImage->id)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset($image->image_path) }}" alt="Baked Good Image" class="img-fluid img-thumbnail">
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
    
    

        <h2>{{ $bakedGood->name }}</h2>
        <p><strong>Price:</strong> P{{ $bakedGood->price }}</p>
        <p><strong>Availability:</strong> {{ $bakedGood->is_available ? 'Available' : 'Not Available' }}</p>
        <p><strong>Description:</strong> {{ $bakedGood->description ?: 'N/A' }}</p>
        <p><strong>Weight (grams):</strong> {{ $bakedGood->weight_gram ?: 'N/A' }}</p>

        <!-- Ingredients images -->
        <div class="mt-3">
            <p class='fw-bold'>Ingredients</p>
            <div class="row">
                <ol class="p-2">
                    @foreach($bakedGood->ingredients as $ingredient)
                        <li class='mx-4'>
                            {{$ingredient->qty . $ingredient->unit . " " . $ingredient->name}}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Ingredients images -->
        <div class="mt-3">
            <h3 class="fw-bold">Reviews</h3>
            @php
                $totalRating = 0;
                $totalReviews = 0;
            @endphp
        
            @foreach($bakedGood->orderedGood as $orderedGood)
                @if ($orderedGood->order && $orderedGood->order->reviews)
                    {{-- Assuming $orderedGood->order->reviews is a collection --}}
                    @php 
                        $review = $orderedGood->order->reviews; 
                        $totalRating += $review->rating;
                        $totalReviews++;
                    @endphp
                @endif
            @endforeach
        
            @if ($totalReviews > 0)
                @php
                    $averageRating = $totalRating / $totalReviews;
                @endphp
                <p class="mb-0">Average Rating: {{ number_format($averageRating, 1) }} / 5</p>
            @else
                <p class="mb-0">No reviews available</p>
            @endif
            <hr>
            <div class="row">
                @foreach($bakedGood->orderedGood as $orderedGood)
                        @if ($orderedGood->order && $orderedGood->order->reviews)
                            <div class="col-md-6 review d-flex align-items-start border-bottom gap-2 py-2">
                                <div class="image-user pr-2" style='min-width: 70px; min-height: 70px;'>
                                    @php
                                        $image_path = $orderedGood->order->buyer->user->profile_image_path ?? 'uploaded_files/default-profile.png';
                                    @endphp
                                    <img src="{{asset($image_path)}}" alt="user" class='img-thumbnail' style='width: 50px; height: 50px;'>
                                </div>
                                <div class="mb-3 pb-2">
                                    <p class="mb-0">Name: {{$orderedGood->order->buyer->fname . " " . $orderedGood->order->buyer->lname}}</p>
                                    {{-- Assuming $orderedGood->order->reviews is a collection --}}
                                    <p class="mb-0">Rating: <span>{{$orderedGood->order->reviews->rating}} / 5</span></p>
                                    <p class="mb-0">Comment:</p>
                                    <p class="mb-0">{{$orderedGood->order->reviews->comment}}</p>
                                    @if ($orderedGood->order->reviews->reviewImages)
                                        <div class="mt-2">
                                            <p class="mb-0">Images:</p>
                                            <div class="row">
                                                @foreach($orderedGood->order->reviews->reviewImages as $reviewImage)
                                                    <div class="col-3">
                                                        <img src="{{asset($reviewImage->image_path)}}" alt="Image Review" class="img-fluid">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                @endforeach
            </div>
        </div>
        
        
        


    </div>

</div>
@endsection
