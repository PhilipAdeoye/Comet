<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Location.php';

/**
 * Methods that deal with the locations table
 *
 * @author Philip
 */
class Location_model extends CI_Model{
    
    const TABLE_NAME = 'locations';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the location specified by the id
     * 
     * @param int id The location's id
     * @return data_classes\Location|NULL A data_classes\Location object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
                'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Location');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Gets all locations
     * 
     * @return array An array of data_classes\Location Objects
     */
    public function get_all() {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME
        );        
        
        return $query->result('data_classes\Location');
    }
    
    /**
     * Returns an array with all the locations
     * 
     * @return array An array of data_classes\Location objects
     */
    public function get_ids_and_names() {
        $query = $this->db->query('SELECT id, name FROM '. self::TABLE_NAME);
        return $query->result('data_classes\Location');
    }
    
    /**
     * Gets a list of all users at the specified location and their responses/non-responses to the 
     *      media release form
     * 
     * It does not matter if there is a media release form for that location or 
     *      whether or not it's presently in use
     * 
     * @param int|string $id The location id
     * @return array Each element contains the user's full_name, their response, and when they responded_on
     */
    public function get_media_release_responses_for_location($id) {
        $query = $this->db->query('
            SELECT 
                CONCAT(CONCAT(u.first_name, " "), u.last_name) AS full_name,
                CASE u.has_accepted_media_release_form 
                    WHEN 1 THEN "Yes" 
                    WHEN 0 THEN "No"
                    ELSE "" END AS response,
                DATE_FORMAT(u.accepted_media_release_form_on, "%m/%d/%Y") AS responded_on
            FROM users u 
                INNER JOIN locations l on u.preferred_location_id = l.id
            WHERE l.id = ?
            ORDER BY u.last_name',
            array($id)
        );
        return $query->result();
    }
    
    /**
     * Updates a record in the Location table
     * 
     * @param data_classes\Location $location An object with the location details
     * @return void Returns Nothing
     */
    public function update(data_classes\Location $location) {
        $this->db->query('
            UPDATE '.self::TABLE_NAME.'
                SET name = ?, address = ?, email_text = ?, uses_media_release_form = ?, media_release_form = ? 
                WHERE id = ?',
            array(
                $location->name,
                $location->address,
                $location->email_text,
                $location->uses_media_release_form,
                $location->media_release_form,
                $location->id
            )
        );
    }
    
    /**
     * Creates a record in the Location table
     * 
     * @param data_classes\Location $location An object with the location details
     * @return void Returns Nothing
     */
    public function create(data_classes\Location $location) {
        $this->db->query('
            INSERT INTO '.self::TABLE_NAME.' (name, address, email_text, uses_media_release_form, media_release_form) 
                VALUES (?, ?, ?, ?, ?)',
            array(
                $location->name,
                $location->address,
                $location->email_text,
                $location->uses_media_release_form,
                $location->media_release_form
            )
        );
    }
}
