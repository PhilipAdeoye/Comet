<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * A library containing event listeners and handlers that send emails
 *
 * @author Philip
 */
class Mailer {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();

        $this->CI->load->library('email');
        $this->_initialize_email();

        $this->CI->load->model('user_model');
        $this->CI->load->model('email_model');

        if ('production' === ENVIRONMENT) {
            Events::register('user_created', array($this, 'send_user_registered_email'));
            Events::register('user_details_changed', array($this, 'send_user_details_changed_email'));
            Events::register('user_password_changed', array($this, 'send_password_changed_email'));
            Events::register('temporary_auth_initiated', array($this, 'send_temporary_auth_email'));

            Events::register('user_scheduled', array($this, 'send_user_scheduled_email'));
            Events::register('user_unscheduled', array($this, 'send_user_unscheduled_email'));
            
            Events::register('message_delivery_requested', array($this, 'send_arbitrary_email'));
        } elseif (is_cli()) {
            Events::register('reminder_for_date', array($this, 'send_reminder_emails_for_date'));
        }
    }

    private function _initialize_email() {

        // For other config options, please check the codeigniter docs
        // https://codeigniter.com/userguide3/libraries/email.html#email-preferences

        $config['smtp_crypto'] = 'ssl';
        $config['wordwrap'] = FALSE;

        $this->CI->email->initialize($config);
    }

    /**
     * Sends a welcome message to newly registered users
     * 
     * @param array $params An associative array. 
     *      Keys. 1. email: The email address of the recipient
     *            2. first_name: The first name of the recipient
     */
    public function send_user_registered_email($params) {
        $ss_email = $this->CI->email_model->get_by_name('new_user_registered');
        if (is_null($ss_email)) {
            return;
        }

        $this->CI->email
            ->from(
                $this->CI->config->item('email_sent_from_address'), $this->CI->config->item('email_sent_from_name'))
            ->to($params['email'])
            ->subject($ss_email->subject)
            ->message(str_replace('@{first_name}', $params['first_name'], $ss_email->message))
            ->send();
    }

    /**
     * Sends a message notifying users that their account details have been changed
     * 
     * @param array $params An associative array. 
     *      Keys. 1. email: The email address of the recipient
     *            2. first_name: The first name of the recipient
     */
    public function send_user_details_changed_email($params) {
        $ss_email = $this->CI->email_model->get_by_name('user_details_changed');
        if (is_null($ss_email)) {
            return;
        }

        $this->CI->email
            ->from(
                $this->CI->config->item('email_sent_from_address'), $this->CI->config->item('email_sent_from_name'))
            ->to($params['email'])
            ->subject($ss_email->subject)
            ->message(str_replace('@{first_name}', $params['first_name'], $ss_email->message))
            ->send();
    }

    /**
     * Sends an email notifying users that their password has been recently changed
     * 
     * @param string $email The email address of the recipient
     */
    public function send_password_changed_email($email) {
        $ss_email = $this->CI->email_model->get_by_name('user_password_changed');
        if (is_null($ss_email)) {
            return;
        }

        $user = $this->CI->user_model->get_by_email($email);

        $this->CI->email
            ->from(
                $this->CI->config->item('email_sent_from_address'), $this->CI->config->item('email_sent_from_name'))
            ->to($email)
            ->subject($ss_email->subject)
            ->message(str_replace('@{first_name}', $user->first_name, $ss_email->message))
            ->send();
    }

    /**
     * Sends an email to users to help them recover their account
     * 
     * @param array $params An associative array. 
     *      Keys. 1. email: The email address of the recipient
     *            2. temporary_auth_token: A string that serves as a temporary authentication token
     */
    public function send_temporary_auth_email($params) {
        $email = $params['email'];
        $temporary_auth_token = $params['temporary_auth_token'];
        $user = $this->CI->user_model->get_by_email($email);

        $link_url = base_url('account/validate_temporary_auth') .
            '?email=' . $email . '&tat=' . $temporary_auth_token;

        // Send an email
        $this->CI->email
            ->from(
                $this->CI->config->item('email_sent_from_address'), $this->CI->config->item('email_sent_from_name'))
            ->to($email)
            ->subject('[' . $this->CI->config->item('email_sent_from_name') . '] Password Change Request')
            ->message('Hi ' . $user->first_name . ',' . PHP_EOL . PHP_EOL
                . "Forget your password? Click the link below and let's get you a new one." . PHP_EOL . PHP_EOL
                . $link_url . PHP_EOL . PHP_EOL
                . "BTW, hurry because you've only got 10 minutes before that link expires and we have to do this all over again" . PHP_EOL . PHP_EOL
                . 'Thank you for being a part of our volunteer community')
            ->send();
    }

    public function send_user_scheduled_email($opportunity_id) {
        $ss_email = $this->CI->email_model->get_by_name('user_is_scheduled');
        $details = $this->CI->email_model->get_details_for_opportunity($opportunity_id);

        $this->_replace_placeholders_with_actual_data_and_send($ss_email, $details);
    }

    public function send_user_unscheduled_email($params) {
        $ss_email = $this->CI->email_model->get_by_name('user_is_no_longer_scheduled');
        $details = $params['details'];

        $this->_replace_placeholders_with_actual_data_and_send($ss_email, $details);
    }

    /**
     * Sends email reminders to all users signed up for opportunities on the specified date
     * 
     * @param string $date The date for the opportunities
     */
    public function send_reminder_emails_for_date($date) {
        $ss_email = $this->CI->email_model->get_by_name('next_day_reminder');
        $details = $this->CI->email_model->get_reminder_details_for_date($date);

        $this->_replace_placeholders_with_actual_data_and_send($ss_email, $details);
    }
    
    /**
     * Sends an arbitrary message to recipients
     * 
     * @param array $params An associative array. 
     *      Keys. 1. sender: The name of the sender eg. VCP
     *            2. emails: A comma-delimited string of email addresses
     *            3. subject: The message's subject
     *            4. message: The body of the message
     */
    public function send_arbitrary_email($params) {
        $this->CI->email
            ->from(
                $this->CI->config->item('email_sent_from_address'), $params['sender'])
            ->to($params['emails'])
            ->subject($params['subject'])
            ->message($params['message'])
            ->send();
    }

    /**
     * Replaces the placeholders in $system_sent_email with the actual data in $details 
     *      and sends out one email for each $detail in $details
     * 
     * @param data_classes\Email $system_sent_email An object containing the id, name, subject, 
     *      and placeholder/template body for the email message
     * @param array $details Each element contains at least the email address (that the message will get sent to)
     *      and the actual data to replace the placeholders
     * 
     */
    private function _replace_placeholders_with_actual_data_and_send($system_sent_email, $details) {
        if (is_null($system_sent_email) || count($details) === 0) {
            return;
        }

        $keys = array_keys(get_object_vars($details[0]));
        $placeholders = $this->_get_placeholders_to_replace($keys);

        foreach ($details as $detail) {
            $actual_data = $this->_get_actual_data($detail, $keys);

            $this->CI->email
                ->from(
                    $this->CI->config->item('email_sent_from_address'), $this->CI->config->item('email_sent_from_name'))
                ->to($detail->email)
                ->subject($system_sent_email->subject)
                ->message(str_replace($placeholders, $actual_data, $system_sent_email->message))
                ->send();
        }
    }

    /**
     * Returns an array of strings to be used as the $search argument for str_replace
     * 
     * @param array $keys An array of strings with each element a key
     * @return array An array of strings with each element resembling by @{key}
     */
    private function _get_placeholders_to_replace($keys) {
        $strings_to_replace = array();
        foreach ($keys as $index => $name) {
            $strings_to_replace[] = '@{' . $name . '}';
        }
        return $strings_to_replace;
    }

    /**
     * Returns the actual data be used as the $replace argument for str_replace
     * 
     * @param stdObject $params An object containing the data for each user to be emailed
     * @param array $keys An array of strings with each element a key
     * 
     * @return array An array of strings with the actual data
     */
    private function _get_actual_data($params, $keys) {
        $replacements = array();
        $params_array = (array) $params;
        foreach ($keys as $index => $name) {
            $replacements[] = $params_array["$name"];
        }
        return $replacements;
    }

}
