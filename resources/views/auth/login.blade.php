@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 110px)">
    <div class="col-lg-4" id="auth-container" data-login-route="{{ route('login') }}" data-register-route="{{ route('register') }}">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5 background-styling">
                <div class="text-center mb-3">
                    <div class="toggle-container position-relative">
                        <button id="btn-register" class="btn-toggle"><a style="text-decoration: none; color: black;" href="{{route('register')}}">Sign up</a></button>
                        <button id="btn-login" class="btn-toggle active">Log in</button>
                        <div class="btn-toggle-indicator position-absolute"></div>
                    </div>
                </div>
                <div id="login_alert"></div>
                <form action="{{ route('login') }}" method="POST" id="login_form">
                    @csrf
                    <div class="mb-3">
                        <input type="email" name="email" id="email" class="form-control rounded-pill" placeholder="Enter Email or Username">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" id="password" class="form-control rounded-pill" placeholder="Password">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill">Log in</button>
                    </div>
                    <div class="text-center">
                        <div class="text-secondary">OR</div>
                        <div class="mt-2">
                            <a href="#" class="btn btn-outline-primary btn-social"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-danger btn-social"><i class="fab fa-google"></i></a>
                            <a href="#" class="btn btn-outline-info btn-social"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $("#login_form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Your password must be at least 6 characters long"
            }
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
</script>
@endsection

@section('script')
<script src="{{ asset('js/toggle.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@yield('script')
@endsection
