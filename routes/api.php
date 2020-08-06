<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'auth.'], function() {
	Route::post('/register', 'UserRegisterController')->name('register');
	Route::post('/login', 'UserLoginController')->name('login');
});
