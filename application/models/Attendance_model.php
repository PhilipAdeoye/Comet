<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'data_classes/Attendance.php';

/**
 * Methods that deal with the attendance table
 *
 * @author Philip
 */
class Attendance_model extends CI_Model{
    
    const TABLE_NAME = 'attendance';
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the attendance data for the specified location, partner, and date
     * 
     * @param int|string $location_id The location id
     * @param int|string $partner_id The partner id. Use 0 to select all partners
     * @param string $date The date formatted as Y-m-d
     * 
     * @return array Each element contains the opportunity_id and role_description. If someone signed up for the
     *      opportunity, each element also contains the last_name, first_name, partner_name, and status (1 = present, 
     *      0 = absent, NULL = unmarked). If someone signed up, then can_take_attendance = 1, 0 otherwise.
     */
    public function get_records_for_location_partner_and_date($location_id, $partner_id, $date) {
        
        $query = $this->db->query('
            SELECT DISTINCT            
                o.id AS opportunity_id,
                r.description as role_description,
                CASE WHEN p.name IS NOT NULL THEN p.name ELSE "Unfilled" END as partner_name,
                u.last_name,
                u.first_name,
                att.status,
                CASE WHEN o.user_id IS NOT NULL THEN 1 ELSE 0 END AS can_take_attendance
            FROM opportunities o
                INNER JOIN locations l on o.location_id = l.id AND l.id = ?
                INNER JOIN roles r on o.role_id = r.id
                INNER JOIN abilities a on a.role_id = o.role_id AND a.training_level_id IN (
                    SELECT id 
                    FROM training_levels 
                    WHERE (partner_id = ? OR 0 = ?)
                )
                LEFT OUTER JOIN users u ON o.user_id = u.id
                LEFT OUTER JOIN training_levels t ON u.training_level_id = t.id
                LEFT OUTER JOIN attendance att ON o.id = att.opportunity_id
                LEFT OUTER JOIN partners p ON u.partner_id = p.id
            WHERE ((u.partner_id = ? OR 0 = ?) OR o.user_id IS NULL) 
                AND o.date = ?
            ORDER BY partner_name, can_take_attendance DESC, last_name, first_name',
            array(
                $location_id,
                $partner_id,
                $partner_id,
                $partner_id,
                $partner_id,
                $date
            )
        );
        
        return $query->result();        
    }
    
    /**
     * Records attendance to the attendance table
     * 
     * @param array $unmarked An array of integers representing records that are neither present nor absent
     * @param array $marked An array of data\classes objects representing those marked either present or absent     
     * 
     * @return void Returns Nothing
     */
    public function capture_records($unmarked, $marked) {
        $this->db->trans_start();
        
        // DELETE any existing records for those that are unmarked
        if (count($unmarked) > 0) {
            $this->db->where_in('opportunity_id', $unmarked);
            $this->db->delete(self::TABLE_NAME);            
        }
        
        // INSERT new attendance records or UPDATE existing ones
        foreach ($marked as $attendance_record) {
            $this->db->query('
                INSERT INTO '. self::TABLE_NAME. ' (opportunity_id, status) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)',
                array(
                    $attendance_record->opportunity_id,
                    $attendance_record->status
                )
            );
        }            
        
