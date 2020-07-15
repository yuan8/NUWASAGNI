<?php

$data=DB::connection('sy')->table('master_pn_2020_pn')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table)->insertOrIgnore([
    			'nama'=>$d->pn,
    			'jenis'=>'PN',
    			'info_path'=>($key+1)
    		]);

    	}

    	
    	$data=DB::connection('sy')->table('master_pn_2020_pp')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table)->insertOrIgnore([
    			'nama'=>$d->pp,
    			'jenis'=>'PP',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp
    		]);

    	}
    	$data=DB::table($rpjm_table.' as d')->where('jenis','PN')->get();
    	
    	foreach ($data as $key => $d) {
    		$data=DB::table($rpjm_table)
    		->where('jenis','!=','PN')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pn'=>$d->id
    		]);
    		
    	}

    	// -----------------------------PN DAN PP

    	$data=DB::connection('sy')->table('master_pn_2020_kp')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table)->insertOrIgnore([
    			'nama'=>$d->kp,
    			'jenis'=>'KP',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp
    		]);

    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','PP')->get();
    	
    	foreach ($data as $key => $d) {
    		$data=DB::table($rpjm_table)
    		->where('jenis','!=','PP')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pp'=>$d->id,
    			'id_pn'=>$d->id_pn
    		]);
    		
    	}
    	// -------------------------------KP


    	$data=DB::connection('sy')->table('master_pn_2020_propn')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table)->insertOrIgnore([
    			'nama'=>$d->propn,
    			'jenis'=>'PROPN',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp.'.'.$d->id_propn
    		]);

    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','KP')->get();
    	
    	foreach ($data as $key => $d) {
    		$data=DB::table($rpjm_table)
    		->where('jenis','!=','KP')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_kp'=>$d->id,
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id_pp

    		]);
    		
    	}

    	// ---------------------- PRONAS


    	$data=DB::connection('sy')->table('master_pn_2020_pronas')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table)->insertOrIgnore([
    			'nama'=>$d->pronas,
    			'jenis'=>'PRONAS',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp.'.'.$d->id_propn.'.'.$d->id_pronas
    		]);

    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','PROPN')->get();
    	
    	foreach ($data as $key => $d) {
    		$data=DB::table($rpjm_table)
    		->where('jenis','!=','PROPN')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_propn'=>$d->id,
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id_pp,
    			'id_kp'=>$d->id_kp,
    		]);
    		
    	}

    	-------------------------------indikator

    	$data=DB::connection('sy')->table('master_pn_2020_pp_indikator')->get();
    	foreach($data as $d){
    		if(!empty($d->indikator)){

    		DB::table($rpjm_table_indikator)->insertOrIgnore([
    			'nama'=>$d->indikator,
    			'target_1_1'=>$d->t2020,
    			'target_2_1'=>$d->t2021,
    			'target_3_1'=>$d->t2022,
    			'target_4_1'=>$d->t2023,
    			'target_5_1'=>$d->t2024,
    			'anggaran'=>(float)(str_replace(',', '', $d->pendanaan)),
    			'lokasi'=>$d->lokasi,
    			'instansi'=>$d->instansi,
    			'jenis'=>'PP',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.@'.$d->tag_pp
    		]);
    		}
    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','PP')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table_indikator)->where('jenis','PP')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id,

    		]);
    	}

    	// ------------------------ KP

    	$data=DB::connection('sy')->table('master_pn_2020_kp_indikator')->get();
    	foreach($data as $d){
    		if(!empty($d->indikator)){

    		DB::table($rpjm_table_indikator)->insertOrIgnore([
    			'nama'=>$d->indikator,
    			'target_1_1'=>$d->t2020,
    			'target_2_1'=>$d->t2021,
    			'target_3_1'=>$d->t2022,
    			'target_4_1'=>$d->t2023,
    			'target_5_1'=>$d->t2024,
    			'anggaran'=>(float)(str_replace(',', '', $d->pendanaan)),
    			'lokasi'=>$d->lokasi,
    			'instansi'=>$d->instansi,
    			'jenis'=>'KP',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp.'.@'.$d->tag_kp
    		]);
    		}
    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','KP')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table_indikator)->where('jenis','KP')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id_pp,
    			'id_kp'=>$d->id,


    		]);
    	}
    	// --------------propn


    	$data=DB::connection('sy')->table('master_pn_2020_propn_indikator')->get();
    	foreach($data as $d){
    		if(!empty($d->indikator)){
    			DB::table($rpjm_table_indikator)->insertOrIgnore([
    			'nama'=>$d->indikator,
    			'target_1_1'=>$d->t2020,
    			'target_2_1'=>$d->t2021,
    			'target_3_1'=>$d->t2022,
    			'target_4_1'=>$d->t2023,
    			'target_5_1'=>$d->t2024,
    			'anggaran'=>(float)(str_replace(',', '', $d->pendanaan)),
    			'lokasi'=>$d->lokasi,
    			'instansi'=>$d->instansi,
    			'jenis'=>'PROPN',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp.'.'.$d->id_propn.'.@'.$d->tag_propn
    			]);
    		}
    	}

    	$data=DB::table($rpjm_table.' as d')->where('jenis','PROPN')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table_indikator)->where('jenis','PROPN')->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id_pp,
    			'id_kp'=>$d->id_kp,
    			'id_propn'=>$d->id,



    		]);
    	}


    	// ---------------pronas

    	$data=DB::connection('sy')->table('master_pn_2020_pronas_indikator')->get();
    	foreach($data as $d){
    		if(!empty($d->indikator)){
    			DB::table($rpjm_table_indikator)->insertOrIgnore([
    			'nama'=>$d->indikator,
    			'target_1_1'=>$d->t2020,
    			'target_2_1'=>$d->t2021,
    			'target_3_1'=>$d->t2022,
    			'target_4_1'=>$d->t2023,
    			'target_5_1'=>$d->t2024,
    			'anggaran'=>(float)(str_replace(',', '', $d->pendanaan)),
    			'lokasi'=>$d->lokasi,
    			'instansi'=>$d->instansi,
    			'jenis'=>'PRONAS',
    			'info_path'=>$d->id_pn.'.'.$d->id_pp.'.'.$d->id_kp.'.'.$d->id_propn.'.'.$d->id_pronas.'.@'.$d->id_pronas_indikator
    			]);
    		}
    	}
    	set_time_limit(-1);

    	$data=DB::table($rpjm_table.' as d')->where('jenis','PRONAS')->get();
    	foreach ($data as $key => $d) {
    		DB::table($rpjm_table_indikator)->where('jenis','PRONAS')->where('id_pronas',null)->where('info_path','ilike',$d->info_path.'%')->update([
    			'id_pn'=>$d->id_pn,
    			'id_pp'=>$d->id_pp,
    			'id_kp'=>$d->id_kp,
    			'id_propn'=>$d->id_propn,
    			'id_pronas'=>$d->id,

    		]);
    	}
    	
