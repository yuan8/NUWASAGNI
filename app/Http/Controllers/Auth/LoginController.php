<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use HP;
use Validator;
use DB;
use Auth;
use Alert;
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
            'tahun_akses'=>'numeric|min:'.(date('Y')-1)
        ]);
        

        if($valid->fails()){
            Alert::error('Gagal','Cek Email dan Password Atau Hubungin Administrator');
            return back();

        }else{

               $user=DB::table('users')->where('email',$request->email)->first();
                if($user->password==md5($request->password)){
                    session(['fokus_tahun' => $request->tahun_akses]);

                    Auth::loginUsingId($user->id, true);

                    session(['fokus_tahun' => $request->tahun_akses]);
                   

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
