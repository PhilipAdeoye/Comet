<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller which handles triggering of processes that we want done at specific times
 * 
 * @author Philip 
 */
class Cron extends CI_Controller {    

    public function __construct() {
        parent::__construct();
        
        // Allow access to this controller only via the command line
        if (!is_cli()) {
            show_404();
        }
        
        // Load the classes that contain the events that can be triggered
        $this->load->event('mailer');
        $this->load->event('maintenance');
    }

    /**
     * Triggers the reminder_for_date event
     */
    public function trigger_next_day_reminder() {
        $date = date('Y-m-d', strtotime('tomorrow'));
        Events::trigger('reminder_for_date', $date);        
    }
    
    /**
     * Triggers the user_reset_for_new_semester event
     */
    public function trigger_reset_users_for_new_semester() {
        Events::trigger('user_reset_for_new_semester');
    }

    /**
     * Triggers a test event
     */
    public function test() {  
        Events::trigger('test', 'This is a test of the cron email system');        
    }    

}
