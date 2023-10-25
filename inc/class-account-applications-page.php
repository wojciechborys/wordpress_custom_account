<?php

namespace fpern;

class Account_Applications_Page
{
    use Render_Trait;
    public function __construct()
    {
        add_action('fpern_account_applications', [$this, 'display_page']);
    }

    public function display_page()
    {
        // List user posts and store the post information in an array
        $user = wp_get_current_user();

        $posts = $this->list_user_posts();

        echo $this->render('admin/_account-applications', [
            'posts' => $posts,
        ]);
    }

    public function list_user_posts()
    {   
        $args = array(
            'post_type' => 'wnioski',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );

        if (isset($_GET['sort'])) {
            $args['orderby'] = sanitize_text_field($_GET['sort']);
        }

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
                $org_type = get_field('rodzaj_organizacji', 'user_' . $user_id);
                $application_number = get_the_title();
                $krs = get_field('krs', 'user_' . $user_id);
                $post_status = get_field('app_status', get_the_ID());
                $post_link = home_url().'/moje-konto/wniosek?id=' . get_the_ID(); 
                // Store the post information in the array
                $post_data = array(
                    'project_name' => $project_name,
                    'project_email' => $project_email,
                    'post_date' => $post_date,
                    'org_type' => $org_type,
                    'krs' => $krs,
                    'application_number' => $application_number,
                    'post_status' => $post_status,
                    'post_link' => $post_link,
                ); 

                $posts[] = $post_data;
            }
            
            wp_reset_postdata();
        }

        return $posts;
    }
}