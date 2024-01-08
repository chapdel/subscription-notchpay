<?php

namespace Laravelcm\Subscriptions\Traits;

use Laravelcm\Subscriptions\Models\Plan;
use Laravelcm\Subscriptions\Models\Transaction;
use NotchPay\Exceptions\ApiException;
use NotchPay\NotchPay;
use NotchPay\Payment;

trait HasTransactions
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPaymentUrl(Plan $plan)
    {
        NotchPay::setApiKey(config('services.notchpay.public_key'));

        $transaction = $this->transactions()->create([
            'amount' => $plan->price * $plan->invoice_period??1,
            'currency'=> $plan->currency,
            'status' => 'pending'
        ]);

        try {
            $tranx = Payment::initialize([
                'amount'=> $plan->price,
                'email'=> $this->subscriber->email,
                'currency'=> $plan->currency,
                'callback'=> route('subscription.notchpay.handle', $transaction->reference),         // optional callback url
                'reference'=>$transaction->reference,
            ]);
        }catch (ApiException $e) {
            abort(500, json_encode($e->errors));
        }

        return $tranx->authorization_url;
    }

    public function hasPaid(): bool
    {
        return $this->paid_at != null || $this->status == 'paid' || $this->plan->isFree();
    }
}
