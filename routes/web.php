<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhonePeTestController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/pay', [PhonePeTestController::class, 'phonePe'])->name('phonepe.pay');
Route::any('phonepe-response',[PhonePeTestController::class,'response'])->name('response');