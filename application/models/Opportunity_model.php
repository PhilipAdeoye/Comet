<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'data_classes/Opportunity.php';

/**
 * Methods that deal with the opportunities table
 *
 * @author Philip
 */
class Opportunity_model extends CI_Model {

    const TABLE_NAME = 'opportunities';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets the opportunity specified by the id
     * 
     * @param int id The opportunity's id
     * @return data_classes\Opportunity|NULL A data_classes\Opportunity object if found, otherwise NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = ?', array($id)
        );

        $array = $query->result('data_classes\Opportunity');
        if (count($array) > 0) {
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Create an opportunity - or many copies of an opportunity
     * 
     * @param data_classes\Opportunity $oppo The opportunity to create
     * @param int $how_many How many copies of the opportunity to create. Default is 1
     * @return void Returns Nothing
     */
    public function create(data_classes\Opportunity $oppo, $how_many = 1) {
        if($how_many > 0) {            
            $rows_to_add = array();
            for($i = 0; $i < $how_many; $i++) {
                $rows_to_add[] = $oppo;
            }        
            $this->db->insert_batch(self::TABLE_NAME, $rows_to_add);
        }
    }
    
    /**
     * Delete an opportunity
     * 
     * @param int|string $id The opportunity's id
     * @return void Returns Nothing
     */
    public function delete($id) {
        $this->db->query('DELETE FROM '. self::TABLE_NAME . ' WHERE id = ?', array($id));
    }
    
    /**
     * Gets all opportunities on a specified date for the specified location
     * 
     * @param string $date The date formatted as 'Y-m-d'
     * @param int|string $location_id The location id
     * @return array Each element contains the id, date, location_name, role_description, role_id, start_time, end_time,
     *      volunteer's last_name, first_name, email, interpreter, user_id, phone_number, and training_level
     */
    public function get_by_date_for_location($date, $location_id) {
        $query = $this->db->query('
            SELECT
                o.id,
                o.date,
                l.name as location_name,
                r.description as role_description,
                r.id AS role_id,
                DATE_FORMAT(o.start_time, "%H:%i") as start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                u.last_name,
                u.first_name,
                u.email,
                u.interpreter,
                o.user_id,
                u.phone_number,
                t.name as training_level,
                1 as training_level_is_qualified
            FROM opportunities o
                INNER JOIN locations l on o.location_id = l.id
                INNER JOIN roles r on o.role_id = r.id
                LEFT OUTER JOIN users u ON o.user_id = u.id
                LEFT OUTER JOIN training_levels t on u.training_level_id = t.id
            WHERE o.date = ? AND o.location_id = ?
            ORDER BY location_name, role_description',
            array(
                $date,
                $location_id
            )
        );
        
        return $query->result();
    }
    
    /**
     * Gets a roster of all opportunities on a specified date for the specified partner 
     *      as well as opportunities that the user can sign up for based on their 
     *      training_level and location
     * 
     * @param string $date The date formatted as 'Y-m-d'
     * @param int|string $partner_id The partner id
     * @param int|string $training_level_id The training level
     * @param int|string $location_id The location id
     * 
     * @return array Each element contains the id, date, location_name, role_description, role_id, start_time, end_time,
     *      volunteer's last_name, first_name, email, interpreter, user_id, phone_number, and training_level
     */
    public function get_by_date_for_partner_and_location($date, $partner_id, $training_level_id, $location_id) {
        
        // Dec. 17, 2016. The following query is imperfect. For opportunities where multiple training_levels within
        // a discipline (partner) can fill the role AND that opportunity has not yet been signed up for, when a user
        // with one of the potential training_levels views this list, those opportunities shows up twice.
        // Both instances have the same opportunity_id, but one of them has a training_level_is_qualified = 1 
        // while the other has it = 0. 
        $query = $this->db->query('
            SELECT DISTINCT
                o.id,
                o.date,
                l.name as location_name,
                r.description as role_description,
                r.id AS role_id,
                DATE_FORMAT(o.start_time, "%H:%i") as start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                u.last_name,
                u.first_name,
                u.email,
                u.interpreter,
                o.user_id,
                u.phone_number,
                t.name as training_level,
                CASE WHEN a.training_level_id = ? AND o.user_id IS NULL THEN 1 ELSE 0 END as training_level_is_qualified
            FROM opportunities o
                INNER JOIN locations l on o.location_id = l.id AND l.id = ?
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN abilities a on a.role_id = o.role_id AND a.training_level_id IN (
                    SELECT id 
                    FROM training_levels 
                    WHERE partner_id = ?
                )
                LEFT OUTER JOIN users u ON o.user_id = u.id
                LEFT OUTER JOIN training_levels t on u.training_level_id = t.id
            WHERE o.date = ?
            ORDER BY location_name, role_description, o.id, training_level_is_qualified DESC',
            array($training_level_id, $location_id, $partner_id, $date)
        );
        
        $result = $query->result();
        $count = count($result);        
        if ($count < 1) {
            return $result;            
        }
        
        // Here, we remove the instance that has the training_level_is_qualified = 0 in the duplicate set
        // by relying on the ORDER. The instance with the value = 1 comes first as per the query.
        $to_be_returned = array();
        $previous_oppo_id = '0';
        for ($i = 0; $i < $count; $i++) {
            $id = $result[$i]->id;
            if ($previous_oppo_id !== $id) {
                $to_be_returned[] = $result[$i];
            }
            $previous_oppo_id = $id;
        }
        
        return $to_be_returned;
    }
    
    /**
     * Gets users who are suitable for the opportunity
     * 
     * A suitable user is one whose training_level_id forms a tuple with the opportunity's role_id
     *      in the abilities table, is at the same location as the opportunity, and is available to serve
     * @param int|string $id The opportunity's id
     * @return array Each element contains the user's first_name, last_name, email, user_id, training_level_id,
     *      and the opportunity_id
     */
    public function get_suitable_users($id) {
        $query = $this->db->query('
            SELECT 
                u.first_name, 
                u.last_name, 
                u.email,
                u.id as user_id, 
                u.interpreter,
                t.name as training_level,
                o.id as opportunity_id
            FROM users u
                INNER JOIN training_levels t ON u.training_level_id = t.id
                INNER JOIN abilities p ON t.id = p.training_level_id
                INNER JOIN roles r ON p.role_id = r.id
                INNER JOIN opportunities o ON r.id = o.role_id
            WHERE o.id = ? 
                AND u.preferred_location_id = o.location_id 
                AND u.available_to_serve = 1
            ORDER BY u.last_name',
            array($id)
        );
        
        return $query->result();
    }
    
    /**
     * Returns if any opportunities are available at the specified date and location
     * 
     * @param string $date The date formatted as 'Y-m-d'
     * @param int|string $location_id The location id
     * @return bool TRUE if any opportunities are found, FALSE otherwise
     */
    public function oppos_exist_on_date_at_location($date, $location_id) {
        $query = $this->db->query('
            SELECT COUNT(*) AS num FROM opportunities WHERE location_id = ? AND date = ?',
            array($location_id, $date)
        );
        return (int)$query->result()[0]->num > 0;
    }
    
    /**
     * Updates a opportunity record with a user id
     * 
     * @param int|string $opportunity_id The opportunity id
     * @param int|string $user_id The user id
     * @return void Returns Nothing
     */
    public function sign_up_user($opportunity_id, $user_id) {
        $this->db->query(
            'UPDATE opportunities SET user_id = ? WHERE id = ? AND user_id IS NULL',
            array($user_id, $opportunity_id)
        );        
        
        // If the user is being added to an opportunity in the future, send them a confirmation email
        $oppo = $this->get_by_id($opportunity_id);
        if (strtotime($oppo->date . ' ' .$oppo->start_time ) > strtotime('now')) {            
            $this->load->event('mailer');
            Events::trigger('user_scheduled', $opportunity_id);
        }
    }
    
    /**
     * Gets the first opportunity that presents a time conflict for the user
     * 
     * @param int|string $opportunity_id The opportunity id
     * @param int|string $user_id The user's id
     * 
     * @return array The element contains the location_name, role_description, date, start_time, and end_time
     *      of the conflicting opportunity
     */
    public function get_time_conflicting_opportunity($opportunity_id, $user_id) {
        
        $oppo = $this->get_by_id($opportunity_id);        
        
        $query = $this->db->query('
            SELECT 
                l.name as location_name,
                r.description as role_description,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time
            FROM opportunities o
                INNER JOIN locations l on o.location_id = l.id
                INNER JOIN roles r on o.role_id = r.id
            WHERE 
                (o.user_id = ? AND o.start_time >= ? AND o.start_time <= ? AND o.date = ?)                
                OR (o.user_id = ? AND o.start_time <= ? AND o.end_time >= ? AND o.date = ?)
                OR (o.user_id = ? AND o.end_time >= ? AND o.end_time <= ? AND o.date = ?)
            LIMIT 1',
            array(
                $user_id, $oppo->start_time, $oppo->end_time, $oppo->date,
                $user_id, $oppo->start_time, $oppo->end_time, $oppo->date,
                $user_id, $oppo->start_time, $oppo->end_time, $oppo->date
            )
        );
        
        return $query->result();        
    }
    
    /**
     * Updates an opportunity record's user_id to NULL
     * 
     * @param int|string $opportunity_id The opportunity id
     * @param int|string $user_id The user id to set to NULL
     * @return void Returns Nothing
     */
    public function unschedule_user($opportunity_id, $user_id) {
        
        // We need to get the details for the confirmation email before unscheduling the user
        $this->load->model('email_model');
        $details_for_confirmation_email = $this->email_model->get_details_for_opportunity($opportunity_id);
        
        $this->db->query(
            'UPDATE opportunities SET user_id = NULL WHERE id = ? AND user_id = ?',
            array($opportunity_id, $user_id)
        );
           
        // If the user is being removed from an opportunity in the future, send them a confirmation email
        $oppo = $this->get_by_id($opportunity_id);
        if (strtotime($oppo->date . ' ' .$oppo->start_time ) > strtotime('now')) {
            $this->load->event('mailer');
            Events::trigger(
                'user_unscheduled', 
                array('details' => $details_for_confirmation_email)
            );
        }
    }
    
    /**
     * Reschedules an event by rescheduling all opportunities
     * 
     * Changes the date for all opportunities on a specified date and location
     * 
     * @param string $current_date The date to be changed formatted as 'Y-m-d'
     * @param string $new_date The new date of the event formatted as 'Y-m-d'
     * @param int|string $location_id The location id
     * @return void Returns Nothing
     */
    public function reschedule_opportunities($current_date, $new_date, $location_id) {
        $this->db->query('
            UPDATE opportunities SET date = ? WHERE date = ? AND location_id = ?',
            array(
                $new_date, 
                $current_date, 
                $location_id
            )
        );
    }
    
    /**
     * Gets all upcoming opportunities for the specified location that haven't been signed up for
     * 
     * @param int|string $id The location id
     * @return array Each element contains the opportunity id, date, start_time, end_time,
     *      role_description, role_id, and location_name
     */
    public function get_available_upcoming_for_location($id) {
        $query = $this->db->query('
            SELECT
                o.id,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                r.description AS role_description,
                r.id AS role_id,
                l.name AS location_name
            FROM opportunities o 
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
            WHERE
                o.user_id IS NULL AND o.date >= CURDATE()
                AND o.location_id = ?
            ORDER BY role_description, date',
            array($id)
        );
        
        return $query->result();
    }
    
    /**
     * Gets all upcoming opportunities for the specified role and location 
     *      that haven't been signed up for
     * 
     * @param int|string $role_id The role id
     * @param int|string $location_id The location id
     * @return array Each element contains the opportunity id, date, start_time, end_time,
     *      role_description, role_id, and location_name
     */
    public function get_available_upcoming_for_role_and_location($role_id, $location_id) {
        $query = $this->db->query('
            SELECT
                o.id,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                r.description AS role_description,
                r.id AS role_id,
                l.name AS location_name
            FROM opportunities o 
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
            WHERE
                o.user_id IS NULL AND o.date >= CURDATE()
                AND o.role_id = ? AND o.location_id = ?
            ORDER BY location_name, date',
            array(
                $role_id,
                $location_id
            )
        );
        
        return $query->result();
    }
    
    /**
     * Gets all upcoming opportunities for the specified location and training level that haven't
     *      been signed up for
     * 
     * @param int|string $training_level_id The training level id
     * @param int|string $location_id The location id     * 
     * @return array Each element contains the opportunity id, date, start_time, end_time, role_description, role_id
     *      and location_name
     */
    public function get_all_available_upcoming_for_training_level_and_location($training_level_id, $location_id) {
        $query = $this->db->query('
            SELECT
                o.id,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                r.description AS role_description,
                r.id AS role_id,
                l.name AS location_name
            FROM opportunities o 
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
                INNER JOIN abilities a on a.role_id = r.id AND a.training_level_id = ?
            WHERE o.date >= CURDATE() AND o.user_id IS NULL AND o.location_id = ?
            ORDER BY o.date, r.description',
            array(
                $training_level_id,
                $location_id
            )
        );
        
        return $query->result();
    }    
    
    /**
     * Gets the roles, role ids, and counts of all upcoming opportunities
     *      at the specified location that no one has signed up for
     * 
     * @param int|string $location_id The location id
     * @return array Each element contains role_description, num_spots, and role_id
     */
    public function get_all_available_upcoming_for_location_grouped_by_role($location_id) {
        $query = $this->db->query('
            SELECT 
                r.description AS role_description, 
                COUNT(o.role_id) AS num_spots,
                r.id as role_id
            FROM opportunities o
                INNER JOIN roles r on o.role_id = r.id
            WHERE o.date >= CURDATE() 
                AND o.user_id IS NULL
                AND o.location_id = ?
            GROUP BY r.description
            ORDER BY r.description',
            array($location_id)
        );
        
        return $query->result();
    }
    
    /**
     * Gets the dates of all opportunities at the specified location
     * 
     * @param int|string $location_id The location id
     * @return array An array dates formatted as MM/DD/YYYY
     */
    public function get_all_dates_for_location($location_id) {
        $query = $this->db->query('
            SELECT DISTINCT 
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date
            FROM opportunities o
            WHERE o.location_id = ?
            ORDER BY o.date DESC',
            array($location_id)
        );
        
        return $query->result();
    }
    
    /**
     * Gets the dates of upcoming opportunities at a specified location 
     * that a user with the specified training level can volunteer on
     * 
     * @param int|string $training_level_id The training level
     * @param int|string $location_id The location id
     * @return array An array dates formatted as MM/DD/YYYY and corresponding month names and year
     */
    public function get_upcoming_dates_for_training_level_and_location($training_level_id, $location_id) {
        $query = $this->db->query('
            SELECT DISTINCT 
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date
            FROM opportunities o  
                INNER JOIN abilities a ON a.role_id = o.role_id AND a.training_level_id = ?
            WHERE o.date >= CURDATE() AND o.location_id = ?
            ORDER BY o.date DESC',
            array(
                $training_level_id,
                $location_id
            )
        );
        
        return $query->result();
    }
    
    /**
     * Gets upcoming opportunities that a user has signed up for
     * 
     * @param int|string $user_id The user's id
     * @return array An array where each element contains the id, date, start_time, and end_time of the opportunity,
     *      whether the user's commitment can_be_cancelled, the role_description, role_id, and location_name
     */
    public function get_upcoming_opportunities_by_user_id($user_id) {
        $days_before_opportunity_can_be_cancelled = $this->config->item('days_before_opportunity_can_be_cancelled');
        $query = $this->db->query('
            SELECT
                o.id,
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                DATE_FORMAT(o.start_time, "%H:%i") AS start_time,
                DATE_FORMAT(o.end_time, "%H:%i") AS end_time,
                CASE WHEN o.date > DATE_ADD(CURDATE(), INTERVAL ? DAY) THEN 1 ELSE 0 END AS can_be_cancelled,
                r.description AS role_description,
                r.id AS role_id,
                l.name AS location_name
            FROM opportunities o 
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
            WHERE
                o.user_id = ? AND o.date >= CURDATE()',
            array(
                $days_before_opportunity_can_be_cancelled, 
                $user_id
            )
        );
        
        return $query->result();
    }
    
    /**
     * Gets a user's volunteer record
     * 
     * @param int|string $user_id The user's id
     * @return array An array of objects containing the date, role, and location
     */
    public function get_volunteer_record($user_id) {
        $query = $this->db->query('
            SELECT 
                DATE_FORMAT(o.date, "%m/%d/%Y") AS date,
                r.description AS role,
                l.name AS location
            FROM opportunities o 
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN locations l on o.location_id = l.id
            WHERE o.user_id = ?
            ORDER BY o.date DESC',
            array($user_id)
        );
        
        return $query->result();
    }

}
