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

    <!-- Modal for baked good -->
    <div class="modal fade" id="bakedGoodModal" tabindex="-1" role="dialog" aria-labelledby="bakedGoodModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bakedGoodModalLabel">Baked Good Details</h5>
                </div>
                <div class="modal-body">
                    <!-- Baked good details will be loaded here -->
                    <div id="bakedGoodDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.auth = {
            isAuthenticated: @json(auth()->check()),
            isAdmin: @json(auth()->check() ? auth()->user()->is_admin : false),
            userId: @json(auth()->check() ? auth()->user()->id : null)
        };

    </script>
    <script src="{{ asset('js/script.js') }}"></script>

@endsection
