<?php 

namespace fpern;

class Framework
{
    public function __construct(){
        $this->load_dependencies();

        add_action('fpern_account', [$this, 'display_account_action']);
    }

    protected function load_dependencies() {
        $this->user_role_manager = new User_Role_Manager;
        $this->assets_manager = new Assets_Manager;
        $this->account_dashboard_page = new Account_Dashboard_Page;
        $this->account_details_page = New Account_Details_Page;
        $this->account_application_page = New Account_Application_Page;
        $this->account_main_page = New Account_Main_Page;
        $this->account_applications_page = New Account_Applications_Page;
        $this->account_users_page = New Account_Users_Page;
        $this->account_single_user_page = New Account_Single_User_Page;
        $this->account_communication_page = New Account_Communication_Page;
    }

    public function display_account_action(){

        if(!is_user_logged_in()) return;
        //add admin validation
        $page_id = get_the_ID();

        if($page_id == ACCOUNT_DETAILS_PAGE_ID) do_action('fpern_account_details');
        elseif($page_id == ACCOUNT_DASHBOARD_PAGE_ID) do_action('fpern_account_dashboard');
        elseif($page_id == ACCOUNT_APPLICATION_PAGE_ID) do_action('fpern_account_application');
        elseif($page_id == ACCOUNT_MAIN_PAGE_ID) do_action('fpern_account_main');
        elseif($page_id == ACCOUNT_USERS_PAGE_ID) do_action('fpern_account_users');
        elseif($page_id == ACCOUNT_APPLICATIONS_PAGE_ID) do_action('fpern_account_applications');
        elseif($page_id == ACCOUNT_USER_PAGE_ID) do_action('fpern_account_single_user');
        elseif($page_id == ACCOUNT_COMMUNICATION_PAGE_ID) do_action('fpern_account_communication');
    }
}