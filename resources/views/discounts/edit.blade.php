@extends('layouts.app')

@section('content')
    <h1>Edit Discount</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('discounts.update', $discount->discount_code) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="discount_code" class="form-label">{{ __('Discount Code') }}</label>
            <input type="text" class="form-control" id="discount_code" name="discount_code" value="{{ old('discount_code', $discount->discount_code) }}">
        </div>

        <div class="mb-3">
            <label for="percent" class="form-label">{{ __('Percent') }}</label>
            <input type="number" class="form-control" id="percent" name="percent" value="{{ old('percent', $discount->percent) }}">
        </div>

        <div class="mb-3">
            <label for="max_number_buyer" class="form-label">{{ __('Max Number of Buyers (Optional)') }}</label>
            <input type="number" class="form-control" id="max_number_buyer" name="max_number_buyer" value="{{ old('max_number_buyer', $discount->max_number_buyer) }}">
        </div>

        <div class="mb-3">
            <label for="min_order_price" class="form-label">{{ __('Minimum Order Price (Optional)') }}</label>
            <input type="number" step="0.01" class="form-control" id="min_order_price" name="min_order_price" value="{{ old('min_order_price', $discount->min_order_price) }}">
        </div>
        <div class="mb-2">
            <label for="max_discount_amount" class="form-label">{{ __('Max Discount Amount (Optional)') }}</label>
            <input type="number" step="0.01" class="form-control @error('max_discount_amount') is-invalid @enderror" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}">
        </div>
        <div class="mb-3">
            <label for="is_one_time_use" class="form-label">{{ __('Is One Time Use') }}</label>
            <select class="form-select" id="is_one_time_use" name="is_one_time_use">
                <option value="1" {{ old('is_one_time_use', $discount->is_one_time_use) == 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_one_time_use', $discount->is_one_time_use) == 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="discount_start" class="form-label">{{ __('Discount Start Date') }}</label>
            <input type="date" class="form-control" id="discount_start" name="discount_start" value="{{ old('discount_start', $discount->discount_start) }}">
        </div>

        <div class="mb-3">
            <label for="discount_end" class="form-label">{{ __('Discount End Date') }}</label>
            <input type="date" class="form-control" id="discount_end" name="discount_end" value="{{ old('discount_end', $discount->discount_end) }}">
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">{{ __('Update Image') }}</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Add more input fields for other discount details -->

        <button type="submit" class="btn btn-primary">{{ __('Update Discount') }}</button>
    </form>
@endsection