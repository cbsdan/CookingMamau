@extends ('layouts.app')

@section('content')
    <!-- ========================= Main ==================== -->
    <div class="main">



        <!-- ======================= Cards ================== -->
        <div class="cardBox grid grid-cols-3 gap-4 p-10">

            <div class="card">
                <div>
                    <div class="numbers">80</div>
                    <div class="cardName">Sales</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="cart-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">{{ $userCount }}</div>
                    <div class="cardName">User</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers">PHP 7,842</div>
                    <div class="cardName">Earning</div>
                </div>

                <div class="iconBx">
                    <ion-icon name="fast-food-outline"></ion-icon>
                </div>
            </div>
        </div>

        <!-- Charts -->


        <div class="grid grid-cols-2 gap-10 p-10">
            <div class="chart-container">
                <canvas id="pie"></canvas>

            </div>
            <div class="grid grid-rows-2 gap-4">
                <div class="chart-container ">
                    <canvas id="bar"></canvas>

                </div>
                <div class="chart-container">
                    <canvas id="line" class="w-full"></canvas>
                </div>
            </div>
        </div>



        <!-- ================ Order Details List ================= -->
        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>Recent Orders</h2>
                    <a href="#" class="btn">View All</a>
                </div>

                <table id="">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>Price</td>
                            <td>Payment</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>Banana Bread</td>
                        <td>php 150</td>
                        <td>Due</td>
                        <td><span class="status inProgress">In Progress</span></td>
                    </tr>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <!-- ================= New Customers ================ -->
            <div class="recentCustomers">
                <div class="cardHeader">
                    <h2>Recent Registered User</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>User Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td width="60px">
                                    <a href="#" class='btn'>
                                        <div class="imgBx">
                                            <img src="{{ ($user->profile_image_path) ? $user->profile_image_path : 'uploaded_files/default-profile.png' }}" alt="">
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <h4>{{ $user->buyer->fname }} {{ $user->buyer->lname }}<br><span>{{ $user->email }}</span></h4>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>


    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@endsection

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="{{ asset('js/dashboard.js') }}" defer></script>
    @vite(['resources/css/adminDashboard.css', 'resources/js/adminDashboard.js'])
@endsection
