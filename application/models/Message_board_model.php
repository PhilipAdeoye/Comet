<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Message.php';

/**
 * Methods that deal with the message_board table
 *
 * @author Philip
 */
class Message_board_model extends CI_Model {

    const TABLE_NAME = 'message_board';

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    /**
     * Returns an array of distinct calendar years on which messages posted to the message_board
     * were last modified. Ordered in a descending fashion by the modified_on (date)
     * 
     * @return array [{'year' => '2016'}, {'year' => '2015'} ...]
     */
    public function get_distinct_message_posted_years() {
        $query = $this->db->query('
            SELECT DISTINCT YEAR(modified_on) AS year 
            FROM ' . self::TABLE_NAME . ' 
            ORDER BY modified_on DESC');

        return $query->result();
    }

    /**
     * Returns an array of messages last updated in the specified year
     * Ordered in a descending fashion by the modified_on (date)
     * 
     * @param string $year The year to get all messages for
     * @return array Each object contains id, created_on, modified_on, user_id, full_name,
     *      title, and message
     */
    public function get_all_messages_updated_in_year($year) {
        $query = $this->db->query('
            SELECT 
                m.id, 
                m.created_on, 
                DATE_FORMAT(m.modified_on, "%m/%d/%Y") AS modified_on, 
                m.user_id, 
                m.title, 
                m.message,
                CONCAT(CONCAT(u.first_name, " "), u.last_name) as full_name
            FROM ' . self::TABLE_NAME . ' m
                INNER JOIN users u ON m.user_id = u.id
            WHERE year(m.modified_on) = ?
            ORDER BY m.modified_on DESC', array($year));

        return $query->result();
    }

    /**
     * Gets the message specified by the id
     * 
     * @param int id The message's id
     * @return data_classes\Message|NULL A data_classes\Message object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
                'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Message');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }

    /**
     * Creates a message in the Message_board table
     * 
     * @param data_classes\Message $message An object with the message details
     * @return void Returns Nothing
     */
    public function create(data_classes\Message $message) {
        $this->db->query('
            INSERT INTO '.self::TABLE_NAME.' (created_on, modified_on, user_id, title, message)
            VALUES (NOW(), NOW(), ?, ?, ?)',
            array(
                $message->user_id, 
                $message->title, 
                $message->message
            )
        );
    }
    
    /**
     * Updates a message in the Message_board table
     * 
     * @param data_classes\Message $message An object with the message details
     * @return void Returns Nothing
     */
    public function update(data_classes\Message $message) {
        $this->db->query('
            UPDATE ' . self::TABLE_NAME . ' 
                SET user_id = ?, modified_on = NOW(), title = ?, message = ?
                WHERE id = ?', 
            array(
                $message->user_id, 
                $message->title, 
                $message->message, 
                $message->id
            )
        );
    }

}
