<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/Partner.php';

/**
 * Partner Controller which creating and modifying Partners
 * 
 * @author Philip 
 */
class Partner extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->helper('text');
        $this->load->model('partner_model');
    }
    
    /**
     * Route: partner/index OR partner
     * 
     * Shows the listing of partners with buttons to add to and edit them
     */
    public function index() {
        $view = $this->load->view('partner/index', array(), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Partners',
            'main' => $view,
        ));
    }
    
    /**
     * Route: partner/partners
     * 
     * Returns a string that describes a HTML table containing all the partners
     */
    public function partners() {
        echo $this->load->view('partner/partners', array(
            'partners' => $this->partner_model->get_all()
        ), TRUE);
    }
    
    /**
     * Route: [GET/POST] partner/create.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create a 
     * partner entry. On POST requests, accepts form submissions and creates a new 
     * partner record.
     */
    public function create() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        
        if ($this->form_validation->run()) {
            $partner = new data_classes\Partner();
            $partner->name = $this->input->post('name'); 

            $this->partner_model->create($partner);
        }
        echo $this->load->view('partner/create_form', array(
            'name' => $this->input->post('name')
        ), TRUE);
    }
    
    /**
     * Route: [GET] partner/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a partner entry
     */
    public function edit() {
        
        $partner = $this->partner_model->get_by_id($this->input->get('id'));
        if (!is_null($partner)) {
            echo $this->load->view('partner/edit_form', array(
                'id' =>$partner->id,
                'name' => $partner->name
            ), TRUE);
        }
        else {
            echo show_404('The resource you requested was not found');
        }        
    }
    
    /**
     * Route: [POST] partner/post_edit?url_params...
     * 
     * Accepts form submissions and updates a partner entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Partner Integrity', 'trim|required|is_natural_no_zero|callback__verify_partner', 'Partner cannot be saved as is');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run()) {
            $partner = new data_classes\Partner();
            
            $partner->id = $this->input->post('id');
            $partner->name = $this->input->post('name'); 

            $this->partner_model->update($partner);
        }
        echo $this->load->view('partner/edit_form', array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name')
        ), TRUE);
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
            $this->form_validation->set_message('_verify_partner', 'Unknown Partner');
            return FALSE;
        }
        return TRUE;
    }
    
}
