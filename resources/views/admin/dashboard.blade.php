@extends ('layouts.app')
@section ('content-1')
  <nav class="sidebar-content close">
    <header>

      <div class="image-text">
        <span class="image">
          <img src="{{asset('images/logo.png')}}" alt="logo">
        </span>

        <div class="text header-text">
          <span class="name"> Cooking Mamau</span>
          <span class="title">Administrator</span>
        </div>
      </div>

      <i class='bx bx-chevron-right toggle'></i>
      </header>

      <div class="menu-bar">
        <div class="menu">
          <ul class="menu-link">
            <li class="">
              <a href=""  >
              <i class='bx bxs-dashboard icon' ></i>
                  <span class="text nav-text">Dashboard</span>
              </a>
            </li>
            <li class="">
              <a href="" >
                <i class='bx bxs-baguette icon' ></i>
                  <span class="text nav-text">Baked Goods</span>
              </a>
              </li>
              <li class="">
              <a href="" >
              <i class='bx bxs-discount icon' ></i>
                  <span class="text nav-text">Discount</span>
              </a>
              </li>
              <li class="">
                  <a href="" >
                  <i class='bx bx-cart icon'></i>
                      <span class="text nav-text">Orders</span>
                  </a>
              </li>
              <li class="">
                  <a href="" >
                  <i class='bx bx-cheese icon'></i>
                      <span class="text nav-text">Ingredients</span>
                  </a>
              </li>
              <li class="">
                  <a href="" >
                  <i class='bx bx-calendar icon'></i>
                      <span class="text nav-text">Available Schedule</span>
                  </a>
              </li>
              <li class="">
                  <a href="" >
                  <i class='bx bx-comment-add icon'></i>
                      <span class="text nav-text">Order Reviews</span>
                  </a>
              </li>
              <li class="">
                  <a href="" >
                  <i class='bx bxs-user-account icon' ></i>
                      <span class="text nav-text">My account</span>
                  </a>
              </li>
    
          </ul>
        </div>
        <div class="bottom-content">
              <ul>
                  <li class="">
                      <a href="">
                          <i class='bx bx-log-out icon'></i>
                          <span class="text nav-text">Logout</span>
                      </a>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
@endsection
@section ('content')
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
                <div class="numbers">284</div>
                <div class="cardName">Comments</div>
            </div>

            <div class="iconBx">
                <ion-icon name="chatbubbles-outline"></ion-icon>
            </div>
        </div>

        <div class="card">
            <div>
                <div class="numbers">PHP 7,842</div>
                <div class="cardName">Earning</div>
            </div>

            <div class="iconBx">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
        </div>
    </div>

            <!-- Charts -->


<div class="grid grid-cols-2 gap-10 p-10">
    <div class="chart-container">
        <canvas id="pie"></canvas>
        
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('pie').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Sales',
                        data: [40, 59, 80, 81, 56, 55, 40],
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
            var ctx = document.getElementById('bar').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Sales',
                        data: [65, 59, 80, 81, 56, 55, 40],
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
    <canvas id="line" class="w-full"></canvas>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('line').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [{
                        label: 'Sales',
                        data: [65, 59, 80, 81, 56, 55, 40],
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
</div>
</div>
    


            <!-- ================ Order Details List ================= -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                        <a href="#" class="btn">View All</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Price</td>
                                <td>Payment</td>
                                <td>Status</td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Cookies</td>
                                <td>php 1200</td>
                                <td>Paid</td>
                                <td><span class="status delivered">Delivered</span></td>
                            </tr>

                            <tr>
                                <td>Cake</td>
                                <td>php 110</td>
                                <td>Due</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>

                            <tr>
                                <td>brownies</td>
                                <td>php 50</td>
                                <td>Paid</td>
                                <td><span class="status return">Return</span></td>
                            </tr>

                            <tr>
                                <td>Banana Bread</td>
                                <td>php 150</td>
                                <td>Due</td>
                                <td><span class="status inProgress">In Progress</span></td>
                            </tr>
                            <tr>
                                <td>Cookies</td>
                                <td>php 1200</td>
                                <td>Paid</td>
                                <td><span class="status delivered">Delivered</span></td>
                            </tr>

                            <tr>
                                <td>Cake</td>
                                <td>php 110</td>
                                <td>Due</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>

                            <tr>
                                <td>brownies</td>
                                <td>php 50</td>
                                <td>Paid</td>
                                <td><span class="status return">Return</span></td>
                            </tr>

                            <tr>
                                <td>Banana Bread</td>
                                <td>php 150</td>
                                <td>Due</td>
                                <td><span class="status inProgress">In Progress</span></td>
                            </tr>
                            
                            <tr>
                                <td>Cake</td>
                                <td>php 110</td>
                                <td>Due</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>

                            <tr>
                                <td>brownies</td>
                                <td>php 50</td>
                                <td>Paid</td>
                                <td><span class="status return">Return</span></td>
                            </tr>

                            <tr>
                                <td>Banana Bread</td>
                                <td>php 150</td>
                                <td>Due</td>
                                <td><span class="status inProgress">In Progress</span></td>
                            </tr>
                            
                            <tr>
                                <td>Cake</td>
                                <td>php 110</td>
                                <td>Due</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>

                            <tr>
                                <td>brownies</td>
                                <td>php 50</td>
                                <td>Paid</td>
                                <td><span class="status return">Return</span></td>
                            </tr>

                            <tr>
                                <td>Banana Bread</td>
                                <td>php 150</td>
                                <td>Due</td>
                                <td><span class="status inProgress">In Progress</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ================= New Customers ================ -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Customers</h2>
                    </div>

                    <table>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/bg2.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/bg2.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="{{asset('images/bg2.png')}}" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Jana <br> <span>Tabi-tabi</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="images/logo.png" alt=""></div>
                            </td>
                            <td>
                                <h4>Mau <br> <span>wawa</span></h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection