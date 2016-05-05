<?php

namespace App\Providers;
use App\Policies\ProductPolicy;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserModel;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Models\ProductModel' => 'App\Policies\ProductPolicy',
    ];

    //protected $users = [];

    /*public function __construct()
    {
        $this->users = $users;
    }*/


    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        //$user = new UserModel();
        //$user = $user->find(14);

        /*$gate->define('default', function ($user){
            $this->users = $user;
            return true;
        });*/
            
        //print_r($this->users);exit;
        //循环权限
        /*foreach($user->role as $role){
            $gate->define($role->role, function ($user, $post){
                foreach($user->role as $role){
                    //print_r(explode('|',$post)[0]);exit;
                    if(explode('_',$role->role)[0]==explode('|',$post)[0]){
                        //echo '<pre>';print_r($role->permission);exit;
                        foreach($role->permission as $permission){
                            //echo $permission->action;
                            if($permission->action==explode('|',$post)[1]){
                                return true;
                            }
                        }
                    }
                }
            });   
        }*/
        //去找权限
        $gate->define('check', function ($user, $post){

            $post_array = explode('|', $post);//print_r($post_array);
            $role_array = explode(',', $post_array[0]);
            //print_r($role_array);exit;  
            foreach($user->role as $role){
                if(in_array($role->role, $role_array)){
                    //echo '<pre>';
                    //print_r($role);
                    foreach($role->permission as $permission){echo $permission->action;
                        if(in_array($permission->action, $post_array)){//echo 111;exit;
                            return true;
                        }
                    }
                }
            }//exit;
        });


    }
}



