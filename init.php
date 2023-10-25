<?php 

namespace fpern;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(function($class){
    $name = explode('\\', $class);
    if($name[0] == 'fpern') {
        $name = end($name);
        $name = strtolower($name);
        $name = str_replace('_', '-', $name);
        $prefix = 'class';

        if(strpos($name, '-trait') !== false) {
            $prefix = 'trait';
            $name = str_replace('-trait', '', $name);
        }

        require_once __DIR__ . '/inc/'.$prefix.'-'.$name.'.php';
    }
});


define('ACCOUNT_PARENT', 47371);
define('ACCOUNT_LOGIN', 47464);
define('ACCOUNT_REGISTER', 47462);
define('ACCOUNT_DASHBOARD_PAGE_ID', 47494);
define('ACCOUNT_DETAILS_PAGE_ID', 47405);
define('ACCOUNT_APPLICATION_PAGE_ID', 47749);
define('ACCOUNT_COMMUNICATION_PAGE_ID', 47496);
define('ACCOUNT_MAIN_PAGE_ID', 47789); //admin
define('ACCOUNT_USERS_PAGE_ID', 47797); //admin
define('ACCOUNT_APPLICATIONS_PAGE_ID', 47799); //admin
define('ACCOUNT_USER_PAGE_ID', 47804); //admin

new Framework;