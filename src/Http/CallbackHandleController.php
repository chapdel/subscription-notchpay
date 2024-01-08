<?php

namespace Laravelcm\Subscriptions\Http;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Laravelcm\Subscriptions\Models\Transaction;

class CallbackHandleController extends Controller
{
    public function handle(Request $request, $reference)
    {
        $transaction = Transaction::whereReference($reference)->firstOrFail();

        $subscription = $transaction->subscription;


        switch ($request->status) {
            case 'complete':

                // update transaction
                $transaction->paid_at = now();
                $transaction->status = 'complete';
                $transaction->notchpay_reference = $request->reference;
                $transaction->save();

                //update subscription
                $subscription->markAsPaid();
                break;
            default:
                $transaction->paid_at = now();
                $transaction->status = $request->status;
                $transaction->notchpay_reference = $request->reference;
                $transaction->save();
                break;
        }

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
