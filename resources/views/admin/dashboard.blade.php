@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <h1>Admin Dashboard</h1>
        <!-- Logout Button -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf <!-- Include CSRF token -->
            <button type="submit" class="btn btn-link">Logout</button>
        </form>
        <!-- Example: Display a list of users -->
        <div class="card">
            <div class="card-header">
                Users List
            </div>
            <div class="card-body">
                <ul id="userList">
                    @foreach($users as $user)
                        <li>{{ $user->email }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

{{-- <?php
    // use App\Models\Order;
    // use App\Models\User;
    // use App\Models\BakedGood;
?>
@extends ('layouts.app')

@section ('content')
<div class="main">
    <h1>Dashboard</h1>
    <!-- ======================= Cards ================== -->
    <div class="cardBox grid grid-cols-3 gap-4 p-10">

        <div class="card">
            <div>
                <div class="numbers">{{Order::where('order_status', 'Delivered')->count()}}</div>
                <div class="cardName">Completed Orders</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cart-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">{{User::all()->count()}}</div>
                <div class="cardName">Users</div>
            </div>

            <div class="iconBx">
                <ion-icon name="chatbubbles-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">{{BakedGood::all()->count()}}</div>
                <div class="cardName">Baked Good Products</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
        </div>
    </div>

            <!-- Charts -->

    <h3>Reports</h3>
<div class="grid grid-cols-2 gap-10 p-10">
    <div class="chart-container">
        <canvas id="pie"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            console.log(chartData)
            // Convert collections to arrays
            const bakedGoods = chartData.labels;
            const quantity = chartData.data;


            // Debugging
            console.log(chartData);

            // Rendering the chart using Chart.js
            var ctx = document.getElementById('pie').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: bakedGoods,
                    datasets: [{
                        label: 'Sales',
                        data: quantity,
                        backgroundColor: 'rgba(187, 165, 230)',
                        borderColor: 'rgba(116, 82, 168)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

        });
    </script>
    </div>
    <div class="grid grid-rows-2 gap-4">
    <div class="chart-container ">
        <canvas id="bar"></canvas>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);

            const bakedGoods = chartData.map(data => data.name);
            const quantity = chartData.map(data => data.qty);

            var ctx = document.getElementById('bar').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bakedGoods,
                    datasets: [{
                        label: 'Baked Products',
                        data: quantity,
                        backgroundColor: 'rgba(187, 165, 230)',
                        borderColor: 'rgba(116, 82, 168)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    </div>
    <div class="chart-container">
    <canvas id="lineChart" class="w-full"></canvas>
    <script>
        const revenueData = @json($revenueData);

        const schedules = revenueData.map(data => data.schedule);
        const revenues = revenueData.map(data => data.revenue);
        ctx = document.getElementById('lineChart')
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: schedules,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
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
                    @php $latestOrders = Order::orderBy('created_at', 'desc')->take(10)->get()@endphp
                    <table>
                        <thead>
                            <tr>
                                <td>Order ID</td>
                                <td>Ordered Goods</td>
                                <td>Payment</td>
                                <td>Status</td>
                                <td>Delivery Schedule</td>
                                <td>View</td>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($latestOrders)
                                @foreach ($latestOrders as $order)
                                    @php $orderedGoods = $order->orderedGoods; $t @endphp

                                    <tr>
                                        <td>{{$order->id}}</td>
                                        <td>
                                            @if ($orderedGoods)
                                                @foreach($orderedGoods as $orderedGood)
                                                    <a href={{route('baked_goods.show', $orderedGood->meal->id)}}>{{$orderedGood->meal->name}}</a>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>P{{$order->payment->amount}}</td>
                                        <td><span class="status {{strtolower($order->order_status)}}">{{$order->order_status}}</span></td>
                                        <td>{{$order->schedule->schedule}}</td>
                                        <td><a href="{{route('user.order.show', $order->id)}}">View</a></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Users</h2>
                    </div>
                    @php $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get()@endphp

                    <table>
                        @if ($recentUsers)
                            @foreach ($recentUsers as $user)
                            <tr>
                                <td width="60px">
                                    <a href="{{route('admin.users.edit', $user->id)}}" class='btn'>
                                        <div class="imgBx"><img src="{{asset($user->profile_image_path ?? 'uploaded_files/default-profile.png')}}" alt=""></div>
                                    </a>
                                </td>
                                <td>
                                    @php $userInfo = optional($user->buyer); @endphp
                                    @if ($userInfo)
                                        <h4>{{ $userInfo->fname . " " . $userInfo->lname }}<br><span>{{ $userInfo->created_at }}</span></h4>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection --}}
