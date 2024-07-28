$(document).ready(function() {
    //Fetching User Info
    $.ajax({
    type: "GET",
    url: `/api/dashboard/userInfo`,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType: "json",
    success: function (data) {
        console.log(data);

        // Update counts
        $('.all-user-count').html(data.totalUserCount);
        $('.active-user-count').html(data.activatedUserCount);
        $('.deactivated-user-count').html(data.deactivatedUserCount);
        $('.admin-user-count').html(data.adminUserCount);

        data.activatedUsers.reverse();

        $('#recentActiveUsersTable tbody').html(`<tr ><td colspan="2" class='text-center'>No Users yet</td></tr>`);

        // Generate table rows for activated users
        if (data.activatedUsers.length > 0) {
            let activatedUsersRows = '';
            data.activatedUsers.forEach(user => {
                activatedUsersRows += `
                    <tr>
                        <td width="60px">
                            <a href="#" class='btn'>
                                <div class="imgBx">
                                    <img src="/${user.profile_image_path ? user.profile_image_path : 'uploaded_files/default-profile.png'}" alt="img">
                                </div>
                            </a>
                        </td>
                        <td>
                            <h4>${user.buyer.fname} ${user.buyer.lname}<br><span>${user.email}</span></h4>
                        </td>
                    </tr>
                `;
            });

            // Append rows to the table body
            $('#recentActiveUsersTable tbody').html(activatedUsersRows);
        }
    },
    error: function (error) {
        console.log(error);
    }
    })

    //Fetching Sales Stats
    $.ajax({
    type: "GET",
    url: `/api/dashboard/salesStats`,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType: "json",
    success: function (data) {
        console.log(data);
        if (data){
            $('.all-sales-count').html(data.allSalesCount)
            $('.last-sched-sales-count').html(data.lastSchedSalesCount)
            if (data.lastSchedSales.schedule) {
                const scheduleDatetime = new Date(data.lastSchedSales.schedule);

                const formattedDate = scheduleDatetime.toLocaleDateString(); // e.g., "7/24/2024"
                const formattedTime = scheduleDatetime.toLocaleTimeString(); // e.g., "2:30:00 PM"

                const formattedDatetime = formattedDate + ' ' + formattedTime;

                // Set the formatted datetime in the HTML
                $('.last-sched-date').html(formattedDatetime);
            }
            $('.this-week-sales-count').html(data.thisWeekSalesCount)
            $('.this-month-sales-count').html(data.thisMonthSalesCount)
        }
    },
    error: function (error) {
        console.log(error);
    }
    })


    //Fetching Sales Stats
    $.ajax({
    type: "GET",
    url: `/api/dashboard/salesEarnings`,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType: "json",
    success: function (data) {
        console.log(data);
        if (data){
            $('.all-sales-earnings').html(`₱${data.totalEarnings.toFixed(2)}`)
            $('.last-sched-sales-earnings').html(`₱${data.lastScheduleEarnings.toFixed(2)}`)
            $('.this-week-sales-earnings').html(`₱${data.thisWeekEarnings.toFixed(2)}`)
            $('.this-month-sales-earnings').html(`₱${data.thisMonthEarnings.toFixed(2)}`)
        }
    },
    error: function (error) {
        console.log(error);
    }
    })


    //Fetching Top Baked Goods
    $.ajax({
    type: "GET",
    url: `/api/dashboard/topBakedGoods`,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType: "json",
    success: function (response) {
        if (response) {
            // Extract the names and counts from the response
            let labels = response.map(item => item.baked_good.name);
            let data = response.map(item => item.count);

            // Prepare the colors for the pie chart
            let backgroundColors = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 199, 199, 0.2)'
            ];
            let borderColors = [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)'
            ];

            // Create the pie chart
            var ctx = document.getElementById('pie').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Top 7 Baked Goods Sales'
                        }
                    }
                }
            });
        } else {
            console.log("No data available");
        }

    },
    error: function (error) {
        console.log(error);
    }
    })

    //Fetch the last seven schedule and their sales count
    $.ajax({
    type: "GET",
    url: `/api/dashboard/latestSevenScheduleSales`,
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType: "json",
    success: function (data) {
        console.log(data);

        if (data){
            data.reverse();
            // Extract labels and data for the chart
            var labels = [];
            var salesData = [];
            var salesEarning = []
            data.forEach(function(scheduleSales) {
                labels.push(scheduleSales.schedule.schedule); // Assuming schedule.schedule is the date
                salesData.push(scheduleSales.salesCount);
                salesEarning.push(scheduleSales.earnings);
            });

            // Bar chart for Latest Seven Schedule Sales Count
            var ctx = document.getElementById('bar').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Latest Seven Schedule Sales',
                        data: salesData,
                        backgroundColor: 'rgba(232, 197, 0, 0.7)',
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

            var ctx = document.getElementById('line').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Latest Seven Schedule Earnings',
                        data: salesEarning,
                        backgroundColor: 'rgba(232, 197, 0, 0.7)',
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
        } else {
            console.log('No data to be displayed')
        }
    },
    error: function (error) {
        console.log(error);
    }
    })

    //Fetch the previous orders
    $.ajax({
        type: "GET",
        url: `/api/dashboard/previousOrder`,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "json",
        success: function (data) {
            console.log(data);
            data.reverse();
            let orderColumn = `<tr ><td colspan="5" class='text-center'>No orders yet</td></tr>`;

            if (data.length > 0) {
                orderColumn = data.map((order) => {
                    // Define the class based on order status
                    let statusClass = '';
                    switch (order.orderStatus) {
                        case 'In Progress':
                            statusClass = 'status inProgress';
                            break;
                        case 'Out for Delivery':
                            statusClass = 'status out-for-delivery';
                            break;
                        case 'Canceled':
                            statusClass = 'status canceled';
                            break;
                        case 'Pending':
                            statusClass = 'status pending';
                            break;
                        case 'Delivered':
                            statusClass = 'status delivered';
                            break;
                        default:
                            statusClass = '';
                            break;
                    }

                    return `<tr>
                                <td>${order.productName}</td>
                                <td>${order.customerName}</td>
                                <td>₱${order.price}</td>
                                <td>${order.schedule}</td>
                                <td><span class="${statusClass}">${order.orderStatus}</span></td>
                            </tr>`;
                }).join('');

                $('#previousOrderTable tbody').html(orderColumn);
            } else {
                $('#previousOrderTable tbody').html(orderColumn);
            }
        },
        error: function (error) {
            console.log(error);
        }
    })
});
