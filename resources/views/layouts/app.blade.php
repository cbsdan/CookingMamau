@php
    use App\Models\BakedGood;
    use App\Models\CartItem;
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
    <script src="{{ asset('js/function.js') }}" defer></script>
    {{-- <script src="{{ asset('js/profile.js') }}"></script> --}}

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script src="{{ asset('js/datatable.js') }}"></script>
    {{-- <script src="{{ asset('js/admin.js') }}"></script> --}}
    @yield('head')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- @vite(['resources/css/adminDashboard.css', 'resources/js/adminDashboard.js']) --}}
    {{-- @vite(['resources/css/adminNavPanel.css', 'resources/js/adminNavPanel.js']) --}}
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
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Account Deactivated',
                                        text: 'Your account is deactivated. Please contact the administrator.',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        // Check if the user clicked the "OK" button
                                        if (result.isConfirmed) {
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
                                        }
                                    });
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
                                        <li><a class="dropdown-item" href="">{{ __('Dashboard (not working)') }}</a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item" href="{{route('users')}}">{{ __('Users') }}</a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item"
                                                href="{{ route('ingredients') }}">{{ __('Baked Goods Ingredients') }}</a>
                                        </li>
                                    @endif
                                    @if (!auth()->user()->is_admin)
                                        <li><a class="dropdown-item"
                                                href="{{ route('welcome') }}">{{ __('Baked Goods') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item"
                                                href="{{ route('bakedgoods') }}">{{ __('Baked Goods') }}</a></li>
                                    @endif
                                    @if (auth()->user()->is_admin)
                                        <li><a class="dropdown-item"
                                                href="{{ route('available_schedules') }}">{{ __('Available Schedule') }}</a>
                                        </li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item" href="">{{ __('Orders (not working)') }}</a>
                                        </li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item"
                                                href="">{{ __('Order Reviews (not working)') }}</a></li>
                                    @endif
                                    @if (auth()->user())
                                        <li><a class="dropdown-item"
                                                href="{{ route('discounts') }}">{{ __('Discounts') }}</a></li>
                                    @endif
                                    @if (!auth()->user()->is_admin)
                                        <li><a class="dropdown-item"
                                                href="{{ route('user.profile') }}">{{ __('Account') }}</a></li>
                                    @else
                                        <li><a class="dropdown-item"
                                                href="{{ route('admin.profile') }}">{{ __('Account') }}</a></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
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
        @if (auth()->check() && !auth()->user()->is_admin)
            <!-- Shopping Cart -->
            @php
                $userId = auth()->id();
                $cartItems = CartItem::where('id_user', $userId)->with('bakedGood')->get();
                $grandTotal = 0;
                $cartItemNumber = $cartItems->count();
            @endphp
            <div id="cart-items-container" class="cart flex-column justify-content-between" style="display:none;">
                <div id="cartItems">
                    <h2>My Cart</h2>
                    @foreach ($cartItems as $cartItem)
                        @php
                            $bakedGood = $cartItem->bakedGood;
                            $total = $cartItem->qty * $bakedGood->price;
                            $grandTotal += $total;
                        @endphp
                        <div class='cartItem d-flex flex-row align-items-center p-1 mb-2'
                            style="background: rgb(141, 244, 223); position: relative;">
                            <input type='hidden' name='idCart' value="{{ $cartItem->id }}" class='bakedGoodId'>
                            <div class="col-3 px-1">
                                <img src="{{ $bakedGood->images->firstWhere('is_thumbnail', true)->image_path ??
                                    ($bakedGood->images->first()->image_path ?? 'uploaded_files/default-profile.png') }}"
                                    class="img-thumbnail" style='height: 70px; width: 70px;'>
                            </div>
                            <div class="col-3 px-1 d-flex flex-column align-items-center justify-content-center">
                                <p class='mb-0'>{{ $bakedGood->name }}</p>
                                <p class='mb-0'>P<span class='price'>{{ $bakedGood->price }}</span></p>
                            </div>

                            {{-- <form action="{{route('cart.update', $cartItem->id)}}" method="POST" class="d-flex align-items-center px-1 text-center flex-1"> --}}
                            <form action="" method="POST"
                                class="d-flex align-items-center px-1 text-center flex-1">
                                @csrf
                                <div class="quantity-toggler on-cart btn minus">-</div>
                                <input type="number" name='qty'
                                    class="quantity-input border-0 m-0 d-flex align-items-center justify-content-center"
                                    style="width:30px; background: transparent;" min=1
                                    value="{{ $bakedGood->is_available ? $cartItem->qty : 0 }}"
                                    {{ $bakedGood->is_available ? '' : 'readonly' }}>
                                <div class="quantity-toggler on-cart btn add">+</div>
                            </form>

                            <div class="d-flex align-items-center col-2 px-1 text-start">
                                <p class="mb-0 w-100 text-start total">P{{ $total }}</p>
                            </div>
                            {{-- <form action="{{route('cart.remove')}}" method="POST" class="d-flex align-items-center col-1 px-1 text-center" style='position: relative;'> --}}
                            <div class="d-flex align-items-center col-1 px-1 text-center" style='display: flex; align-items: center; justify-content: center; position: absolute; width: 40px; height: 40px; border-radius: 50%; right: -20px; top: -20px; z-index: 100'>
                                @csrf
                                <input type="hidden" name='id' value='{{ $cartItem->id }}'>
                                <button name="submit" class="btn btn-danger mb-0 p-1 text-center delete-cart-item"
                                    data-id="{{ $cartItem->id }}"
                                    style="width: 30px; height: 30px; border-radius: 50%; padding: 0; margin: 0;">
                                    x
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div>
                    <div class="cart-total text-end">
                        Total: P<span class='grand-total'>{{ $grandTotal }}</span>
                    </div>
                    @if ($cartItems->count())
                        {{-- <form action="{{route('checkout')}}" method="GET"> --}}
                        <form action="{{route('checkout_page')}}" method="GET">
                            @csrf
                            <button type='submit' class="btn btn-success checkout-btn"
                                style="background:#ead660; color: black">Checkout</button>
                        </form>
                    @else
                        <a href='{{ route('welcome') }}' class="btn btn-success checkout-btn"
                            style="background:#ead660; color: black">Go to Baked Goods</a>
                    @endif
                </div>
            </div>
            <button id="open-btn" class="btn open-btn p-2 cart-toggle-visibility" style="background:#ead660">
                <img src='{{ asset('images/cart-icon.png') }}' alt="Cart"
                    style="width: 50px; height: 50px; border-radius: 50% !important;">
                <span class='dot-label'>
                    {{ $cartItemNumber }}
                </span>
            </button>
            <button id="close-btn" class="btn close-btn p-2 cart-toggle-visibility"
                style="display: none; background:#ead660; margin-right:350px;">
                <img src='{{ asset('images/cart-icon.png') }}' alt="Cart"
                    style="width: 50px; height: 50px; border-radius: 50% !important;">
                <span class='dot-label'>{{ $cartItemNumber }}</span>
            </button>

            <script>
                $(document).ready(function() {
                    if (window.location.pathname === '/home') {
                        document.getElementById('open-btn').style.display = 'none';
                        document.getElementById('close-btn').style.display = 'none';
                    }

                    $('#open-btn').click(function() {
                        $('#cart-items-container').show();
                        $('#open-btn').hide();
                        $('#close-btn').show();
                    });

                    $('#close-btn').click(function() {
                        $('#cart-items-container').hide();
                        $('#open-btn').show();
                        $('#close-btn').hide();
                        console.log('clicked close btn')
                    });
                });
            </script>
        @endif

    </div>
</body>

</html>
