<?php

namespace App\Jobs;

use App\Mail\SoaManagement;
use App\Models\Account;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SoaDelay implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $accountId
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $account = Account::with(['customer', 'transactions'])->find($this->accountId);
        
        if ($account && $account->customer) {
            Mail::to($account->customer->email)->send(new SoaManagement($account));
        }
    }
}