<?php 

namespace fpern;

class Assets_Manager
{
    public function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts(){

        /**
         * MODAL
         */
        wp_enqueue_script('modal', get_template_directory_uri().'/framework/assets/libs/modal/jquery.modal.min.js', ['jquery'], null, true);
        wp_enqueue_style('modal', get_template_directory_uri().'/framework/assets/libs/modal/jquery.modal.min.css', [], null);      


        /**
         * APP JS
         */

        $relative_path = '/framework/assets/dist/js/app.js';
        $version = filemtime(get_template_directory().$relative_path);        
        wp_enqueue_script('app', get_template_directory_uri().$relative_path, ['jquery'], $version, true);
        wp_localize_script('app', 'settings', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);

        /**
         * APP CSS
         */

        $relative_path = '/framework/assets/dist/css/app.css';
        $version = filemtime(get_template_directory().$relative_path);        
        wp_enqueue_style('app', get_template_directory_uri().$relative_path, null, $version);


        /**
         * Disable Gutenberg Assets
         */
        // wp_dequeue_style( 'wp-block-library' );
        // wp_dequeue_style( 'wp-block-library-theme' );
        // wp_dequeue_style( 'global-styles' );
 
    }
}