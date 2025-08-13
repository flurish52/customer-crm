<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        $validated = $request->validated();
        $job = Job::findorFail($validated['jobId']);
        $activity = Activity::create([
            'user_id' => auth()->id(),
            'subject_type' => 'App\Models\Job',
            'subject_id' => $validated['jobId'],
            'customer_id' => $job->customer_id,
            'type' => 'payment',
            'changes' => json_encode([
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'method' => $validated['method'],
                'notes' => $validated['notes']
            ])
        ]);

        return response()->json([
            'message' => 'Payment recorded successfully',
            'activity' => Activity::where('user_id', Auth::user()->id)->where('customer_id', $job->customer_id)->get(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
