<?php 

namespace fpern;

class Account_Application_Page
{
    use Render_Trait;

    public function __construct(){
        add_action('fpern_account_application', [$this, 'display_page']);
    }
    
    public function display_page(){
        $errors = [];
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        $post = !empty($post_id) ? get_post($post_id) : NULL;
        $post_author_id = intval($post->post_author);
        $user = wp_get_current_user();
        $user_accepted = get_field('zaakceptuj_uzytkownika', 'user_' . $user->ID);
        if (
            empty($user_accepted) ||
            (!empty($user_accepted) && $user_accepted == 'false') ||
            (!current_user_can('administrator') && !empty($post_id) && get_current_user_id() !== $post_author_id)
        ) {
            echo '<h1>Brak dostępu.</h1>';
            return false;
        }

        if(!empty($_POST['fpern_action']) && !empty($_POST['Form']) && !$is_admin) {
            $form = new Application_Form();
            $form->load($_POST['Form']);
            if($_POST['fpern_action'] == 'fpern_save_application') {
                if($form->validate('publish')){
                    $form->save();
                    $this->add_comment($post_id);
                    $success_url = home_url('/moje-konto/wnioski?status=application-success"');
                    $this->redirect($success_url);
                }
                
                else {
                    $errors = $form->errors;
                }
            } else {
                if($form->validate('draft')){
                    $form->save('draft', $post_id);
                    $success_url = home_url('/moje-konto/wnioski?status=draft-success"');
                    $this->redirect($success_url);
                    if (empty($post_id)) {
                        $post_id = $form->get_saved_post_id(); // Get the newly created post ID
                    }
                } 
            }
        } else {
            if($_POST['fpern_action'] == 'fpern_accept_application') {
                $this->save_status($post_id, 'accepted');
            } else if($_POST['fpern_action'] == 'fpern_reject_application') {
                $this->save_status($post_id, 'rejected');
            }
        }

        $is_admin = in_array('administrator', $user->roles);

        if($is_admin) {
            echo $this->render('admin/_account-application', [
                'organisation_fields' => $this->get_organisation_data_fields($errors),
                'coop_fields' => $this->get_previous_coop_fields($errors),
                'billing_fields' => $this->get_billing_fields($errors),
                'info_fields' => $this->get_info_fields($errors),
                'data_fields' => $this->get_data_fields($errors),
                'document_fields' => $this->get_document_fields($errors),
                'contact_person_fields' => $this->get_contact_person_fields($errors),
                'consent_fields' => $this->get_consent_fields($errors),
                'post_status' => $this->get_post_status(),
                'errors' => $errors,
            ]);
        } else {
            echo $this->render('beneficjent/_account-application', [
                'organisation_fields' => $this->get_organisation_data_fields($errors),
                'coop_fields' => $this->get_previous_coop_fields($errors),
                'billing_fields' => $this->get_billing_fields($errors),
                'info_fields' => $this->get_info_fields($errors),
                'data_fields' => $this->get_data_fields($errors),
                'document_fields' => $this->get_document_fields($errors),
                'contact_person_fields' => $this->get_contact_person_fields($errors),
                'consent_fields' => $this->get_consent_fields($errors),
                'post_status' => $this->get_post_status(),
                'user_accepted' => $user_accepted,
                'errors' => $errors,
            ]);
        }
    }

    protected function redirect($url)
    {
        echo '<script>window.location.href = "' . esc_url($url) . '";</script>';
    }

    protected function save_status($post_id, $status) {
        update_field('app_status', $status, $post_id);
    }
    
    protected function add_comment($postId) {
        $comment_content = __('Twój wniosek został złożony');
        $commentdata = array(
            'comment_post_ID' => $postId,
            'comment_author' => 19,
            'comment_content' => $comment_content,
            'comment_approved' => 1,
        );
        wp_insert_comment($commentdata);
    }

    protected function get_post_status() {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        $status = $post_id !== null ? get_field('app_status', $post_id) : '';
        return $status;
    }

    protected function get_organisation_data_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_642d36d438ec9', $errors, $post_id);
    }

    protected function get_previous_coop_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_649acf61bbb6f', $errors, $post_id);
    }

    protected function get_billing_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_649c905c4d35f',  $errors, $post_id);
    }

    protected function get_info_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_649acf82c1807', $errors, $post_id);
    }

    protected function get_data_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_649c9bdb4c8c1', $errors, $post_id);
    }
    
    protected function get_document_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;

        return $this->get_group_fields('group_649acff56f292', $errors, $post_id);
    }

    protected function get_contact_person_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;

        return $this->get_group_fields('group_642e702ae8032', $errors, $post_id);
    }

    protected function get_consent_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;

        return $this->get_group_fields('group_649acfe0d8b0d', $errors, $post_id);
    }

    protected function get_group_fields($group_id, $errors = [], $post_id = null){       
        $result = []; 
        $fields = acf_get_fields($group_id, $post_id);

        if(!empty($fields)){
            foreach($fields as $field){
                $has_conditional_logic = !empty($field['conditional_logic']);
                if ($has_conditional_logic) {
                    foreach ($field['conditional_logic'] as $rule_group) {
                        foreach ($rule_group as $rule) {
                            $related_field_key = $rule['field'];
                            $related_field_name = ''; // Initialize the field name variable
                    
                            // Find the field with the matching key and retrieve its name
                            foreach ($fields as $field_item) {
                                if ($field_item['key'] === $related_field_key) {
                                    $related_field_name = $field_item['name'];
                                    break; // Exit the loop once the field is found
                                }
                            }
                    
                            $related_fields[] = $related_field_name;
                        }
                    }

                    $field_names[] = esc_attr($field['name']); // Standardized field name for conditional fields
                }

                if ($group_id === 'group_642d36d438ec9') {
                    $value = get_user_meta(get_current_user_id(), $field['name'], true);
                } else {
                    $value = get_field($field['name'], $post_id);
                }

                $is_field_empty = empty($value);
                $is_field_required = Application_Form::is_field_required($field['name']);
                $required = $is_field_required && !$is_field_empty;

                $result[$field['name']] = [
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'value' => $value,
                    'placeholder' => $field['label'],
                    'maxlength' => isset($field['maxlength']) ? $field['maxlength'] : null,
                    'errors' => !empty($errors[$field['name']]) ? $errors[$field['name']] : [],
                    'choices' => !empty($field['choices']) ? $field['choices'] : [],
                    'conditional_logic' => !empty($field['conditional_logic']) ? $field['conditional_logic'] : [],
                    'related_fields' => !empty($related_fields) ? $related_fields : [] ,
                    'required' => $required
                ];

                if(isset($_POST['Form'][$field['name']])) $result[$field['name']]['value'] = $_POST['Form'][$field['name']];
            }
        }

        return $result;
    }
}