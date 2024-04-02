@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Ingredient</h1>
    <hr>
    <!-- Display validation errors if any -->
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p class='mb-0'>{{ $error }}</p>
        @endforeach
    </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Form for creating a new ingredient -->
    <form action="{{ route('ingredients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Name field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>

        <!-- Quantity field -->
        <div class="mb-3">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" value="{{ old('qty') }}">
        </div>
        <div class="mb-3">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}">
        </div>

        <!-- Baked Good selection (if applicable) -->
        <div class="mb-3">
            <label for="baked_good" class="form-label">Baked Good</label>
            <input type='text' class="form-control" name='baked_good' id='baked_good' value='{{$bakedGood->name}}' readonly>
            <input type='hidden' name='id_baked_goods' id='id_baked_goods' value='{{$bakedGood->id}}' readonly>
        </div>

        <!-- Image file upload -->
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Create Ingredient</button>
    </form>
</div>
@endsection
