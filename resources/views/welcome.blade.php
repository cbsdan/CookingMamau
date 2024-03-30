@extends('layouts.app')

@section('content')
    <style>
        /* Add custom styles here */
        .product-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .product-image {
            max-width: 100%;
            height: auto;
        }
        .quantity-input {
            width: 70px;
            text-align: center;
        }
    </style>
    <h1>Welcome to Cooking Mamau Shop</h1>
    <div class="container">
        <div class="row">
            @if (isset($bakedGoods))
                @foreach($bakedGoods as $bakedGood) 
                    <div class="col-3 mb-4">
                        <div class="product-card">
                            @php
                                $thumbnail = $bakedGood->thumbnailImage;
                                // dd($thumbnail);
                                $image_path = $thumbnail ? $thumbnail->image_path : "uploaded_files/default-profile.png";
                            @endphp
                            <img src="{{asset($image_path)}}" alt="{{$bakedGood->name}}" class="product-image" style='max-height: 200px; height: 200px;'>
                            <h5 class="mt-2">{{$bakedGood->name}}</h5>
                            <p class='mb-1'>Price: P{{$bakedGood->price}}</p>
                            <p class='mb-1'>Weight: {{$bakedGood->weight_gram}} gram</p>
                            <p class='mb-1'>Availability: {{$bakedGood->is_available ? "Available" : "Not Available"}}</p>
                            <div class="d-flex flex-column align-items-center gap-2">
                                <div class="counter d-flex flex-row col-12">
                                    <button class="btn btn-primary mr-2 col-3" {{$bakedGood->is_available ? "" : "disabled"}}>-</button>
                                    <input type="number" class="form-control border-0 w-50 quantity-input col-8" min=1 value="{{$bakedGood->is_available ? 1 : 0}}" {{$bakedGood->is_available ? "" : "readonly"}}>
                                    <button class="btn btn-primary ml-2 col-3" {{$bakedGood->is_available ? "" : "disabled"}}>+</button>
                                </div>
                                <button class="btn btn-success ml-auto col-12" {{$bakedGood->is_available ? "" : "disabled"}}>Add to Cart</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h3>No Baked Goods Available</h3>
            @endif
            
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Increment and Decrement Quantity
            var buttons = document.querySelectorAll('.btn-primary');
            buttons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var input = this.parentNode.querySelector('input');
                    var value = parseInt(input.value);
                    if (this.textContent === '+') {
                        input.value = value + 1;
                    } else {
                        input.value = Math.max(value - 1, 1);
                    }
                });
            });
        });

    </script>
@endsection