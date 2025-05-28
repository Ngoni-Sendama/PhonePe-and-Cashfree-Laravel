<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhonePeTestController;
use App\Http\Controllers\CashfreePaymentController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/pay', [PhonePeTestController::class, 'phonePe'])->name('phonepe.pay');
Route::any('phonepe-response',[PhonePeTestController::class,'response'])->name('response');


Route::get('cashfree/payments/create', [CashfreePaymentController::class, 'create'])->name('callback');
Route::post('cashfree/payments/store', [CashfreePaymentController::class, 'store'])->name('store');
Route::any('cashfree/payments/success', [CashfreePaymentController::class, 'success'])->name('success');