<?php

Route::prefix('web-api')->group(function(){
	Route::prefix('pdam')->group(function(){
		Route::get('kategori-trafik/{status?}','FRONT\PDAM@trafik_kategori')->name('web_api.pdam.trafik');
		Route::post('kategori-pencapaian','FRONT\PDAM@pencapaian_kualitas')->name('web_api.pdam.pencapaian');
	});

	Route::prefix('nuwas')->group(function(){
		Route::get('daerah-target','FRONT\NUWAS_PROJECT@api_daerah_target')->name('web_api.nuwas.daerah.target');

	});

	Route::prefix('dash')->group(function(){
		Route::prefix('rpjmn')->group(function(){
			Route::post('turunan','DASH\KEBIJAKAN\PUSAT\RPJMN@api_turunan')->name('web_api.d.rpjmn.turunan');

		});
	});
});