<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'jobs' => \App\Models\Job::with('customer', 'activities')
            ->orderBy('created_at', 'DESC')
            ->where('user_id', Auth::id())->get(),

        'recentActivities' => \App\Models\Activity::with('subject', 'customer')
            ->orderBy('created_at', 'DESC')
        ->where('user_id', Auth::id())->paginate(),

        'customers' => \App\Models\Customer::with('jobs.activities')
            ->orderBy('created_at', 'DESC')
        ->where('user_id', Auth::id())->paginate(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::post('/customer_store', [CustomerController::class, 'store'])
        ->name('customer.store');

    Route::get('/get_customer', [CustomerController::class, 'index'])->name('customers.get');
    Route::get('/customer/{customer_id}', [CustomerController::class, 'viewCustomer'])->name('customer.get');
    Route::post('/store_job', [JobController::class, 'store'])->name('job.store');
    Route::get('/get_jobs/{customer_id}', [JobController::class, 'index'])->name('job.get_all');
    Route::get('/get_user/jobs', [JobController::class, 'getJobs'])->name('job.user_jobs_all');

    Route::patch('/customer_update/{customer_id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::patch('/job_update/status/{job}', [JobController::class, 'updateJobStatus'])->name('job.completed_status');
    Route::post('/user/payments/received', [ActivityController::class, 'store'])->name('activity.payment');
    Route::get('/dashboard/customers', [CustomerController::class, 'returnCustomers'])->name('return.customers');
    Route::get('/dashboard/jobs', [JobController::class, 'returnJobs'])->name('return.jobs');
    Route::get('/dashboard/receipts', [JobController::class, 'returnReceipts'])->name('return.receipts');
    Route::get('/dashboard/payments', [JobController::class, 'returnPayments'])->name('return.payments');
    Route::get('/dashboard/reports', [CustomerController::class, 'returnReports'])->name('return.reports');
    Route::get('/dashboard/settings', [ProfileController::class, 'returnSettings'])->name('return.settings');
    Route::get('/dashboard/profile', [ProfileController::class, 'returnProfile'])->name('return.profile');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('edit.profile');

    Route::get('/dashboard/job/{job}', [JobController::class, 'returnJob'])->name('return.job');
    Route::patch('/update_job/{job}', [JobController::class, 'update'])->name('edit.job');
    Route::delete('/job_delete/{job}', [JobController::class, 'destroy'])->name('delete.job');
    Route::delete('/customer_delete/{customer}', [CustomerController::class, 'destroy'])->name('delete.customer');
    Route::get('/get_user/total_receivables', [CustomerController::class, 'getReceivables'])->name('tatal_received.payments');
});

require __DIR__.'/auth.php';

