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


Auth::routes();

Route::middleware('auth:web')->group(function(){
	Route::get('/home',function(){
		return redirect('/');
	});

	Route::get('pilih-tahun','AKSESTAHUN@index')->name('pilih_tahun');
	Route::post('pilih-tahun','AKSESTAHUN@storing')->name('pilih_tahun.store');



	Route::prefix('pdam')->group(function(){

		Route::get('/', 'FRONT\PDAM@index')->name('p.pdam');

		Route::get('/map', 'FRONT\PDAM@map')->name('p.pdam.map');

		Route::get('/sat-laporan/{id}', 'FRONT\PDAM@sat')->name('p.laporan_sat');

		Route::prefix('simspam')->group(function(){
			Route::get('/{id}','FRONT\SIMSPAM@index')->name('p.simspam.perpipaan');

		});
	});

	Route::prefix('prokeg')->group(function(){
		Route::get('/', 'FRONT\PROKEG@index')->name('p.prokeg');
		Route::get('/program-kegiatan-perdaerah', 'FRONT\PROKEG@per_provinsi')->name('p.prokeg.per.daerah');
		Route::get('/program-kegiatan-per-kota/{id}', 'FRONT\PROKEG@per_kota')->name('p.prokeg.per.kota');
		Route::get('/program-kegiatan-per-urusan/{id}', 'FRONT\PROKEG@dearah_per_urusan')->name('p.prokeg.per.urusan_kota');
		Route::get('/program-kegiatan-per-urusan/{id}', 'FRONT\PROKEG@dearah_per_urusan')->name('p.prokeg.per.urusan_kota');

		Route::get('/program-kegiatan-per-sub_urusan/{id}/{id_urusan}', 'FRONT\PROKEG@dearah_per_sub_urusan')->name('p.prokeg.per.sub_urusan_kota');
		Route::get('/program-kegiatan-per-daerah-sub-urusan-per-program/{id}/{id_urusan}', 'FRONT\PROKEG@dearah_per_program')->name('p.prokeg.per.sub_urusan_kota.prgram');
		
		Route::get('/program-kegiatan-list-p/{id}', 'FRONT\PROKEG@detail_program')->name('pr.program.det');

		Route::get('/urusan', 'FRONT\PROKEG@index')->name('p.prokeg.urusan');


		Route::get('/program-kegiatan-data/{id}', 'FRONT\PROKEG@data')->name('pr.data');
		





	});

	Route::prefix('nuwas_project')->group(function(){
		Route::get('/', 'FRONT\NUWAS_PROJECT@index')->name('p.nuwas.index');
		Route::get('/prokeg', 'FRONT\NUWAS_PROJECT@prokeg_index')->name('p.nuwas.prokeg.index');
		Route::get('/prokeg/detail/{id}', 'FRONT\NUWAS_PROJECT@prokeg_detail')->name('p.nuwas.prokeg.detail');



	});

Route::get('/','FRONT\Dashboard@index');

});


Route::prefix('output')->group(function(){

	Route::prefix('map')->group(function(){
		Route::get('module-builder/show/{tahun}/{id}','OUTPUT\MAP@show')->name('own.out.map');
		Route::get('module-builder-offlin/{tahun}/{id}/{id_doc}','OUTPUT\MAP@buildOffLine')->name('own.out.offline');
		Route::get('module-builder/store','OUTPUT\MAP@convertion');


	});


});



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
	});

	Route::prefix('output')->group(function(){
		Route::get('/map','DASH\OUTPUT\MAP@index')->name('d.out.map.index');
		Route::get('/map/upload','DASH\OUTPUT\MAP@upload')->name('d.out.map.upload');
		Route::post('/map/upload','DASH\OUTPUT\MAP@store')->name('d.out.map.store');
	});


});




include __dir__.'/webBot.php';
include __dir__.'/apiWeb.php';

