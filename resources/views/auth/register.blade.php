@extends('layouts.app')
@section('title', 'Register')
@section('content')
    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 110px)">
        <div class="col-lg-4" id="auth-container" >
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 background-styling">
                    <div class="text-center mb-3">
                        <div class="toggle-container position-relative">
                            <button id="btn-register" class="btn-toggle active">Sign up</button>
                            <button id="btn-login" class="btn-toggle"><a style="text-decoration: none; color: black;" href="{{route('login')}}">Log in</a></button>
                            <div class="btn-toggle-indicator position-absolute"></div>
                        </div>
                    </div>
                    <div id="show_success_alert"></div>
                    <form action="{{ route('auth.register') }}" method="POST" id="register_form" enctype="multipart/form-data">
                        @csrf
                        <!-- Form fields here -->
                        <div class="mb-3">
                            <input type="text" name="fname" id="fname" class="form-control rounded-pill" placeholder="First Name">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="lname" id="lname" class="form-control rounded-pill" placeholder="Last Name">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="contact" id="contact" class="form-control rounded-pill" placeholder="Contact Number">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="address" id="address" class="form-control rounded-pill" placeholder="Address">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="barangay" id="barangay" class="form-control rounded-pill" placeholder="Barangay">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="city" id="city" class="form-control rounded-pill" placeholder="City">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="landmark" id="landmark" class="form-control rounded-pill" placeholder="Landmark">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" id="email" class="form-control rounded-pill" placeholder="Email">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" id="password" class="form-control rounded-pill" placeholder="Password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="cpassword" id="cpassword" class="form-control rounded-pill" placeholder="Confirm Password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <input type="file" name="image" id="image" class="form-control rounded-pill" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3 d-grid">
                            <input type="submit" value="Sign Up" class="btn btn-primary rounded-pill" id="register_btn">
                        </div>
                        <div class="text-center text-secondary">
                            <div>Already a member? <a href="{{ route('login') }}" class="text-decoration-none">Log in</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $("#register_form").submit(function(e) {
                e.preventDefault();
                $("#register_btn").val('Please Wait...');
                let formData = new FormData(this);
                $.ajax({
                    url: 'api/register',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.status === 422) {
                            showError('fname', res.message.fname);
                            showError('lname', res.message.lname);
                            showError('contact', res.message.contact);
                            showError('address', res.message.address);
                            showError('barangay', res.message.barangay);
                            showError('city', res.message.city);
                            showError('landmark', res.message.landmark);
                            showError('email', res.message.email);
                            showError('password', res.message.password);
                            showError('cpassword', res.message.cpassword);
                            $("#register_btn").val('Register');
                        } else {
                            // $("#show_success_alert").html(showMessage('success', res.message));
                            $("#register_form")[0].reset();
                            removeValidationClasses("#register_form");
                            $("#register_btn").val('Register');
                            window.location.href = '{{ route ('login') }}';
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred. Please try again later.');
                        $("#register_btn").val('Register');
                    }
                });
            });

            function showError(field, message) {
                $('#' + field).addClass('is-invalid');
                $('#' + field).next('.invalid-feedback').text(message);
            }

            function showMessage(type, message) {
                return `<div class="alert alert-${type}">${message}</div>`;
            }
        function removeValidationClasses(form) {
            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.invalid-feedback').text('');
        }
    });
</script>
@endsection

@section('script')
    <script src="{{ asset('js/toggle.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@yield('script')
@endsection
