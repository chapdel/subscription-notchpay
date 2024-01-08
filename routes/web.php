<?php

use Illuminate\Support\Facades\Route;

Route::get('/notchpay/s/handle/{reference}', [\Laravelcm\Subscriptions\Http\CallbackHandleController::class, 'handle'])->name('subscription.notchpay.handle');
