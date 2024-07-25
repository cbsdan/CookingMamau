<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
use App\Models\User; // Import User model

class DashboardController extends Controller
{
    public function usersInfo()
    {
        // Fetch all users
        $allUsers = User::get();

        // Fetch all activated users
        $activatedUsers = User::with('buyer')->where('is_activated', 1)->where('is_admin', 0)->get();

        // Fetch all deactivated users
        $deactivatedUsers = User::with('buyer')->where('is_activated', 0)->where('is_admin', 0)->get();

        // Fetch all admin users
        $adminUsers = User::where('is_admin', 1)->get();

        // Count the total number of users
        $totalUserCount = $allUsers->count();

        // Count the number of activated users
        $activatedUserCount = $activatedUsers->count();

        // Count the number of deactivated users
        $deactivatedUserCount = $deactivatedUsers->count();

        // Count the number of admin users
        $adminUserCount = $adminUsers->count();

        // Return the data in JSON format
        return response()->json([
            'allUsers' => $allUsers,
            'activatedUsers' => $activatedUsers,
            'deactivatedUsers' => $deactivatedUsers,
            'adminUsers' => $adminUsers,
            'totalUserCount' => $totalUserCount,
            'activatedUserCount' => $activatedUserCount,
            'deactivatedUserCount' => $deactivatedUserCount,
            'adminUserCount' => $adminUserCount
        ]);
    }

    public function salesStats() {
        // Fetch all sales where the order status is 'Delivered'
        $allSales = Order::with('orderedGoods', 'schedule', 'discount', 'orderedGoods')->where('order_status', 'Delivered')->get();
        $allSalesCount = $allSales->count();

        // Get the most recent schedule
        $lastSchedule = AvailableSchedule::orderBy('schedule', 'desc')->first();

        if ($lastSchedule) {
            // Sales for the last schedule
            $lastSchedSales = Order::with('orderedGoods', 'schedule', 'discount', 'orderedGoods')
                ->where('order_status', 'Delivered')
                ->where('id_schedule', $lastSchedule->id)
                ->get();
            $lastSchedSalesCount = $lastSchedSales->count();
        } else {
            $lastSchedSales = collect();
            $lastSchedSalesCount = 0;
        }

        // Sales for this week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $thisWeekSales = Order::with('orderedGoods', 'schedule', 'discount', 'orderedGoods')
            ->where('order_status', 'Delivered')
            ->whereHas('schedule', function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('schedule', [$startOfWeek, $endOfWeek]);
            })
            ->get();
        $thisWeekSalesCount = $thisWeekSales->count();

