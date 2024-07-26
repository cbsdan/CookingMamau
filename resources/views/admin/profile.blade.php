@extends('layouts.app')

@section('content')
<div class="container">
    <div id="success-message" class="alert alert-success row col-12 d-none"></div>
    <div id="error-message" class="alert alert-danger row col-12 d-none"></div>

    <form id="profileForm" class="row col-12 d-flex flex-column align-items-start mb-3" enctype="multipart/form-data">
        @csrf

        <h1>Profile</h1>
        <hr>
        <!-- Profile form fields -->
        <input type='hidden' name='userId' value='{{auth()->user()->id}}'>
        <div class="row">
            <div class="col-12 row d-flex flex-row gap-3 align-items-start">
                <div class="row mb-3 col-4">
                    @php
                        $profileImagePath = Auth::user()->profile_image_path ?? 'uploaded_files/default-profile.png';
                    @endphp
                    <img src="{{ asset($profileImagePath) }}" class="img-thumbnail col-12" alt="Profile" >
                    <div class="col-12 p-0 mb-3">
                        <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row col-8 d-flex align-items-start justify-content-start">
                    <div class="row mb-3 col-12">
                        <label for="email" class="col-md-4 col-form-label p-0">{{ __('Email') }}</label>
                        <div class="col-12 p-0">
                            <input id="email" type="text" class="form-control w-100" name="email" value="{{ Auth::user()->email }}">
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="row mb-0 col-12 p-0">
                        <div class="col-md-6" >
                            <button type="submit" class="btn btn-success w-100">
                                {{ __('Update Profile') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <form id="passwordForm" class="row col-12 d-flex flex-column align-items-start mb-5">
        @csrf
        <h1>Update Password</h1>
        <hr>
        <!-- Old Password -->
        <input type='hidden' name='userId' value='{{auth()->user()->id}}'>
        <div class="row mb-3 col-8 d-flex align-items-center">
            <label for="old_password" class="col-md-4 col-form-label">{{ __('Old Password') }}</label>
            <div class="col-md-8">
                <input id="old_password" type="password" class="form-control" name="old_password" >
            </div>
        </div>

        <!-- New Password -->
        <div class="row mb-3 col-8 d-flex align-items-center">
            <label for="password" class="col-md-4 col-form-label">{{ __('New Password') }}</label>
            <div class="col-md-8">
                <input id="password" type="password" class="form-control" name="password" >
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="row mb-3 col-8 d-flex align-items-center">
            <label for="password_confirmation" class="col-md-4 col-form-label">{{ __('Confirmation') }}</label>
            <div class="col-md-8">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" >
            </div>
        </div>

        <!-- Submit Button for Password Update -->
        <div class="row mb-3 col-12">
            <div class="col-3">
                <button type="submit" class="btn btn-success w-100">
                    {{ __('Update Password') }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        var data = $('#profileForm')[0];
        var formData = new FormData(data);

        // Log the contents of the formData object
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }

        $.ajax({
            type: 'POST',
            url: '/api/admin/profile/update',
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function(response) {
                console.log(formData)
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "Update profile successfully.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
                $.ajax({
                    type: 'GET',
                    url: '/api/users/{{auth()->user()->id}}/getProfileImagePath',
                    dataType: "json",
                    success: function(response) {
                        if (response.profile_image_path) {
                            $('.img-thumbnail').attr('src', response.profile_image_path);
                        }
                        $('#image').val('');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            },
            error: function(error) {
                console.log(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Failed to update profile",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();

        var data = $('#passwordForm')[0];
        var formData = new FormData(data);

        $.ajax({
            type: 'POST',
            url: '/api/admin/password/update',
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "Password updated successfully.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
                $('#old_password').val('')
                $('#password').val('')
                $('#password_confirmation').val('')
            },
            error: function(error) {
                console.log(error);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Password failed to update.",
                    timer: 2000,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>
@endsection
