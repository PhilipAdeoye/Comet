<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/User.php';
require_once APPPATH.'data_classes/Training_level.php';

/**
 * Account Controller which handles user login, registration, forgotten passwords, etc
 * 
 * @author Philip 
 */
class Account extends CI_Controller {

    const TEMPLATE = 'shared/layout';
    public $page_title_prefix = '';

    public function __construct() {
        parent::__construct();
        $this->page_title_prefix = $this->config->item('clinic_name_abbr').' | ';

        $this->load->model('training_level_model');
        $this->load->model('user_model');
        $this->load->model('partner_model');
        $this->load->model('location_model');
        
        if (!is_null($_POST) && count($_POST) > 0) {
            $this->load->helper('htmlpurifier');
            $_POST = html_purify($_POST);            
        }
    }

    public function index() {
        redirect('account/login');
    }

    /**
     * Route: 'account/login'.
     * 
     * Shows the login form at views/account/login and accepts the form submission.
     * If a user successfully logs in they are redirected to 'welcome/index'
     */
    public function login() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback__is_authenticated['.$this->input->post('password').']');

        if(!$this->form_validation->run()) {
            $view = $this->load->view('account/login', array(), TRUE);

            return $this->load->view(self::TEMPLATE, array(
                'page_title' => $this->page_title_prefix . 'Login',
                'main' => $view,
            ));
        }
        else {
            $this->_log_user_in($this->input->post('email'));
            redirect('welcome/index');
        }
    }

    /**
     * Route: 'account/register'.
     * 
     * Shows the register form at views/account/register and accepts the form submission.
     * If the user successfully registers, they get logged in, and redirected to 'welcome/index'
     */
    public function register() {

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__unique_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('partner_id', 'Affiliation', 'required|callback__verify_partner');
        $this->form_validation->set_rules('training_level_id', 'Training Level', 'required|callback__verify_training_level');
        $this->form_validation->set_rules('preferred_location_id', 'Preferred Location', 'required|callback__verify_location');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[14]');
        $this->form_validation->set_rules('estimated_graduation_year', 'Graduation Year', 'trim|required|is_natural_no_zero|integer');
        $this->form_validation->set_rules('birth_month', 'Birth Month', 'trim|required|is_natural_no_zero|greater_than[0]|less_than[13]');
        $this->form_validation->set_rules('birth_year', 'Birth Year', 'trim|required|is_natural_no_zero|exact_length[4]');

        // form_validation only returns TRUE when everything is valid on a POST
        if (!$this->form_validation->run()) {
            $view = $this->load->view('account/register', array(
                'training_levels' => $this->training_level_model->get_ids_and_names_for_partner((int)$this->input->post('partner_id')),
                'partners' => $this->partner_model->get_ids_and_names(),
                'locations' => $this->location_model->get_ids_and_names(),
                'months_of_the_year' => $this->user_model->get_months_of_the_year(),
                'ethnicities' => $this->user_model->get_ethnicity_assoc_array(),
                'genders' => $this->user_model->get_gender_list(),
            ), TRUE);

            return $this->load->view(self::TEMPLATE, array(
                'main' => $view,
                'page_title' => $this->page_title_prefix . 'Register'
            ));
        }
        else {
            $user = new data_classes\User();
            
            $user->email = $this->input->post('email');            
            $user->password = $this->input->post('password');
            $user->first_name = $this->input->post('first_name');
            $user->last_name = $this->input->post('last_name');
            $user->training_level_id = $this->input->post('training_level_id');
            $user->partner_id = $this->input->post('partner_id');
            $user->preferred_location_id = $this->input->post('preferred_location_id');
            $user->phone_number = $this->input->post('phone_number');
            $user->pager_number = $this->input->post('pager_number');
            $user->estimated_graduation_year = $this->input->post('estimated_graduation_year');
            $user->interpreter = NULL !== $this->input->post('interpreter');
            $user->available_to_serve = NULL !== $this->input->post('available_to_serve');
            $user->birth_month = $this->input->post('birth_month');
            $user->birth_year = $this->input->post('birth_year');
            $user->ethnicity = $this->input->post('ethnicity');
            $user->gender = $this->input->post('gender');
            
            $this->user_model->create($user);
            $this->_log_user_in($user->email);
            
            redirect('welcome/index');
        }
    }
    
    /**
     * Route: [GET] account/confirm_data?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the 
     * user-editable fields of a user
     */
    public function confirm_data() { 
        $user = $this->user_model->get_by_id($_SESSION['user']->id);
        
        if (!is_null($user)) {
            
            $view = $this->load->view('account/confirm_form', array(
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'training_level_id' => $user->training_level_id,
                'training_levels' => $this->training_level_model->get_ids_and_names_for_partner($user->partner_id),
                'partner_id'=> $user->partner_id,
                'partners' => $this->partner_model->get_ids_and_names(),
                'preferred_location_id' => $user->preferred_location_id,
                'locations' => $this->location_model->get_ids_and_names(),
                'phone_number' => $user->phone_number,
                'pager_number' => $user->pager_number,
                'estimated_graduation_year' => $user->estimated_graduation_year,
                'admin' => filter_var($user->admin, FILTER_VALIDATE_BOOLEAN),
                'interpreter' => filter_var($user->interpreter, FILTER_VALIDATE_BOOLEAN),
                'available_to_serve' => TRUE,
                'months_of_the_year' => $this->user_model->get_months_of_the_year(),
                'birth_month' => $user->birth_month,
                'birth_year' => $user->birth_year,
                'ethnicities' => $this->user_model->get_ethnicity_assoc_array(),                
                'ethnicity' => $user->ethnicity,
                'genders' => $this->user_model->get_gender_list(),
                'gender' => $user->gender
            ), TRUE);
            
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => $this->page_title_prefix . 'Confirm Data'
            ));
        }
        else{
            show_404('The resource you requested was not found');
        }
    }
    
    /**
     * Route: [POST] account/post_confirm_data?url_params...
     * 
     * Accepts form submissions and updates a user.
     */
    public function post_confirm_data() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__appropriate_email['.$_SESSION['user']->id.']');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('partner_id', 'Affiliation', 'required|callback__verify_partner');
        $this->form_validation->set_rules('training_level_id', 'Training Level', 'required|callback__verify_training_level');
        $this->form_validation->set_rules('preferred_location_id', 'Preferred Location', 'required|callback__verify_location');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[14]');
        $this->form_validation->set_rules('estimated_graduation_year', 'Graduation Year', 'trim|required|is_natural_no_zero|integer');
        $this->form_validation->set_rules('birth_month', 'Birth Month', 'trim|required|is_natural_no_zero|greater_than[0]|less_than[13]');
        $this->form_validation->set_rules('birth_year', 'Birth Year', 'trim|required|is_natural_no_zero|exact_length[4]');
        
        if ($this->form_validation->run()) {
            $user = new data_classes\User();
            
            $user->id = $_SESSION['user']->id;
            $user->email = $this->input->post('email');
            $user->first_name = $this->input->post('first_name');
            $user->last_name = $this->input->post('last_name');
            $user->training_level_id = $this->input->post('training_level_id');
            $user->partner_id = $this->input->post('partner_id');
            $user->preferred_location_id = $this->input->post('preferred_location_id');
            $user->phone_number = $this->input->post('phone_number');
            $user->pager_number = $this->input->post('pager_number');
            $user->estimated_graduation_year = $this->input->post('estimated_graduation_year');
            $user->admin = NULL !== $this->input->post('admin');
            $user->interpreter = NULL !== $this->input->post('interpreter');
            $user->available_to_serve = NULL !== $this->input->post('available_to_serve');
            $user->birth_month = $this->input->post('birth_month');
            $user->birth_year = $this->input->post('birth_year');
            $user->ethnicity = $this->input->post('ethnicity');
            $user->gender = $this->input->post('gender');
            
            $this->user_model->update($user);
            $_SESSION['user'] = $this->user_model->get_by_id($user->id);
            $_SESSION['location'] = $this->location_model->get_by_id($user->preferred_location_id);
            
            redirect('welcome/index');
        } 
        else {
            $view = $this->load->view('account/confirm_form', array(
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'training_level_id' => $this->input->post('training_level_id'),
                'training_levels' => $this->training_level_model->get_ids_and_names_for_partner((int)$this->input->post('partner_id')),
                'partner_id'=> $this->input->post('partner_id'),
                'partners' => $this->partner_model->get_ids_and_names(),
                'preferred_location_id' => $this->input->post('preferred_location_id'),
                'locations' => $this->location_model->get_ids_and_names(),
                'phone_number' => $this->input->post('phone_number'),
                'pager_number' => $this->input->post('pager_number'),
                'estimated_graduation_year' => $this->input->post('estimated_graduation_year'),
                'admin' => NULL !== $this->input->post('admin'),
                'interpreter' => NULL !== $this->input->post('interpreter'),
                'available_to_serve' => NULL !== $this->input->post('available_to_serve'),
                'months_of_the_year' => $this->user_model->get_months_of_the_year(),
                'birth_month' => $this->input->post('birth_month'),
                'birth_year' => $this->input->post('birth_year'),
                'ethnicities' => $this->user_model->get_ethnicity_assoc_array(),  
                'ethnicity' => $this->input->post('ethnicity'),
                'genders' => $this->user_model->get_gender_list(),
                'gender' => $this->input->post('gender'),
            ), TRUE);
            
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => $this->page_title_prefix . 'Confirm Data'
            ));
        }
    }
    
    /**
     * Route: 'account/get_training_levels_for_partner'
     * 
     * Gets a dropdown list of training levels for a specified partner_id
     */
    public function get_training_levels_for_partner() {
        echo $this->load->view('shared/training_levels_for_partner', array(
            'training_levels' => $this->training_level_model->get_ids_and_names_for_partner($this->input->get('partner_id'))
        ), TRUE);
    }

    /**
     * Route: 'account/forgot_password'
     * 
     * Gets a form that allows the user to initiate the process of setting a new password
     */
    public function forgot_password() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__is_registered_email');
        
        if (!$this->form_validation->run()) {
            $view = $this->load->view('account/forgot_password', array(), TRUE);
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => 'So you forgot your password... tsk tsk.'
            ));
        }
        else {
            $this->user_model->initiate_temporary_auth_for($this->input->post('email'));
            $view = $this->load->view('account/check_email', array(), TRUE);
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => 'The Countdown is on...'
            ));
        }        
    }
    
    /**
     * This route will most likely be accessed by following a link in an email. It checks
     *      to see if the email address and temporary auth token are for the same user. If so, 
     *      it expires the user's present password (since they most likely forgot it), and 
     *      redirects to the welcome page, which will redirect them to choose a new password
     */
    public function validate_temporary_auth() {
        $email = $this->input->get('email');
        $temporary_auth_token = $this->input->get('tat');
        
        $user = $this->user_model->get_by_email($email);
        if (!is_null($user->temporary_auth_token) && $user->temporary_auth_token === $temporary_auth_token
                && !is_null($user->temporary_auth_token_expires_on) && $user->temporary_auth_token_expires_on > date('Y-m-d H:i:s')) {
            
            $this->user_model->expire_password_for($email);
            $this->user_model->delete_temporary_auth_token_for($email);
            $this->_log_user_in($email);
            
            redirect('welcome/index');
        }
        else {
            redirect('account/login');
        }
    }
    
    /**
     * Route: 'account/choose_new_password'
     * 
     * Returns a form for the user to choose a new password
     */
    public function choose_new_password() {
        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirm_password]|callback__is_new_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        
        $email = $_SESSION['user']->email;
        $user = $this->user_model->get_by_email($email);
        
        if (!$this->form_validation->run()) {
            $view = $this->load->view('account/choose_new_password', array(
                'first_name' => $user->first_name
            ), TRUE);
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => $this->page_title_prefix . 'Choose New Password'
            ));
        }
        else{
            $this->user_model->update_password($this->input->post('password'), $email);
            $this->_log_user_in($email);
            redirect('welcome/index');
        }
    }
    
    /**
     * Route: 'account/media_release_form'
     * 
     * Returns a form for the user to choose to accept or reject the media release form
     */
    public function media_release_form() {
        $this->form_validation->set_rules('accept', 'Yes or No', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        
        $user = $_SESSION['user'];
        
        if (!$this->form_validation->run()) {
            $view = $this->load->view('account/media_release_form', array(
                'name' => $user->first_name . ' ' .$user->last_name,
                'media_release_form_text' => $_SESSION['location']->media_release_form
            ), TRUE);
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => $this->page_title_prefix . 'Media Release Form'
            ));
        }
        else {
            $status = $this->input->post('accept') === 'Yes';
            $this->user_model->update_media_release_status($status, $user->id);
            
            $_SESSION['user'] = $this->user_model->get_by_id($user->id);
            redirect('welcome/index');
        }
    }
    
    /**
     * Logs the user out and redirects to welcome/index (the 'home')
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('welcome/index');
    }

    /**
     * Checks if the email address is unique. Sets a form validation error otherwise
     * 
     * @param string $email The user's email address
     * @return bool Returns TRUE if the email is unique. Otherwise FALSE
     */
    public function _unique_email($email) {
        if (!is_null($this->user_model->get_by_email($email))) {
            $this->form_validation->set_message('_unique_email', 'This email address is already in use');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks if the email belongs to a registered user. Sets a form validation error otherwise
     * 
     * @param string $email The user's email address
     * @return bool Returns TRUE if the email belongs to a registered user. Otherwise FALSE
     */
    public function _is_registered_email($email) {
        if (is_null($this->user_model->get_by_email($email))) {
            $this->form_validation->set_message('_is_registered_email', 'Sorry, we do not recognize this email address. 
                Please use the email address you registered with');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks if the email entered by the user is already in use for the present user or another user. Sets
     *      a form validation error if the email is not valid for use.
     * 
     * @param string $email The email address to check
     * @param int|string $id The id for the present user to corroborate the email address with
     * @return bool Returns TRUE if the email address is clear to use, FALSE otherwise
     */
    public function _appropriate_email($email, $id) {
        $user = $this->user_model->get_by_email($email);        
        
        // If a user exists with $email and they are not the person being edited
        if(!is_null($user) && (int)$user->id !== (int)$id) {
            $this->form_validation->set_message('_appropriate_email', 'This email address is already in use by someone else.
                Please enter a different email address');            
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Checks if the selected training_level is valid. Also sets a 
     *      form validation error if the training_level is invalid
     * 
     * @param int|string $id The training_level's id
     * @return bool Returns TRUE if the training_level is valid. Otherwise FALSE
     */
    public function _verify_training_level($id) {
        if (is_null($this->training_level_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_training_level', 'Please select a Training Level');
            return FALSE;
        }
        return TRUE;
    } 
    
    /**
     * Checks if the selected partner is valid. Also sets a 
     *      form validation error if the partner is invalid
     * 
     * @param int|string $id The partner's id
     * @return bool Returns TRUE if the partner is valid. Otherwise FALSE
     */
    public function _verify_partner($id) {
        if (is_null($this->partner_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_partner', 'Please select an Affiliation');
            return FALSE;
        }
        return TRUE;
    } 
    
    /**
     * Checks if the selected location is valid. Also sets a 
     *      form validation error if the location is invalid
     * 
     * @param int|string $id The location's id
     * @return bool Returns TRUE if the location is valid. Otherwise FALSE
     */
    public function _verify_location($id) {
        if (is_null($this->location_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_location', 'Please select a location');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks the database to see if the email and password provided go together. Also sets a 
     *      form validation error if the supplied credentials are invalid
     * 
     * @param string $email The user's email address. 
     * @param string $password The user's password
     * @return bool Returns TRUE if the provided credentials are valid. Otherwise FALSE
     */
    public function _is_authenticated($email, $password) {
        $user = $this->user_model->get_by_email($email);
        if(!is_null($user) && password_verify($password, $user->password)) {
            return TRUE;
        }
        $this->form_validation->set_message('_is_authenticated', 'Sorry, but the user name or password you 
            provided  is incorrect');
        return FALSE;
    }
    
    /**
     * Checks the database to see if the password supplied is different from the one presently in use.
     *      Also sets a form validation error they are the same.
     * 
     * @param string $password The new password
     * @return bool Returns TRUE if the password is new, FALSE otherwise
     */
    public function _is_new_password($password) {
        $user = $this->user_model->get_by_id($_SESSION['user']->id);
        if(password_verify($password, $user->password)) {
            $this->form_validation->set_message('_is_new_password', 'Please choose a different password');
            return FALSE;
        }
        return TRUE;        
    }
    
    /**
     * Logs the user in by adding their data to the $_SESSION
     * 
     * @uses user_model->get_by_email to get the user's id
     * 
     * @param string $email The user's email address
     * @return void Returns nothing
     */
    private function _log_user_in($email) {
        $user = $this->user_model->get_by_email($email);
        
        if((int)$user->preferred_location_id > 0) {
            $_SESSION['location'] = $this->location_model->get_by_id($user->preferred_location_id);
        }        
        
        $_SESSION['user'] = $user;
        $_SESSION['view_as_admin'] = $user->admin;
    }
}
