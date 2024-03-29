@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Discount') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('discounts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-2">
                            <label for="discount_code" class="form-label">{{ __('Discount Code') }}</label>
                            <input type="text" class="form-control @error('discount_code') is-invalid @enderror" id="discount_code" name="discount_code" value="{{ old('discount_code') }}" >
                            @error('discount_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="percent" class="form-label">{{ __('Percentage') }}</label>
                            <input type="number" class="form-control @error('percent') is-invalid @enderror" id="percent" name="percent" value="{{ old('percent') }}" >
                            @error('percent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="max_number_buyer" class="form-label">{{ __('Max Number of Buyers (Optional)') }}</label>
                            <input type="number" class="form-control @error('max_number_buyer') is-invalid @enderror" id="max_number_buyer" name="max_number_buyer" value="{{ old('max_number_buyer') }}">
                            @error('max_number_buyer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="min_order_price" class="form-label">{{ __('Minimum Order Price (Optional)') }}</label>
                            <input type="number" step="0.01" class="form-control @error('min_order_price') is-invalid @enderror" id="min_order_price" name="min_order_price" value="{{ old('min_order_price') }}">
                            @error('min_order_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="max_discount_amount" class="form-label">{{ __('Max Discount Amount (Optional)') }}</label>
                            <input type="number" step="0.01" class="form-control @error('max_discount_amount') is-invalid @enderror" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}">
                            @error('max_discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="is_one_time_use" class="form-label">{{ __('Is One Time Use?') }}</label>
                            <select class="form-control @error('is_one_time_use') is-invalid @enderror" id="is_one_time_use" name="is_one_time_use" >
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('is_one_time_use')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="discount_start" class="form-label">{{ __('Discount Start Date') }}</label>
                            <input type="date" class="form-control @error('discount_start') is-invalid @enderror" id="discount_start" name="discount_start" value="{{ old('discount_start') }}" >
                            @error('discount_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="discount_end" class="form-label">{{ __('Discount End Date') }}</label>
                            <input type="date" class="form-control @error('discount_end') is-invalid @enderror" id="discount_end" name="discount_end" value="{{ old('discount_end') }}" >
                            @error('discount_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="image" class="form-label">{{ __('Image') }}</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                            <a href="{{ route('discounts.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
