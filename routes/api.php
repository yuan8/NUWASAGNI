<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('api-dash')->group(function(){
		Route::post('file-upload','DASH\POST\KEGIATAN@file_store')->name('d.post.kegiatan.file_store');
		Route::get('url-get-meta','DASH\POST\KEGIATAN@active_url')->name('d.post.kegiatan.active_url');

});
Route::prefix('bot')->group(function(){
		Route::prefix('bangda')->group(function(){
					Route::post('/news/store','BOT\BANGDA@storing')->name('bot.bangda.store');
		});
});
