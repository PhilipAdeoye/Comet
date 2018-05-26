<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Partner.php';

/**
 * Methods that deal with the Partners table
 *
 * @author Philip
 */
class Partner_model extends CI_Model {
    
    const TABLE_NAME = 'partners';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the partner specified by the id
     * 
     * @param int id The partner's id
     * @return data_classes\Partner|NULL A data_classes\Partner object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
                'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Partner');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Returns an array with all the partners
     * 
     * @return array An array of data_classes\Partner objects
     */
    public function get_ids_and_names() {
        $query = $this->db->query('SELECT id, name FROM '. self::TABLE_NAME. ' ORDER BY name');
        return $query->result('data_classes\Partner');
    }
    
    /**
     * Gets all partners
     * 
     * @return array An array of data_classes\Partner Objects
     */
    public function get_all() {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME. ' ORDER BY name'
        );        
        
        return $query->result('data_classes\Partner');
    }
    
    /**
     * Updates a record in the Partner table
     * 
     * @param data_classes\Partner $partner An object with the partner details
     * @return void Returns Nothing
     */
    public function update(data_classes\Partner $partner) {
        $this->db->query('
            UPDATE '.self::TABLE_NAME.' SET name = ? WHERE id = ?',
            array($partner->name, $partner->id)
        );
    }
    
    /**
     * Creates a record in the Partner table
     * 
     * @param data_classes\Partner $partner An object with the partner details
     * @return void Returns Nothing
     */
    public function create(data_classes\Partner $partner) {
        $this->db->query('
            INSERT INTO '.self::TABLE_NAME.' (name) VALUES (?)',
            array($partner->name)
        );
    }
            
}
