<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EmailController;

Route::apiResource('contacts', ContactController::class);
Route::apiResource('groups', GroupController::class);

Route::get('contacts-export', [ContactController::class, 'export']);
Route::post('contacts-import', [ContactController::class, 'import']);

Route::post('/send-email', [EmailController::class, 'send']);

