@extends('layouts.app')

@section('content')
<style>
    body {
        overflow-x: hidden; /* Hide horizontal scrollbars */
        margin: 0; /* Remove default margin */
        padding: 0;
        background-color: lavender; /* Corrected spelling */
    }

    .bg-video-wrapper {
position: relative;
top: 0;
left: 0;
width: 85vw; /* Full viewport width */
height: 90vh; /* Full viewport height */
overflow: hidden;
display: flex;
justify-content: center;
align-items: center;
margin-left: -10px; /* Adjust the margin on the left side */
}


    .bg-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .text-black {
        color: black;
    }

    .ml-20 {
        margin-left: 20px; /* Adjust as needed */
    }

    .mt-48 {
        margin-top: 48px; /* Adjust as needed */
    }

    .max-w-xl {
        max-width: 600px;
    }

    .absolute {
        position: absolute;
    }

    .top-0 {
        top: 0;
    }

    .left-0 {
        left: 0;
    }

    .text-6xl {
        font-size: 4rem; /* Adjust font size as needed */
        line-height: 1.2; /* Adjust line height as needed */
    }

    .font-semibold {
        font-weight: 600;
    }

    .mb-4 {
        margin-bottom: 1rem; /* Adjust margin as needed */
    }

    .text-lg {
        font-size: 1.5rem; /* Keep the font size */
        line-height: 2; /* Keep the line height */
        margin-top: 300px; /* Adjust the margin-top as needed */
        font-family: Arial, sans-serif; /* Keep the font family */
        }


    .font-bold {
        font-weight: bold; /* Make text bold */
    }

    .mt-10 {
        margin-top: 5rem; /* Adjust margin as needed */
    }

    .bg-yellow-300 {
        background-color: #f59e0b; /* Adjust color as needed */
    }

    .rounded-3xl {
        border-radius: 1.5rem; /* Adjust border radius as needed */
    }

    .py-3 {
        padding-top: 0.9rem; /* Adjust padding as needed */
        padding-bottom: 0.75rem; /* Adjust padding as needed */
    }

    .px-8 {
        padding-left: 2rem; /* Adjust padding as needed */
        padding-right: 2rem; /* Adjust padding as needed */
    }

    .font-medium {
        font-weight: 500;
    }

    .inline-block {
        display: inline-block;
    }

    .mr-4 {
        margin-right: 4rem; /* Adjust margin as needed */
    }

    .welcome-container {
        position: relative;
        height: 100vh; /* Adjust height as needed */
    }
    .container {
        position: relative;
    }
    .welcome-message {
        position: absolute;
        top: 170px; /* Adjust distance from the top as needed */
        left: 20px; /* Adjust distance from the right as needed */
        font-weight: bold;
        font-size: 4rem; /* Adjust font size as needed */
        color: #333; /* Adjust color as needed */
        z-index: 1; /* Ensure5the message appears above other elements */
        /* Add any other styling you want */
        }

    .product-image {
        max-height: 200px;
        height: 200px;
        width: 100%;
    }

    .counter {
        margin-bottom: 15px;
    }

    .quantity-toggler {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        margin: 1px;
    }

    .quantity-input {
        margin-right: 10px;
        margin-left: 15px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .input-group-append .btn {
        margin-left: 10px;
        background-color: #F5F5DC;
        border-color: #9400D3;
        color: #9400D3;
    }

    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        margin-bottom: 20px;
        border: 1px solid black;
        color: black;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5), 0 0 40px rgba(0, 0, 0, 0.5);
    }

</style>

<div class="container">
    @if(session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif
    <div class="bg-video-wrapper">
        <video autoplay muted loop class="bg-video">
            <source src="uploaded_files/cover.mp4" type="video/mp4">
        </video>

        <div class="ml-20 text-black mt-48 max-w-xl absolute top-0 left-0">
            <p class="text-lg">Indulge in the heavenly delights of our pastry goods,<br> where each bite is a celebration of flavor, texture, and artistry.</p>
            @if (!auth()->check() || !auth()->user()->is_admin )
                <div class="mt-10">
                    <a href="{{route('welcome')}}" class="btn bg-yellow-300 rounded-3xl py-3 px-8 font-medium inline-block mr-4 hover:bg-transparent hover:border-yellow-300 hover:text-white duration-300 hover border border-transparent">Order Now</a>
                </div>
            @endif
        </div>
    </div>

    <h1 class="welcome-message">Welcome 
        @if (auth()->check())
            @if (auth()->user()->is_admin)
                Admin
            @else
                to Cooking <br>Mamau Shop
            @endif
        @else
            to Cooking <br> Mamau Shop
        @endif
    </h1>
    
</div>
@endsection
