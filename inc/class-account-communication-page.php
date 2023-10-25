<?php

namespace fpern;

class Account_Communication_Page
{
    use Render_Trait;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_ajax']);
        add_action('fpern_account_communication', [$this, 'display_page']);
        add_action('wp_ajax_check_new_comments', [$this, 'check_new_comments']);
        add_action('wp_ajax_nopriv_check_new_comments', [$this, 'check_new_comments']);

        add_action('wp_ajax_load_comments_callback', [$this, 'load_comments_callback']);
        add_action('wp_ajax_nopriv_load_comments_callback', [$this, 'load_comments_callback']);
    }

    public function display_page()
    {
        // List user posts and store the post information in an array
        $user = wp_get_current_user();
        $is_admin = in_array('administrator', $user->roles);
        $posts = $this->list_user_posts();

        if (isset($_POST['comment_content']) && isset($_POST['post_id'])) {
            $this->add_comment_callback();
        }

        $current_user_id = get_current_user_id();
        $comments = $this->load_comments_callback();
        $post_id = isset($_GET['wniosek']) ? intval($_GET['wniosek']) : null;
        $last_comment = $this->get_last_comment($post_id);
        $last_comment_user_id = intval($last_comment->user_id);
        if ($post_id && $last_comment && $last_comment_user_id !== $current_user_id) {
            $meta_key = 'new_comments';
            $meta_value = false;
            delete_post_meta($post_id, $meta_key, $meta_value);
        }

        echo $this->render('elements/_account-communication-page', [
            'comments' => $comments,
            'posts' => $posts,
            'current_user_id' => $current_user_id,
            'last_author' => $last_comment_user_id,
        ]);
    }

    public function enqueue_ajax()
    {
        wp_enqueue_script('enqueue_ajax', get_template_directory_uri() . '/framework/assets/src/js/ajax.js', array('jquery'), '1.0', true);
        wp_localize_script('enqueue_ajax', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function list_user_posts()
    {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $is_admin = in_array('administrator', $user->roles);

        if ($is_admin) {
            $args = array(
                'post_type' => 'wnioski',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );

            $query = new \WP_Query($args);
            $posts = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    $user_id = get_the_author_meta('ID');
                    $application_number = get_the_title();
                    get_post_meta($post_id, '', $meta_value);
                    // Store the post information in the array
                    $post_data = array(
                        'application_number' => $application_number,
                        'post_status' => $post_status,
                        'post_id' => $post_id,
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
                'post_status' => 'publish',
            );

            $query = new \WP_Query($args);
            $posts = array();

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    $user_id = get_the_author_meta('ID');
                    $application_number = get_the_title();
                    // Store the post information in the array
                    $post_data = array(
                        'application_number' => $application_number,
                        'post_status' => $post_status,
                        'post_id' => $post_id,
                    );
                    $posts[] = $post_data;
                }
                wp_reset_postdata();
            }
        }

        return $posts;
    }

    private function load_comments_callback()
    {
        $comments = array();
        if (isset($_GET['wniosek']) || isset($_POST['post_id'])) {
            $post_id = intval($_GET['wniosek']);
            $comments_args = array(
                'post_id' => $post_id,
                'order' => 'ASC',
                'status' => 'approve', // Only load approved comments
            );
            $comments = get_comments($comments_args);
        }

        return $comments;
    }

    public function add_comment_callback()
    {
        $response = array();
    
        $post_id = intval($_POST['post_id']);
        $comment_content = sanitize_text_field($_POST['comment_content']);
    
        // Check if the current user is the author of the post
        $post = get_post($post_id);
        if ($post && isset($_POST['comment_content'])) {
    
            // Check if the comment author is the app author
            $post_author_id = get_post_field('post_author', $post_id);
            $is_app_author_comment = (intval($post_author_id) === get_current_user_id());

            // Create the comment
            $comment_data = array(
                'comment_post_ID' => $post_id,
                'comment_content' => $comment_content,
                'comment_author' => get_the_author_meta('display_name', get_current_user_id()), // Retrieve the author display name
                'user_id' => get_current_user_id(),
            );
    
            $comment_id = wp_insert_comment($comment_data);
    
            // Add custom post meta
            $meta_key = 'new_comments';
            $meta_value = true;
            add_post_meta($post_id, $meta_key, $meta_value);
    
            // Check if the current user is admin
            $is_admin = current_user_can('administrator');
    
            // Send email notification to admin when the app author leaves a comment
            if ($is_app_author_comment) {
                // Get the email address of the admin to send the email
                $admin_email = get_option('admin_email');
                $subject = 'Nowa wiadomość od autora wniosku Fundacja PERN';
    
                $message = 'Sprawdź swoje konto Fundacja PERN, aby przeczytać nową wiadomość';
    
                wp_mail($admin_email, $subject, $message);
            }
    
            // Send email notification to the app author when the admin leaves a comment
            if ($is_admin) {
                $app_author_email = get_the_author_meta('user_email', $post_author_id);
                
                $subject = 'Nowa wiadomość od Administratora Fundacja PERN';
    
                $message = 'Sprawdź swoje konto Fundacja PERN, aby przeczytać nową wiadomość';
    
                wp_mail($app_author_email, $subject, $message);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'You are not the author of this post.';
        }
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
