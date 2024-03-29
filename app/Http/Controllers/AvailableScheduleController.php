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
     * @return \Illuminate\Http\Response
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
        
        return view('available_schedules.index', compact('upcomingSchedules', 'pastSchedules'));
    }
    

    /**
     * Show the form for creating a new available schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('available_schedules.create');
    }

    /**
     * Store a newly created available schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the submitted data
        $request->validate([
            'schedules.*' => 'required|date',
        ]);

        // Process each schedule date and store it in the database
        foreach ($request->schedules as $schedule) {
            AvailableSchedule::create([
                'schedule' => $schedule,
            ]);
        }

        // Redirect back to the index page with a success message
        return redirect()->route('available_schedules.index')->with('success', 'Available schedules created successfully.');
    }

    /**
     * Show the form for editing the specified available schedule.
     *
     * @param  \App\Models\AvailableSchedule  $availableSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(AvailableSchedule $availableSchedule)
    {
        $availableSchedules = AvailableSchedule::all();
        return view('available_schedules.edit', compact('availableSchedule'));
    }

    /**
     * Update the specified available schedule in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AvailableSchedule  $availableSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AvailableSchedule $availableSchedule)
    {
        // Validate the request data
        $request->validate([
            'schedule' => ['required', 'date']
        ]);

        // Update the available schedule
        $availableSchedule->update($request->all());

        // Redirect after updating the available schedule
        return redirect()->route('available_schedules.index')
                         ->with('success', 'Available schedule updated successfully.');
    }

    /**
     * Remove the specified available schedule from storage.
     *
     * @param  \App\Models\AvailableSchedule  $availableSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(AvailableSchedule $availableSchedule)
    {
        // Delete the available schedule
        $availableSchedule->delete();

        // Redirect after deleting the available schedule
        return redirect()->route('available_schedules.index')
                         ->with('success', 'Available schedule deleted successfully.');
    }
}
