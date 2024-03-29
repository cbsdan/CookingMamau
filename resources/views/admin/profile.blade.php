@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success row col-12 ">
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger pb-0 row col-12 ">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.update.profile') }}" enctype="multipart/form-data" class='row col-12 d-flex flex-column align-items-start mb-3'>
        @csrf
        @method('PUT')
        <h1>Profile</h1>
        <hr>

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

    <!-- Password Update Form -->
    <form method="POST" action="{{ route('admin.update.password') }}" class="row col-12 d-flex flex-column align-items-start mb-5">
        @csrf
        @method('PUT')
    
        <h1>Update Password</h1>
        <hr>
    
        <!-- Old Password -->
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
@endsection
