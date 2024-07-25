// import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    console.log('dashboard.js');
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
     //bar chart
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
     //piechart
     var ctx = document.getElementById('pie').getContext('2d');
     var myChart = new Chart(ctx, {
         type: 'pie',
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

// document.addEventListener('DOMContentLoaded', function () {
//     if (window.chartData) {
//         var labels = window.chartData.labels;
//         var data = window.chartData.data;

//         var ctx = document.getElementById('pie').getContext('2d');
//         new Chart(ctx, {
//             type: 'pie', // Chart type
//             data: {
//                 labels: labels,
//                 datasets: [{
//                     label: 'Top Products',
//                     data: data,
//                     backgroundColor: [
//                         'rgba(255, 99, 132, 0.2)',
//                         'rgba(54, 162, 235, 0.2)',
//                         'rgba(255, 206, 86, 0.2)',
//                         'rgba(75, 192, 192, 0.2)',
//                         'rgba(153, 102, 255, 0.2)'
//                     ],
//                     borderColor: [
//                         'rgba(255, 99, 132, 1)',
//                         'rgba(54, 162, 235, 1)',
//                         'rgba(255, 206, 86, 1)',
//                         'rgba(75, 192, 192, 1)',
//                         'rgba(153, 102, 255, 1)'
//                     ],
//                     borderWidth: 1
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 plugins: {
//                     legend: {
//                         position: 'top',
//                     },
//                     tooltip: {
//                         callbacks: {
//                             label: function(tooltipItem) {
//                                 return tooltipItem.label + ': ' + tooltipItem.raw;
//                             }
//                         }
//                     }
//                 }
//             }
//         });
//     }
// });

