<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * A library containing event listeners and handlers that perform maintenance functions
 *
 * @author Philip
 */
class Maintenance {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
        
        $this->CI->load->model('user_model');

        if ('production' === ENVIRONMENT) {
        }
        elseif (is_cli()) {            
            Events::register('user_reset_for_new_semester', array($this, 'reset_users_for_new_semester'));
        }
    }
    
    public function reset_users_for_new_semester() {
        $this->CI->user_model->reset_availability_for_all_users();
    }
}
