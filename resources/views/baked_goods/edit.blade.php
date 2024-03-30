@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Baked Good</h1>
    <hr>
    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form for editing the baked good -->
    <form action="{{ route('baked_goods.update', $bakedGood->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $bakedGood->name) }}">
        </div>

        <!-- Price field -->
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $bakedGood->price) }}">
        </div>

        <!-- Available field -->
        <div class="mb-3">
            <label for="is_available" class="form-label">Availability</label>
            <select class="form-select" id="is_available" name="is_available">
                <option value="1" {{ $bakedGood->is_available == 1 ? 'selected' : '' }}>Available</option>
                <option value="0" {{ $bakedGood->is_available == 0 ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <!-- Description field -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $bakedGood->description) }}</textarea>
        </div>

        <!-- Weight field -->
        <div class="mb-3">
            <label for="weight_gram" class="form-label">Weight (grams)</label>
            <input type="number" class="form-control" id="weight_gram" name="weight_gram" value="{{ old('weight_gram', $bakedGood->weight_gram) }}">
        </div>

        <!-- Thumbnail upload field -->
        <div class="mb-3">
            <label for="thumbnail_image_id" class="form-label">Select Thumbnail</label>
            <select class="form-select" id="thumbnail_image_id" name="thumbnail_image_id">
                <option value="">Select Thumbnail</option>
                @foreach ($bakedGood->images as $image)
                    <option value="{{ $image->id }}" {{ $bakedGood->thumbnail_image_id == $image->id ? 'selected' : '' }}>Image ID: {{ $image->id }}</option>
                @endforeach
            </select>
        </div>

        <!-- Image field -->
        <div class="mb-3">
            <label for="images" class="form-label">Images:</label>
            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary my-3">Update</button>
    </form>
    
    <h1>Ingredients</h1>
    <hr>
    @php
        $ingredients = $bakedGood->ingredients;
    @endphp

    @if (isset($ingredients))
    <ul class='list-unstyled'>
        @foreach ($ingredients as $ingredient)
            <li class='row'>
                <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" style="display: inline-block;" class='d-flex flex-row align-items-center gap-2'>
                    @csrf
                    @method('DELETE')
                    <p class='mb-0 col-4'>
                        @php
                            $thumbnail_path = $ingredient->image_path ?? 'uploaded_files/default-profile.png';
                        @endphp
                        <img src="{{ asset($thumbnail_path) }}" class='col-1' style="min-width: 50px" alt="{{$ingredient->name}}" >   
                        <span>{{$ingredient->qty . $ingredient->unit . " " . $ingredient->name}}</span>
                    </p>
                    <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-success">Edit</a>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </li>
        @endforeach
        
    </ul>
    @endif
    
    <a href="{{ route('ingredients.add', $bakedGood->id) }}" class="btn btn-success">Add new ingredient</a>

    <h1>Baked Good Images</h1>
    <hr>
    @if ($bakedGood->images->isNotEmpty())
    <div class="row mt-3">
        @foreach($bakedGood->images as $image)
        <div class="col-md-3">
            <div class="border px-2 d-flex flex-column align-items-center">
                <strong class='py-2 mb-0'>Image ID: <span>{{ $image->id }}</span></strong>
                <img src="{{ asset($image->image_path) }}" alt="Baked Good Image" class="img-thumbnail">
                <form method="POST" action="{{route('baked_goods.delete_image', $image->id)}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger my-2" name="id" value="{{ $image->id }}">Delete Image</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
