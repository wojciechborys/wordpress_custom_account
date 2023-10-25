<?php 

namespace fpern;

class Account_Details_Page
{
    use Render_Trait;

    public function __construct(){
        add_action('fpern_account_details', [$this, 'display_page']);
    }

    public function display_page(){
        $user = wp_get_current_user();
        $errors = [];
        $form_updated = false;
        if(!empty($_POST['fpern_action']) && $_POST['fpern_action'] == 'fpern_save_beneficjent' && !empty($_POST['Form'])){
            $form = new Beneficjent_Form();
            $form->load($_POST['Form']);
            if($form->validate()) {
                $form->save();
                update_user_meta($user->id, 'data_saved', true);
                $form_updated = true;
            }
            else{
                $errors = $form->errors;
            }
        }

        $user_name = $user->user_login;;
        $user_email = $user->user_email;
        $user_accepted = get_field('zaakceptuj_uzytkownika', 'user_' . $user->ID);
        $data_saved = get_user_meta($user->id, 'data_saved');
        echo $this->render('beneficjent/_account-details', [
            'user_name' => $user_name,
            'user_email' => $user_email,
            'organisation_fields' => $this->get_organisation_data_fields($errors),
            'contact_fields' => $this->get_contact_fields($errors),
            'correspondence_fields' => $this->get_correspondence_fields($errors),
            'contact_person_fields' => $this->get_contact_person_fields($errors),
            'consent_fields' => $this->get_consent_fields($errors),
            'errors' => $errors,
            'data_saved' => $data_saved,
            'user_accepted' => $user_accepted,
            'form_updated' => $form_updated,
        ]);
    }

    protected function get_organisation_data_fields($errors = []){                
        return $this->get_group_fields('group_642d36d438ec9', $errors);
    }

    protected function get_contact_fields($errors = []){                
        return $this->get_group_fields('group_642e6db3eb7d2', $errors);
    }

    protected function get_correspondence_fields($errors = []){
        return $this->get_group_fields('group_642e6f1d5c6ec', $errors);
    }

    protected function get_contact_person_fields($errors = []){
        return $this->get_group_fields('group_642e702ae8032', $errors);
    }

    protected function get_consent_fields($errors = []){
        return $this->get_group_fields('group_642e730d9676c', $errors);
    }

    protected function get_group_fields($group_id, $errors = []){       
        $result = []; 
        $fields = acf_get_fields($group_id);
        if(!empty($fields)){
            foreach($fields as $field){

                $result[$field['name']] = [
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'value' => get_user_meta(get_current_user_id(), $field['name'], true),
                    'placeholder' => $field['label'],
                    'maxlength' => isset($field['maxlength']) ? $field['maxlength'] : null,
                    'errors' => !empty($errors[$field['name']]) ? $errors[$field['name']] : [],
                    'choices' => !empty($field['choices']) ? $field['choices'] : [],
                    'required' => Beneficjent_Form::is_field_required($field['name'])
                ];

                if(isset($_POST['Form'][$field['name']])) $result[$field['name']]['value'] = $_POST['Form'][$field['name']];
            }
        }

        return $result;
    }
}