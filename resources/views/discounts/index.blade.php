@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Discounts</h1>
    <hr>
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
        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('discounts.create') }}" class="btn btn-primary">Create New Discount</a>
        @endif
    </div>

    <table class="table" id='{{auth()->check() && auth()->user()->is_admin ? 'myDataTable' : '' }}'>
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
                @if (auth()->check() && auth()->user()->is_admin)
                    <th>Actions</th>
                @endif
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
                    <td class="d-flex flex-row gap-1" style="height: 103px;">
                        @if (auth()->check() && auth()->user()->is_admin)
                            <a href="{{ route('discounts.edit', $discount->discount_code) }}" class="btn btn-success" style="height:36.78px">Edit</a>
                            <form action="{{ route('discounts.destroy', $discount->discount_code) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this discount?')">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
