<?php

Route::prefix('web-api')->group(function(){
	Route::prefix('pdam')->group(function(){
		Route::get('kategori-trafik/{status?}','FRONT\PDAM@trafik_kategori')->name('web_api.pdam.trafik');
		Route::post('kategori-pencapaian','FRONT\PDAM@pencapaian_kualitas')->name('web_api.pdam.pencapaian');
	});

	Route::prefix('nuwas')->group(function(){
		Route::get('daerah-target','FRONT\NUWAS_PROJECT@api_daerah_target')->name('web_api.nuwas.daerah.target');

		Route::get('daerah-target','FRONT\NUWAS_PROJECT@api_daerah_target_map')->name('web_api.nuwas.daerah.target.map');

		Route::prefix('daerah')->group(function(){
			
			Route::post('target-2-tahun','FRONT\NUWAS_PROJECT@api_daerah_target_2_tahun')->name('web_api.nuwas.daerah.target.2');
			Route::post('snap/{kode_daerah?}','FRONT\DAERAH@api_snap')->name('web_api.daerah.profile');
		});
	});

	Route::prefix('prokeg')->group(function(){
		Route::get('map-status-air-minum','FRONT\PROKEG@daerah_upload_air_minum_map')->name('web_api.prokeg.status.air_minum');

		Route::get('widget-prokeg', 'FRONT\PROKEG@widget_prokeg')->name('web_api.prokeg.w1');
		Route::get('widget-prokeg-2', 'FRONT\PROKEG@widget_prokeg_pelaporan')->name('web_api.prokeg.w2');

	});

	Route::prefix('dash')->group(function(){
		Route::prefix('rpjmn')->group(function(){
			Route::post('turunan','DASH\KEBIJAKAN\PUSAT\RPJMN@api_turunan')->name('web_api.d.rpjmn.turunan');

		});
	});
});