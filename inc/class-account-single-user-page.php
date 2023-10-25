<?php 

namespace fpern;

class Account_Single_User_Page
{
    use Render_Trait;

    public function __construct(){
        add_action('fpern_account_single_user', [$this, 'display_page']);
    }
    
    public function display_page() {
        $errors = [];

        $user_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        if(!empty($_POST['fpern_action'])){
            if($_POST['fpern_action'] == 'fpern_accept_user') {
                update_field('zaakceptuj_uzytkownika', 'true', 'user_' . $user_id);
            } else if($_POST['fpern_action'] == 'fpern_reject_user') {
                update_field('zaakceptuj_uzytkownika', 'false', 'user_' . $user_id);
            }
        }

        $user_accepted = get_field('zaakceptuj_uzytkownika', 'user_' . $user_id);
        $user_info = get_userdata($user_id);
        echo $this->render('admin/_account-single-user', [
            'user_name' => $user_info->display_name ? $user_info->display_name : $user_info->user_login,
            'user_email' => $user_info->user_email,
            'organisation_fields' => $this->get_organisation_data_fields($errors),
            'user_id' => isset($_GET['id']) ? absint($_GET['id']) : null,
            'contact_data' => $this->get_contact_data($errors),
            'user_accepted' => $user_accepted,
            'contact_person_fields' => $this->get_contact_person_fields($errors),
            'errors' => $errors,
        ]);
    }

    protected function redirect($url)
    {
        echo '<script>window.location.href = "' . esc_url($url) . '";</script>';
    }

    protected function get_organisation_data_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_642d36d438ec9', $errors, $post_id);
    }

    protected function get_contact_data($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;
        return $this->get_group_fields('group_642e6db3eb7d2', $errors, $post_id);
    }

    protected function get_contact_person_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;

        return $this->get_group_fields('group_642e702ae8032', $errors, $post_id);
    }
    
    protected function get_contact_fields($errors = [])
    {
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : null;

        return $this->get_group_fields('group_642e702ae8032', $errors, $post_id);
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

                $value = get_user_meta($post_id, $field['name'], true);

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
                    'required' => Application_Form::is_field_required($field['name'])
                ];

                if(isset($_POST['Form'][$field['name']])) $result[$field['name']]['value'] = $_POST['Form'][$field['name']];
            }
        }

        return $result;
    }
}