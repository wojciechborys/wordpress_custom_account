<?php
namespace fpern;

if ( ! is_admin() ) {
    require_once( ABSPATH . 'wp-admin/includes/post.php' );
}
class Application_Form
{
    public $errors = [];
    private $fields = [];
	private $files = [];

    public function load($array)
    {
        $this->fields = $array;
    }

    public function validate($status)
    {
        if($status == 'draft') {
            $rules = [
                'szczegolowy_opis_projektu_pdf' => ['pdf'],
                'szczegolowy_budzet_projektu_xls' => ['xls'],
                'file_1' => ['pdf' ],
                'file_2' => ['pdf'],
                'file_3' => ['pdf'],
                'file_4' => ['pdf'],
            ];
        } else {
            $rules = [
                'coop' => ['required'],
                'coop_billing' => ['required'],
                'desc' => ['required'],
                'desc_1' => ['required'],
                'desc_3' => ['required'],
                'desc_4' => ['required'],
                'koszt_calkowity'  => ['required'],
                'uzyskane_darowizny'  => ['required'],
                'udzial_wlasny_wnioskodawcy'  => ['required'],
                'kwota_brakujaca'  => ['required'],
                'szczegolowy_opis_projektu_pdf' => ['pdf'],
                'szczegolowy_budzet_projektu_xls' => ['xls'],
                'cp_nazwisko_i_imie' => ['required'],
                'cp_email' => ['required'],
                'cp_telefon_kontaktowy' => ['required'],
                'accept_1' => ['required'],
                'accept_2' => ['required'],
                'accept_3' => ['required'],
                'accept_4' => ['required'],
                'file_1' => ['pdf'],
                'file_2' => ['pdf'],
                'file_3' => ['pdf'],
                'file_4' => ['pdf'],
            ];
        }

        foreach ($rules as $field_name => $rules) {
            foreach ($rules as $rule) {
                $this->validate_rule($field_name, $rule);
            }
        }

        return empty($this->errors);
    }

    public function save($status = 'publish', $post_id = null)
    {
        if (isset($_GET['id'])) {
            $post_id = absint($_GET['id']);

            $post_data = array(
                'ID' => $post_id,
                'post_status' => $status  // Set the post status as desired
            );

            $updated = wp_update_post($post_data);
            $this->save_status($post_id, $status);
            if ($updated) {
                foreach ($this->fields as $field_name => $field_value) {
                    update_field($field_name, $field_value, $post_id);
                }

                return true;
            }
        } else {
            $number = random_int(100000, 999999);
            $post_title = __('Wniosek ', 'pern') . $number;

            while (post_exists($post_title)) {
                $number = random_int(100000, 999999);
                $post_title = __('Wniosek ', 'pern') . $number;
            }

            $post_data = array(
                'post_type' => 'wnioski', // Custom post type slug
                'post_title' => $post_title, // Set the post title as desired
                'post_status' => $status  // Set the post status as desired
            );

            // Insert the post
            $post_id = wp_insert_post($post_data);
            $this->save_status($post_id, $status);

            if ($post_id) {
                // Save the form fields as ACF fields for the post
                foreach ($this->fields as $field_name => $field_value) {
                    update_field($field_name, $field_value, $post_id);
                }

                return true;
            }
        }
        
        return false;
    }
    
    protected function save_status($post_id, $status) {
        update_field('app_status', $status, $post_id);
        $post_author = intval(get_post_field('post_author', $post_id));
        if ($status === 'publish') {
            $post_author = 1; //admin
            $this->send_email($post_author);
        } elseif ($status === 'accepted' || $status === 'rejected') {
            $post_author = intval(get_post_field('post_author', $post_id));
            $this->send_email($post_author);//send email to post author
        }
    }

    protected function send_email($user_id) {
        // Get the email address of the admin to send the email
        $user_mail = get_the_author_meta('user_email', $user_id);

        $subject = 'Nowy status wniosku';

        $message = 'Nowy status wniosku dla Twojej aplikacji.';

        wp_mail($user_mail, $subject, $message);
    }
    
    protected function validate_rule($field_name, $rule_name)
    {
        switch ($rule_name) {
            case 'required':
                if (empty($this->fields[$field_name]) || empty(trim($this->fields[$field_name]))) {
                    $this->add_error($field_name, 'To pole jest wymagane');
                }
                break;

            case 'pdf':
                if(!empty($_FILES['Form']['name'][$field_name])){
					$uploaded_file_name = $_FILES['Form']['name'][$field_name];
					$uploaded_file_tmp_path = $_FILES['Form']['tmp_name'][$field_name];
					$extension_valid = strtolower(pathinfo($uploaded_file_name, PATHINFO_EXTENSION)) == 'pdf';
					$mime_valid = mime_content_type($uploaded_file_tmp_path) == 'application/pdf';
					
					if(!$mime_valid || !$extension_valid){
						$this->add_error($field_name, 'Dozwolone są wyłącznie pliki w formacie <strong>*.pdf</strong>');
					}
					else{
						$file = new File();
						$file_id = $file->upload($uploaded_file_name, $uploaded_file_tmp_path);
						if(!$file_id){
							$this->add_error($field_name, 'Nie udało się wgrać pliku.');
						}
						else{
							$this->fields[$field_name] = $file_id;
						}
					}
				}
                break;

            case 'xls':
                if (!empty($_FILES['Form']['name'][$field_name])) {
                    $uploaded_file_name = $_FILES['Form']['name'][$field_name];
                    $uploaded_file_tmp_path = $_FILES['Form']['tmp_name'][$field_name];
                    $extension_valid = in_array(strtolower(pathinfo($uploaded_file_name, PATHINFO_EXTENSION)), ['xls', 'xlsx']);
                    $mime_valid = in_array(mime_content_type($uploaded_file_tmp_path), ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
            
                    if (!$mime_valid || !$extension_valid) {
                        $this->add_error($field_name, 'Dozwolone są wyłącznie pliki w formacie <strong>*.xls</strong> lub <strong>*.xlsx</strong>');
                    } else {
                        $file = new File();
                        $file_id = $file->upload($uploaded_file_name, $uploaded_file_tmp_path);
                        if (!$file_id) {
                            $this->add_error($field_name, 'Nie udało się wgrać pliku.');
                        } else {
                            $this->fields[$field_name] = $file_id;
                        }
                    }
                }
                break;
        }
    }

    protected function add_error($field_name, $message)
    {
        if (!isset($this->errors[$field_name])) {
            $this->errors[$field_name] = [];
        }

        $this->errors[$field_name][] = $message;
    }

    public static function is_field_required($field_name){
		return !empty(self::$rules[$field_name]) && in_array('required', self::$rules[$field_name]);
	}
}