        // Sales for this month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $thisMonthSales = Order::with('orderedGoods', 'schedule', 'discount', 'orderedGoods')
            ->where('order_status', 'Delivered')
            ->whereHas('schedule', function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('schedule', [$startOfMonth, $endOfMonth]);
            })
            ->get();
        $thisMonthSalesCount = $thisMonthSales->count();

        return response()->json([
            'allSales' => $allSales,
            'allSalesCount' => $allSalesCount,
            'lastSchedSales' => $lastSchedSales,
            'lastSchedSalesCount' => $lastSchedSalesCount,
            'thisWeekSales' => $thisWeekSales,
            'thisWeekSalesCount' => $thisWeekSalesCount,
            'thisMonthSales' => $thisMonthSales,
            'thisMonthSalesCount' => $thisMonthSalesCount
        ]);
    }

    public function salesEarnings() {
        // Fetch all delivered orders
        $allOrders = Order::with('payments', 'discount', 'schedule', 'orderedGoods')->where('order_status', 'Delivered')->get();
        $allOrdersCount = $allOrders->count();

        $totalEarnings = 0;
        $lastScheduleEarnings = 0;
        $thisWeekEarnings = 0;
        $thisMonthEarnings = 0;

        // Get the most recent schedule
        $lastSchedule = AvailableSchedule::orderBy('schedule', 'desc')->first();

        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        foreach ($allOrders as $order) {
            $orderEarnings = 0;

            // Calculate earnings for each baked good in the order
            foreach ($order->orderedGoods as $orderedGood) {
                $orderEarnings += $orderedGood->pivot->price_per_good * $orderedGood->pivot->qty;
            }

            // Deduct discount if applicable
            if ($order->discount) {
                $discountAmount = ($orderEarnings * ($order->discount->percent / 100));
                $orderEarnings -= $discountAmount;
            }

            // Add the earnings for this order to the total earnings
            $totalEarnings += $orderEarnings;

            // Calculate earnings for the last schedule
            if ($lastSchedule && $order->id_schedule == $lastSchedule->id) {
                $lastScheduleEarnings += $orderEarnings;
            }

            // Calculate earnings for this week
            if ($order->created_at >= $currentWeekStart && $order->created_at <= $currentWeekEnd) {
                $thisWeekEarnings += $orderEarnings;
            }

            // Calculate earnings for this month
            if ($order->created_at >= $currentMonthStart && $order->created_at <= $currentMonthEnd) {
                $thisMonthEarnings += $orderEarnings;
            }
        }

        return response()->json([
            'totalEarnings' => $totalEarnings,
            'lastScheduleEarnings' => $lastScheduleEarnings,
            'thisWeekEarnings' => $thisWeekEarnings,
            'thisMonthEarnings' => $thisMonthEarnings,
        ]);
    }

    public function topBakedGoods() {
        // Fetch all delivered orders along with the ordered goods
        $orders = Order::with('orderedGoods')->where('order_status', 'Delivered')->get();

        // If there are no orders, return null
        if ($orders->isEmpty()) {
            return response()->json(null);
        }

        // Create an associative array to store the count of orders for each baked good
        $bakedGoodCounts = [];

        // Iterate through each order and count the occurrences of each baked good
        foreach ($orders as $order) {
            foreach ($order->orderedGoods as $bakedGood) {
                if (!isset($bakedGoodCounts[$bakedGood->id])) {
                    $bakedGoodCounts[$bakedGood->id] = [
                        'baked_good' => $bakedGood,
                        'count' => 0
                    ];
                }
                $bakedGoodCounts[$bakedGood->id]['count'] += $bakedGood->pivot->qty;
            }
        }

        // Sort the baked goods by the count in descending order
        usort($bakedGoodCounts, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        // Get the top seven baked goods or fewer if there are not enough
        $topSevenBakedGoods = array_slice($bakedGoodCounts, 0, 7);

        return response()->json($topSevenBakedGoods);
    }

    public function latestSevenScheduleSales() {
        $schedules = AvailableSchedule::with(['orders' => function($query) {
            $query->where('order_status', 'Delivered')->with('orderedGoods', 'discount');
        }])
        ->orderBy('schedule', 'desc')
        ->take(7)
        ->get();

        $scheduleSales = [];

        foreach ($schedules as $schedule) {
            $deliveredOrders = $schedule->orders;

            $salesCount = $deliveredOrders->count();
            $earnings = 0;

            foreach ($deliveredOrders as $order) {
                $orderEarnings = 0;

                // Calculate earnings for each baked good in the order
                foreach ($order->orderedGoods as $orderedGood) {
                    $orderEarnings += $orderedGood->pivot->price_per_good * $orderedGood->pivot->qty;
                }

                // Deduct discount if applicable
                if ($order->discount) {
                    $discountAmount = ($orderEarnings * ($order->discount->percent / 100));
                    $orderEarnings -= $discountAmount;
                }

                // Add the earnings for this order to the total earnings for the schedule
                $earnings += $orderEarnings;
            }

            $scheduleSales[] = [
                'schedule' => $schedule,
                'salesCount' => $salesCount,
                'earnings' => $earnings,
            ];
        }

        return response()->json($scheduleSales);
    }

    public function previousOrder() {
        $orders = Order::with(['orderedGoods', 'discount', 'buyer', 'schedule'])->get();

        $previousOrders = [];

        foreach ($orders as $order) {
            $total = $order->orderedGoods->reduce(function ($sum, $item) {
                return $sum + ($item->pivot->qty * $item->pivot->price_per_good);
            }, 0.0);

            $shippingCost = floatval($order->shipping_cost);
            $discountAmount = $order->discount ? (($order->discount->percent / 100) * $total) : 0.0;

            $grandTotal = ($total + $shippingCost - $discountAmount);

            // Generate the product name display
            $orderedGoodsNames = $order->orderedGoods->pluck('name');
            $productName = $orderedGoodsNames->first();
            $additionalItemsCount = $orderedGoodsNames->count() - 1;
            if ($additionalItemsCount > 0) {
                $productName .= ' + ' . $additionalItemsCount . ' item' . ($additionalItemsCount > 1 ? 's' : '');
            }

            // Format the price with discount indication
            $price = number_format($grandTotal, 2);
            if ($discountAmount > 0) {
                $price .= ' with discount';
            }

            $previousOrders[] = [
                'productName' => $productName,
                'customerName' => $order->buyer_name,
                'price' => $price,
                'schedule' => $order->schedule->schedule,
                'orderStatus' => $order->order_status,
            ];
        }

        return response()->json($previousOrders);
    }

    public function adminAction(Request $request)
    {
        // Implement your admin actions here
        return response()->json(['message' => 'Admin action executed successfully.']);
    }

    public function showOrderSummary()
    {
        $orderSummary = Order::getOrderSummary();
        return view('admin.dashboard', compact('orderSummary'));
    }

}
