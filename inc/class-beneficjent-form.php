<?php 

namespace fpern;

class Beneficjent_Form
{
	public $errors = [];

	private $fields = [];

	private $files = [];

	public static $rules = [
		'pelna_nazwa_organizacji' => ['required'],
		'krs' => ['required'],
		'numer_konta_bankowego' => ['required'],
		'opis_dzialalnosci' => ['required'],
		'cp_nazwisko_i_imie' => ['required'],
		'cp_email' => ['required'],
		'cp_telefon_kontaktowy' => ['required'],
		'zgoda_dane_osobowe' => ['required'],
		'skan_krs' => ['pdf'],
		'skan_status' => ['pdf']
	];

	public function load($array){
		$this->fields = $array;
	}

	public function validate(){
		foreach(self::$rules as $field_name => $rules){
			foreach($rules as $rule){
				$this->validate_rule($field_name, $rule);
			}			
		}

		return empty($this->errors);
	}

	public function save(){
		$id = get_current_user_id();
		if(!$id) return false;
		foreach($this->fields as $name => $value){
			update_user_meta($id, $name, $value);
		}
		return true;
	}

	protected function validate_rule($field_name, $rule_name){
		switch($rule_name){
			case 'required': 
				if(empty($this->fields[$field_name]) || empty(trim($this->fields[$field_name]))){
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
		}
	}

	protected function add_error($field_name, $message){
		if(!isset($this->errors[$field_name])){
			$this->errors[$field_name] = [];
		}

		$this->errors[$field_name][] = $message;
	}

	public static function is_field_required($field_name){
		return !empty(self::$rules[$field_name]) && in_array('required', self::$rules[$field_name]);
	}
}