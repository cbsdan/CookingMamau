@extends('layouts.app')

@section('content')
    @if(session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    <style>
        /* Your existing CSS styles */
    </style>

    <h1>Our Baked Products</h1>
    <hr>

    <!-- Main Content -->
    <div class="container mt-4">
        <form action="" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search products..." name="query">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <!-- Product Cards -->
        <div class="row" id="baked-goods-container">
            <!-- Products will be appended here by AJAX -->
        </div>

        <!-- Loader -->
        <div id="loader" style="display:none; text-align: center; width: 100%;">
            Loading...
        </div>
        <div id="end" style="display:none; text-align: center; width: 100%;">
            You have reached the end...
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var loading = false; // Flag to prevent multiple AJAX calls
            var page = 1; // Start with the first page
            function loadMoreBakedGoods() {
                if (loading) return; // Prevent multiple AJAX calls

                loading = true;
                $('#loader').show(); // Show the loader

                $.ajax({
                    url: 'api/bakedgoods/paginate?page=' + page,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        setTimeout(function() {
                        // Hide the loader and reset the loading flag after 2 seconds
                        $('#loader').hide();
                        loading = false;

                        page++; // Increment the page number

                        // Append new baked goods to the container
                        var bakedGoodsContainer = $('#baked-goods-container');
                        response.data.forEach(function(bakedGood) {
                            var imagePath = bakedGood.images.length ? bakedGood.images[0].image_path : 'uploaded_files/default-profile.png';
                            var html = `
                                <form class="col-3" action='' method='POST'>
                                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                    <div class="product-card" style="position: relative;">
                                        <a href="/baked-goods/${bakedGood.id}">
                                            <img src="${imagePath}" alt="${bakedGood.name}" class="product-image">
                                        </a>
                                        <h5 class="mt-2">${bakedGood.name}</h5>
                                        <p class='mb-1'><span class='fw-semibold'>Price:</span> P${bakedGood.price}</p>
                                        <p class='mb-1'><span class='fw-semibold'>Weight:</span> ${bakedGood.weight_gram} gram</p>
                                        <p class="mb-1 ${bakedGood.is_available ? 'bg-success' : 'bg-danger'}" style="position: absolute; top: 0; left: 0; padding: 5px 10px; color: white; border-bottom-right-radius: 10px; border-top-left-radius: 5px;">${bakedGood.is_available ? "Available" : "Not Available"}</p>
                                        <input type='hidden' name='id' value='${bakedGood.id}'>
                                        <input type='hidden' name='name' value='${bakedGood.name}'>
                                        <input type='hidden' name='price' value='${bakedGood.price}'>

                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <div class="counter d-flex flex-row">
                                                <div class="btn btn-primary quantity-toggler" ${bakedGood.is_available ? "" : "disabled"}>-</div>
                                                <input type="number" name='qty' class="form-control border-0 quantity-input" min=1 value="${bakedGood.is_available ? 1 : 0}" ${bakedGood.is_available ? "" : "readonly"}>
                                                <div class="btn btn-primary quantity-toggler" ${bakedGood.is_available ? "" : "disabled"}>+</div>
                                            </div>
                                            <button type='submit' class="btn ${bakedGood.is_available ? "btn-success" : "btn-danger"} mt-2" style="width: 100%" ${bakedGood.is_available ? "" : "disabled"} data-product-id="${bakedGood.id}" data-product-name="${bakedGood.name}" data-product-price="${bakedGood.price}">${bakedGood.is_available ? "Add to Cart" : "Unavailable"}</button>
                                        </div>
                                    </div>
                                </form>
                            `;
                            bakedGoodsContainer.append(html); // Add the new baked goods to the container
                        });

                        // Show the #end element if there's no more pages
                        if (!response.next_page_url) {
                            $('#end').show(); // Show the end indicator
                            $(window).off('scroll', onScroll); // Remove the scroll event listener
                        }
                    }, 2000); // Wait for 2 seconds before appending products
                },
                    error: function() {
                        $('#loader').hide(); // Hide the loader on error
                        $('#end').show(); // Show the end indicator on error
                        loading = false; // Reset loading flag
                    }
                });
            }

            function onScroll() {
                // Check if user has scrolled close to the bottom
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                    loadMoreBakedGoods(); // Load more baked goods
                }
            }

            $(window).on('scroll', onScroll); // Attach scroll event listener

            // Initial load
            loadMoreBakedGoods(); // Load the first batch of baked goods
        });
    </script>

@endsection
