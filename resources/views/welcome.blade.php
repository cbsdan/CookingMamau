@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    <style>
        .suggestions-box {
            position: absolute; /* Position the suggestions box absolutely */
            background-color: #fff; /* White background for the box */
            border: 1px solid #ddd; /* Light border around the box */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            z-index: 1000; /* Ensure it appears above other elements */
            width: 500px; /* Adjust width as needed */
        }

        .suggestion {
            padding: 10px; /* Space around text */
            background-color: #ffffe0; /* Light yellow background */
            color: #333; /* Dark text color for readability */
            border-bottom: 1px solid #ddd; /* Border between items */
            cursor: pointer; /* Pointer cursor on hover */
        }

        .suggestion:hover {
            background-color: #f0f0f0; /* Slightly darker background on hover */
        }
    </style>

    <h1>Our Baked Products</h1>
    <hr>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="search-bar mb-3">
            <input type="text" id="itemSearch" name="q" class="form-control" placeholder="Search for baked goods..." autocomplete="off">
            <div id="suggestions" class="suggestions-box w-50"></div>
        </div>

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
    <div class="modal fade" id="bakedGoodModal" tabindex="-1" role="dialog" aria-labelledby="bakedGoodModalLabel"
        aria-hidden="true">
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
            userId: @json(auth()->check() ? auth()->user()->id : null),
        };
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
