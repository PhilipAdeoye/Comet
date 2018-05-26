<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The root route of the application as defined in config/routes.php $route['default_controller']
 * 
 * @author Philip
 */
class Welcome extends MY_Controller {

    public $permitted_methods_for_non_admins = [
        'index',
        'get_privacy_policy'
    ];

    public function index() {
        redirect('opportunity/index');
    }
    
    /**
     * Allows admins to switch between a user mode an being a full admin
     * 
     * While viewing the site as a user, admins still retain their full admin privileges.
     *      User mode only restricts admins viewing shared spaces such as the message board
     *      and the opportunities view. Admin only spaces are unrestricted for admins in user mode
     *      with the only restriction being that there are no easy links or buttons to 
     *      navigate to those Admin only spaces
     */
    public function toggle_admin_view_mode() {
        $_SESSION['view_as_admin'] = ((int)$_SESSION['view_as_admin'] + 1) % 2;
    }
}
