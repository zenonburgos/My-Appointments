<?php

use Illuminate\Http\Request;

Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
	
	Route::get('/user', function (Request $request) {
	    return 'privado';
	});
});
