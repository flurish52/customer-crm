<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Carbon;

class DeactivateInactiveUsers extends Command
{
    protected $signature = 'app:deactivate-inactive-users';
    protected $description = 'Deactivate users inactive for 3 months';

    public function handle(): void
    {
        $threshold = Carbon::now()->subMonths(3);

        $count = User::where('is_active', true)
            ->where('last_login_at', '<', $threshold)
            ->update(['is_active' => false]);

        $this->info($count . ' user(s) deactivated.');
    }
}
