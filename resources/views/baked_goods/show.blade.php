@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back button -->
    <div class="mb-3">
        <a href="{{ route('baked_goods.index') }}" class="btn btn-primary">Back</a>
    </div>
    <h1>Baked Good Details</h1>

    <div class='border p-2'>
        @if ($bakedGood->thumbnailImage)
        <div class="mb-3">
            <p>Current Thumbnail:</p>
            <img src="{{ asset($bakedGood->thumbnailImage->image_path) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 100px;">
        </div>
        @endif
        
        <h2>{{ $bakedGood->name }}</h2>
        <p><strong>Price:</strong> ${{ $bakedGood->price }}</p>
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

        <!-- Display images -->
        <div class="mt-3">
            <h3>Images</h3>
            <div class="row">
                @foreach($bakedGood->images as $image)
                    <div class="col-md-3">
                        <img src="{{ asset($image->image_path) }}" alt="Baked Good Image" class="img-thumbnail">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