        $this->db->trans_complete();
    }
    
    /**
     * Gets a listing of how many volunteers were at clinic on each day within the specified date range
     * 
     * @param int|string $location_id The location id
     * @param int|string $partner_id The partner id. The select all partners, use 0.
     * @param string $start_date The first day in the date range formatted as 'Y-m-d'
     * @param string $end_date The last day in the date range formatted as 'Y-m-d'
     * @param bool|int $use_attendance_data A boolean indicating if attendance data should be used or merely the 
     *      sign up data. 
     * 
     * @return array Each element contains the oppo_date, partner, and the volunteer_count
     */
    public function get_volunteer_counts_for_clinic_days($location_id, $partner_id, $start_date, $end_date, $use_attendance_data) {
          
        if ($use_attendance_data) {
            $query = $this->db->query('
                SELECT
                    DATE_FORMAT(o.date, "%m/%d/%Y") as oppo_date,
                    p.name AS partner,
                    COUNT(o.id) AS volunteer_count 
                FROM opportunities o 
                    INNER JOIN users u ON o.user_id = u.id
                    INNER JOIN partners p ON u.partner_id = p.id
                    INNER JOIN attendance a ON o.id = a.opportunity_id
                WHERE o.date BETWEEN ? AND ?
                    AND o.location_id = ?
                    AND (u.partner_id = ? OR 0 = ?)
                    AND a.status = 1
                GROUP BY oppo_date, partner
                ORDER BY partner, oppo_date',
                array(
                    $start_date,
                    $end_date,
                    $location_id,
                    $partner_id,
                    $partner_id
                )
            );
        }
        
        else {
            $query = $this->db->query('
                SELECT
                    DATE_FORMAT(o.date, "%m/%d/%Y") as oppo_date,
                    p.name AS partner,
                    COUNT(o.id) AS volunteer_count    
                FROM opportunities o 
                    INNER JOIN users u ON o.user_id = u.id
                    INNER JOIN partners p ON u.partner_id = p.id
                WHERE o.date BETWEEN ? AND ?
                    AND o.location_id = ?
                    AND (u.partner_id = ? OR 0 = ?)
                GROUP BY oppo_date, partner
                ORDER BY partner, oppo_date',
                array(
                    $start_date,
                    $end_date,
                    $location_id,
                    $partner_id,
                    $partner_id
                )
            );
        }
        
        return $query->result();        
    }
    
    /**
     * Gets a breakdown of how each volunteers contribution by times and hours volunteered within the specified date range
     * 
     * @param int|string $location_id The location id
     * @param int|string $partner_id The partner id. The select all partners, use 0.
     * @param string $start_date The first day in the date range formatted as 'Y-m-d'
     * @param string $end_date The last day in the date range formatted as 'Y-m-d'
     * @param bool|int $use_attendance_data A boolean indicating if attendance data should be used or merely the 
     *      sign up data.
     * 
     * @return array Each element contains the full_name, id, partner, times_volunteered, and hours_volunteered
     */
    public function get_volunteer_hours_summary($location_id, $partner_id, $start_date, $end_date, $use_attendance_data) {              
        
        if ($use_attendance_data) {
            $query = $this->db->query('
                SELECT	
                    CONCAT(CONCAT(u.first_name, " "), u.last_name) AS full_name,
                    u.id,
                    p.name AS partner,
                    COUNT(o.id) AS times_volunteered,
                    SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS hours_volunteered    

                FROM opportunities o 
                    INNER JOIN users u ON o.user_id = u.id        
                    INNER JOIN partners p ON u.partner_id = p.id
                    INNER JOIN attendance a on o.id = a.opportunity_id
                WHERE o.date BETWEEN ? AND ?
                    AND o.location_id = ?    
                    AND (u.partner_id = ? OR 0 = ?)
                    AND a.status = 1
                GROUP BY u.id, partner
                ORDER BY partner, full_name',
                array(
                    $start_date,
                    $end_date,
                    $location_id,
                    $partner_id,
                    $partner_id
                )
            );
        }
        
        else {
            $query = $this->db->query('
                SELECT
                    CONCAT(CONCAT(u.first_name, " "), u.last_name) AS full_name,
                    u.id,
                    p.name AS partner,
                    COUNT(o.id) AS times_volunteered,
                    SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600) AS hours_volunteered
                FROM opportunities o 
                    INNER JOIN users u ON o.user_id = u.id    
                    INNER JOIN partners p ON u.partner_id = p.id
                WHERE o.date BETWEEN ? AND ?
                    AND o.location_id = ?
                    AND (u.partner_id = ? OR 0 = ?)
                GROUP BY u.id, partner
                ORDER BY partner, full_name',
                array(
                    $start_date,
                    $end_date,
                    $location_id,
                    $partner_id,
                    $partner_id
                )
            );
        }
        
        return $query->result();
    }
    
    
}
