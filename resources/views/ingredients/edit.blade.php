@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Ingredient</h1>
    <hr>
    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Form for editing the ingredient -->
    <form action="{{ route('ingredients.update', $ingredient->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $ingredient->name) }}">
        </div>

        <!-- Quantity field -->
        <div class="mb-3">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" value="{{ old('qty', $ingredient->qty) }}">
        </div>
        <!-- Unit field -->
        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <input type="string" class="form-control" id="unit" name="unit" value="{{ old('unit', $ingredient->unit) }}">
        </div>

        <!-- Baked Good selection (if applicable) -->
        <div class="mb-3">
            <label for="id_baked_goods" class="form-label">Baked Good</label>
            <select class="form-select" id="id_baked_goods" name="id_baked_goods">
                <option value="">Change Baked Good</option>
                @foreach ($bakedGoods as $bakedGood)
                    <option value="{{ $bakedGood->id }}" {{$ingredient->id_baked_goods == $bakedGood->id ? "selected" : ""}}>{{ $bakedGood->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Image field -->
        <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <!-- Current Image -->
        @if ($ingredient->image_path)
        <div class="mb-3">
            <p>Current Image:</p>
            <img src="{{ asset($ingredient->image_path) }}" alt="Ingredient Image" class="img-thumbnail" style="max-width: 200px;">
        </div>
        @endif

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary my-3">Update</button>
    </form>
</div>
@endsection
