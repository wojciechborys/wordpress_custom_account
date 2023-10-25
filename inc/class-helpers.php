<?php 
namespace fpern;

class Helpers {
    public static function getCurrentUser() {
        $current_user = wp_get_current_user();
        return $current_user;
    }
} 
?>