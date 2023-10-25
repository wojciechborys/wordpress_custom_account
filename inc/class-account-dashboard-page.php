<?php

namespace fpern;

class Account_Dashboard_Page
{
    use Render_Trait;
    public function __construct()
    {
        add_action('fpern_account_dashboard', [$this, 'display_page']);
    }

    public function display_page()
    {
        // List user posts and store the post information in an array
        $user = wp_get_current_user();

        $is_admin = in_array('administrator', $user->roles);
        $posts = $this->list_user_posts();

        if($is_admin) {
            echo $this->render('admin/_account-dashboard', [
                'posts' => $posts,
            ]);
        } else {
            $user_accepted = get_field('zaakceptuj_uzytkownika', 'user_' . $user->ID);
            $data_saved = get_user_meta($user->id, 'data_saved');
            echo $this->render('beneficjent/_account-dashboard', [
                'posts' => $posts,
                'user_accepted' => $user_accepted,
                'data_saved' => $data_saved,
        ]);
        }
    }

    public function list_user_posts()
    {   
        $user = wp_get_current_user();
        $user_id = $user->id;

        $is_admin = in_array('administrator', $user->roles);
        if($is_admin) {

            $args = array(
                'post_type' => 'wnioski',
                'posts_per_page' => -1,
                'post_status' => 'any'
            );

            $query = new \WP_Query($args);
            $posts = array();
            if ($query->have_posts()) {

                while ($query->have_posts()) {

                    $query->the_post();
                    
                    $user_id = get_the_author_meta( 'ID' );

                    // Get post metadata    
                    $project_name = get_field('pelna_nazwa_organizacji', 'user_' . $user_id);
                    $project_email = get_field('email', 'user_' . $user_id);
                    $post_date = get_the_date();
                    $application_number = get_the_title();
                    $post_amount = get_field('koszt_calkowity', get_the_ID());
                    $post_status = get_field('app_status', get_the_ID());
                    $post_link = home_url().'/moje-konto/wniosek?id=' . get_the_ID(); 
                    // Store the post information in the array
                    $post_data = array(
                        'project_name' => $project_name,
                        'post_date' => $post_date,
                        'application_number' => $application_number,
                        'post_amount' => $post_amount,
                        'post_status' => $post_status,
                        'post_link' => $post_link,
                    ); 
                    $posts[] = $post_data;
                }
                wp_reset_postdata();
            }

           
        } else {
            $args = array(
                'post_type' => 'wnioski',
                'author' => $user_id,
                'posts_per_page' => -1,
                'post_status' => 'any'
            );

            $query = new \WP_Query($args);
            $posts = array();

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    // Get post metadata    
                    $project_name = get_field('pelna_nazwa_organizacji', 'user_' . $user_id);
                    $post_date = get_the_date();
                    $last_update_date = get_the_modified_date();
                    $application_number = get_the_title();
                    $amount_requested = get_field('kwota_brakujaca', get_the_ID());
                    $post_status = get_field('app_status', get_the_ID());
                    $post_link = home_url().'/moje-konto/wniosek?id=' . get_the_ID(); 
                    // Store the post information in the array
                    $post_data = array(
                        'project_name' => $project_name,
                        'post_date' => $post_date,
                        'last_update_date' => $last_update_date,
                        'application_number' => $application_number,
                        'amount_requested' => $amount_requested,
                        'post_status' => $post_status,
                        'post_link' => $post_link,
                    ); 
                    $posts[] = $post_data;
                }
                wp_reset_postdata();
            }
        }
        
      

       
        return $posts;
    }
}