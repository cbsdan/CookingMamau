@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Baked Good</h1>

    <!-- Display validation errors -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form for creating a new baked good -->
    <form action="{{ route('baked_goods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Name field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <!-- Price field -->
        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
        </div>

        <!-- Availability field -->
        <div class="mb-3">
            <label for="is_available" class="form-label">Available:</label>
            <select class="form-select" id="is_available" name="is_available" required>
                <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <!-- Description field -->
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <!-- Weight field -->
        <div class="mb-3">
            <label for="weight_gram" class="form-label">Weight (grams):</label>
            <input type="number" class="form-control" id="weight_gram" name="weight_gram" value="{{ old('weight_gram') }}">
        </div>

        <!-- Image field -->
        <div class="mb-3">
            <label for="images" class="form-label">Images:</label>
            <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
