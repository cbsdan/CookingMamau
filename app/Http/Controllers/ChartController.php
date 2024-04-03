<?php

namespace App\Http\Controllers;

use App\Models\BakedGood;
use App\Models\OrderedGood;
use App\Models\AvailableSchedule;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function showRevenueChart()
    {
        $schedules = AvailableSchedule::with('orders')->get();

        $revenueData = [];

        foreach ($schedules as $schedule) {
            $scheduleRevenue = 0;

            foreach ($schedule->orders as $order) {
                $scheduleRevenue += $order->payment->amount;
            }

            $revenueData[] = [
                'schedule' => $schedule->schedule, 
                'revenue' => $scheduleRevenue,
            ];
        }

        return view('dashboard', compact('revenueData'));
    }

    public function showPopularProductsChart()
    {
        // Retrieve the IDs of successfully delivered orders
        $deliveredOrderIds = OrderedGood::whereHas('order', function ($query) {
            $query->where('order_status', 'Delivered');
        })->pluck('id')->toArray();

        // Retrieve the counts of ordered goods grouped by product ID
        $productCounts = OrderedGood::whereIn('id', $deliveredOrderIds)
            ->select('baked_good_id', DB::raw('count(*) as count'))
            ->groupBy('baked_good_id')
            ->orderByDesc('count')
            ->take(5) // Get top 5 most ordered products
            ->get();

        // Retrieve the details of the top products
        $topProducts = [];
        foreach ($productCounts as $productCount) {
            $product = BakedGood::find($productCount->baked_good_id);
            if ($product) {
                $topProducts[] = [
                    'name' => $product->name,
                    'quantity' => $productCount->count,
                ];
            }
        }

        // Prepare data for the chart
        $chartData = [
            'labels' => collect($topProducts)->pluck('name'),
            'data' => collect($topProducts)->pluck('quantity'),
        ];

        // Return the view with chart data
        return view('popular-products-chart', compact('chartData'));
    }
}
