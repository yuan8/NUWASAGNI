<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserRole
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }




    public function before(User $user)
    {
        if(session('fokus_tahun')) {
           if((session('fokus_tahun')!='')OR(!empty(session('fokus_tahun'))) ){

           }else{
            return false;
           }

        }else{
            return false;

        }
    }

    public function Daerah(User $user)
    {   
        
        if(in_array($user->role,[1,2,3])){
            return true;
        }else{
            return false;
        }

    }


    public function Admin(User $user){
        if(in_array($user->role,[1,2])){
            return true;
        }else{
            return false;
        }
    }


    public function SuperAdmin(User $user){
          if(in_array($user->role,[1])){
            return true;
        }else{
            return false;
        }
    }
}
