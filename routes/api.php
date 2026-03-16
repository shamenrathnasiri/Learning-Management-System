
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the Application's "api" route group and
| are assigned the "api" middleware group and "/api" URL prefix.
|
*/

Route::get('/ping', function () {
	return ['message' => 'pong'];
});

