<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use HP;
use Validator;
use DB;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


     public function login(Request $request){
         // Hp::checkdb($request->tahun);

         $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
             $this->fireLockoutEvent($request);
             return $this->sendLockoutResponse($request);
         }


        $valid=Validator::make($request->all(),[
            'email'=>'string|exists:users,email',
            'tahun'=>'numeric|min:2020'
        ]);
        

        if($valid->fails()){
            Alert::error('Gagal','Cek Email dan Password Atau Hubungin Administrator');
            return back();

        }else{

               $user=DB::table('users')->where('email',$request->email)->first();
                if($user->password==md5($request->password)){
                     Auth::loginUsingId($user->id, true);

                    // if(!in_array($user->role,[1,2])){
                    //     $urusan=DB::table('user_urusan')
                    //     ->select('id_urusan',DB::raw('(select nama from master_urusan where master_urusan.id =user_urusan.id_urusan ) as nama'))
                    //     ->where('id_user',$user->id)->get();
                    //     $first=(isset($urusan[0]))?$urusan[0]:[];
                    // }else{
                    //     $urusan=DB::table('master_urusan')->select('id as id_urusan','nama')
                    //     ->whereIn('id',[3,4,15,16,20,21,25])
                    //     ->get();
                    //     $first=(isset($urusan[0]))?$urusan[0]:[];


                    // }

                    // $list_urusan=[];
                    // foreach($urusan as $u){
                    //     $list_urusan[$u->id_urusan]=$u->nama;
                    // }

                    // $list_urusan_key=array_keys($list_urusan);
                  

                    session(['fokus_tahun' => $request->tahun]);
                    // session(['route_access' => $list_urusan]);
                    // session(['fokus_urusan' => (array) $first]);


                }else{

                    return $this->sendLoginResponse($request);



                }

                $this->incrementLoginAttempts($request);

                // if(!Hp::fokus_urusan()){
                //     return redirect('login');
                // }

                return $this->sendFailedLoginResponse($request);


        }

     




    }
}
