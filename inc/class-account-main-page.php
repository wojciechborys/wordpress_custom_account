<?php

namespace fpern;

class Account_Main_Page
{
    use Render_Trait;
    public function __construct()
    {
        add_action('fpern_account_main', [$this, 'display_page']);
    }

    public function display_page()
    {
        // List user posts and store the post information in an array
        $user = wp_get_current_user();

        $is_admin = in_array('administrator', $user->roles);
        $posts = $this->list_user_posts();
        $users = $this->list_users();
        $comments = $this->load_comments_callback();

        if($is_admin) {
            echo $this->render('admin/_account-main', [
                'posts' => $posts,
                'users' => $users,
                'comments' => $comments,
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
                'posts_per_page' => 7,
                'post_status' => 'publish',
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
        } 
        return $posts;

    }

    public function list_users()
    {
        $args = array(
            'role' => 'beneficjent', // Replace with the desired user role
            'orderby' => 'registered',
            'order' => 'ASC',
            'number' => 2,
        );
        
        $users = get_users($args);
        $user_list = array();
        
        foreach ($users as $user) {
            $user_id = $user->ID;
            $user_registered = $user->user_registered;
            $formatted_date = date('d/m/Y', strtotime($user_registered));

            // Get additional user meta data if needed
            $project_name = get_field('pelna_nazwa_organizacji', 'user_' . $user_id);
            $project_email = get_field('email', 'user_' . $user_id);

            $full_name = $project_name ? $project_name : $user->display_name;
            $project_email = $project_email ? $project_email : $user->user_email;
            $post_link = home_url() . '/moje-konto/beneficjent?id=' . $user_id;
            $user_data = array(
                'project_name' => $full_name,
                'project_email' => $project_email,
                'post_link' => $post_link,
            );

            $user_list[] = $user_data;
        }
        return $user_list;
    }

    private function load_comments_callback()
    {
        $comments = array();
        $comments_args = array(
            'order' => 'DESC',
            'number' => 1,
            'status' => 'approve', // Only load approved comments
        );
        $comments = get_comments($comments_args);

        return $comments;
    } 

    private function get_last_comment($post_id)
    {
        $comments_args = array(
            'post_id' => $post_id,
            'number' => 1,
            'status' => 'approve',
            'order' => 'DESC',
        );

        $comments = get_comments($comments_args);

        if (!empty($comments)) {
            return $comments[0];
        }

        return null;
    }
}