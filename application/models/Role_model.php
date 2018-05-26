<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Role.php';

/**
 * Methods that deal with the roles table
 *
 * @author Philip
 */
class Role_model extends CI_Model{
    
    const TABLE_NAME = 'roles';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the role specified by the id
     * 
     * @param int id The role's id
     * @return data_classes\Role|NULL A data_classes\Role object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
                'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Role');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Gets all roles
     * 
     * @return array An array of data_classes\Role Objects
     */
    public function get_all() {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME. ' ORDER BY description'
        );        
        
        return $query->result('data_classes\Role');
    }
    
    
    
    /**
     * Returns an array with all the roles
     * 
     * @return array An array of data_classes\Role objects
     */
    public function get_ids_and_descriptions() {
        $query = $this->db->query('SELECT id, description FROM '. self::TABLE_NAME. ' ORDER BY description');
        return $query->result('data_classes\Role');
    }
    
    /**
     * Updates a record in the Role table
     * 
     * @param data_classes\Role $role An object with the role details
     * @return void Returns Nothing
     */
    public function update(data_classes\Role $role) {
        $this->db->query('
            UPDATE '.self::TABLE_NAME.'
                SET description = ?, help_text = ?, email_text = ?
                WHERE id = ?',
            array(
                $role->description,
                $role->help_text,
                $role->email_text,
                $role->id)
        );
    }
    
    /**
     * Creates a record in the Role table
     * 
     * @param data_classes\Role $role An object with the role details
     * @return void Returns Nothing
     */
    public function create(data_classes\Role $role) {
        $this->db->query('
            INSERT INTO '.self::TABLE_NAME.' (description, help_text, email_text) 
                VALUES (?, ?, ?)',
            array(
                $role->description,
                $role->help_text,
                $role->email_text)
        );
    }
}
