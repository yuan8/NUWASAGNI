<?php

Route::prefix('bot')->group(function(){
	Route::prefix('sat')->group(function(){
			Route::get('/croning','BOT\SAT@store_data');
			Route::get('/cek/{id}','BOT\SAT@checking');
	});
	

	Route::prefix('bppspam')->group(function(){
			Route::get('/storing',function(){
				
				BPPSPAM::storingdata('mybppspam');
			});
			
	});
	
	Route::prefix('bangda')->group(function(){
			Route::get('/news','BOT\BANGDA@listing');

	});
});


