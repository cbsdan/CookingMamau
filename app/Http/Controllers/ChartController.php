<?php

namespace App\Http\Controllers;

use App\Models\AvailableSchedule;

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
}
