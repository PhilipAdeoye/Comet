<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/Role.php';

/**
 * Role Controller which creating and modifying Roles
 * 
 * @author Philip 
 */
class Role extends MY_Controller {
    
    public $permitted_methods_for_non_admins = [
        'get_by_id_as_JSON'
    ];
    
    public function __construct() {
        parent::__construct();
                
        $this->load->helper('text');
        $this->load->model('role_model');
        $this->load->model('ability_model');
    }
    
    /**
     * Route: role/index OR role
     * 
     * Shows the listing of roles with buttons to add to and edit them
     */
    public function index() {
        $view = $this->load->view('role/index', array(), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Roles',
            'main' => $view,
        ));
    }
    
    /**
     * Route: role/roles
     * 
     * Returns a string that describes a HTML table containing all the roles
     */
    public function roles() {
        echo $this->load->view('role/roles', array(
            'roles' => $this->role_model->get_all()
        ), TRUE);
    }
    
    /**
     * Route: role/get_by_id_as_JSON
     * 
     * Returns a JSON encoded string that describes a role
     */
    public function get_by_id_as_JSON() {
        echo json_encode($this->role_model->get_by_id($this->input->get('id')), 
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_HEX_APOS);
    }
    
    /**
     * Route: [GET/POST] role/create.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create a 
     * role entry. On POST requests, accepts form submissions and creates a new 
     * role record.
     */
    public function create() {
        $this->form_validation->set_rules('description', 'Name', 'trim|required');
        
        if ($this->form_validation->run()) {
            $role = new data_classes\Role();
            $role->description = $this->input->post('description');            
            $role->help_text = $this->input->post('help_text');
            $role->email_text = $this->input->post('email_text');

            $this->role_model->create($role);
        }
        echo $this->load->view('role/create_form', array(
            'description' => $this->input->post('description'),
            'help_text' => $this->input->post('help_text'),
            'email_text' => $this->input->post('email_text')
        ), TRUE);
    }
    
    /**
     * Route: [GET] role/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a role entry
     */
    public function edit() {
        
        $role = $this->role_model->get_by_id($this->input->get('id'));
        if (!is_null($role)) {
            echo $this->load->view('role/edit_form', array(
                'id' =>$role->id,
                'description' => $role->description,
                'help_text' => $role->help_text,
                'email_text' => $role->email_text
            ), TRUE);
        }
        else {
            echo show_404('The resource you requested was not found');
        }        
    }
    
    /**
     * Route: [POST] role/post_edit?url_params...
     * 
     * Accepts form submissions and updates a role entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Role Integrity', 'trim|required|is_natural_no_zero|callback__verify_role', 'Role cannot be saved as is');
        $this->form_validation->set_rules('description', 'Name', 'trim|required');

        if ($this->form_validation->run()) {
            $role = new data_classes\Role();
            
            $role->id = $this->input->post('id');
            $role->description = $this->input->post('description');            
            $role->help_text = $this->input->post('help_text');
            $role->email_text = $this->input->post('email_text');

            $this->role_model->update($role);
        }
        echo $this->load->view('role/edit_form', array(
            'id' => $this->input->post('id'),
            'description' => $this->input->post('description'),
            'help_text' => $this->input->post('help_text'),
            'email_text' => $this->input->post('email_text')
        ), TRUE);
    }
    
    /**
     * Route: [GET] role/abilities?id=<id>
     * 
     * Returns a HTML string with checkboxes for every training level where a checked box represents
     *      an ability that for the role
     */
    public function abilities() {
        echo $this->load->view('role/abilities', array(
            'tuples' => $this->ability_model->get_all_for_role_id($this->input->get('id'))
        ), TRUE);
    }
    
    /**
     * Route: [POST] role/abilities?url_params...
     * 
     * Accepts a POST request and updates the abilities for the specified role
     */
    public function edit_abilities() {
        $training_level_ids = explode(',', $this->input->post('training_level_ids'));
        $this->ability_model->update_for_role_id($training_level_ids, $this->input->post('id'));
    }
    
    /**
     * Checks if the selected role is valid. Also sets a 
     *      form validation error if the role is invalid
     * 
     * @param int|string $id The role's id
     * @return bool Returns TRUE if the role is valid. Otherwise FALSE
     */
    public function _verify_role($id) {
        if (is_null($this->role_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_role', 'Unknown Role');
            return FALSE;
        }
        return TRUE;
    }
    
}
