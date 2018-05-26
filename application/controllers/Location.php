<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/Location.php';

/**
 * Location Controller which creating and modifying Locations
 * 
 * @author Philip 
 */
class Location extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->helper('text');
        $this->load->model('location_model');
    }
    
    /**
     * Route: location/index OR location
     * 
     * Shows the listing of locations with buttons to add to and edit them
     */
    public function index() {
        $view = $this->load->view('location/index', array(), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Locations',
            'main' => $view,
        ));
    }
    
    /**
     * Route: location/locations
     * 
     * Returns a string that describes a HTML table containing all the locations
     */
    public function locations() {
        echo $this->load->view('location/locations', array(
            'locations' => $this->location_model->get_all()
        ), TRUE);
    }
    
    /**
     * Route: [GET/POST] location/create.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create a 
     * location entry. On POST requests, accepts form submissions and creates a new 
     * location record.
     */
    public function create() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('media_release_form', 'Release Form Text', 'trim|callback__require_if_using_release_form');
        
        if ($this->form_validation->run()) {
            $location = new data_classes\Location();
            $location->name = $this->input->post('name');            
            $location->address = $this->input->post('address');
            $location->email_text = $this->input->post('email_text');
            $location->uses_media_release_form = NULL !== $this->input->post('uses_media_release_form');
            $location->media_release_form = $this->input->post('media_release_form');

            $this->location_model->create($location);
        }
        echo $this->load->view('location/create_form', array(
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'email_text' => $this->input->post('email_text'),
            'uses_media_release_form' => NULL !== $this->input->post('uses_media_release_form'),
            'media_release_form' => $this->input->post('media_release_form')
        ), TRUE);
    }
    
    /**
     * Route: [GET] location/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a location entry
     */
    public function edit() {
        
        $location = $this->location_model->get_by_id($this->input->get('id'));
        if (!is_null($location)) {
            echo $this->load->view('location/edit_form', array(
                'id' =>$location->id,
                'name' => $location->name,
                'address' => $location->address,
                'email_text' => $location->email_text,
                'uses_media_release_form' => filter_var($location->uses_media_release_form, FILTER_VALIDATE_BOOLEAN),
                'media_release_form' => $location->media_release_form
            ), TRUE);
        }
        else {
            echo show_404('The resource you requested was not found');
        }        
    }
    
    /**
     * Route: [POST] location/post_edit?url_params...
     * 
     * Accepts form submissions and updates a location entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Location Integrity', 'trim|required|is_natural_no_zero|callback__verify_location', 'Location cannot be saved as is');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('media_release_form', 'Release Form Text', 'trim|callback__require_if_using_release_form');

        if ($this->form_validation->run()) {
            $location = new data_classes\Location();
            
            $location->id = $this->input->post('id');
            $location->name = $this->input->post('name');            
            $location->address = $this->input->post('address');
            $location->email_text = $this->input->post('email_text');
            $location->uses_media_release_form = NULL !== $this->input->post('uses_media_release_form');
            $location->media_release_form = $this->input->post('media_release_form');

            $this->location_model->update($location);
        }
        echo $this->load->view('location/edit_form', array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address'),
            'email_text' => $this->input->post('email_text'),
            'uses_media_release_form' => NULL !== $this->input->post('uses_media_release_form'),
            'media_release_form' => $this->input->post('media_release_form')
        ), TRUE);
    }
    
    public function media_release_form_responses() {
        $location = $this->location_model->get_by_id($this->input->get('id'));
        if (!is_null($location)) {
            
            $view = $this->load->view('location/media_release_form_responses', array(
                'responses' => $this->location_model->get_media_release_responses_for_location($location->id),
                'location_name' => $location->name
            ), TRUE);
            
            return $this->load->view('shared/bare_bones_layout', array(
                'main' => $view,
                'page_title' => $location->name . ' - Media Release Form Responses'
            ));
        }
        else {
            echo show_404('The resource you requested was not found');
        }
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
            $this->form_validation->set_message('_verify_location', 'Unknown Location');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks if a media release form was provided if the user checks the 'use media release form' box
     * 
     * @param string $media_release_form The text content of the media release form
     * @return bool Returns TRUE if the test passes, FALSE otherwise
     */
    public function _require_if_using_release_form($media_release_form) {
        $uses_media_release_form = NULL !== $this->input->post('uses_media_release_form');
        if($uses_media_release_form && strlen(trim($media_release_form)) < 1) {
            $this->form_validation->set_message('_require_if_using_release_form', 'If intending to use a release form, 
                please provide some text content for it. Preferably one with a lot of inscrutable legalese');            
            return FALSE;
        }
        return TRUE;
    }
    
}
