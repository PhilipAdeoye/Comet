<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'data_classes/Training_level.php';
/**
 * Methods that deal with the training_levels table
 *
 * @author Philip
 */
class Training_level_model extends CI_Model {
    
    const TABLE_NAME = 'training_levels';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets a training_level by the id
     * 
     * @param int id The training_level's id
     * @return data_classes\Training_level|NULL If found a data_classes\Training_level object, NULL otherwise
     */
    public function get_by_id($id) {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME.' WHERE id = ?',
            array($id)
        );        
        
        $array = $query->result('data_classes\Training_level');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Gets all training levels
     * 
     * @return array An array of data_classes\Training_level Objects
     */
    public function get_all() {
        $query = $this->db->query('
            SELECT 
                t.id,
                t.name,
                p.name as partner_name,
                t.email_text
            FROM training_levels t 
                LEFT OUTER JOIN partners p ON t.partner_id = p.id
            ORDER BY p.name, t.name'
        );        
        
        return $query->result();
    }
    
    /**
     * Returns an array with all the training_levels
     * 
     * @return array An array of data_classes\Training_level objects
     */
    public function get_ids_and_names() {
        $query = $this->db->query('SELECT id, name FROM '. self::TABLE_NAME.' ORDER BY name');
        return $query->result('data_classes\Training_level');
    }
    
    /**
     * Returns an array with the training_levels for the specified partner
     * 
     * @return array An array of data_classes\Training_level objects
     */
    public function get_ids_and_names_for_partner($partner_id) {
        $query = $this->db->query('
            SELECT id, name 
            FROM '. self::TABLE_NAME.'
            WHERE partner_id = ?
            ORDER BY name',
            array($partner_id)
        );        
        
        return $query->result('data_classes\Training_level');
    }
    
    /**
     * Updates a record in the Training_level table
     * 
     * @param data_classes\Training_level $training_level An object with the training_level details
     * @return void Returns Nothing
     */
    public function update(data_classes\Training_level $training_level) {
        $this->db->query('
            UPDATE '.self::TABLE_NAME.'
                SET name = ?, partner_id =?, email_text = ?
                WHERE id = ?',
            array(
                $training_level->name,
                $training_level->partner_id,
                $training_level->email_text,
                $training_level->id)
        );
    }
    
    /**
     * Creates a record in the Training_levels table
     * 
     * @param data_classes\Training_level $training_level An object with the training_level details
     * @return void Returns Nothing
     */
    public function create(data_classes\Training_level $training_level) {
        $this->db->query('
            INSERT INTO '.self::TABLE_NAME.' (created_on, name, partner_id, email_text) 
                VALUES (NOW(), ?, ?, ?)',
            array(
                $training_level->name,
                $training_level->partner_id,
                $training_level->email_text)
        );
    }
    
}
