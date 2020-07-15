<?php

Route::prefix('bot')->group(function(){
	Route::prefix('sat')->group(function(){
			Route::get('/croning','BOT\SAT@store_data');
			Route::get('/cek/{id}','BOT\SAT@checking');
	});
	
			Route::get('/rpjmn','BOT\RPJMN@store');



	Route::prefix('bppspam')->group(function(){
			Route::get('/storing',function(){
				BPPSPAM::storingdata('simspam');
			});
			
	});
	
	Route::prefix('bangda')->group(function(){
			Route::get('/news','BOT\BANGDA@listing');

	});
});


