@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-3">
        <form action="{{ route('discounts.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by Discount Code" value="{{ request()->input('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>
    </div>

    <div class="mb-3">
        @if(auth()->user()->is_admin)
            <a href="{{ route('discounts.create') }}" class="btn btn-primary">Create New Discount</a>
        @endif
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Discount Code</th>
                <th>Logo</th>
                <th>Percent</th>
                <th>Max Number of Buyers</th>
                <th>Min Order Price</th>
                <th>Max Discount Amount</th>
                <th>Usage</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>{{ $discount->discount_code }}</td>
                    <td class='col-1'>
                        @php
                            $imagePath = $discount->image_path ?? 'uploaded_files/default-profile.png';
                        @endphp
                        <img src="{{ asset($imagePath) }}" class="img-thumbnail" alt="Discount" >   
                    </td>
                    <td>{{ $discount->percent . "% OFF" }}</td>
                    <td>{{ (isset($discount->max_number_buyer) ? $discount->max_number_buyer : 'No maximum buyers') }}</td>
                    <td>{{ (isset($discount->min_order_price) ? $discount->min_order_price : 'No minimum order') }}</td>
                    <td>{{ (isset($discount->max_discount_amount) ? $discount->max_discount_amount : 'No maximun amount') }}</td>
                    <td>{{ ($discount->is_one_time_use ? "One Time" : "Multiple")}}</td>
                    <td>{{ $discount->discount_start }}</td>
                    <td>{{ $discount->discount_end }}</td>
                    <td>Available</td>
                    <td>
                        <a href="{{ route('discounts.edit', $discount->discount_code) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('discounts.destroy', $discount->discount_code) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this discount?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
