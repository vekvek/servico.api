<?php

use Illuminate\Support\Facades\Route;

/**
 * Welcome endpoint.
 * 
 * @return \Illuminate\Http\JsonResponse
 */
Route::get('/', function () {
	return response()
		->json(['message' => 'Welcome!'], 200);
})->name('index');
