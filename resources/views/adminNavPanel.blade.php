@vite(['resources/css/adminNavPanel.css', 'resources/js/adminNavPanel.js'])


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<body>
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

</body>
</html>