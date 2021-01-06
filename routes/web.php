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
Route::get('/',function(){
	return redirect('/v/');
});


Route::get('xxx',function(){
	return HP::sat_indikator('$text');
});

Route::middleware('auth:web')->get('logout',function(){
	Auth::logout();
	return redirect('/');
});



Route::middleware('auth:web')->group(function(){

	Route::prefix('data-pad')->group(function(){
		Route::get('/data', 'FRONT\PAD@index')->name('f.pad.index');
		Route::get('/detail/{kode_daerah}', 'FRONT\PAD@detail')->name('pad.detail');
	});


	Route::prefix('dash-api')->group(function(){
		Route::get('/existing/{tahun}', 'FRONT\DASH@existing')->name('dash.existing');
		Route::get('/pdam-bppspam/{tahun}', 'FRONT\DASH@kondisi_pdam_bppspam')->name('dash.pdam_bppspam');
		Route::get('/ikfd-pemda/{tahun}', 'FRONT\DASH@ikfd')->name('dash.ikfd');
		Route::get('/pdam_sat/{tahun}', 'FRONT\DASH@pdam_sat')->name('dash.pdam_sat');
		Route::get('/rkpd/{tahun}', 'FRONT\DASH@rkpd')->name('dash.rkpd');
		Route::get('/jadwal/{tahun}', 'FRONT\DASH@jadwal')->name('dash.jadwal');






	});


	// Route::get('/home',function(){
	// 	return redirect('/v/');
	// });



	Route::get('pilih-tahun','AKSESTAHUN@index')->name('pilih_tahun');

	Route::post('pilih-tahun','AKSESTAHUN@storing')->name('pilih_tahun.store');


	Route::prefix('daerah')->group(function(){
		Route::get('doc/{kode_daerah}/{jenis}','FRONT\DOKUMEN@list')->name 	('d.doc.list');
	});

	Route::prefix('ikfd')->group(function(){
		Route::get('/','FRONT\IKFD@index')->name('ikfd.index');
	});

	Route::prefix('rakortek')->group(function(){
		Route::get('/','FRONT\RAKORTEK@index')->name('rakortek.index');
		Route::get('/detail/{kode_daerah}','FRONT\RAKORTEK@detail')->name('rakortek.detail');

	});

	Route::prefix('nomenklatur')->group(function(){
		Route::get('/','FRONT\NOMENKLATUR@index')->name('nomenklatur.index');

	});

	Route::prefix('rpjmn')->group(function(){
		Route::get('/','FRONT\RPJMN@index')->name('rpjmn.index');
	});

	Route::prefix('kinerja-air-minum')->group(function(){
		Route::get('/indikator-rkpd','FRONT\KINERJA@index')->name('kinerja-rkpd.index');
		Route::get('/indikator-rkpd/{kode_daerah}','FRONT\KINERJA@detail')->name('kinerja-rkpd.detail');

		Route::get('/spm','FRONT\CAPAIANSPM@index')->name('spm.kinerja-rkpd.index');

	});

	Route::prefix('bppspam')->group(function(){
		Route::get('/','FRONT\BPPSPAM@index')->name('bppspam.index');
		Route::get('/{kode_daerah}/','FRONT\BPPSPAM@detail')->name('bppspam.detail');

	});




	Route::prefix('pdam')->group(function(){
		Route::get('/', 'FRONT\PDAM@index')->name('p.pdam');
		Route::get('/map', 'FRONT\PDAM@map')->name('p.pdam.map');
		Route::get('/sat-laporan/{id}', 'FRONT\PDAM@sat')->name('p.laporan_sat');
		Route::prefix('simspam')->group(function(){
			Route::get('/{id}','FRONT\SIMSPAM@index')->name('p.simspam.perpipaan');
		});
	});

	Route::prefix('dukungan')->group(function(){
		Route::get('/','FRONT\DUKUNGAN@index')->name('d.index');
		Route::get('/{kode_daerah}','FRONT\DUKUNGAN@detail')->name('d.detail');

		Route::get('/{kode_daerah}/program','FRONT\DUKUNGAN@program')->name('d.program');
		Route::get('/{kode_daerah}/program/{id_program}/kegiatan','FRONT\DUKUNGAN@kegiatan')->name('d.program.kegiatan');
		Route::get('/{kode_daerah}/program/{id_program}/kegiatan/{id_kegiatan}/sumber-dana','FRONT\DUKUNGAN@sumberdana')->name('d.program.kegiatan.sumberdana');
	});


	Route::prefix('typologi')->group(function(){
		Route::get('/','FRONT\TYPOLOGI@index')->name('ty.index');
		Route::get('/{kode_daerah}/dinas','FRONT\TYPOLOGI@detail_daerah')->name('ty.daerah');
	});

	Route::prefix('kelembagaan')->group(function(){
		Route::get('/profil-pemda','FRONT\KELEMBAGAAN@index')->name('kl.index');
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

		Route::get('/program-kegiatan-table', 'FRONT\PROKEG@list_program_kegiatan_daerah')->name('pr.table');

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

	Route::prefix('post')->middleware('auth:web')->group(function(){
		Route::get('article/{id}/{slug?}','DASH\OUTPUT\POST@show_article')->name('own.out.post.show');
	});
});


Route::prefix('kegiatan')->group(function(){
	Route::get('show/{id}','FRONT\KEGIATAN@show')->name('k.show');
	Route::get('/','FRONT\KEGIATAN@index')->name('k.index');

});

// Route::get('login',function(){
// 	return redirect('/v/');
// })->name('login');
//

Route::auth();


include __dir__.'/webDash.php';
include __dir__.'/webBot.php';
include __dir__.'/apiWeb.php';
