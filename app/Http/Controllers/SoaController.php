<?php

namespace App\Http\Controllers;

use App\Jobs\SoaDelay;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoaController extends Controller
{
    public function soaGeneration(Request $request)
    {
        $query = Account::with(['customer', 'transactions']);

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $accounts = $query->get();

        return view('soa.index', [
            'accounts' => $accounts,
            'selectedStatus' => $request->get('status', '')
        ]);
    }

    public function generateAllSOAs()
    {
        $accounts = $this->getAccountsForSOA();
        $delay = 0;

        foreach ($accounts as $account) {
            Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");

            // Dispatch the SoaDelay job to send the SOA email with a delay
            SoaDelay::dispatch($account->id)
                ->delay(now()->addSeconds($delay));

            Log::info("SoaDelay job dispatched for Account ID: {$account->id} with {$delay}s delay");
            
            $delay += 5; // Add 5 seconds delay for each subsequent email
        }

        return redirect()->route('soa.index')->with('status', 'All SOAs have been queued for sending with 5-second delays.');
    }

    public function generateSingleSOA(Account $account)
    {
        Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");

        // Dispatch the SoaDelay job to send the SOA email with a 5-second delay
        SoaDelay::dispatch($account->id)
            ->delay(now()->addSeconds(5));

        Log::info("SoaDelay job dispatched for Account ID: {$account->id} with 5s delay");

        return back()->with('status', "SOA for account {$account->account_number} has been queued for sending.");
    }

    private function getAccountsForSOA()
    {
        return Account::with(['customer', 'transactions'])
            ->get();
        // Uncomment the line below and comment the line above for production use
        // return Account::with(['customer', 'transactions'])
        //     ->whereDay('start_date', 20)
        //     ->get();
    }
}