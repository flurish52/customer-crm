<?php

namespace App\Http\Controllers;

use App\Models\ActivityWithClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityWithClientController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:customers,id',
            'type' => 'required|string|in:call,email,note,meeting',
            'content' => 'required|string|max:1000',
            'date' => 'required|date',
        ]);

        $activity = ActivityWithclient::create([
            'customer_id' => $validated['client_id'],
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


    public function jobActivityStore(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:customers,id',
            'job_id' => 'required|integer|exists:customer_jobs,id',
            'type' => 'required|string|in:call,email,note,meeting',
            'content' => 'required|string|max:1000',
            'date' => 'required|date',
        ]);

        $activity = ActivityWithclient::create([
            'customer_id' => $validated['client_id'],
            'job_id' => $validated['job_id'],
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


    public function update(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:email,call,note,meeting',
            'content' => 'required|string',
            'date' => 'required|date',
            'id' => 'required|integer|exists:prospect_activity_logs,id',
            'client_id' => 'required|integer|exists:customers,id',
        ]);

        $activity = ActivityWithClient::find($validated['id']);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }
        $activity->type = $validated['type'];
        $activity->content = $validated['content'];
        $activity->activity_date = $validated['date'];
        $activity->customer_id = $validated['client_id'];
        $activity->save();

        return response()->json([
            'message' => 'Activity updated successfully',
            'activity' => $activity
        ]);
    }



    public function destroy(ActivityWithClient $activity)
    {
        if (auth()->id() !== $activity->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }















    public function jobActivityUpdate(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:email,call,note,meeting',
            'content' => 'required|string',
            'date' => 'required|date',
            'id' => 'required|integer|exists:prospect_activity_logs,id',
            'client_id' => 'required|integer|exists:customers,id',
            'job_id' => 'required|integer|exists:customer_jobs,id',
        ]);

        $activity = ActivityWithClient::find($validated['id']);

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }
        $activity->type = $validated['type'];
        $activity->content = $validated['content'];
        $activity->activity_date = $validated['date'];
        $activity->customer_id = $validated['client_id'];
        $activity->job_id = $validated['job_id'];
        $activity->save();

        return response()->json([
            'message' => 'Activity updated successfully',
            'activity' => $activity
        ]);
    }



    public function jobActivityDestroy(ActivityWithClient $activity)
    {
        if (auth()->id() !== $activity->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted successfully']);
    }
}
