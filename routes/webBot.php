<?php

Route::prefix('bot')->group(function(){
	Route::prefix('sat')->group(function(){
			Route::get('/croning','BOT\SAT@store_data');
			Route::get('/cek/{id}','BOT\SAT@checking');
	});
});