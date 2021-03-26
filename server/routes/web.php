<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::post('/vt_notif', 'SnapMidtransController@notification');

/* testing snap */
/*
Route::get('/snap', 'SnapController@snap');
Route::get('/snaptoken', 'SnapController@token');
Route::post('/snapfinish', 'SnapController@finish');
*/

Route::middleware(['signed'])->group(function () {
	Route::get(
		'auto-login-to-nova-product-resource',
		'Api\V1\AutoLoginToNovaController@autoLoginToProductResource'
	)
	->name('autoLoginToNovaProductResource');

	Route::get(
		'products/{productId}/playback-video',
		'Api\V1\ProductController@getPlaybackVideoFile'
	)
	->name('products.playback-video');
});
