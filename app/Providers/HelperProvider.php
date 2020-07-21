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

    static function sat_indikator($text){
        $text=str_replace('fkl','fiskal',$text);
        $text=str_replace('pel','pelayanan',$text);
        $text=str_replace('jar','jaringan',$text);
        $text=str_replace('dg','dengan',$text);
        $text=str_replace('dlm','dalam',$text);
        $text=str_replace('kum','kumulatif',$text);
        $text=str_replace('lap','laporan',$text);
        $text=str_replace('per','periode',$text);
        $text=str_replace('dds','didistribusikan',$text);
        $text=str_replace('sam','sambungan',$text);
        $text=str_replace('ppp','perpipaan',$text);
        $text=str_replace('bkn','bukan',$text);
        $text=str_replace('pop','populasi',$text);
        $text=str_replace('yg','yang',$text);
        $text=str_replace('tg','target',$text);
        $text=str_replace('pjl','penjualan',$text);
        $text=str_replace('pensut','penyusutan',$text);
        $text=str_replace('lstrk','listrik',$text);
        $text=str_replace('trl','terlayani',$text);
        $text=str_replace('pny','penyediaan',$text);
        $text=str_replace('_u','_untuk',$text);
        $text=str_replace('alk','alokasi',$text);
        $text=str_replace('dr','dari',$text);
        $text=str_replace('slm','selama',$text);
        $text=str_replace('diop','dioperasikan',$text);
        $text=str_replace('pd','penduduk',$text);
        $text=str_replace('sbl','sebelumnya',$text);
        $text=str_replace('prd','period',$text);
        $text=str_replace('ttl','total',$text);
        $text=str_replace('hg','harga',$text);
        $text=str_replace('pdpt','pendapatan',$text);
        $text=str_replace('by','biaya',$text);
        $text=str_replace('ln','lainlain',$text);
        $text=str_replace('rek','rekening',$text);
        $text=str_replace('_nilai','',$text);
        $text=str_replace('sat_','',$text);
        return strtoupper(str_replace('_',' ',$text));

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

    public static function slugify($text)
        {
          // replace non letter or digits by -
          $text = preg_replace('~[^\pL\d]+~u', '-', $text);

          // transliterate
          $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

          // remove unwanted characters
          $text = preg_replace('~[^-\w]+~', '', $text);

          // trim
          $text = trim($text, '-');

          // remove duplicate -
          $text = preg_replace('~-+~', '-', $text);

          // lowercase
          $text = strtolower($text);

          if (empty($text)) {
            return 'n-a';
          }

          return $text;
        }

  static function get_tahun_rpjmn($tahun=null){
        if($tahun==null){
            $tahun=static::fokus_tahun();
        }

        $tahun=(int)$tahun;

        $poin_start=2020;
        $point_finish=$poin_start+4;

        // 2020 - 2024
        // 2025 - 2029

        if(($poin_start+4)>=$tahun){
            $index=($poin_start - $tahun)+1;
            
        }else{
              do{
                $poin_start+=5;
                $point_finish=$poin_start+4;
                if(($poin_start<=$tahun)and($point_finish>=$tahun)){
                    $ok=false;

                }

            }while($ok);

        }
        $index=($poin_start - $tahun)+1;


        return [
            'tahun_akses'=>$tahun,
            'index'=>$index,
            'start'=>$poin_start,
            'finish'=>$point_finish,
            'table'=>static::get_rpjmn_table(null,$tahun),
            'table_indikator'=>static::get_rpjmn_table('indikator',$tahun),
        ];
        
    }
    

    static function get_rpjmn_table($tambahan=null,$tahun=null){
        if($tahun==null){
            $tahun=static::fokus_tahun();
        }

        $tahun=(int)$tahun;

        $poin_start=2020;
        $point_finish=$poin_start+4;

        // 2020 - 2024
        // 2025 - 2029

        if(($poin_start+4)>=$tahun){

             return ('master_'.(($poin_start)).'_'.$point_finish.'_rpjmn'.(!empty($tambahan)?'_'.$tambahan:'') );

        }else{
              do{
                $poin_start+=5;
                $point_finish=$poin_start+4;
                if(($poin_start<=$tahun)and($point_finish>=$tahun)){
                    $ok=false;

                }

            }while($ok);

        }

        return ('master_'.(($poin_start)).'_'.$point_finish.'_rpjmn'.(!empty($tambahan)?'_'.$tambahan:'') );

       
       
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
            if(!empty(session('fokus_tahun'))){
                if(session('fokus_tahun')){
                    
                    return (int) session('fokus_tahun');
                }else{
                    Auth::logout();
                    header("Location: ".route('login'));
                    exit();
                    return 0;
                }
            }else{

                Auth::logout();
                header("Location: ".route('login'));
                exit();
                return 0;
            }
        }else{

          
          return 0;

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
