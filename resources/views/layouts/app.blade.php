@php
    use App\Models\BakedGood;
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">

    <!-- FontAwesome -->
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>

    <!-- Include jQuery Validation Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>

    <!-- Bootstrap JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> --}}

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>

    <!-- Chart.js -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.js" crossorigin="anonymous"></script> --}}

    <!-- Bootbox.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <!-- Custom Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/function.js') }}" defer></script>
    {{-- <script src="{{ asset('js/profile.js') }}"></script> --}}

    <script src="{{ asset('js/datatable.js') }}"></script>
    {{-- <script src="{{ asset('js/admin.js') }}"></script> --}}
    @yield('head')
    @vite(['resources/css/adminDashboard.css', 'resources/js/adminDashboard.js'])
    @vite(['resources/css/adminNavPanel.css', 'resources/js/adminNavPanel.js'])
    @vite(['resources/css/adminProfile.css'])

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background: beige;">
            <div class="container">
                <img src="{{ asset('uploaded_files/e.jpg') }}" alt="Logo" width="40" height="40"
                    style="border-radius: 50%;">

                <a class="navbar-brand ml-3" href="{{ auth()->check() ? url('/home') : route('welcome') }}">
                    {{ config('app.name', 'Cooking Mamau') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class='nav-item'>
                                <a class="nav-link" href="{{ route('welcome') }}">
                                    {{ __('Baked Goods') }}
                                </a>
                            </li>
                            <li class='nav-item'>
                                <a class="nav-link" href="{{ route('discounts') }}">
                                    {{ __('Discounts') }}
                                </a>
                            </li>
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if (!auth()->user()->is_activated)
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <script>
                                    alert("Your account is deactivated. Please contact the administrator.");

                                    // Create a form element
                                    var form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = "{{ route('logout') }}";
                                    document.body.appendChild(form);

                                    // Create a hidden input field for the CSRF token
                                    var csrfToken = document.createElement('input');
                                    csrfToken.type = 'hidden';
                                    csrfToken.name = '_token';
                                    csrfToken.value = "{{ csrf_token() }}";
                                    form.appendChild(csrfToken);

                                    // Submit the form
                                    form.submit();
                                </script>
                            @endif
                            <li class='nav-item'>
                                <a class="nav-link" href="{{ route('welcome') }}">
                                    {{ __('Baked Goods') }}
                                </a>
                            </li>
                            <li class='nav-item'>
                                <a class="nav-link" href="{{ route('discounts') }}">
                                    {{ __('Discounts') }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (auth()->user()->is_admin)
                                        Administrator
                                    @else
                                        {{ Auth::user()->buyer->fname . ' ' . Auth::user()->buyer->lname }}
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="">{{ __('Dashboard (not working)') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="">{{ __('Users (not working)') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ route('ingredients') }}">{{ __('Baked Goods Ingredients') }}</a></li>
                                    @endif
                                    @if (!auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ route('welcome') }}">{{ __('Baked Goods') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ route('bakedgoods') }}">{{ __('Baked Goods') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ route('available_schedules') }}">{{ __('Available Schedule') }}</a></li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item" href="">{{ __('Orders (not working)') }}</a></li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item" href="">{{ __('Order Reviews (not working)') }}</a></li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item" href="{{ route('discounts') }}">{{ __('Discounts') }}</a></li>
                                    @endif
                                    @if (!auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{ route('user.profile') }}">{{ __('Account') }}</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}">{{ __('Account') }}</a></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4 container">
            @yield('content')
        </main>
    </div>
</body>

</html>
