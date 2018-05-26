<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Metrics, Insights, Tables, etc
 *
 * @author Philip
 */
class Insights extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->model('attendance_model');
        $this->load->model('location_model');
        $this->load->model('partner_model');
        
        $this->load->library('user_agent');
    }
    
    /**
     * Route: insights/index OR insights
     * 
     * Shows the insights main page
     */
    public function index() {
        $attendance_filter_view = $this->load->view('insights/attendance_form', array(
            'locations' => $this->location_model->get_ids_and_names(),
            'location_id' => $_SESSION['user']->preferred_location_id,
            'partners' => $this->partner_model->get_ids_and_names(),
            'partner_id' => $_SESSION['user']->partner_id,
            'use_attendance_data' => FALSE
        ), TRUE);
        
        $view = $this->load->view('insights/index', array(
            'attendance_filters' => $attendance_filter_view
        ), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Insights',
            'main' => $view,
        ));
    }
    
    /**
     * Route: [GET] insights/attendance_summary
     * 
     * Gets the attendance summaries 
     */
    public function attendance_summary() {
        $attendance_start_date = $this->input->get('attendance_start_date');
        $attendance_end_date = $this->input->get('attendance_end_date');
        $location_id = $this->input->get('location_id');
        $partner_id = $this->input->get('partner_id');
        $use_attendance_data = NULL !== $this->input->get('use_attendance_data');
        
        // Using form_validation->set_data to add the GET request data
        // because by default form_validation looks only at POST data
        $this->form_validation->set_data(array(
            'attendance_start_date' => $attendance_start_date,
            'attendance_end_date' => $attendance_end_date,
            'location_id' => $location_id,
            'partner_id' => $partner_id
        ));
        
        $this->form_validation->set_rules('attendance_start_date', 'Start From', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('attendance_end_date', 'End At', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('attendance_end_date', 'End At', 'trim|required|callback__verify_start_end_date['.$attendance_start_date.']');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required|callback__verify_location');
        $this->form_validation->set_rules('partner_id', 'Partner', 'trim|required|callback__verify_partner');
                
        $volunteer_counts_for_clinic_days = $volunteer_hours_summary = array();
        if ($this->form_validation->run()) {
            $volunteer_counts_for_clinic_days = $this->attendance_model->get_volunteer_counts_for_clinic_days(
                $location_id,
                $partner_id,
                date('Y-m-d', strtotime($attendance_start_date)),
                date('Y-m-d', strtotime($attendance_end_date)),
                $use_attendance_data
            );
            
            $volunteer_hours_summary = $this->attendance_model->get_volunteer_hours_summary(
                $location_id,
                $partner_id,
                date('Y-m-d', strtotime($attendance_start_date)),
                date('Y-m-d', strtotime($attendance_end_date)),
                $use_attendance_data
            );            
        }
        
        echo $this->load->view('insights/attendance_summary', array(
            'volunteer_counts_for_clinic_days' => $volunteer_counts_for_clinic_days,
            'volunteer_hours_summary' => $volunteer_hours_summary
        ), TRUE);        
    }
    
    /**
     * Checks if the selected partner is valid. Also sets a form validation error if the partner is invalid
     * 
     * @param int|string $id The partner's id
     * @return bool Returns TRUE if the partner is valid. Otherwise FALSE
     */
    public function _verify_partner($id) {
        if ($id != 0 && is_null($this->partner_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_partner', 'Please select a partner');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks to see if the end time comes after the start time
     * 
     * Simply compares the $end_date and $start_date to see if the $end_date is greater
     * 
     * @param string $end_date The end date
     * @param string $start_date The start date
     * @return bool Returns TRUE if the end date comes after the start date
     */
    public function _verify_start_end_date($end_date, $start_date) {        
        if (strtotime($end_date) <= strtotime($start_date)) {
            $this->form_validation->set_message('_verify_start_end_date', 'The End Date must come after the Start Date');
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
            $this->form_validation->set_message('_verify_location', 'Please select a location');
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


