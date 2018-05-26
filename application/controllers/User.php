<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'data_classes/Training_level.php';

/**
 * Description of User
 *
 * @author Philip
 */
class User extends MY_Controller {
    
    public $permitted_methods_for_non_admins = [
        'edit',
        'post_edit',
        'get_training_levels_for_partner'
    ];
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('training_level_model');
        $this->load->model('partner_model');
        $this->load->model('location_model');
    }
    
    /**
     * Route: [GET] user/index?type=<users|admins>
     */
    public function index()
	{   
        $view = $this->load->view('user/index', array(
            'type' => $this->input->get('type')
        ), TRUE);
        
        $this->load->view($this->template, array(
            'container_class' => 'container-fluid',
            'main' => $view,
            'page_title' => $this->page_title_prefix. 'People'
        ));
	}
    
    /**
     * Route: [GET] user/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a user
     */
    public function edit() {
        $logged_in_user = $this->get_logged_in_user();
        $user_id = $this->input->get('id');
        $user = $this->user_model->get_by_id($user_id);
        
        if (($logged_in_user->user_is_admin || (int)$user_id === (int)$logged_in_user->user_id)
                && !is_null($user)) {
            
            echo $this->load->view('user/edit_form', array(
                'id' => $user->id,
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
                'available_to_serve' => filter_var($user->available_to_serve, FILTER_VALIDATE_BOOLEAN),
                'months_of_the_year' => $this->user_model->get_months_of_the_year(),
                'birth_month' => $user->birth_month,
                'birth_year' => $user->birth_year,
                'ethnicities' => $this->user_model->get_ethnicity_assoc_array(),                
                'ethnicity' => $user->ethnicity,
                'genders' => $this->user_model->get_gender_list(),
                'gender' => $user->gender
            ), TRUE);
        }
        else{
            echo show_404('The resource you requested was not found');
        }
    }
    
    /**
     * Route: [POST] user/post_edit?url_params...
     * 
     * Accepts form submissions and updates a user.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Identity', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__appropriate_email['.$this->input->post('id').']');
        $this->form_validation->set_rules('password', 'Password', 'trim|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|matches[password]');
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
            
            $user->id = $this->input->post('id');
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
            $user->admin = NULL !== $this->input->post('admin');
            $user->interpreter = NULL !== $this->input->post('interpreter');
            $user->available_to_serve = NULL !== $this->input->post('available_to_serve');
            $user->birth_month = $this->input->post('birth_month');
            $user->birth_year = $this->input->post('birth_year');
            $user->ethnicity = $this->input->post('ethnicity');
            $user->gender = $this->input->post('gender');
            
            $this->user_model->update($user);
            if((int)$user->id === (int)$_SESSION['user']->id) {
                $_SESSION['user'] = $this->user_model->get_by_id($user->id);
            }
        }
        echo $this->load->view('user/edit_form', array(
            'id' => $this->input->post('id'),
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
    }
    
    /**
     * Gets all users at the user's preferred location
     */
    public function users() {        
        echo $this->load->view('user/users', array(
            'users' => $this->user_model->get_users($_SESSION['user']->preferred_location_id),
            'location_name' => $this->location_model->get_by_id($_SESSION['user']->preferred_location_id)->name
        ), TRUE);
    }
    
    /**
     * Get all users that are admins
     */
    public function admins() {
        echo $this->load->view('user/admins', array(
            'admins' => $this->user_model->get_admins($_SESSION['user']->preferred_location_id),
            'location_name' => $this->location_model->get_by_id($_SESSION['user']->preferred_location_id)->name
        ), TRUE);
    }
    
    /**
     * Route: 'user/get_training_levels_for_partner'
     * 
     * Gets a dropdown list of training levels for a specified partner_id
     */
    public function get_training_levels_for_partner() {
        echo $this->load->view('shared/training_levels_for_partner', array(
            'training_levels' => $this->training_level_model->get_ids_and_names_for_partner($this->input->get('partner_id'))
        ), TRUE);
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
}