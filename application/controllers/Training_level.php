<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/Training_level.php';

/**
 * Training_level Controller which creating and modifying Training_levels
 * 
 * @author Philip 
 */
class Training_level extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->helper('text');
        $this->load->model('training_level_model');
        $this->load->model('ability_model');
        $this->load->model('partner_model');
    }
    
    /**
     * Route: training_level/index OR training_level
     * 
     * Shows the listing of training levels with buttons to add to and edit them
     */
    public function index() {
        $view = $this->load->view('training_level/index', array(), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Training Levels',
            'main' => $view,
        ));
    }
    
    /**
     * Route: training_level/training_levels
     * 
     * Returns a string that describes a HTML table containing all the training levels
     */
    public function training_levels() {
        echo $this->load->view('training_level/training_levels', array(
            'training_levels' => $this->training_level_model->get_all()
        ), TRUE);
    }
    
    /**
     * Route: [GET/POST] training_level/create.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create a 
     * training_level entry. On POST requests, accepts form submissions and creates a new 
     * training_level record.
     */
    public function create() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email_text', 'Email Text', 'trim');
        
        if ($this->form_validation->run()) {
            $t_level = new data_classes\Training_level();
            $t_level->name = $this->input->post('name');
            $t_level->partner_id = $this->input->post('partner_id');
            $t_level->email_text = $this->input->post('email_text');

            $this->training_level_model->create($t_level);
        }
        echo $this->load->view('training_level/create_form', array(
            'name' => $this->input->post('name'),
            'partner_id' => $this->input->post('partner_id'),
            'partners' => $this->partner_model->get_ids_and_names(),
            'email_text' => $this->input->post('email_text')
        ), TRUE);
    }
    
    /**
     * Route: [GET] training_level/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a training_level entry
     */
    public function edit() {
        
        $training_level = $this->training_level_model->get_by_id($this->input->get('id'));
        if (!is_null($training_level)) {
            echo $this->load->view('training_level/edit_form', array(
                'id' =>$training_level->id,
                'name' => $training_level->name,
                'partner_id' => $training_level->partner_id,
                'partners' => $this->partner_model->get_ids_and_names(),
                'email_text' => $training_level->email_text
            ), TRUE);
        }
        else {
            echo show_404('The resource you requested was not found');
        }        
    }
    
    /**
     * Route: [POST] training_level/post_edit?url_params...
     * 
     * Accepts form submissions and updates a training_level entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Training Level Integrity', 'trim|required|is_natural_no_zero|callback__verify_training_level', 'Training level cannot be saved as is');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email_text', 'Email Text', 'trim');

        if ($this->form_validation->run()) {
            $t_level = new data_classes\Training_level();
            $t_level->id = $this->input->post('id');
            $t_level->name = $this->input->post('name');
            $t_level->partner_id = $this->input->post('partner_id');
            $t_level->email_text = $this->input->post('email_text');

            $this->training_level_model->update($t_level);
        }
        echo $this->load->view('training_level/edit_form', array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'partner_id' => $this->input->post('partner_id'),
            'partners' => $this->partner_model->get_ids_and_names(),
            'email_text' => $this->input->post('email_text')
        ), TRUE);
    }
    
    /**
     * Route: [GET] training_level/abilities?id=<id>
     * 
     * Returns a HTML string with checkboxes for every role where a checked box represents
     *      an ability that for the training_level
     */
    public function abilities() {
        echo $this->load->view('training_level/abilities', array(
            'tuples' => $this->ability_model->get_all_for_training_level_id($this->input->get('id'))
        ), TRUE);
    }
    
    /**
     * Route: [POST] training_level/abilities?url_params...
     * 
     * Accepts a POST request and updates the abilities for the specified training_level
     */
    public function edit_abilities() {
        $role_ids = explode(',', $this->input->post('role_ids'));
        $this->ability_model->update_for_training_level_id($role_ids, $this->input->post('id'));
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
            $this->form_validation->set_message('_verify_training_level', 'Unknown Training Level');
            return FALSE;
        }
        return TRUE;
    }
    
}
