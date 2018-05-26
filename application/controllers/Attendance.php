<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'data_classes/Attendance.php';

/**
 * Attendance Controller which manages attendance related stuff
 *
 * @author Philip
 */
class Attendance extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->model('attendance_model');
        $this->load->model('location_model');
        $this->load->model('partner_model');
        
        $this->load->library('user_agent');
    }
    
    /**
     * Route: attendance/index OR attendance
     * 
     * Shows the attendance main page
     */
    public function index() {
        $filter_view = $this->load->view('attendance/filter_form', array(
            'locations' => $this->location_model->get_ids_and_names(),
            'location_id' => $_SESSION['user']->preferred_location_id,
            'partners' => $this->partner_model->get_ids_and_names(),
            'partner_id' => $_SESSION['user']->partner_id            
        ), TRUE);
        
        $view = $this->load->view('attendance/index', array(
            'filters' => $filter_view
        ), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Attendance',
            'main' => $view,
        ));
    }
    
    /**
     * Route: [GET] attendance/filter
     * 
     * Gets the attendance records for the date, location, and partner
     */
    public function filter() {        
        $date = $this->input->get('date');
        $location_id = $this->input->get('location_id');
        $partner_id = $this->input->get('partner_id');
        
        // Using form_validation->set_data to add the GET request data
        // because by default form_validation looks only at POST data
        $this->form_validation->set_data(array(
            'date' => $date,
            'location_id' => $location_id,
            'partner_id' => $partner_id
        ));
        
        $this->form_validation->set_rules('date', 'Date', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required|callback__verify_location');
        $this->form_validation->set_rules('partner_id', 'Partner', 'trim|required|callback__verify_partner');
        
        $records = array();
        if ($this->form_validation->run()) {
            $records = $this->attendance_model->get_records_for_location_partner_and_date(
                $location_id,
                $partner_id,
                date('Y-m-d', strtotime($date))
            );
        }
        
        echo $this->load->view('attendance/take', array(
            'records' => $records,
            'date' => $date,
            'location_id' => $location_id,
            'partner_id' => $partner_id
        ), TRUE);
    }
    
    /**
     * Route: [POST]attendance/capture
     * 
     * Saves attendance records
     */
    public function capture() {
        $unmarked_records = array();
        $marked_records = array();
        
        foreach ($this->input->post('records') as $record) {
            $opportunity_id = $record['opportunity_id'];
            $status = $record['status'];
            if ($status === '') {
                $unmarked_records[] = $opportunity_id;
            }
            else {
                $att_record = new data_classes\Attendance();
                $att_record->opportunity_id = $opportunity_id;
                $att_record->status = $status;
                $marked_records[] = $att_record;
            }
        }
        $this->attendance_model->capture_records($unmarked_records, $marked_records);            
    }
    
    /**
     * Route: [GET]attendance/get_sign_in_sheet
     * 
     * Gets a sign in sheet for the location, partner, and date
     */
    public function get_sign_in_sheet() {
        $date = $this->input->get('date');
        $location_id = $this->input->get('location_id');
        $partner_id = $this->input->get('partner_id');
        
        $this->form_validation->set_data(array(
            'date' => $date,
            'location_id' => $location_id,
            'partner_id' => $partner_id
        ));
        
        $this->form_validation->set_rules('date', 'Date', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required|callback__verify_location');
        $this->form_validation->set_rules('partner_id', 'Partner', 'trim|required|callback__verify_partner');
                
        if ($this->form_validation->run()) {
            $records = $this->attendance_model->get_records_for_location_partner_and_date(
                $location_id,
                $partner_id,
                date('Y-m-d', strtotime($date))
            );
            
            $partner = $this->partner_model->get_by_id($partner_id);
            $partner_name = is_null($partner) ? 'All Partners' : $partner->name;
            $view = $this->load->view('attendance/sign_in_sheet', array(
                'records' => $records,
                'date' => $date,
                'location_name' => $this->location_model->get_by_id($location_id)->name,
                'partner_name' => $partner_name
            ), TRUE);
            
            return $this->load->view('shared/print_friendly_layout', array(
                'main' => $view,
                'page_title' => date('m-d-Y', strtotime($date)).' Sign In Sheet'
            ));
        }
        else {
            show_404();
        }
    }
    
    /**
     * Checks if the selected partner is valid. Also sets a form validation error if the partner is invalid
     * 
     * @param int|string $id The partner's id
     * @return bool Returns TRUE if the partner is valid. Otherwise FALSE
     */
    public function _verify_partner($id) {
        if ($id != 0 && is_null($this->partner_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_partner', 'Please select your partner');
            return FALSE;
        }
        return TRUE;
    }
    
    
    /**
     * Checks if the selected location is valid. Also sets a form validation error if the location is invalid
     * 
     * @param int|string $id The location's id
     * @return bool Returns TRUE if the location is valid. Otherwise FALSE
     */
    public function _verify_location($id) {
        if (is_null($this->location_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_location', 'Please select your location');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks to see if the supplied date can be parsed into a date
     * 
     * @param string $date The date to check
     * @return bool Returns TRUE if the date can be parsed, FALSE otherwise
     */
    public function _is_date($date) {
        if(!strtotime($date)) {
            $this->form_validation->set_message('_is_date', 'Please provide a date');
            return FALSE;
        }
        return TRUE;
    }
}


