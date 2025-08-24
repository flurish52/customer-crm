<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Business;
use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
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
        return $jobs = Job::orderBy('created_at', 'DESC')
            ->where('user_id', Auth::id())
            ->where('customer_id', $customer_id)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getJobs()
    {
        $user_id = auth()->user()->id;
        return $jobs = Job::orderBy('created_at', 'DESC')
            ->with('activities', 'customer')
            ->where('user_id', $user_id)
            ->get();
    }

    public function create()
    {
        //
    }
    public function viewJob(Job $job)
    {
        if ($job->user_id !== Auth::id()) return;
        $job->load('customer', 'invoice', 'business');
        return inertia::render('Job/View', [
            'selectedJob'=> $job,
        ]);
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
        $business =  Business::where('user_id', Auth::id())->first();

        DB::beginTransaction();
        $job = Job::create([
            'user_id' => auth()->id(),
            'business_id' => $business->id,
            ...$request->validated(),
            'satisfaction' => $request->completedExtras['satisfaction'] ?? null,
        ]);
        $now = now();
        // Job completion activity (only if status is completed)
        if ($job->status === 'completed') {
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Job::class,
                'subject_id' => $job->id,
                'customer_id' => $job->customer_id,
                'type' => 'complete_job',
                'changes' => json_encode([
                    'status' => 'completed',
                    'completed_at' => $now->toDateTimeString(),
                ]),
            ]);
        }
// Payment activity (only if amount_paid is set and > 0)
        if (!empty($request->completedExtras['amount_paid'])) {
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => Job::class,
                'customer_id' => $job->customer_id,
                'subject_id' => $job->id,
                'type' => 'payment',
                'changes' => json_encode([
                    'amount' => $request->completedExtras['amount_paid'],
                    'method' => $request->completedExtras['payment_method'] ?? 'N/A',
                    'date' => $now->toDateString(),
                    'notes' => 'Payment received for job',
                ]),
            ]);
        }
        DB::commit();

        $jobs = Job::orderBy('id', 'DESC')
            ->where('user_id', auth()->id())
            ->where('customer_id', $request->customer_id)
            ->get();

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
        'jobs' => Job::orderBy('created_at', 'DESC')
            ->with('activities', 'customer')
            ->where('user_id', Auth::id())
            ->get(),
        ]);
    }

    public function returnJob(Job $job)
    {
        $viewingJob = Job::with('activities', 'customer')
            ->where('user_id', Auth::id())
            ->where('id', $job->id)->first();
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
            'payments' => Activity::orderBy('created_at', 'DESC')->with('subject', 'customer')
                ->where('user_id', Auth::id())
                ->where('type', 'payment')
                ->get(),
            'jobs' => Job::orderBy('job_title', 'DESC')->where('user_id', Auth::id())->get(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Job $job)
    {
//        dd($request);
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $previousStatus = $job->status;
            $now = now();

            $job->update($validated);


            if (
                isset($validated['status']) &&
                $validated['status'] === 'completed' &&
                $previousStatus !== 'completed'
            ) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'subject_type' => Job::class,
                    'subject_id' => $job->id,
                    'customer_id' => $job->customer_id,
                    'type' => 'complete_job',
                    'changes' => json_encode([
                        'status' => 'completed',
                        'completed_at' => $now->toDateTimeString(),
                    ]),
                ]);
            }
            // If amount_paid exists and is greater than 0, log payment activity
            if (!empty($validated['completedExtras']['amount_paid']) && $validated['completedExtras']['amount_paid'] > 0) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'subject_type' => Job::class,
                    'subject_id' => $job->id,
                    'customer_id' => $job->customer_id,
                    'type' => 'payment',
                    'changes' => json_encode([
                        'amount' => $validated['completedExtras']['amount_paid'],
                        'method' => $validated['completedExtras']['payment_method'] ?? 'N/A',
                        'date' => $now->toDateString(),
                        'notes' => 'Payment received for job',
                    ]),
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
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
        if ($job->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this job'
            ], 403);
        }

        $job->delete();
        return response()->json([
            'message' => 'Job deleted successfully'
        ]);
    }

}
