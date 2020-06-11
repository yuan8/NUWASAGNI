<?php 
Route::prefix('dash-admin')->middleware('auth:web')->group(function(){
	Route::get('/','DASH\DASH@index');

	Route::prefix('meet')->group(function(){

		Route::get('/','DASH\MEET@index')->name('d.meet.index');
		Route::get('/video-conference/','DASH\MEET@video')->name('d.meet.v');
		Route::get('/video-conference/TACT_LG','DASH\MEET@video');
		Route::get('/video-conference/DSS_TEAM','DASH\MEET@video');
		Route::get('/video-conference/CAMPURAN','DASH\MEET@video');
		Route::get('/video/initian','DASH\MEET@initial')->name('d.meet.initial.video');
		Route::get('/video-conference/directory-share','DASH\MEET@share');
	});

	Route::prefix('prokeg')->group(function(){
	Route::get('/','DASH\PROKEG@index')->name('d.prokeg.index');
	Route::get('/detail/{id}','DASH\PROKEG@detail')->name('d.prokeg.detail');
	Route::get('/pemetaan/{id}','DASH\PROKEG@pemetaan')->name('d.prokeg.pemetaan');
	Route::post('/pemetaan-store','DASH\PROKEG@pemetaan_store')->name('d.prokeg.pemetaan.store');

	});

	Route::prefix('kebijakan')->group(function(){
		Route::prefix('rpjmn')->group(function(){
			Route::get('/','DASH\KEBIJAKAN\PUSAT\RPJMN@index')->name('d.kb.rpjmn.index');
			Route::get('/pemetaan-rpjmn','DASH\KEBIJAKAN\PUSAT\RPJMN@pemetaan')->name('d.kb.rpjmn.pemetaan');

		});

		Route::prefix('file')->group(function(){

			Route::get('/{jenis}','DASH\KEBIJAKAN\FILE@index')->name('d.kb.f.index');
			Route::post('/{jenis}/upload','DASH\KEBIJAKAN\FILE@upload')->name('d.kb.f.upload');
			Route::get('/{jenis}/update/{id?}','DASH\KEBIJAKAN\FILE@view')->name('d.kb.f.view');
			Route::post('/{jenis}/update/{id?}','DASH\KEBIJAKAN\FILE@update')->name('d.kb.f.update');




		});
	});

	Route::prefix('output')->group(function(){
		Route::get('/map','DASH\OUTPUT\MAP@index')->name('d.out.map.index');
		Route::get('/map/upload','DASH\OUTPUT\MAP@upload')->name('d.out.map.upload');
		Route::post('/map/upload','DASH\OUTPUT\MAP@store')->name('d.out.map.store');
	});


	Route::prefix('user')->middleware('can:role.superadmin')->group(function(){
		Route::get('/map','DASH\OUTPUT\MAP@index')->name('d.user.index');

	});


});