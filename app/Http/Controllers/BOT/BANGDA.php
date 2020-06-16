<?php

namespace App\Http\Controllers\BOT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Storage;
use Carbon\Carbon;
class BANGDA extends Controller
{
    //

    public function listing(){
    	set_time_limit(-1);
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		$response = curl_exec($ch);

		$headers = [
		    
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL,'https://bangda.kemendagri.go.id/home/get_berita_bangda/');
		$server_output=null;
		$server_output = curl_exec ($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$server_output=substr($server_output, $header_size);
		curl_close ($ch);

		return view('bot.bangda.index')->with('data',$server_output);

    }


    public function storing(Request $request){

    	if($request->data){

    		foreach ($request->data as $key => $d) {
    			
    			$exist=DB::table('berita_bangda')->find($d['id']);
    			$date=Carbon::parse($d['time']);
    			if(!$exist){
    				$img=file_get_contents($d['link_img']);
    				if($img){
    					$img_d=Storage::put('public/berita_bangda/'.$d['id'].'.jpg',$img);
    					$img_path='/storage/berita_bangda/'.$d['id'].'.jpg';
    				}else{
    					$img_path=null;
    				}
  
    				DB::table('berita_bangda')->insert([
    					'title'=>$d['title'],
    					'content'=>$d['content'],
    					'link'=>$d['link'],
    					'thumbnail_path'=>$img_path,
    					'created_at'=>$date

    				]);




    			}else{

    			}
    		}

    		return array('code'=>200);
    	}

    }
}
