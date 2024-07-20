@extends('layouts.app')

@section('content')
<div class="container">
    @if (Auth::user()->is_admin)
        <script>window.location = '{{ route('admin.profile') }}';</script>
    @endif
    {{ddd(Auth()->user())}}
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

    <form method="POST" action="" enctype="multipart/form-data" class='row col-12 d-flex flex-column align-items-start mb-5'>
        @csrf
        @method('PUT')
        <h1>Profile</h1>
        <hr>

        <div class="row">
            <div class="col-8">
                <!-- User Information -->
                <div class="row mb-3 col-12">
                    <label for="email" class="col-md-3 col-form-label">{{ __('Email') }}</label>
                    <div class="col-md-9">
                        <input id="email" type="text" class="form-control" name="email" value="{{ Auth::user()->email }}">
                    </div>
                </div>

                <!-- Buyer Information -->
                <div class="row mb-3 col-12">
                    <label for="fname" class="col-md-3 col-form-label">{{ __('First Name') }}</label>
                    <div class="col-md-9">
                        <input id="fname" type="text" class="form-control" name="fname" value="{{ Auth::user()->buyer ? Auth::user()->buyer->fname : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="lname" class="col-md-3 col-form-label">{{ __('Last Name') }}</label>
                    <div class="col-md-9">
                        <input id="lname" type="text" class="form-control" name="lname" value="{{ Auth::user()->buyer ? Auth::user()->buyer->lname : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="contact" class="col-md-3 col-form-label">{{ __('Contact') }}</label>
                    <div class="col-md-9">
                        <input id="contact" type="text" class="form-control" name="contact" value="{{ Auth::user()->buyer ? Auth::user()->buyer->contact : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="address" class="col-md-3 col-form-label">{{ __('Address') }}</label>
                    <div class="col-md-9">
                        <input id="address" type="text" class="form-control" name="address" value="{{ Auth::user()->buyer ? Auth::user()->buyer->address : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="barangay" class="col-md-3 col-form-label">{{ __('Barangay') }}</label>
                    <div class="col-md-9">
                        <input id="barangay" type="text" class="form-control" name="barangay" value="{{ Auth::user()->buyer ? Auth::user()->buyer->barangay : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="city" class="col-md-3 col-form-label">{{ __('City') }}</label>
                    <div class="col-md-9">
                        <input id="city" type="text" class="form-control" name="city" value="{{ Auth::user()->buyer ? Auth::user()->buyer->city : '' }}">
                    </div>
                </div>

                <div class="row mb-3 col-12">
                    <label for="landmark" class="col-md-3 col-form-label">{{ __('Landmark') }}</label>
                    <div class="col-md-9">
                        <input id="landmark" type="text" class="form-control" name="landmark" value="{{ Auth::user()->buyer ? Auth::user()->buyer->landmark : '' }}">
                    </div>
                </div>
            </div>
            <div class='col-4'>
                <div class="row mb-3">
                    @php
                        $profileImagePath = Auth::user()->profile_image_path ?? 'uploaded_files/default-profile.png';
                    @endphp
                    <img src="{{ asset($profileImagePath) }}" class="img-thumbnail" alt="Profile" >
                </div>
                <div class="row">
                    <div class="col-12 p-0">
                        <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="row mb-0 col-12 ">
            <div class="col-md-4" >
                <button type="submit" class="btn btn-success w-100">
                    {{ __('Update Profile') }}
                </button>
            </div>
        </div>
    </form>

    <!-- Password Update Form -->
    <form method="POST" action="{{ route('user.update.password') }}" class="row col-12 d-flex flex-column align-items-start mb-5">
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
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">
                    {{ __('Update Password') }}
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
