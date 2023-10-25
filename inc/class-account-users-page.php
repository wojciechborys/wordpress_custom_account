<?php

namespace fpern;


class Account_Users_Page
{
    use Render_Trait;
    public function __construct()
    {
        add_action('fpern_account_users', [$this, 'display_page']);
    }

    public function display_page()
    {
        // List user posts and store the post information in an array

        $user_list = $this->list_users();
        echo $this->render('admin/_account-users', [
            'posts' => $user_list,
        ]);
    }

    public function list_users()
    {   

        $args = array(
            'role' => 'beneficjent', // Replace with the desired user role
            'orderby' => 'registered',
            'order' => 'ASC',
        );

        if (isset($_GET['sort'])) {
            $args['orderby'] = sanitize_text_field($_GET['sort']);
        }
        
        $users = get_users($args);
        $user_list = array();
        
        foreach ($users as $user) {
            $user_id = $user->ID;
            $user_registered = $user->user_registered;
            $formatted_date = date('d/m/Y', strtotime($user_registered));

            // Get additional user meta data if needed
            $project_name = get_field('pelna_nazwa_organizacji', 'user_' . $user_id);
            $project_email = get_field('email', 'user_' . $user_id);
            $org_status = get_field('zaakceptuj_uzytkownika', 'user_' . $user_id);

            $full_name = $project_name ? $project_name : $user->display_name;
            $project_email = $project_email ? $project_email : $user->user_email;
            $org_type = get_field('rodzaj_organizacji', 'user_' . $user_id);
            $org_status = $org_status ? $org_status : __('W trakcie', 'pern');
            $krs = get_field('krs', 'user_' . $user_id);
            $post_link = home_url() . '/moje-konto/beneficjent?id=' . $user_id;
            $user_data = array(
                'project_name' => $full_name,
                'project_email' => $project_email,
                'post_date' => $formatted_date,
                'org_type' => $org_type,
                'krs' => $krs,
                'org_status' => $org_status,
                'post_link' => $post_link,
            );

            $user_list[] = $user_data;
        }

        return $user_list;
    }
}