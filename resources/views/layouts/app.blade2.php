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
    <link href="//cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" defer></script>
    <script src="//cdn.datatables.net/2.0.3/js/dataTables.min.js" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>

    <!-- =========== Scripts =========  -->
    <script src="{{asset('js/main.js')}}" defer> </script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js" defer></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js" defer></script>

    @vite(['resources/css/adminProfile.css'])


</head>
<body>
    <div id="app">
        @yield('content-1')

        <main class="py-4 container">
            @yield('content')
        </main>

        @if (!auth()->check() || !auth()->user()->is_admin)
            <!-- Shopping Cart -->
            @php
                $cartItems = session()->get('cart', []);
                $grandTotal = 0;
                $cartItemNumber = 0;
            @endphp
            <div class="cart flex-column justify-content-between" style="display:none;" >
                <div id="cartItems">
                    <h2>My Cart</h2>
                    @foreach ($cartItems as $id => $details)
                        @php 
                            $cartItemNumber++;
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
                                <button type="submit" name='submit' class="btn mb-0 p-0 text-start ">{{"R"}}</button>
                            </form>
                        </div>

                    @endforeach
                </div>
                <div>
                    <div class="cart-total text-end">
                        Total: P<span class='grand-total'>{{$grandTotal}}</span> 
                    </div>
                    @if ($cartItems)
                        <form action="{{route('checkout')}}" method="GET">
                            @csrf
                            <button type='submit' class="btn btn-success checkout-btn">Checkout</button>
                        </form>
                    @else
                        <a href='{{route('welcome')}}' class="btn btn-success checkout-btn">Go to Baked Goods</a>

                    @endif
                </div>
                <!-- Open button -->
            </div>
            <button class="btn btn-primary open-btn cart-toggle-visibility">Open Cart <span class='dot-label'>{{$cartItemNumber}}</span></button>
            <!-- Close button -->
            <button class="btn btn-danger close-btn cart-toggle-visibility" style="display: none;">Close Cart <span class='dot-label'>{{$cartItemNumber}}</span></button>
        @endif
    </div>
</body>
</html>
