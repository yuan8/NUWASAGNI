<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
class HelperProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    static function get_tahun_rpjmn(){
        $tahun=static::fokus_tahun();
        $tahun_start=2020;
        $jumlah_tahun_range=(int)explode('.', (''.($tahun-$tahun_start)/5))[0];
        $index=($tahun-(($tahun_start)+(4*$jumlah_tahun_range)))+1;
        return $index;
        
    }

    static function get_rpjmn_table($tambahan=null){
        $tahun=static::fokus_tahun();
        $tahun_start=2020;
        $jumlah_tahun_range=(int)explode('.', (''.($tahun-$tahun_start)/5))[0];
        $dekade=0;
        $tahun_ahir=$tahun_start+(($jumlah_tahun_range+1)*4 );
        return ('master_'.(($tahun_start)+(4*$jumlah_tahun_range)).'_'.$tahun_ahir.'_rpjmn'.(!empty($tambahan)?'_'.$tambahan:'') );
    }

    static function banil($old=0,$new=0){
        
        if($old==null){
            return 0;
        }

        if($old<$new){
            return 1;
            // kenaikan
        }else if($old==$new){
            return 0;
            // stabil
        }else if($old>$new){
            return -1;
            // penurunan
        }else{
            return 0;
        }
    }

    static public function fokus_tahun(){
        if(Auth::User()){
            $tahun=(int)session('fokus_tahun');
            if((!empty($tahun) and($tahun>=2018)) and ($tahun!='') and $tahun!=0 ){
                return $tahun;
            }else{
                header("Location: ".route('login'));
                Auth::logout();
                header("Location: ".route('login'));
            }
        }else{
            header("Location: ".route('login'));
        }
    
    }


    static function pdam_kat_color($status){
        $color='#f7f4d7';
         switch($status){
                    case 1:
                    $color='#ca3333';
                    break;
                    case 2:
                    $color='#f89f0f';
                    break;
                      case 3:
                    $color='#ffff26';
                    break;
                      case 4:
                    $color='#1158d2';
                    break;
                      case 5:
                    $color='#4caf50';
                    break;
            }


            return $color;

    }

    static function hari_ini($data=''){
    $hari = date("D");
    $hari=strtoupper($hari);
    $hari_ini='xxx';
    $hari_ini2='xxx';

    switch($hari){
        case 'SUN':
            $hari_ini = "Minggu";
            $hari_ini2 = "Sunday";

        break;
 
        case 'MON':         
            $hari_ini = "Senin";
            $hari_ini2 = "Monday";

        break;
 
        case 'TUE':
            $hari_ini = "Selasa";
            $hari_ini2 = "Tuesday";

        break;
 
        case 'WED':
            $hari_ini = "Rabu";
            $hari_ini2 = "Wednesday";

        break;
 
        case 'THU':
            $hari_ini = "Kamis";
            $hari_ini2 = "Thursday";

        break;
 
        case 'FRI':
            $hari_ini = "Jumat";
            $hari_ini2 = "Friday";

        break;
 
        case 'SAT':
            $hari_ini = "Sabtu";
            $hari_ini2 = "Saturday";

        break;
        
        default:
            $hari_ini = "Tidak di ketahui";  
            $hari_ini2 = "No Day";

        break;
    }

    if($data!=''){
        $data=strtolower($data);
        $hari_ini=strtolower($hari_ini);
        $hari_ini2=strtolower($hari_ini2);

        if($data==$hari_ini){
            return 1;

        }else if($data==$hari_ini2){
            return 1;

        }else{
            return 0;
        }
    }
 
    return $hari_ini;

    }



}
