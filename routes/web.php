<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityWithClientController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProspectActivityLogController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\QuoteController;
use App\Models\ActivityWithClient;
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
        'jobs' => \App\Models\Job::with('customer', 'invoices.payments', 'activities', 'business')
            ->orderBy('updated_at', 'DESC')
            ->where('user_id', Auth::id())->get(),

        'recentActivities' => \App\Models\Activity::with('subject', 'customer')
            ->orderBy('created_at', 'DESC')
        ->where('user_id', Auth::id())->paginate(),

        'customers' => \App\Models\Customer::with('jobs.activities', 'jobs.invoices.payments')
            ->orderBy('created_at', 'DESC')
        ->where('user_id', Auth::id())->paginate(),
    ]);
})->middleware(['auth', 'verified', 'hasBusiness'])->name('dashboard');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');




Route::middleware(['auth'])->group(function () {
    Route::post('/save/business/info', [BusinessController::class, 'store'])->name('business.store');
});
Route::middleware(['auth', 'hasBusiness'])->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/business/setup', [BusinessController::class, 'index'])->name('business.index');

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
    Route::get('/dashboard/payments', [PaymentController::class, 'returnPayments'])->name('return.payments');
    Route::get('/dashboard/reports', [CustomerController::class, 'returnReports'])->name('return.reports');
    Route::get('/dashboard/settings', [ProfileController::class, 'returnSettings'])->name('return.settings');
    Route::get('/dashboard/profile', [ProfileController::class, 'returnProfile'])->name('return.profile');
    Route::get('/dashboard/invoices', [InvoiceController::class, 'index'])->name('dashboard.invoice');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('edit.profile');

    Route::get('/dashboard/job/{job}', [JobController::class, 'returnJob'])->name('return.job');
    Route::patch('/update_job/{job}', [JobController::class, 'update'])->name('edit.job');
    Route::delete('/job_delete/{job}', [JobController::class, 'destroy'])->name('delete.job');
    Route::delete('/customer_delete/{customer}', [CustomerController::class, 'destroy'])->name('delete.customer');
    Route::get('/get_user/total_receivables', [CustomerController::class, 'getReceivables'])->name('tatal_received.payments');

    Route::patch('/save/business/info/{business}', [BusinessController::class, 'update'])->name('business.update');
    Route::get('/get_user/job_details/{job}', [InvoiceController::class, 'create'])->name('create.invoice');
    Route::post('/store/invoice/generate', [InvoiceController::class, 'store'])->name('store.invoice');
    Route::patch('/update_invoice/status/{invoice}', [InvoiceController::class, 'update'])->name('update.invoice');
    Route::get('/dashboard/invoice/{invoice}', [InvoiceController::class, 'viewInvoice'])->name('view.invoice');
    Route::get('/dashboard/job/{job}/view', [JobController::class, 'viewJob'])->name('view.job');
    Route::get('/dashboard/quote_proposals', [QuoteController::class, 'index'])->name('view.job');



    Route::get('/invoice/{id}/download', [InvoiceController::class, 'downloadInvoice']);
    Route::post('/invoice/{invoice}/send', [InvoiceController::class, 'sendInvoiceInvoice']);
    Route::get('/invoice/{invoice}/serve', [InvoiceController::class, 'serveInvoice']);
    Route::get('/invoice/{invoice}/download', [InvoiceController::class, 'downloadInvoice']);
    Route::post('/user/payment/on_invoice', [PaymentController::class, 'store']);
    Route::patch('/mark_payment/invalid/{payment}', [PaymentController::class, 'update']);
    Route::post('/dashboard/user/notify_client', [NotificationController::class, 'sendJobProgressEmail']);
    Route::get('/dashboard/serve/{ref_number}', [PaymentController::class, 'index']);
    Route::post('/dashboard/user/{ref_number}/send_receipt', [PaymentController::class, 'resendReceipt']);

    Route::get('/dashboard/prospects', [ProspectController::class, 'create'])->name('prospect.get');
    Route::get('/dashboard/prospect/{prospect}', [ProspectController::class, 'show'])->name('prospect.show');
    Route::post('/dashboard/prospect', [ProspectController::class, 'store'])->name('prospect.store');
    Route::patch('/dashboard/prospect/{prospect}', [ProspectController::class, 'update'])->name('prospect.update');
    Route::delete('/dashboard/prospect/{prospect}', [ProspectController::class, 'destroy'])->name('prospect.destroy');

    Route::post('/prospect/activity_activity', [ProspectActivityLogController::class, 'store'])
        ->name('prospect_activity.store');
    Route::patch('/prospect/activity_activity/{activity}', [ProspectActivityLogController::class, 'update'])
        ->name('prospect_activity.update');
    Route::delete('/prospect/activity_activity/{activity}', [ProspectActivityLogController::class, 'destroy'])
        ->name('prospect_activity.delete');


    Route::patch('/prospect_update/status/{prospect}', [ProspectController::class, 'updateStatus'])
        ->name('prospect_activity.update_status');


    Route::post('/quote/store', [QuoteController::class, 'store'])->name('quotes.store');
    Route::patch('/quote/update/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::get('/quote/view/{quote}', [QuoteController::class, 'viewQuote'])->name('quote.view');
    Route::patch('/quote/update/status/{quote}', [QuoteController::class, 'updateQuoteStatus'])->name('quote.update.status');
    Route::patch('/quote/delete/{quote}', [QuoteController::class, 'destroy'])->name('quote.delete');

    Route::get('/quote/download/{quote_number}', [QuoteController::class, 'downloadQuote'])->name('quote.download');
    Route::post('/dashboard/user/{quote_number}/send_quote', [QuoteController::class, 'sendQuoteViaEmail'])->name('quote.send');

    Route::post('/client/activity', [ActivityWithClientController::class, 'store'])->name('clientActivity.store');
    Route::patch('/client/activity/{activity}', [ActivityWithClientController::class, 'update'])->name('clientActivity.update');
    Route::delete('/client/activity/{activity}', [ActivityWithClientController::class, 'destroy'])->name('clientActivity.destroy');

    Route::post('/job/activity', [ActivityWithClientController::class, 'jobActivityStore'])->name('clientActivity.store');
    Route::patch('/job/activity/{activity}', [ActivityWithClientController::class, 'jobActivityUpdate'])->name('clientActivity.update');
    Route::delete('/job/activity/{activity}', [ActivityWithClientController::class, 'jobActivityDestroy'])->name('clientActivity.destroy');

});


require __DIR__.'/auth.php';

