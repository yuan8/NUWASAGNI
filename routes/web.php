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



	Route::prefix('pdam')->group(function(){

		Route::get('/', 'FRONT\PDAM@index')->name('p.pdam');

		Route::get('/map', 'FRONT\PDAM@map')->name('p.pdam.map');

		Route::get('/sat-laporan/{id}', 'FRONT\PDAM@sat')->name('p.laporan_sat');

	});

	Route::prefix('prokeg')->group(function(){
		Route::get('/', 'FRONT\PROKEG@index')->name('p.prokeg');
		Route::get('/program-kegiatan-perdaerah', 'FRONT\PROKEG@per_provinsi')->name('p.prokeg.per.daerah');
		Route::get('/program-kegiatan-per-kota/{id}', 'FRONT\PROKEG@per_kota')->name('p.prokeg.per.kota');
		Route::get('/program-kegiatan-per-urusan/{id}', 'FRONT\PROKEG@dearah_per_urusan')->name('p.prokeg.per.urusan_kota');
		Route::get('/program-kegiatan-per-urusan/{id}', 'FRONT\PROKEG@dearah_per_urusan')->name('p.prokeg.per.urusan_kota');

		Route::get('/program-kegiatan-per-sub_urusan/{id}/{id_urusan}', 'FRONT\PROKEG@dearah_per_sub_urusan')->name('p.prokeg.per.sub_urusan_kota');
		Route::get('/program-kegiatan-per-daerah-sub-urusan-per-program/{id}/{id_urusan}', 'FRONT\PROKEG@dearah_per_program')->name('p.prokeg.per.sub_urusan_kota.prgram');
			Route::get('/program-kegiatan-per-daerah-sub-urusan-per-program/{id}/', 'FRONT\PROKEG@dearah_per_program')->name('pr.program.det');

		Route::get('/urusan', 'FRONT\PROKEG@index')->name('p.prokeg.urusan');


		Route::get('/program-kegiatan-data/{id}', 'FRONT\PROKEG@data')->name('pr.data');
		





	});

	Route::prefix('nuwas_project')->group(function(){
		Route::get('/', 'FRONT\NUWAS_PROJECT@index')->name('p.nuwas.index');

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
	Route::prefix('output')->group(function(){
		Route::get('/map','DASH\OUTPUT\MAP@index')->name('d.out.map.index');
		Route::get('/map/upload','DASH\OUTPUT\MAP@upload')->name('d.out.map.upload');
		Route::post('/map/upload','DASH\OUTPUT\MAP@store')->name('d.out.map.store');




	});


});




include __dir__.'/webBot.php';
