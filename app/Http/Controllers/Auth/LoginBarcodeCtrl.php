<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use DB;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Validator;
use Carbon\Carbon;
class LoginBarcodeCtrl extends Controller
{
    
	public static function encript($key_token,$no_build=false){

		$os=(PHP_OS);

    	if(env('DEV')){
	    	if($os=='WINNT'){

	    		$ip=trim(shell_exec("ipconfig"));

		    }else{
	    		$ip=trim(shell_exec("ifconfig"));

		    }

		    $ipline=[];
	    	foreach(preg_split("/((\r?\n)|(\r\n?))/", $ip) as $line){
			    // do stuff with $line
			    $ipline[]=$line;
			} 

			foreach($ipline as $key=> $line){
				$line=trim($line);
				if($line==''){
					unset($ipline[$key]);
				}else{
					$ar=explode(':', $line);
					if((isset($ar[1]))&&(!empty($ar[1]))){
						$ipline[$key]=trim($ar[1]);
					}else{
						unset($ipline[$key]);
					}
				}
			}

			$ipline=array_values($ipline);
			$ip='';
			foreach ($ipline as $key => $value) {
				if($value=='255.255.255.0'){
					$ip=$ipline[$key-1].':80';
				}
			}
    	}else{
    		$ip=url('');
    	}





		$date_in=date('mdi');

		$date_in=base64_encode($date_in);

		$url=route('br.login');
		$url=str_replace('http://localhost', 'http://'.$ip, $url);

		$url=base64_encode($url);

		$return=$url.'Ka&'.$date_in.'MT|'.$key_token;

		if($no_build){
			return $return;
		}

    	$options = new QROptions([
			'version'    => QRCode::VERSION_AUTO,
			'outputType' => QRCode::OUTPUT_MARKUP_SVG,
			'eccLevel'   => QRCode::ECC_L,
		]);


		// invoke a fresh QRCode instance
		$qrcode = new QRCode($options);

	
        $data=base64_encode($return);

        $generator=$qrcode->render($return);


		return ($generator);

	}

    public function landing(Request $request){

    	
    	$finger=$request->fingerprint();

    	if(Auth::check()){
    		$fin=DB::table('fingerprint')->where('id_finger',$finger)->first();
    		if($fin){
    			$fin=DB::table('fingerprint')->where('id_finger',$finger)->update([
    				'approve_login'=>false
    			]);
    		}
    		return redirect('home');
    	}else{

			$generator=static::encript($finger);

    		$fin=DB::table('fingerprint')->where('id_finger',$finger)->first();
    		if($fin){
    			$fin=DB::table('fingerprint')->where('id_finger',$finger)->update([
    				'approve_login'=>false
    			]);
    		}else{
    			$fin=DB::table('fingerprint')->insert([
    				'id_finger'=>$finger
    			]);
    		}
    	}

    	return view('adminlte::login')->with('barcode',($generator))->with('fingerprint',static::encript($finger,true));



    }

    public function login(Request $request){
    	if(Auth::user()){
    		return array(
    			'code'=>100
    		);

    	}else{
		
    		$key=explode('MT|',$request->key)[1];
			
    		
    		$approve=DB::table('fingerprint')->where('id_finger',$key)
    		->where('approve_login',true)->where('id_user','!=',null)->first();
    		if($approve){

    			Auth::loginUsingId($approve->id_user,true);
    			$approve=DB::table('fingerprint')->where('id_finger',$key)->update([
    				'approve_login'=>false,
    				'id_user'=>null
    			]
    			);

    			return array(
    				'code'=>100,
    				'url'=>'',
                    'message'=>strtoupper(Auth::User()->name)=='DSS'?'D S S':Auth::User()->name
    			);


    		}

    	}

    	return array('code'=>401);

    }


    public function update_meta(Request $request){

        if(empty(Session('fokus_urusan'))){
          if(in_array(Auth::User()->role,[1,2])){

          }else{
            $d=DB::table('user_urusan')->where('id_user',Auth::user()->id)->first();
            if(!$d){
                Auth::logout();
                return back();
            }
          }
        }

    	if(!empty($request->tahun)){
       
    		return static::update_meta_action($request);
    	}

    	return view('meta_user')->with('agent',Auth::User());

    }

    public static function update_meta_action(Request $request){

    	$user=Auth::User();

    	 if(!in_array($user->role,[1,2])){
            $urusan=DB::table('user_urusan')

            ->select('id_urusan',DB::raw('(select nama from master_urusan where master_urusan.id =user_urusan.id_urusan ) as nama'))
            ->where('id_user',$user->id)->get();
            $first=(isset($urusan[0]))?$urusan[0]:[];
        }else{
            $urusan=DB::table('master_urusan')->select('id as id_urusan','nama')
            ->whereIn('id',[3,4,15,16,20,21,25])
            ->get();

            $first=(isset($urusan[0]))?$urusan[0]:[];


        }

        $list_urusan=[];
        foreach($urusan as $u){
            $list_urusan[$u->id_urusan]=$u->nama;
        }

        $list_urusan_key=array_keys($list_urusan);
      

        // dd($request->tahun);
        session(['fokus_tahun' => $request->tahun]);
        session(['route_access' => $list_urusan]);
        session(['fokus_urusan' => (array) $first]);


        return redirect('home');

    }


    public function force_login(Request $request){

    	$user=DB::table('users')
    	->where('email',$request->email)
    	->where('password',$request->pass)->first();
        $app=false;
        if($user){
            if(in_array($user->role,[1,2])){
            $app=true;
            }else{
            $app=DB::table('user_urusan')->where('id_user',$user->id)->first();
            }
        }

    	if($app){
    		$login=DB::table('fingerprint')->where('id_finger',$request->token)->first();
    		if($login){
    			$k=DB::table('fingerprint')->where('id_finger',$request->token)->update([
    				'id_user'=>$user->id,
    				'approve_login'=>true
    			]);
    			if($k){
    				return array('code'=>200,'message'=>'successs login');
    			}

    		}else{
    				return array('code'=>401,'message'=>'gagal login');
    		}
    	}else{
    		return array('code'=>401,'message'=>'gagal login');
    	}

    }

    public function update_token(Request $request){
    	$finger=$request->fingerprint();

    	$key=explode('MT|',$request->key)[1];
    	$token=DB::table('fingerprint')->where('id_finger',$key)->first();
    	if($token){
    		$valid=Validator::make(['id_finger'=>$finger],[
    			'id_finger'=>'required|string|exists:fingerprint,id_finger'
    		]);

    		if($valid->fails()){
    			$token=DB::table('fingerprint')->where('id_finger',$key)->update([
	    			'id_finger'=>$finger,
	    			'id_user'=>null,
	    			'updated_at'=>Carbon::now()
    			]);
    		}else{
    			$token=DB::table('fingerprint')->where('id_finger',$finger)->update([
	    			'id_finger'=>$finger,
	    			'id_user'=>null,
	    			'updated_at'=>Carbon::now()
    			]);

    		}

    		return array('code'=>200,'data'=>static::encript($finger),'fingerprint'=>static::encript($finger,true)); 
    		
    	}else{
    		return array('code'=>100,'data'=>null);

    	}


    }
}
