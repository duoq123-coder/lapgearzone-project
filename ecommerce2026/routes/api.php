<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayOSController;

/*
|--------------------------------------------------------------------------
| PayOS Webhook & Payment Status API
|--------------------------------------------------------------------------
*/
Route::post('/payos/webhook', [PayOSController::class, 'webhook'])->name('api.payos.webhook');
Route::get('/payos/check-status/{order}', [PayOSController::class, 'checkStatus'])->name('api.payos.check-status');