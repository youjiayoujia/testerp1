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

        $user = new UserModel();
        $user = $user->find(14);

        /*$gate->define('default', function ($user){
            $this->users = $user;
            return true;
        });*/
            
        //print_r($this->users);exit;
        foreach($user->role as $role){
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
        }
        /*$gate->define('default', function ($user, $post){
            foreach($user->role as $role){
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
            }
        });*/
    }
}



