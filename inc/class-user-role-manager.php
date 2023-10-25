<?php 

namespace fpern;

class User_Role_Manager
{
    public function __construct(){
        add_action('init', [$this, 'create_roles']);
        add_filter('login_redirect', [$this, 'login_redirect'], 10, 3);
    }

    public function create_roles(){
        add_role('beneficjent', 'Beneficjent', [

        ]);
        //$this->user_role_manager = new User_Role_Manager;
    }

    public function login_redirect($redirect_to, $request, $user ){
        if (isset($user->roles) && is_array($user->roles)) {
            if(in_array('beneficjent', $user->roles)) {
                $redirect_to = get_permalink(47371);
            }
        }
        return $redirect_to;
    }
}