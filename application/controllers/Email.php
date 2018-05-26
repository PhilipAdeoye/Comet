<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email Controller which manages editing automatically system-sent emails
 *
 * @author Philip
 */
class Email extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
                
        $this->load->helper('text');
        $this->load->event('mailer');
        $this->load->model('email_model');
    }
    
    /**
     * Route: email/index OR email
     * 
     * Shows the listing of emails with buttons to edit them
     */
    public function index() {
        $view = $this->load->view('email/index', array(), TRUE);

        return $this->load->view($this->template, array(
            'page_title' => $this->page_title_prefix . 'Email Manager',
            'main' => $view,
        ));
    }
    
    /**
     * Route: email/emails
     * 
     * Returns a string that describes a HTML table containing all the emails
     */
    public function emails() {
        echo $this->load->view('email/emails', array(
            'emails' => $this->email_model->get_all()
        ), TRUE);
    }
    
    /**
     * Route: [GET] email/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of an email entry
     */
    public function edit() {
        
        $email = $this->email_model->get_by_id($this->input->get('id'));
        if (!is_null($email)) {
            echo $this->load->view('email/edit_form', array(
                'id' =>$email->id,
                'subject' => $email->subject,
                'message' => $email->message
            ), TRUE);
        }
        else {
            echo show_404('The resource you requested was not found');
        }        
    }
    
    /**
     * Route: [POST] email/post_edit?url_params...
     * 
     * Accepts form submissions and updates an email entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Email Integrity', 'trim|required|is_natural_no_zero|callback__verify_email', 'Email cannot be saved as is');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Body Template', 'trim|required');

        if ($this->form_validation->run()) {
            $email = new data_classes\Email();
            
            $email->id = $this->input->post('id');         
            $email->subject = $this->input->post('subject');
            $email->message = $this->input->post('message');

            $this->email_model->update($email);
        }
        echo $this->load->view('email/edit_form', array(
            'id' => $this->input->post('id'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message')
        ), TRUE);
    }
    
    /**
     * Route: [GET] email/get_send_message_form
     * 
     * Returns a view with the send_message_form
     */
    public function get_send_message_form() {
        echo $this->load->view('email/send_message_form', array(
            'emails' => '',
            'sender' => $this->config->item('email_sent_from_name'),
            'subject' => '',
            'message' => ''
        ), TRUE);
    }
    
    /**
     * Route: [POST] email/send_message
     */
    public function send_message() {
        $this->form_validation->set_rules('emails', 'Recipients', 'trim|required|valid_emails');
        $this->form_validation->set_rules('sender', 'Your Name', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Body', 'trim|required');
        
        if ($this->form_validation->run()) {
            Events::trigger('message_delivery_requested',
                array(
                    'emails' => $this->input->post('emails'),
                    'sender' => $this->input->post('sender'),
                    'subject' => $this->input->post('subject'),
                    'message' => $this->input->post('message')
                )
            );
        }
        echo $this->load->view('email/send_message_form', array(
            'emails' => $this->input->post('emails'),
            'sender' => $this->input->post('sender'),
            'subject' => $this->input->post('subject'),
            'message' => $this->input->post('message')
        ), TRUE);
    }
    
    /**
     * Checks if the selected email is valid. Also sets a 
     *      form validation error if the email is invalid
     * 
     * @param int|string $id The email's id
     * @return bool Returns TRUE if the email is valid. Otherwise FALSE
     */
    public function _verify_email($id) {
        if (is_null($this->email_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_email', 'Unknown Email');
            return FALSE;
        }
        return TRUE;
    }
}


