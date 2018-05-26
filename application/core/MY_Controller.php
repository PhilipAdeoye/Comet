<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'data_classes/User.php';
require_once APPPATH.'data_classes/Location.php';

/**
 * Provides base functionality for all Controllers.
 * 
 * MY_Controller extends CI_Controller and should be extended by every Controller
 *      except Account. 
 *
 * @author Philip
 */
class MY_Controller extends CI_Controller {
    
    /**
     * Layout view that other views will be injected into.
     * 
     * Default is set to 'shared/layout' in the __construct() constructor function
     * 
     * @access public
     * @var string
     */
    public $template = NULL;   
    
    /**
     * List of methods that are accessible by non-admins
     * 
     * Each element is a string with a method name e.g. 'index' that non-admins can access.
     * 
     * @access public
     * @var array
     */
    public $permitted_methods_for_non_admins = array();
    
    public $page_title_prefix = '';
    
    /**
     * MY_Controller constructor.
     * 
     * - Calls the CI_Controller constructor
     * - Sets the $template to 'shared/layout'
     * - Checks to see if the user is logged in. If the are not logged in, it redirects to 'account/login'
     * 
     * @return void Returns nothing
     */
    public function __construct() {
        parent::__construct();
        
        $this->page_title_prefix = $this->config->item('clinic_name_abbr').' | ';
        
        if (is_null($this->template)) {
            $this->template = 'shared/layout';
        }        
        
        $user = $this->get_logged_in_user();
        if(is_null($user)) {
            redirect('account/login');
        }
        
        if ($user->password_has_expired) {
            redirect('account/choose_new_password');
        }
        
        if ($user->needs_to_confirm_data) {
            redirect('account/confirm_data');
        }
        
        if($this->user_needs_to_accept_or_reject_media_release()) {
            redirect('account/media_release_form');
        }
        
        // In the urls, the method is the second segment, e.g. example.com/welcome/index. The controller is the first
        $current_method = $this->uri->segment(2, 'index');
        if ((int)$user->user_is_admin !== 1 && !in_array($current_method, $this->permitted_methods_for_non_admins, TRUE)) {
            show_404();
        }        
        
        if (!is_null($_POST) && count($_POST) > 0) {
            $this->load->helper('htmlpurifier');
            $_POST = html_purify($_POST);            
        }
    }
    
    /**
     * Gets details for the logged in user
     * 
     * Checks the $_SESSION superglobal for the user's id and if set, returns user related stuff in an object. 
     *      Otherwise it returns NULL.
     * 
     * @access protected
     * @return object|NULL Returns the user object if a logged in user is found. Otherwise returns NULL
     */
    protected function get_logged_in_user() {
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            return (object) array(                
                'user_is_admin' => $user->admin,
                'password_has_expired' => (date('Y-m-d H:i:s') > $user->password_expires_on),
                'user_id'=> $user->id,
                'preferred_location_id' => $user->preferred_location_id,
                'needs_to_confirm_data' => (int)$user->has_confirmed_existing_data !== 1
            );
        }
        else {
            return NULL;
        }
    }
    
    /**
     * Checks if the user's preferred location uses a media release form and if the user needs to accept or reject it
     * 
     * @access protected     
     * @return bool Returns TRUE if the condition is met, FALSE otherwise
     */
    protected function user_needs_to_accept_or_reject_media_release() {
        if ((int)$_SESSION['location']->uses_media_release_form === 1 
            && is_null($_SESSION['user']->has_accepted_media_release_form)) {
            return TRUE;
        }
        return FALSE;
    }
    
}
