<?php

namespace App\Http\Controllers;

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
        // Retrieve all available schedules
        $schedules = AvailableSchedule::all();

        // Return the view with the available schedules
        return view('available_schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new available schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return the view for creating a new available schedule
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
        // Validate the request data
        $request->validate([
            'schedule' => ['required', 'date']
            // Add validation rules for other fields if necessary
        ]);

        // Create the available schedule
        AvailableSchedule::create($request->all());

        // Redirect after creating the available schedule
        return redirect()->route('available_schedules.index')
                         ->with('success', 'Available schedule created successfully.');
    }

    /**
     * Show the form for editing the specified available schedule.
     *
     * @param  \App\Models\AvailableSchedule  $availableSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(AvailableSchedule $availableSchedule)
    {
        // Return the view for editing the available schedule
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
            // Add validation rules for other fields if necessary
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
