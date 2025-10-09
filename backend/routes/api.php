<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/ping', [TestController::class, 'ping']);


// Example API route(s)
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
