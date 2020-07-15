<?php

namespace App\Http\Controllers\FRONT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use HP;
class RAKORTEK extends Controller
{
    //

    public function index(Request $request){
        if($request->tahun){
            $tahun=$request->tahun;
        }else{
            $tahun=HP::fokus_tahun();
        }

        $tahun=2021;

        $data=DB::table('daerah_nuwas')->select(DB::RAW("(COUNT(*)) AS jumlah_daerah,sum(case when tahun=".$tahun." then 1 else 0 end ) as jumlah_prioritas, string_agg(concat('\"',kode_daerah,'\"'),',') as list_kode_pemda"))->first();

        $id_pemda_l=str_replace('"', "'", $data->list_kode_pemda);

        $data=DB::connection('rakortek')->table('view_'.$tahun."_rakortek_hasil")
        ->select(
            DB::raw("
                kodepemda,
                max(pemda) as nama_daerah,
                max(provinsi) as nama_provinsi,
                cound(kodeiku) as jumlah_indikator
                ")
        )
        ->groupBy('kodepemda')
        // ->whereRAW("kodepemda in (".$id_pemda_l.")")
        ->orderBy('kodepemda','asc')
        ->get();

        dd($data);

    }


    public function iku_perurusan(){
        $tahun=2021;
        $bidang=static::akomondir_urusan('');
        foreach($bidang as $k=> $u){
            $bidang[$k]['data']=DB::connection('rakortek')->table('master_'.$tahun.'_rakortek_iku_bidang as b')
            ->select('kodeiku',
                DB::raw("concat('".route('front.r.sebaran_indikator_iku_daerah',['tahun'=>$tahun,'kodeiku'=>null]).'/'."',kodeiku) as url"),
                DB::RAW("(select nama_indikator from rakortek.master_".$tahun."_rakortek_iku as iku where iku.kode_indikator=b.kodeiku and nama_indikator <> '' limit 1) as nama_indikator"),
                DB::RAW("(select concat(count(distinct(case when length(kodepemda)<3 then kodepemda else null end ))::text,' Provinsi <br> ',count(distinct(case when length(kodepemda)>2 then kodepemda else null end ))::text,' Kota/Kab ','<br><b>',count(distinct(kodepemda)),' TOTAL','</b>') from rakortek.master_".$tahun."_rakortek_iku as iku where iku.kode_indikator=b.kodeiku  and nama_indikator <> '') as jumlah_pemda"),

                DB::RAW("(select max(target_nasional) from rakortek.master_".$tahun."_rakortek_iku as iku where iku.kode_indikator=b.kodeiku  and nama_indikator <> '' limit 1) as target_nasional"),
                 DB::RAW("(select max(nama_satuan) from rakortek.master_".$tahun."_rakortek_iku as iku where iku.kode_indikator=b.kodeiku  and nama_indikator <> '' limit 1) as nama_satuan")
            )
            ->whereIn('kodeurusan',$u['kode_bidang'])
            ->groupBy('kodeiku')
            ->get();
        }

        return view('front.rakortek.kinerja_urusan.perbidang')->with('data',array_values($bidang));
    
    }

    public function data_iku_pendukung($kode_daerah,$kode_indikator){
        $tahun=2021;

        $data=DB::connection('rakortek')->table('master_'.$tahun.'_rakortek_iku_kegiatan_pendukung as kp')
        ->where([
            'kodepemda'=>$kode_daerah,
            'kodeiku'=>$kode_indikator
        ])->get();

        return view('front.rakortek.kinerja_urusan.kegiatan')->with('data',$data)->render();
        
    }

    public static function akomondir_urusan($temp=10){
      if($temp!=10){
         return [
            $temp.'1_03'=>['nama'=>'PEKERJAAN UMUM DAN PENATAAN RUANG','kode_bidang'=>['P.1.03','K.1.03'],'value'=>0],
            $temp.'1_04'=>['nama'=>'PERUMAHAN DAN KAWASAN PEMUKIMAN','kode_bidang'=>['P.1.04','K.1.04'],'value'=>0],
            $temp.'2_15'=>['nama'=>'PERHUBUNGAN','kode_bidang'=>['P.2.15','K.2.15'],'value'=>0],
            $temp.'2_16'=>['nama'=>'KOMUNIKASI DAN INFORMATIKA','kode_bidang'=>['P.2.16','K.2.16'],'value'=>0],
            $temp.'2_20'=>['nama'=>'STATISTIK','kode_bidang'=>['P.2.10','K.2.20'],'value'=>0],
            $temp.'2_21'=>['nama'=>'PERSANDIAN','kode_bidang'=>['P.2.21','K.2.21'],'value'=>0],
            $temp.'3_25'=>['nama'=>'KELAUTAN DAN PERIKANAN','kode_bidang'=>['P.3.25','K.3.25'],'value'=>0],
        ];

      }else{
          return [
            'P.1.03','K.1.03',
            'P.1.04','K.1.04',
            'P.2.15','K.2.15',
            'P.2.16','K.2.16',
            'P.2.10','K.2.20',
            'P.2.21','K.2.21',
            'P.3.25','K.3.25',
        ];
      }
    }


    public function iku_catatan($kode_daerah,$kodeiku){
        $tahun=2021;

        return nl2br(DB::connection('rakortek')->table("master_".$tahun."_rakortek_iku_catatan_desk")
        ->where('kodepemda',$kode_daerah)
        ->where('kodeiku',$kodeiku)
        ->pluck('catatan')->first());
    }

    public function sebaran_indikator_iku_daerah($tahun,$kodeiku,Request $request){
        $nama_indikator=DB::connection('rakortek')->table("rakortek.master_".$tahun."_rakortek_iku")->where('kode_indikator',$kodeiku)
        ->select('nama_indikator')->pluck('nama_indikator')->first();
       $data= DB::table('master_daerah as d')
        ->leftJoin(DB::RAW("(select * from rakortek.master_".$tahun."_rakortek_iku where kode_indikator='".$kodeiku."' and nama_indikator <> '') as iku "),'iku.kodepemda','=','d.id')
       

        ->select(
            'd.id as kode_daerah',
            'iku.kode_indikator',
            'd.nama as nama_daerah',
            'iku.target_nasional',
            'iku.target_daerah',
            'iku.nama_satuan',
            DB::RAW("'".$nama_indikator."' as nama_indikator"),
             DB::raw("(select  catatan from  rakortek.master_".$tahun."_rakortek_iku_catatan_desk where kodepemda=d.id and kodeiku=iku.kode_indikator limit 1) as catatan"),
           DB::raw("(select  count(*) from  rakortek.master_".$tahun."_rakortek_iku_kegiatan_pendukung where kodepemda=d.id and kodeiku=iku.kode_indikator limit 1) as jumlh_pendukung")  
        )
        ->orderBy(DB::raw('left(d.id,2)::numeric'),'ASC')
        ->orderBy('d.id','ASC')
        ->get();

        $provinsi=DB::table('master_daerah')
        ->where('kode_daerah_parent',null)
        ->get();

        return view('front.rakortek.kinerja_urusan.sebaran_ind_iku')
        ->with('provinsi',$provinsi)
        ->with('data',$data)->with('nama',$nama_indikator);


    }   

    public function kawasan_perbatasan(){
        $tahun=2021;

        $data=DB::connection('rakortek')
        ->table('master_'.$tahun.'_rakortek_perbatasan as kpn')
        ->select(
            "kpn.*",
            DB::RAW("(select nama from public.master_daerah as d where kpn.kode_daerah=id limit 1) as nama_daerah")
        )->orderBy('kpn.kode_daerah','asc')->get();

     

        return view('front.rakortek.kawasan_perbatasan_negara.index')->with('data',$data);
    }

    public function data_iku_ids($id,Request $request){
        $tahun=2021;

         $data=DB::connection('rakortek')
        ->table('master_'.$tahun.'_rakortek_iku as iku')
        ->where('kodepemda',$id)
        ->where('nama_indikator','!=','');

        if(!empty($request->kode_ind)){
            $data=$data->whereIn('kode_indikator',$request->kode_ind);
        }else{
            $data=$data->whereIn('kode_indikator',['xxxx']);
        }
        $data=$data->select(
           'iku.*',
           DB::raw("(select  catatan from  master_".$tahun."_rakortek_iku_catatan_desk where kodepemda=iku.kodepemda and kodeiku=iku.kode_indikator limit 1) as catatan"),
           DB::raw("(select  count(*) from  master_".$tahun."_rakortek_iku_kegiatan_pendukung where kodepemda=iku.kodepemda and kodeiku=iku.kode_indikator limit 1) as jumlh_pendukung")  
        );

        $data=$data->get();


        return view('front.rakortek.kinerja_urusan.table_ind')->with('data',$data)->render();

    }

    // public function index($ind_type=1){
    //     $tahun=2021;
  
    //     $urusan=static::akomondir_urusan();

    //      $data=DB::connection('rakortek')->table("master_".$tahun."_rakortek_iku_bidang as b")
    //     ->whereRaw("left(kodeurusan,1) =(case when length(b.kodepemda)<3 then 'P' else 'K' end)")
    //     ->groupBy('b.kodepemda','b.kodeurusan')
    //     ->select(
    //         DB::RAW("(select nama from public.master_daerah where id = b.kodepemda limit 1) as nama_daerah"),
    //         'b.kodepemda as kode_daerah',
    //         'b.kodeurusan',
    //         DB::raw("left(b.kodeurusan,1) as daerah_kat"),
    //         DB::raw("(replace(max(b.bidang_urusan),'URUSAN PEMERINTAHAN BIDANG','')) as nama_bidang"),
    //         DB::raw("(count(distinct(b.kodeiku))) as jumlah_i"),
    //         DB::raw("(string_agg(distinct(b.kodeiku),'|')) as i_id")
    //     )
    //     ->whereIn('b.kodeurusan',$urusan)
    //     ->get();

    //      $data_return=[];

    //      foreach ($data as $key => $d) {
    //          # code...
    //        if(!isset($data_return[$d->kode_daerah])){
    //          $data_return[$d->kode_daerah]=array(
    //             'kode_daerah'=>$d->kode_daerah,
    //             'nama'=>$d->nama_daerah,
    //             'kat'=>$d->daerah_kat,
    //             'urusan'=>static::akomondir_urusan('')
    //         );

    //          $kodeurusan=str_replace('.','_',str_replace('P.', '', str_replace('K.', '', $d->kodeurusan)));

    //          $data_return[$d->kode_daerah]['urusan'][$kodeurusan]['value']=$d->jumlah_i;
    //          $data_return[$d->kode_daerah]['urusan'][$kodeurusan]['i_id']=explode('|', $d->i_id);


    //        }

    //      }
    

    //      return view('front.rakortek.kinerja_urusan.index')->with('data',array_values($data_return))->with('urusan',static::akomondir_urusan(''));

    // }

    public function iku_kota($id){

         $tahun=2021;
         $data=DB::table('master_daerah as d')
         ->where('id','ilike',$id.'%')
         ->select(
            "d.*",
            DB::raw("(select  count(distinct(kode_indikator)) from rakortek.master_".$tahun."_rakortek_iku where kodepemda = d.id and nama_indikator <> '' ) as jumlah_ind_iku "),

            DB::raw("(select count(distinct(kodeurusan)) from rakortek.master_".$tahun."_rakortek_iku_bidang  where kodepemda = d.id and left(kodeurusan,1) = (case when (d.kode_daerah_parent is null ) then 'P' else 'K' end) and kodeurusan in ('".implode("','",static::akomondir_urusan())."')) as jumlah_bidang
            ")
         )
         ->orderBy('d.id','ASC')
         ->get();

         return view('front.rakortek.kinerja_urusan.table_kota')->with('data',$data)->render();

    } 




    public function storing(){
         $tahun=Hp::fokus_tahun();

        if($ind_type==1){
            $table="master_".$tahun."_rakortek_iku";
            $type=1;
        }else{
            $table="master_".$tahun."_rakortek_imakro";
            $type=2;

        }

        $bidang=DB::connection('rakortek')->table($table)
        ->select(
            'kode_indikator',
            'nama_indikator',
            'nama_satuan',
            'target_nasional'
        )
        ->get();

        foreach($bidang as $d){
            $d=(json_encode($d)).'';
            $d=str_replace('â€“', '-', $d);
            $d=(object) json_decode($d,true);

            if(!DB::connection('rakortek')->table('master_'.$tahun.'_indikator')->where('kode',$d->kode_indikator)->first()){
                if(!empty($d->nama_indikator)){
                    $target1=$d->target_nasional;
                    $target2=null;
                    $cal=null;

                    $temp=str_replace(' ', '', $d->target_nasional);
                    $temp_match=[];
                    preg_match('/[0-9]?-[0-9]?/', $temp, $temp_match);
                    if(isset($temp_match[0]) && (!empty($temp_match[0]))){

                        $temp=explode('-', $temp);
                        
                        if(!empty($temp[0]) OR ($temp[0]=='0' )){
                            $target1=$temp[0];
                        }
                        if(!empty($temp[1]) OR ($temp[1]=='0')){
                            $target2=$temp[1];
                            $cal='AGGREGATE_BETWEEN';
                        }

                    }else{

                    }



                    DB::connection('rakortek')->table('master_'.$tahun.'_indikator')
                    ->insert([
                        'kode'=>$d->kode_indikator,
                        'nama_indikator'=>$d->nama_indikator,
                        'type_indikator'=>$type,
                        'target'=>$target1,
                        'target_max'=>$target2,
                        'satuan'=>$d->nama_satuan,
                        'tipe_cal'=>$cal
                    ]);
                }
            }
        }

        dd($bidang);

    }
}
