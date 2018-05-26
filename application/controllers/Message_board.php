<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Message.php';

/**
 * Message Board controller that handles CRUD operations for the message board
 *
 * @author Philip
 */
class Message_board extends MY_Controller {

    public $permitted_methods_for_non_admins = [
        'index',
        'get_messages_for_year'
    ];
    
    public function __construct() {
        parent::__construct();

        $this->load->model('message_board_model');
    }

    /**
     * Route: message_board/index
     * 
     * Shows the listing of messages posted to the message board in a list grouped by the year
     * the each message was last modified
     */
    public function index() {
        $distinct_message_years = $this->message_board_model->get_distinct_message_posted_years();
        $messages_view = '';

        if (count($distinct_message_years) > 0) {
            $most_recent_messages = $this->message_board_model->get_all_messages_updated_in_year($distinct_message_years[0]->year);
            $messages_view = $this->load->view('message_board/messages', array(
                'messages' => $most_recent_messages
                    ), TRUE);
        }

        $view = $this->load->view('message_board/index', array(
            'years' => $distinct_message_years,
            'most_recent_messages' => $messages_view
                ), TRUE);
        $this->load->view($this->template, array(
            'main' => $view,
            'page_title' => $this->page_title_prefix . 'Message Board'
        ));
    }

    /**
     * Route: message_board/index?year=<year>
     * 
     * Returns a HTML string of all messages last modified within the specified year
     */
    public function get_messages_for_year() {
        $year = $this->input->get('year');
        echo $this->load->view('message_board/messages', array(
            'messages' => $this->message_board_model->get_all_messages_updated_in_year($year)
                ), TRUE);
    }
    
    /**
     * Route: [GET/POST] message_board/create.
     * 
     * On GET requests: Returns a HTML string describing empty form elements to create a 
     * message_board entry. On POST requests, accepts form submissions and creates a new 
     * message_board record.
     */
    public function create() {        
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        
        if ($this->form_validation->run()) {
            $message = new data_classes\Message();
            $message->title = $this->input->post('title');
            $message->message = $this->input->post('message');
            $message->user_id = $_SESSION['user']->id;

            $this->message_board_model->create($message);
        }
        echo $this->load->view('message_board/create_form', array(
            'title' => $this->input->post('title'),
            'message' => $this->input->post('message')
        ), TRUE);        
    }

    /**
     * Route: [GET] message_board/edit?id=<id>
     * 
     * Returns a HTML string describing pre-populated form elements for the user-editable fields
     * of a message_board entry
     */
    public function edit() {

        $message = $this->message_board_model->get_by_id($this->input->get('id'));
        if (!is_null($message)) {

            echo $this->load->view('message_board/edit_form', array(
                'id' => $message->id,
                'title' => $message->title,
                'message' => $message->message
                    ), TRUE);
        } else {
            echo show_404('The resource you requested was not found');
        }
    }

    /**
     * Route: [POST] message_board/post_edit?url_params...
     * 
     * Accepts form submissions and updates a message board entry.
     */
    public function post_edit() {
        $this->form_validation->set_rules('id', 'Message Integrity', 'trim|required|is_natural_no_zero|callback__verify_message', 'The message cannot be saved as is');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run()) {
            $message = new data_classes\Message();
            $message->id = $this->input->post('id');
            $message->title = $this->input->post('title');
            $message->message = $this->input->post('message');
            $message->user_id = $_SESSION['user']->id;

            $this->message_board_model->update($message);
        }
        echo $this->load->view('message_board/edit_form', array(
            'id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'message' => $this->input->post('message')
        ), TRUE);
    }

    /**
     * Verifies that a message id submitted in a form to the post_edit() method is a pre-exiting
     *      entry in the message_board table
     * 
     * @params int|string $id The id of the message_board entry
     * @return bool TRUE or FALSE
     */
    public function _verify_message($id) {
        if (is_null($this->message_board_model->get_by_id($id))) {
            $this->form_validation->set_message('_verify_message', 'This message could not be found');
            return FALSE;
        }
        return TRUE;
    }

}
