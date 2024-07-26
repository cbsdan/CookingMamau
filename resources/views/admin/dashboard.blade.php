@extends ('layouts.app')

@section('content')
    <!-- ========================= Main ==================== -->
    <div class="main">
        <!-- ======================= Cards ================== -->
        <div class="cardBox grid grid-cols-3 gap-4 p-10">
            <div class="card" style='width: 350px; position: relative'>
                <div class="iconBx" style="position: absolute; bottom: -10px; right: 15px" >
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <div><h4 class='text-center'>Users Count</h4></div>
                <div class='d-flex align-items-center g-3'>
                    <div class="left flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">All Users</div>
                            <div class="all-user-count numbers"></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">Active Users</div>
                            <div class="active-user-count numbers"></div>
                        </div>
                    </div>
                    <div class="right flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">Deactivated</div>
                            <div class="deactivated-user-count numbers"></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">Admins</div>
                            <div class="admin-user-count numbers"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style='width: 350px; position: relative'>
                <div class="iconBx" style="position: absolute; bottom: -10px; right: 15px" >
                    <ion-icon name="cart-outline"></ion-icon>
                </div>
                <div><h4 class='text-center'>Sales Count <br><span style="font-size: 14px; color: darkgrey">(Delivered Orders)</span></h4></div>
                <div class='d-flex align-items-center g-3'>
                    <div class="left flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">All Sales</div>
                            <div class="all-sales-count numbers"></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">Last Schedule</div>
                            <div class="last-sched-date"></div>
                            <div class="last-sched-sales-count numbers"></div>
                        </div>
                    </div>
                    <div class="right flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">This Week</div>
                            <div class="this-week-sales-count numbers"></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">This Month</div>
                            <div class="this-month-sales-count numbers"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style='width: 350px; position: relative'>
                <div class="iconBx" style="position: absolute; bottom: -10px; right: 15px" >
                    <ion-icon name="fast-food-outline"></ion-icon>
                </div>
                <div><h4 class='text-center'>Earnings<br><span style="font-size: 14px; color: darkgrey">(Delivered Orders)</span></h4></div>
                <div class='d-flex align-items-center g-3'>
                    <div class="left flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">All Sales</div>
                            <div class="all-sales-earnings numbers" style='font-size: 25px'></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">Last Schedule</div>
                            <div class="last-sched-date"></div>
                            <div class="last-sched-sales-earnings numbers" style='font-size: 25px'></div>
                        </div>
                    </div>
                    <div class="right flex-1">
                        <div class="userStats p-2">
                            <div class="cardName">This Week</div>
                            <div class="this-week-sales-earnings numbers" style='font-size: 25px'></div>
                        </div>
                        <div class="userStats p-2">
                            <div class="cardName">This Month</div>
                            <div class="this-month-sales-earnings numbers" style='font-size: 25px'></div>
                        </div>
                    </div>
                </div>
                <span class='mt-1' style="font-size: 14px; color: darkgrey; font-weight: 600">(Earnings minus shipping fee and discount applied)</span>
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
                    <a href="{{route('orders')}}" class="btn">View All</a>
                </div>

                <table id="previousOrderTable">
                    <thead>
                        <tr>
                            <td>Baked Good/s</td>
                            <td>Customer</td>
                            <td>Payment (+shipping)</td>
                            <td>Schedule</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!--Content -->
                    </tbody>
                </table>
            </div>

            <!-- ================= New Customers ================ -->
            <div class="recentCustomers">
                <div class="cardHeader">
                    <h2>Active User</h2>
                </div>

                <table id="recentActiveUsersTable">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>User Information</th>
                        </tr>
                    </thead>
                    <tbody>
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
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    @vite(['resources/css/adminDashboard.css', 'resources/js/adminDashboard.js'])
@endsection
