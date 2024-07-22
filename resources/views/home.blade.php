@extends('layouts.app')

@section('content')
<style>
html, body {
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
}

.container-fluid {
    height: 100%;
    width: 100%;
    padding: 0;
    overflow: hidden;
}

/* Carousel Styles */
.carousel,
.carousel-inner,
.carousel-item {
    height: 100vh;
    width: 100vw;
    position: absolute;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.6);
}

.carousel-item img,
.carousel-item video {
    object-fit: cover;
    width: 100%;
    height: 100%;
}

.carousel-item::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.carousel-caption {
    bottom: 10%;
    left: 10%;
    text-align: left;
    width: 40%;
    color: white;
    z-index: 2;
    position: absolute;
    animation: fadeIn 2s ease-in-out;
    padding: 20px;
    border-radius: 10px;
}

.carousel-caption p {
    font-size: 1.5rem;
    margin-bottom: 25px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
    line-height: 1.6;
    animation: slideInLeft 1.5s ease-in-out;
}

.carousel-caption .btn {
    animation: slideInLeft 1.8s ease-in-out;
}

.carousel-caption,
.carousel-control-prev,
.carousel-control-next {
    z-index: 2;
}

.carousel-indicators {
    z-index: 3;
}

/* Welcome Message */
.welcome-message {
    position: absolute;
    top: 30%;
    left: 10%;
    transform: translate(0, -40%);
    font-size: 5rem;
    color: white;
    z-index: 10;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
    font-weight: 700;
    letter-spacing: 1px;
    animation: amazingTextAnimation 2s ease-in-out;
}

/* Navigation Bar */
.navbar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 20;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 10px 20px;
}

/* Buttons */
.btn {
    padding: 12px 24px;
    font-size: 1.1rem;
    border-radius: 50px;
    transition: all 0.4s ease;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.btn-primary {
    background-color: #ffd700;
    border-color: #ffd700;
    color: #000;
}

.btn-primary:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background-color: rgba(255,255,255,0.2);
    transition: all 0.4s ease;
    z-index: -1;
}

.btn-primary:hover {
    background-color: #e6c200;
    border-color: #e6c200;
    color: #000;
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.btn-primary:hover:before {
    left: 100%;
}

/* Carousel Controls */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(100%);
    width: 40px;
    height: 40px;
}

/* Animations */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes slideInLeft {
    0% { transform: translateX(-100px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes amazingTextAnimation {
    0% {
        transform: translateX(-100px) scale(0.8);
        opacity: 0;
        color: #FFD700;
    }
    50% {
        transform: translateX(10px) scale(1.2);
        color: #FF6347;
    }
    100% {
        transform: translateX(0) scale(1);
        opacity: 1;
        color: white;
    }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .carousel-caption {
        width: 60%;
    }

    .welcome-message {
        font-size: 2rem;
    }

    .carousel-caption p {
        font-size: 1.3rem;
    }

    .btn {
        padding: 10px 20px;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .welcome-message {
        font-size: 1.5rem;
    }

    .carousel-caption {
        width: 80%;
    }

    .carousel-caption p {
        font-size: 1.1rem;
    }

    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}

</style>

<div class="container-fluid p-0">
    @if(session('error'))
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    @endif

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <video autoplay muted loop class="d-block w-100 h-100">
                    <source src="uploaded_files/cover.mp4" type="video/mp4">
                </video>
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploaded_files/cake1.jpg') }}" class="d-block w-100 h-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploaded_files/cake2.jpg') }}" class="d-block w-100 h-100" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('uploaded_files/cake3.jpg') }}" class="d-block w-100 h-100" alt="Slide 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <div class="carousel-caption d-none d-md-block">
            <p class="text-lg">Indulge in the heavenly delights of our pastry goods,<br> where each bite is a celebration of flavor, texture, and artistry.</p>
            @if (!auth()->check() || !auth()->user()->is_admin )
                <div class="mt-4">
                    <a href="{{route('welcome')}}" class="btn btn-primary">Order Now</a>
                </div>
            @endif
        </div>
    </div>

    <h1 class="welcome-message">Welcome
        @if (auth()->check())
            @if (auth()->user()->is_admin)
                Admin
            @else
                to <br> Cooking <br>Mamau Shop
            @endif
        @else
            to Cooking <br> Mamau Shop
        @endif
    </h1>
</div>
@endsection
