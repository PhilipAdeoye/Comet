<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Opportunity
 *
 * @author Philip
 */
class Opportunity extends MY_Controller {
    
    public $permitted_methods_for_non_admins = [
        'index',
        'opportunity_dates',
        'users_commitments',
        'sign_up',
        'cancel_commitment',
        'oppo_by_date',
        'opportunities_for_quick_sign_up'
    ];
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('opportunity_model');
        $this->load->model('user_model');
        $this->load->model('location_model');
        $this->load->model('role_model');
        
        $this->load->helper('text');
        $this->load->library('user_agent');
    }
    
    /**
     * Route: opportunity/index OR opportunity
     * 
     * Shows the list of upcoming/historical opportunities as well as by role and location. 
     * Also opportunities signed up for
     */
    public function index() {
        $view = $this->load->view('opportunity/index', array(
            'all_users' => json_encode($this->user_model->get_all_users_for_opportunity_list(), JSON_HEX_APOS)
        ), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Opportunities',
            'main' => $view,
        ));
    }
    
    /**
     * Gets all the opportunities for the specified date
     */
    public function oppo_by_date() {
        $date = date('Y-m-d', strtotime($this->input->get('date')));
        
        // If the date is invalid OR (the date is historical AND the user is not an admin)
        if(!$date || ($date < date('Y-m-d', time()) && (int)$_SESSION['view_as_admin'] !== 1)) {
            echo show_404();
            return;
        }
        
        if($_SESSION['view_as_admin']) {
            echo $this->load->view('opportunity/oppo_by_date', array(
                'opportunities' => $this->opportunity_model->get_by_date_for_location(
                    $date,
                    $_SESSION['user']->preferred_location_id)
            ), TRUE);
        }
        else {
            echo $this->load->view('opportunity/oppo_by_date', array(
                'opportunities' => $this->opportunity_model->get_by_date_for_partner_and_location(
                    $date, 
                    $_SESSION['user']->partner_id,
                    $_SESSION['user']->training_level_id,
                    $_SESSION['user']->preferred_location_id
                )
            ), TRUE);
        }
    }
    
    /**
     * Route: [GET/POST] opportunity/create_many.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create one
     *      or multiple opportunities. On POST requests, accepts form submissions and creates the
     *      them
     */
    public function create_many() {
        
        $this->form_validation->set_rules('date', 'Date', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required|callback__verify_location');
        
        if(null !== ($this->input->post('oppos'))) {
            $oppos = $this->input->post('oppos');
            $keys = array_keys($oppos);
            for ($i = 0, $key = $keys[$i], $count = count($keys); $i < $count; $i++, $key = $i < $count ? $keys[$i] : 0) {
                $this->form_validation->set_rules("oppos[$key][role_id]", 'Role', 'trim|required|callback__verify_role');
                $this->form_validation->set_rules("oppos[$key][start_time]", 'Start Time', 'trim|required');
                $this->form_validation->set_rules("oppos[$key][end_time]", 'End Time', 'trim|required|callback__verify_start_end_time['.$this->input->post("oppos[$key][start_time]").']');
                $this->form_validation->set_rules("oppos[$key][num_spots]", 'Spots', 'trim|required|is_natural_no_zero');
            }
        }
        
        if ($this->form_validation->run() && null !== ($this->input->post('oppos'))) {
            for ($i = 0, $key = $keys[$i], $count = count($keys); $i < $count; $i++, $key = $i < $count ? $keys[$i] : 0) {
                $oppo = $oppos[$key];
                
                $op = new data_classes\Opportunity();
                $op->date = date('Y-m-d', strtotime($this->input->post('date'))); 
                $op->location_id = $this->input->post('location_id');   
                
                $op->start_time = $oppo['start_time'];
                $op->end_time = $oppo['end_time'];
                $op->role_id = $oppo['role_id'];

                $this->opportunity_model->create($op, $oppo['num_spots']);
            }            
        }
        
        echo $this->load->view('opportunity/create_many_form', array(
            'date' => $this->input->post('date'),
            'locations' => $this->location_model->get_ids_and_names(),
            'location_id' => $this->input->post('location_id'),
            'roles' => $this->role_model->get_ids_and_descriptions(),
            'oppos' => $this->input->post('oppos')
        ), TRUE);
    }
    
    /**
     * Gets users who are likely to fill the role needed for the opportunity
     */
    public function find_volunteer() {
        echo $this->load->view('opportunity/suitable_users', array(
            'suitable_users' => $this->opportunity_model->get_suitable_users($this->input->get('id'))
        ), TRUE);
    }
    
    /**
     * Gets available upcoming opportunties for the specified location id
     */
    public function opportunities_for_location() {
        echo $this->load->view('opportunity/non_committed', array(
            'opportunities' => $this->opportunity_model->get_available_upcoming_for_location($this->input->get('id'))
        ), TRUE); 
    }
    
    /**
     * Gets available upcoming opportunties for the specified role id
     */
    public function opportunities_for_role() {
        echo $this->load->view('opportunity/non_committed', array(
            'opportunities' => $this->opportunity_model->get_available_upcoming_for_role_and_location(
                $this->input->get('id'),
                $_SESSION['user']->preferred_location_id
            )
        ), TRUE);
    }
    
    /**
     * Gets a table of all upcoming opportunities that no one has signed up for grouped by role
     */
    public function upcoming_by_role() {
        echo $this->load->view('opportunity/upcoming_by_role', array(
            'opportunities' => $this->opportunity_model->get_all_available_upcoming_for_location_grouped_by_role(
                $_SESSION['user']->preferred_location_id
            )
        ), TRUE);
    }
    
    /**
     * Gets a table with the upcoming opportunities the logged in user has signed up for
     */
    public function users_commitments() {
        echo $this->load->view('opportunity/users_commitments', array(
            'commitments' => $this->opportunity_model->get_upcoming_opportunities_by_user_id($_SESSION['user']->id)
        ), TRUE);
    }
    
    /**
     * Gets a table of all upcoming opportunities that a user can sign up for based 
     *      on their training level and location
     */
    public function opportunities_for_quick_sign_up() {
        echo $this->load->view('opportunity/non_committed', array(
            'opportunities' => $this->opportunity_model->get_all_available_upcoming_for_training_level_and_location(
                $_SESSION['user']->training_level_id,
                $_SESSION['user']->preferred_location_id
            )
        ), TRUE);
    }
    
    /**
     * Gets all the dates for the opportunity calendar
     */
    public function opportunity_dates() {
        if ($_SESSION['view_as_admin']) {
            echo $this->load->view('opportunity/calendar', array(
                'dates' => $this->opportunity_model->get_all_dates_for_location($_SESSION['user']->preferred_location_id)
            ), TRUE);
        }
        else {
            echo $this->load->view('opportunity/calendar', array(
                'dates' => $this->opportunity_model->get_upcoming_dates_for_training_level_and_location(
                    $_SESSION['user']->training_level_id,
                    $_SESSION['user']->preferred_location_id
                )
            ), TRUE);
        }
    }
    
    /**
     * Schedules the logged in user for the opportunity specified in the $_POST[id] variable
     */
    public function sign_up() {        
        $this->form_validation->set_rules('id', 'Opportunity', 'trim|required|callback__verify_opportunity');
        $this->form_validation->set_rules('id', 'Scheduling Conflict', 
            'trim|required|callback__verify_scheduling_conflict['.$_SESSION['user']->id.']');
        
        if ($this->form_validation->run()) {
            $this->opportunity_model->sign_up_user($this->input->post('id'), $_SESSION['user']->id);
        }
        else{
            echo $this->form_validation->error_string('<li>', '</li>');
        }
    }
    
    /**
     * Unschedules the logged in user from the opportunity specified in the $_POST[id] variable
     */
    public function cancel_commitment() {
        $this->form_validation->set_rules('id', 'Opportunity', 'trim|required|callback__verify_cancellation');
        if ($this->form_validation->run()) {
            $this->opportunity_model->unschedule_user($this->input->post('id'), $_SESSION['user']->id);
        }
        else{
            echo $this->form_validation->error_string('<li>', '</li>');
        }
    }
    
    /**
     * Schedules a user for an opportunity. This method is intended to be used solely by admins
     */
    public function schedule_user() {
        $this->form_validation->set_rules('id', 'Opportunity', 'trim|required|callback__verify_opportunity');
        $this->form_validation->set_rules('id', 'Scheduling Conflict', 
            'trim|required|callback__verify_scheduling_conflict['.$this->input->post('user_id').']');
        
        if ($this->form_validation->run()) {
            $this->opportunity_model->sign_up_user($this->input->post('id'), $this->input->post('user_id'));
        }
        else{
            echo $this->form_validation->error_string('<li>', '</li>');
        }
    }
    
    /**
     * Unschedules a user from an opportunity that they are signed up for. This method is intended to be
     * used by admins
     */
    public function unschedule_user() {
        $this->form_validation->set_rules('id', 'Opportunity', 'trim|required|callback__verify_cancellation');
        if ($this->form_validation->run()) {
            $this->opportunity_model->unschedule_user($this->input->post('id'), $this->input->post('user_id'));
        }
        else{
            echo $this->form_validation->error_string('<li>', '</li>');
        }
    }
    
    /**
     * Deletes an opportunity
     */
    public function delete() {
        $this->form_validation->set_rules('id', 'Opportunity', 'trim|required|callback__verify_opportunity');
        if ($this->form_validation->run()) {
            $this->opportunity_model->delete($this->input->post('id'));
        }
        else{
            echo $this->form_validation->error_string('<li>', '</li>');
        }
    }
    
    /**
     * Shows a list of opportunities that a user signed up for
     */
    public function user_volunteer_record() {
        echo $this->load->view('opportunity/user_volunteer_record', array(
            'records' => $this->opportunity_model->get_volunteer_record($this->input->get('user_id'))
        ), TRUE);        
    }
    
    /**
     * Reschedules an event to a new date.
     * 
     * An "event" is a collection of opportunities at a location on a certain date
     */
    public function reschedule_event() {
        $this->form_validation->set_rules('current_date', 'Scheduled On', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('current_date', 'Scheduled On', 'callback__date_has_opportunities_at_location['.$this->input->post('location_id').']');
        $this->form_validation->set_rules('new_date', 'Reschedule To', 'trim|required|callback__is_date');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required|callback__verify_location');
        
        if ($this->form_validation->run()) {
            $current_date = date('Y-m-d', strtotime($this->input->post('current_date')));
            $new_date = date('Y-m-d', strtotime($this->input->post('new_date')));
            
            $this->opportunity_model->reschedule_opportunities($current_date, $new_date, $this->input->post('location_id'));
        }
        echo $this->load->view('opportunity/reschedule_form', array(
            'current_date' => $this->input->post('current_date'),
            'new_date' => $this->input->post('new_date'),
            'locations' => $this->location_model->get_ids_and_names(),
            'location_id' => $this->input->post('location_id')
        ), TRUE);        
    }
    
    /**
     * Checks if there are any opportunities at the specified date and location.
     *      Also, sets a form_validation error if no opportunities were found
     * 
     * @param string $date The date
     * @param int|string $location_id The location id
     * @return bool TRUE if any opportunities are found, FALSE otherwise
     */
    public function _date_has_opportunities_at_location($date, $location_id) {
        if($this->opportunity_model->oppos_exist_on_date_at_location(date('Y-m-d', strtotime($date)), $location_id)) {
            return TRUE;
        }
        $this->form_validation->set_message('_date_has_opportunities_at_location', 
                'There are no events on this date at the selected location');
        return FALSE;
    }
    
    /**
     * Checks if the selected opportunity is valid and no other user is already signed
     *      up for it. Also, sets a form_validation error if invalid
     * 
     * @param int|string $id The opportunity id
     * @return bool TRUE if valid. FALSE otherwise
     */
    public function _verify_opportunity($id) {
        $oppo = $this->opportunity_model->get_by_id($id);
        if (is_null($oppo)) {
            $this->form_validation->set_message('_verify_opportunity', 'The opportunity was not found');
            return FALSE;
        }
        else if (is_int($oppo->user_id) && (int)$_SESSION['user']->admin !== 1) {
            $this->form_validation->set_message('_verify_opportunity', 
                    'Someone else is already signed up for this opportunity');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks if there are any time conflicts for the specified user and opportunity
     * 
     * @param int|string $id The opportunity id
     * @param int|string $user_id The user's id
     * 
     * @return bool TRUE if no scheduling conflicts. FALSE otherwise
     */
    public function _verify_scheduling_conflict($id, $user_id) {
        $conflicting_oppos = $this->opportunity_model->get_time_conflicting_opportunity($id, $user_id);
        if (count($conflicting_oppos) > 0) {
            $oppo = $conflicting_oppos[0];
            $this->form_validation->set_message('_verify_scheduling_conflict', "There's a time conflict with another 
                opportunity on $oppo->date from $oppo->start_time to $oppo->end_time at $oppo->location_name for 
                $oppo->role_description");
            
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Checks if the selected opportunity can be unscheduled. 
     * 
     * Does it exist? Is someone other than the  logged in user signed up for it? 
     *      If so, is the logged in user an admin? Has the time limit for cancelling expired?
     * 
     * @param int|string $id The opportunity id
     * @return bool TRUE if can be unscheduled. FALSE otherwise
     */
    public function _verify_cancellation($id) {
        $oppo = $this->opportunity_model->get_by_id($id);
        $days = $this->config->item('days_before_opportunity_can_be_cancelled');
        $days_from_today = date('Y-m-d', strtotime('today + '.$days.' days'));
        if (is_null($oppo)) {
            $this->form_validation->set_message('_verify_cancellation', 'The opportunity was not found');
            return FALSE;
        }
        else if((int)$oppo->user_id !== (int)$_SESSION['user']->id && (int)$_SESSION['user']->admin !== 1) {
            $this->form_validation->set_message('_verify_cancellation', 
                    'Someone else is already signed up for this opportunity');
            return FALSE;
        }
        else if($oppo->date < $days_from_today && (int)$_SESSION['user']->admin !== 1) {
            $this->form_validation->set_message('_verify_cancellation', 
                    'Cancellations can only be made more than '.$days.' days ahead of time');
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
    
    /**
     * Checks to see if an opportunity ends after it starts
     * 
     * Simply compares the $end_time and $start_time to see if the $end_time is greater
     * 
     * @param string $end_time The end time
     * @param string $start_time The start time
     * @return bool Returns TRUE if the end time comes after the start time
     */
    public function _verify_start_end_time($end_time, $start_time) {        
        if (strtotime($end_time) <= strtotime($start_time)) {
            $this->form_validation->set_message('_verify_start_end_time', 'The End Time must come after the Start Time');
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
     * Checks if the selected role is valid. Also sets a form validation error if the role is invalid
     * 
     * @param int|string $id The role's id
     * @return bool Returns TRUE if the role is valid. Otherwise FALSE
     */
    public function _verify_role($id) {
        if (is_null($this->role_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_role', 'Please select a role');
            return FALSE;
        }
        return TRUE;
    }
}
