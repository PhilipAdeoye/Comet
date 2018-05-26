<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Ability.php';

/**
 * Methods that deal with the abilities table
 *
 * @author Philip
 */
class Ability_model extends CI_Model {

    const TABLE_NAME = 'abilities';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets the ability specified by the id
     * 
     * @param int id The ability's id
     * @return data_classes\Ability|NULL A data_classes\Ability object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Ability');
        if (count($array) > 0) {
            return $array[0];
        }
        return NULL;
    }

    /**
     * Gets the abilities for the specified training level 
     * 
     * @param int|string $training_level_id The training level id
     * @return array Each element contains the role_description, role_id, and has_ability:
     *      A boolean indicating whether the training level can fulfil the role   
     */
    public function get_all_for_training_level_id($training_level_id) {
        $query = $this->db->query('
            SELECT 
                r.description AS role_description,
                r.id as role_id,
                IF(a.id IS NULL, 0, 1) as has_ability
            FROM roles r
                LEFT OUTER JOIN abilities a ON r.id = a.role_id AND a.training_level_id = ?
            ORDER BY r.description', array($training_level_id)
        );

        return $query->result();
    }

    /**
     * Update a training level's abilities
     * 
     * Inserts a row into the abilities table for each role id and training level id
     *      combination
     * 
     * @param array[int|string] $role_ids The ids for the roles
     * @param int|string $training_level_id The training level id
     * @return void Returns Nothing
     */
    public function update_for_training_level_id($role_ids, $training_level_id) {

        // Delete the present abilities
        $this->db->query('DELETE FROM abilities WHERE training_level_id = ?', array($training_level_id));

        $rows_to_add = array();
        foreach ($role_ids as $role_id) {

            if (is_numeric($role_id)) {

                // Prepare a tuple with the role_id and training_level_id
                $rows_to_add[] = array(
                    'role_id' => $role_id,
                    'training_level_id' => $training_level_id
                );
            }
        }

        if (count($rows_to_add) > 0) {
            // Insert the ones newly selected
            $this->db->insert_batch(self::TABLE_NAME, $rows_to_add);
        }
    }

    /**
     * Gets the abilities for the specified role
     * 
     * @param int|string $role_id The role id
     * @return array Each element contains the partner_training_level, training_level_id, and has_ability:
     *      A boolean indicating whether the role can be fulfilled by the training level
     */
    public function get_all_for_role_id($role_id) {
        $query = $this->db->query('
            SELECT 
                CONCAT(CONCAT(p.name, ": "), t.name) AS partner_training_level, 
                t.id AS training_level_id, 
                IF(a.id IS NULL, 0, 1) as has_ability 
            FROM training_levels t 
                INNER JOIN partners p on t.partner_id = p.id 
                LEFT OUTER JOIN abilities a ON t.id = a.training_level_id AND a.role_id = ? 
            ORDER BY partner_training_level', array($role_id)
        );

        return $query->result();
    }

    /**
     * Update a role's abilities
     * 
     * Inserts a row into the abilities table for each role id and training level id
     *      combination
     * 
     * @param array[int|string] $training_level_ids The ids for the training levels
     * @param int|string $role_id The role id
     * @return void Returns Nothing
     */
    public function update_for_role_id($training_level_ids, $role_id) {

        // Delete the present abilities
        $this->db->query('DELETE FROM abilities WHERE role_id = ?', array($role_id));

        $rows_to_add = array();
        foreach ($training_level_ids as $training_level_id) {
            if (is_numeric($training_level_id)) {

                // Prepare a tuple with the role_id and training_level_id
                $rows_to_add[] = array(
                    'role_id' => $role_id,
                    'training_level_id' => $training_level_id
                );
            }
        }

        if (count($rows_to_add) > 0) {
            // Insert the ones newly selected
            $this->db->insert_batch(self::TABLE_NAME, $rows_to_add);
        }
    }

}
