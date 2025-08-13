<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use http\Env\Response;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($customer_id)
    {
        $user_id = Auth::user()->id;
        return $jobs = Job::orderBy('created_at', 'DESC')->where('user_id', auth()->id())->where('customer_id', $customer_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getJobs()
    {
        $user_id = auth()->user()->id;
        return $jobs = Job::orderBy('created_at', 'DESC')->with('activities', 'customer')->where('user_id', $user_id)->get();
    }

    public function create()
    {
        //
    }


    public function updateJobStatus(Request $request, Job $job)
    {
        $validated = $request->validate([
            'type' => 'required|in:complete_job',
            'satisfaction_score' => 'required|integer|min:1|max:5',
        ]);

        DB::beginTransaction();

        try {
            switch ($validated['type']) {
                case 'complete_job':
                    $job->status = 'completed';
                    $job->completed_at = now();
                    $job->satisfaction_score = $validated['satisfaction_score'];
                    $job->save();

                    Activity::create([
                        'user_id' => Auth::id(),
                        'subject_type' => Job::class,
                        'subject_id' => $job->id,
                        'customer_id' => $job->customer_id,
                        'type' => 'complete_job',
                        'changes' => json_encode([
                            'status' => 'completed',
                            'completed_at' => now()->toDateTimeString(),
                        ]),
                    ]);

                    break;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return response()->json([
            'message' => 'Job status updated',
            'job' => Job::where('user_id', Auth::user()->id)->where('customer_id', $job->customer_id)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {
        Job::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        $jobs = Job::orderBy('id', 'DESC')->where('user_id', auth()->id())->where('customer_id', $request->customer_id)->get();

        return response()->json($jobs);
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }
    public function returnJobs()
    {
        return inertia::render('User/Jobs', [
        'jobs' => Job::orderBy('created_at', 'DESC')->with('activities', 'customer')->get(),
        ]);
    }

    public function returnJob(Job $job)
    {
        $viewingJob = Job::with('activities', 'customer')->where('id', $job->id)->first();
        return inertia::render('Job/Job', [
            'job' => $viewingJob,
        ]);
    }
    public function returnReceipts()
    {
        return inertia::render('User/Receipts', [

        ]);
    }
    public function returnPayments()
    {
        return inertia::render('User/Payments', [
            'payments' => Activity::with('subject', 'customer')
                ->where('user_id', Auth::id())
                ->where('type', 'payment')
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job)
    {
        try {
            $validated = $request->validated();
            $job->update($validated);
            return response()->json([
                'jobs' => Job::with('activities', 'customer')
                    ->where('user_id', Auth::id())
                    ->where('customer_id', $job->customer_id)
                    ->get(),
                'message' => 'Successfully updated',
            ]);
        } catch (\Exception $e) {
            \Log::error('Job update failed', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return response()->json([
                'message' => 'Failed to update job',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json([
            'message' => 'Job deleted successfully'
        ]);
    }

}
