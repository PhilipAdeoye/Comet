<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Email.php';

/**
 * Methods that deal with the emails table
 *
 * @author Philip
 */
class Email_model extends CI_Model{
    
    const TABLE_NAME = 'emails';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the email specified by the id
     * 
     * @param int id The email's id
     * @return data_classes\Email|NULL A data_classes\Email object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Email');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Get the email specified by the name
     *  
     * @param int id The email's id
     * @return data_classes\Email|NULL A data_classes\Email object if found, otherwise NULL
     */
    public function get_by_name($name) {
        $query = $this->db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE name = ?', array($name)
        );

        $array = $query->result('data_classes\Email');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Gets all emails
     * 
     * @return array An array of data_classes\Email Objects
     */
    public function get_all() {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME
        );        
        
        return $query->result('data_classes\Email');
    }
    
    /**
     * Updates a record in the emails table
     * 
     * @param data_classes\Email $email An object containing the email details
     * @return void Returns nothing
     */
    public function update(data_classes\Email $email) {
        $this->db->query('
            UPDATE '.self::TABLE_NAME.'
                SET subject = ?, message = ?
                WHERE id = ?',
            array(
                $email->subject,
                $email->message,
                $email->id
            )
        );            
    }
    
    /**
     * Gets the email addresses and details for reminder emails
     * 
     * @param string $date The date for the opportunities
     * @return array An object containing the role_description, user's email and firstname, location_name,
     *      address, and the start_time and end_time of the opportunity
     */
    public function get_reminder_details_for_date($date) {
        $query = $this->db->query(
            'SELECT 
                r.description as role_description,
                u.email,
                u.first_name,
                l.name as location_name,
                l.address,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time
            FROM opportunities o
                INNER JOIN users u ON o.user_id = u.id
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
            WHERE o.date = ? AND o.user_id IS NOT NULL',
            array($date)
        );
        
        return $query->result();
    }
    
    /**
     * Gets the email address and details for an opportunity
     * 
     * @param int|string $opportunity_id The opportunity's id
     * @return array Each element contains the receipient's first_name, email, the date,
     *      start_time, end_time, role_description, location, and address of the opportunity
     *      as well as the user's training_level_email and the opportunity's role's role_email_text
     */
    public function get_details_for_opportunity($opportunity_id) {
        $query = $this->db->query(
            'SELECT
                u.first_name,
                u.email,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                r.description AS role_description,
                l.name as location_name,
                l.address,
                t.email_text AS training_level_email_text,
                r.email_text AS role_email_text,
                l.email_text AS location_email_text
            FROM opportunities o
                INNER JOIN users u on o.user_id = u.id
                INNER JOIN locations l on o.location_id = l.id
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN training_levels t on u.training_level_id = t.id
            WHERE o.id = ?',
            array($opportunity_id)
        );
        
        return $query->result();
    }
    
}
