<?php

namespace App\Http\Controllers;

use App\Models\ProspectActivityLog;
use App\Http\Requests\StoreProspectActivityLogRequest;
use App\Http\Requests\UpdateProspectActivityLogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectActivityLogController extends Controller
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prospect_id' => 'required|integer|exists:prospects,id',
            'type' => 'required|string|in:call,email,note,meeting',
            'content' => 'required|string|max:1000',
            'date' => 'required|date',
        ]);

        $activity = ProspectActivityLog::create([
            'prospect_id' => $validated['prospect_id'],
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'content' => $validated['content'],
            'activity_date' => $validated['date'],
        ]);

        return response()->json([
            'message' => 'Activity added successfully',
            'activity' => $activity,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProspectActivityLog $prospectActivityLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProspectActivityLog $prospectActivityLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:email,call,note,meeting',
            'content' => 'required|string',
            'date' => 'required|date',
            'id' => 'required|integer|exists:prospect_activity_logs,id',
            'prospect_id' => 'required|integer|exists:prospects,id',
        ]);

        $activity = ProspectActivityLog::find($validated['id']);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }

        $activity->type = $validated['type'];
        $activity->content = $validated['content'];
        $activity->activity_date = $validated['date'];
        $activity->prospect_id = $validated['prospect_id'];
        $activity->save();

        return response()->json([
            'message' => 'Activity updated successfully',
            'activity' => $activity
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function updateStatus(ProspectActivityLog $prospectActivityLog)
    {

    }
    public function destroy(ProspectActivityLog $activity)
    {
        if (auth()->id() !== $activity->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }

}
