<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Notifications;
use Carbon\Carbon;
use Resend\Laravel\Facades\Resend;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:overdue';
    protected $description = 'Send overdue reminders for jobs, invoices, and quotes';

    public function handle()
    {
        $today = Carbon::today();
        $this->sendConsolidatedOverdueReminders($today);
        $this->info('Overdue reminders sent.');
    }

    private function sendConsolidatedOverdueReminders($today)
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $overdueItems = [
                'jobs' => [],
                'invoices' => [],
                'quotes' => [],
            ];

            $models = [
                'jobs' => \App\Models\Job::class,
                'invoices' => \App\Models\Invoice::class,
                'quotes' => \App\Models\Quote::class,
            ];

            foreach ($models as $type => $modelClass) {
                $records = $modelClass::where('user_id', $user->id)
                    ->whereNotNull('due_date')
                    ->get();

                foreach ($records as $record) {
                    $diff = $today->diffInDays($record->due_date, false);
                    $intervals = [-7, -3, 0, 5];
                    if (!in_array($diff, $intervals)) continue;

                    $exists = \App\Models\Notifications::where('user_id', $user->id)
                        ->where('type', $type . '_overdue')
                        ->where('data->id', $record->id)
                        ->whereDate('created_at', $today)
                        ->exists();
                    if ($exists) continue;

                    $title = $type === 'jobs' ? $record->job_title
                        : ($type === 'invoices' ? $record->invoice_number : $record->quote_number);

                    $link = $type === 'jobs' ? url("/dashboard/job/{$record->id}/view")
                        : ($type === 'invoices' ? url("/dashboard/invoice/{$record->id}") : url("/quote/view/{$record->id}"));

                    $overdueItems[$type][] = [
                        'title' => $title,
                        'due_date' => $record->due_date,
                        'link' => $link,
                        'id' => $record->id,
                    ];

                    \App\Models\Notifications::create([
                        'user_id' => $user->id,
                        'type' => $type . '_overdue',
                        'data' => json_encode(['id' => $record->id]),
                    ]);
                }
            }

            if (empty($overdueItems['jobs']) && empty($overdueItems['invoices']) && empty($overdueItems['quotes'])) {
                continue;
            }

            $html = view('emails.overdue_reminders', [
                'user' => $user,
                'overdueItems' => $overdueItems
            ])->render();

            \Resend\Laravel\Facades\Resend::emails()->send([
                'from' => 'you@entroly.com.ng',
                'to' => $user->email,
                'subject' => 'Overdue Reminders',
                'html' => $html,
            ]);
        }
    }



}
