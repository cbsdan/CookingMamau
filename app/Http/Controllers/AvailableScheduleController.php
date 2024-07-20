<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AvailableSchedule;


class AvailableScheduleController extends Controller
{
    /**
     * Display a listing of the available schedules.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all schedules
        $allSchedules = AvailableSchedule::all();

        // Separate upcoming and past schedules
        $upcomingSchedules = $allSchedules->filter(function ($schedule) {
            return Carbon::parse($schedule->schedule)->isAfter(now());
        });

        $pastSchedules = $allSchedules->filter(function ($schedule) {
            return Carbon::parse($schedule->schedule)->isBefore(now());
        });

        // Return the schedules as a JSON response
        return response()->json([
            'upcomingSchedules' => $upcomingSchedules,
            'pastSchedules' => $pastSchedules
        ]);
    }


    /**
     * Store a newly created available schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the submitted data
        $request->validate([
            'schedule_date' => 'required|date',
        ]);

        // Create the schedule
        $schedule = AvailableSchedule::create([
            'schedule' => $request->schedule_date,
        ]);

        return response()->json([
            'success' => 'Available schedule created successfully.',
            'schedule' => $schedule,
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified available schedule.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $availableSchedule = AvailableSchedule::find($id);

        if ($availableSchedule) {
            return response()->json($availableSchedule);
        } else {
            return response()->json(['error' => 'Schedule not found.'], 404);
        }
    }

    /**
     * Update the specified available schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'schedule_date' => 'required|date'
        ]);

        // Find and update the available schedule
        $availableSchedule = AvailableSchedule::find($id);

        if ($availableSchedule) {
            $availableSchedule->update(['schedule' => $request->schedule_date]);

            return response()->json([
                'success' => 'Available schedule updated successfully.',
                'schedule' => $availableSchedule,
                'status' => 200
            ]);
        } else {
            return response()->json(['error' => 'Schedule not found.'], 404);
        }
    }

    /**
     * Remove the specified available schedule from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $availableSchedule = AvailableSchedule::find($id);

        if ($availableSchedule) {
            $availableSchedule->delete();

            return response()->json([
                'success' => 'Available schedule deleted successfully.',
                'status' => 200
            ]);
        } else {
            return response()->json(['error' => 'Schedule not found.'], 404);
        }
    }
}
