@extends('layouts.app')

@section('content')
    @if(session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif
    <h1>Welcome 
        @if (auth()->check())
            @if (auth()->user()->is_admin)
                Admin
            @else
                to Cooking Mamau Shop
            @endif
        @else
            to Cooking Mamau Shop
        @endif
    </h1>
    <div class="container">
        <div class="row">
            <!-- Product Cards -->
            @if (isset($bakedGoods))
                @foreach($bakedGoods as $bakedGood) 
                    <form class="col-3 mb-4" action='{{route('cart.add', $bakedGood->id)}}' method='POST'>
                        @csrf
                        <div class="product-card">
                            @php
                                $thumbnail = $bakedGood->thumbnailImage;
                                $image_path = $thumbnail ? $thumbnail->image_path : "uploaded_files/default-profile.png";
                            @endphp
                            <a href="{{route('baked_goods.show', $bakedGood->id)}}">
                                <img src="{{asset($image_path)}}" alt="{{$bakedGood->name}}" class="product-image" style='max-height: 200px; height: 200px; width: 100%;'>
                            </a>
                            <h5 class="mt-2">{{$bakedGood->name}}</h5>
                            <p class='mb-1'>Price: P{{$bakedGood->price}}</p>
                            <p class='mb-1'>Weight: {{$bakedGood->weight_gram}} gram</p>
                            <p class='mb-1'>Availability: {{$bakedGood->is_available ? "Available" : "Not Available"}}</p>
                            <input type='hidden' name='id' value='{{$bakedGood->id}}'>
                            <input type='hidden' name='name' value='{{$bakedGood->name}}'>
                            <input type='hidden' name='price' value='{{$bakedGood->price}}'>
                            <input type='hidden' name='image_path' value='{{$image_path}}'>

                            @if (!auth()->check() || !auth()->user()->is_admin)
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="counter d-flex flex-row col-12">
                                        <div class="btn btn-primary mr-2 col-3 quantity-toggler" {{$bakedGood->is_available ? "" : "disabled"}}>-</div>
                                        <input type="number" name='qty' class="form-control border-0 w-50 quantity-input col-8" min=1 value="{{$bakedGood->is_available ? 1 : 0}}" {{$bakedGood->is_available ? "" : "readonly"}}>
                                        <div class="btn btn-primary ml-2 col-3 quantity-toggler" {{$bakedGood->is_available ? "" : "disabled"}}>+</div>
                                    </div>
                                    <button type='submit' class="btn btn-success ml-auto col-12 add-to-cart" {{$bakedGood->is_available ? "" : "disabled"}} data-product-id="{{$bakedGood->id}}" data-product-name="{{$bakedGood->name}}" data-product-price="{{$bakedGood->price}}">Add to Cart</button>
                                </div>
                            @endif

                        </div>
                    </form>
                </a>
                @endforeach
            @else
                <h3>No Baked Goods Available</h3>
            @endif
        </div>
    </div>
@endsection
