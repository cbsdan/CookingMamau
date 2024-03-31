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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" defer></script>

    <script src="{{ asset('js/script.js') }}" defer></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Cooking Mamau') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
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
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (auth()->user()->is_admin)
                                        Administrator
                                    @else
                                        {{ Auth::user()->buyer->fname . " " . Auth::user()->buyer->lname }}
                                    @endif                                
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            {{ __('Users') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('ingredients.index') }}">
                                            {{ __('Baked Goods Ingredients') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('baked_goods.index') }}">
                                            {{ __('Baked Goods') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('available_schedules.index') }}">
                                            {{ __('Available Schedule') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user())
                                        <a class="dropdown-item" href="{{ route('user.orders') }}">
                                            {{ __('Orders') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user())
                                        <a class="dropdown-item" href="{{ route('order_reviews.index') }}">
                                            {{ __('Order Reviews') }}
                                        </a>
                                    @endif  
                                    @if (auth()->user())
                                        <a class="dropdown-item" href="{{ route('discounts.index') }}">
                                            {{ __('Discounts') }}
                                        </a>
                                    @endif  
                                    @if (!auth()->user()->is_admin)
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            {{ __('Account') }}
                                        </a>    
                                    @else 
                                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                            {{ __('Account') }}
                                        </a>
                                    @endif  
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4 container">
            @yield('content')
        </main>
        @if (!auth()->check() || !auth()->user()->is_admin)
            <!-- Shopping Cart -->
            @php
                $cartItems = session()->get('cart', []);
                $grandTotal = 0;
            @endphp
            <div class="cart flex-column justify-content-between" style="display:none;" >
                <div id="cartItems">
                    <h2>My Cart</h2>
                    @foreach ($cartItems as $id => $details)
                        @php 
                            $bakedGood = BakedGood::where('id', $id)->first();

                            $total = $details['quantity'] * $details['price']; 
                            $grandTotal += $total;
                        @endphp
                        <div class='cartItem d-flex flex-row ailgn-items-center p-1 mb-1' style="background: rgb(230, 255, 250);">
                            <input type='hidden' name='bakedGoodId' value={{$id}} class='bakedGoodId'>
                            <div class="col-3 px-1">
                                <img src="{{$details['image_path']}}" alt="{{$details['name']}}" class="img-thumbnail" style='height: 70px; width: 70px;'>
                            </div>
                            <div class="col-3 px-1 d-flex flex-column ailgn-items-center justify-content-center">
                                <p class='mb-0'>{{$details['name']}}<p>
                                <p class='mb-0'>P<span class='price'>{{$details['price']}}</span><p>
                            </div>
                            
                            <div class=" d-flex align-items-center col-3 px-1 text-center">
                                <form action="{{route('cart.update', $id)}}" method="POST">
                                    @csrf
                                    <div class="quantity-toggler btn p-1 minus">-</div>
                                    <input type="text" name='qty' class="quantity-input border-0 d-flex align-items-center justify-content-center" style="width:30px; background: transparent;" min=1 value="{{$bakedGood->is_available ? $details['quantity'] : 0}}" {{$bakedGood->is_available ? "" : "readonly"}}>
                                    <div class="quantity-toggler btn p-1 add">+</div>
                                </form>
                            </div>
                            <div class=" d-flex align-items-center col-2 px-1 text-start">
                                <p class="mb-0 w-100 text-start total">P{{$total}}</p>
                            </div>
                            <form action="{{route('cart.remove')}}" method="POST" class=" d-flex align-items-center col-1 px-1 text-center">
                                @csrf
                                <input type="hidden" name='id' value='{{$id}}'>
                                <button type="submit" name='submit' class="btn mb-0 p-0 text-start ">{{"D"}}</button>
                            </form>
                        </div>

                    @endforeach
                </div>
                <div>
                    <div class="cart-total text-end">
                        Total: P<span class='grand-total'>{{$grandTotal}}</span> 
                    </div>
                    <form action="{{route('checkout')}}" method="GET">
                        @csrf
                        <button type='submit' class="btn btn-success checkout-btn">Checkout</button>
                    </form>
                </div>
                <!-- Open button -->
            </div>
            <button class="btn btn-primary open-btn cart-toggle-visibility">Open Cart</button>
            <!-- Close button -->
            <button class="btn btn-danger close-btn cart-toggle-visibility" style="display: none;">Close Cart</button>
        @endif

    </div>
</body>
</html>
