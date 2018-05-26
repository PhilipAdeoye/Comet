<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'data_classes/User.php';

/**
 * Methods that deal with the users table 
 *
 * @author Philip
 */
class User_model extends CI_Model {
    
    const TABLE_NAME = 'users';
    
    public function __construct() {
        parent::__construct();
                
        $this->load->event('mailer');
    }
    
    /**
     * Searches the database for the first user with the specified email address and returns a
     *   data_classes\User object if found or NULL otherwise
     * 
     * @param string $email_address The email address to get the user by
     * @return data_classes\User|NULL
     */
    public function get_by_email($email_address) {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME.' WHERE email = ? LIMIT 1',
            array($email_address)
        );
        
        $array = $query->result('data_classes\User');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Searches the database for the user with the specified id and returns a
     *   data_classes\User object if found or NULL otherwise
     * 
     * @param int id The user's id
     * @return data_classes\User|NULL
     */
    public function get_by_id($id) {
        $query = $this->db->query(
            'SELECT * FROM '.self::TABLE_NAME.' WHERE id = ?',
            array($id)
        );        
        
        $array = $query->result('data_classes\User');
        if(count($array) > 0){
            return $array[0];
        }
        return NULL;
    }
    
    /**
     * Gets all the users at the specified location from the user table and the 
     *      number of times each user has volunteered
     * 
     * @param int|string $location_id The location id to filter the users
     * @return array 
     */
    public function get_users($location_id) {
        $query = $this->db->query('
            SELECT
                u.id,
                CONCAT(CONCAT(u.first_name, " "), u.last_name) AS name,
                u.admin,
                u.email,
                p.name AS partner_name,
                t.name AS training_level,
                u.estimated_graduation_year,
                CASE u.available_to_serve WHEN 1 THEN "Yes" ELSE "No" END AS available_to_serve,
                CASE u.interpreter WHEN 1 THEN "Yes" ELSE "No" END AS is_an_interpreter,
                COUNT(o.id) AS times_volunteered

            FROM users u 
                INNER JOIN training_levels t ON u.training_level_id = t.id
                INNER JOIN partners p on u.partner_id = p.id
                LEFT OUTER JOIN opportunities o ON u.id = o.user_id
            WHERE u.preferred_location_id = ?
            GROUP BY u.email, t.name
            ORDER BY u.last_name',
            array(
                $location_id
            )
        );
        return $query->result();
    }
    
    /**
     * Gets all users from the users table who are admins and the number of times each has volunteered
     * 
     * @return array 
     */
    public function get_admins($location_id) {
        $query = $this->db->query('
            SELECT
                u.id,
                CONCAT(CONCAT(u.first_name, " "), u.last_name) AS name,
                u.email,
                p.name AS partner_name,
                t.name AS training_level,
                u.estimated_graduation_year,
                CASE u.available_to_serve WHEN 1 THEN "Yes" ELSE "No" END AS available_to_serve,
                CASE u.interpreter WHEN 1 THEN "Yes" ELSE "No" END AS is_an_interpreter,
                COUNT(o.id) AS times_volunteered

            FROM users u 
                INNER JOIN training_levels t ON u.training_level_id = t.id
                INNER JOIN partners p ON u.partner_id = p.id
                LEFT OUTER JOIN opportunities o ON u.id = o.user_id
            WHERE u.admin = 1 AND u.preferred_location_id = ?
            GROUP BY u.email, t.name
            ORDER BY u.last_name',
            array(
                $location_id
            )
        );
        return $query->result();
    }
    
    /**
     * Gets all available to serve users' names and their training levels
     * 
     * @return array Each element contains the (user_id) and (last_name, first_name - training_level)
     */
    public function get_all_users_for_opportunity_list() {
        $query = $this->db->query('
            SELECT 
                u.id,
                CONCAT(CONCAT(CONCAT(CONCAT(u.last_name, ", "), u.first_name), " - "), t.name) AS text
            FROM users u
                INNER JOIN training_levels t on u.training_level_id = t.id
            WHERE u.available_to_serve = 1
            ORDER BY u.last_name'
        );
        return $query->result();
    }
    
    public function get_gender_list() {
        return array('Female', 'Male');
    }
    
    public function get_ethnicity_assoc_array () { 
        return array(
            'American Indian or Alaskan Native' => 'For example, Navajo Nation, Blackfeet Tribe, Mayan, Aztec, Native Village of Barrow Inupiat Traditional Government, Nome Eskimo Community, etc',
            'Asian' => 'For example, Chinese, Filipino, Asian Indian, Vietnamese, Korean, Japanese, etc',
            'Black or African American' => 'For example, African American, Jamaican, Haitian, Nigerian, Ethiopian, Somalian, etc',
            'Hispanic, Latino, or Spanish Origin' => 'For example, Mexican or Mexican American, Puerto Rican, Cuban, Salvadoran, Dominican, Colombian, etc',
            'Middle Eastern or North African' => 'For example, Lebanese, Iranian, Egyptian, Syrian, Moroccan, Algerian, etc',
            'Native Hawaiian or Other Pacific Islander' => 'For example, Native Hawaiian, Samoan, Chamorro, Tongan, Fijan, Marshalese, etc',
            'White' => 'For example, German, Irish, English, Italian, Polish, French, etc'
        );
    }
    
    public function get_months_of_the_year() {
        return array(
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', 
            '4' => 'Apr', '5' => 'May', '6' => 'Jun', 
            '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', 
            '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
    }
    
    /**
     * Inserts a user in the Users table
     * 
     * @param data_classes\User $user An object with the user details
     * @return void Returns nothing
     */
    public function create(data_classes\User $user) {
        $this->db->query(
            'INSERT INTO '.self::TABLE_NAME.' (created_on, modified_on, email, password, admin, last_name, first_name,
                    training_level_id, partner_id, preferred_location_id, estimated_graduation_year, 
                    phone_number, pager_number, interpreter, available_to_serve, birth_month, birth_year, ethnicity,
                    gender, has_confirmed_existing_data)
                VALUES (NOW(), NOW(), ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1);',
            array(
                $user->email,
                password_hash($user->password, PASSWORD_DEFAULT),
                $user->last_name,
                $user->first_name,
                $user->training_level_id,
                $user->partner_id,
                $user->preferred_location_id,
                $user->estimated_graduation_year,
                $user->phone_number,
                $user->pager_number,
                $user->interpreter,
                $user->available_to_serve,
                $user->birth_month,
                $user->birth_year,
                $user->ethnicity,
                $user->gender,
            )
        );
            
        Events::trigger('user_created', array('email' => $user->email, 'first_name'=> $user->first_name));         
    }
    
    /**
     * Updates a record in the Users table
     * 
     * @param data_classes\User $user An object with the user details
     * @return void Returns nothing
     */
    public function update(data_classes\User $user) {
        
        if (isset($user->password) && $user->password !== '') {
            $this->db->query('
                UPDATE '.self::TABLE_NAME.' 
                    SET modified_on = NOW(), email = ?, password = ?, admin = ?, last_name = ?, first_name = ?,
                        training_level_id = ?, partner_id = ?, preferred_location_id = ?,
                        estimated_graduation_year = ?, phone_number = ?, pager_number = ?, interpreter = ?, 
                        available_to_serve = ?, birth_month = ?, birth_year = ?, ethnicity = ?, gender = ?, 
                        has_confirmed_existing_data = 1
                    WHERE id = ?',
                array(
                    $user->email, 
                    password_hash($user->password, PASSWORD_DEFAULT), 
                    $user->admin, 
                    $user->last_name,
                    $user->first_name,
                    $user->training_level_id,
                    $user->partner_id,
                    $user->preferred_location_id,
                    $user->estimated_graduation_year,
                    $user->phone_number,
                    $user->pager_number,
                    $user->interpreter,
                    $user->available_to_serve,
                    $user->birth_month,
                    $user->birth_year,
                    $user->ethnicity,
                    $user->gender,
                    $user->id
                )    
            );
        }
        else{
            $this->db->query('
                UPDATE '.self::TABLE_NAME.' 
                    SET modified_on = NOW(), email = ?, admin = ?, last_name = ?, first_name = ?,
                        training_level_id = ?, partner_id = ?, preferred_location_id = ?,
                        estimated_graduation_year = ?, phone_number = ?, pager_number = ?, interpreter = ?, 
                        available_to_serve = ?, birth_month = ?, birth_year = ?, ethnicity = ?, gender = ?, 
                        has_confirmed_existing_data = 1
                    WHERE id = ?',
                array(
                    $user->email,                     
                    $user->admin, 
                    $user->last_name,
                    $user->first_name,
                    $user->training_level_id,
                    $user->partner_id,
                    $user->preferred_location_id,
                    $user->estimated_graduation_year,
                    $user->phone_number,
                    $user->pager_number,
                    $user->interpreter,
                    $user->available_to_serve,
                    $user->birth_month,
                    $user->birth_year,
                    $user->ethnicity,
                    $user->gender,
                    $user->id
                )    
            );
        }
            
        Events::trigger('user_details_changed', array('email' => $user->email, 'first_name'=> $user->first_name));        
    }
    
    /**
     * Updates a user's password
     * 
     * @param string $password The new password
     * @param string $email The user's email
     * @return void Returns Nothing
     */
    public function update_password($password, $email) {
        $this->db->query('
            UPDATE users 
            SET password = ?, password_expires_on = "2035-12-25 09:52:26"
            WHERE email = ?',
            array(
                password_hash($password, PASSWORD_DEFAULT),
                $email
            )
        );
        
        Events::trigger('user_password_changed', $email);
    }
    
    /**
     * Updates a user's media release consent
     * 
     * @param bool $status A boolean describing whether or not the user consents
     * @param int|string $id The user's id
     * @return void Returns Nothing
     */
    public function update_media_release_status($status, $id) {
        $this->db->query('
            UPDATE users 
            SET has_accepted_media_release_form = ?, accepted_media_release_form_on = NOW()
            WHERE id = ?',
            array(
                $status,
                $id
            )
        );
    }
    
    /**
     * Creates a temporary auth token for a user
     * 
     * Also triggers an event that sends the user a link in an email to log in
     * 
     * @param string $email The user's email
     * @return void Returns Nothing
     */
    public function initiate_temporary_auth_for($email) {
        $temporary_auth_token = uniqid('', TRUE);
        $this->db->query('
            UPDATE users
            SET temporary_auth_token = ?,
                temporary_auth_token_expires_on = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 10 MINUTE)
            WHERE email = ?',
            array(
                $temporary_auth_token,
                $email
            )
        );
        
        Events::trigger(
            'temporary_auth_initiated', 
            array(
                'temporary_auth_token' => $temporary_auth_token,
                'email' => $email
            ),
            'string'
        );        
    }
    
    /**
     * Expires a user's password
     * 
     * Sets the password_expires_on field to the current time
     * 
     * @param string $email The user's email
     * @return void Returns Nothing
     */
    public function expire_password_for($email) {
        $this->db->query('
            UPDATE users 
            SET password_expires_on = CURRENT_TIMESTAMP 
            WHERE email = ?',
            array($email)
        );
    }
    
    /**
     * Discards a user's temporary auth token and its expiry time
     * 
     * @param string $email The user's email
     * @return void Returns Nothing
     */
    public function delete_temporary_auth_token_for($email) {
        $this->db->query('
            UPDATE users 
            SET temporary_auth_token = NULL, temporary_auth_token_expires_on = NULL
            WHERE email = ?',
            array($email)
        );
    }
    
    /**
     * Sets the has_confirmed_existing_data and available_to_serve flags to 
     *      0 for all users, which requires everyone to confirm that they are 
     *      still using the site. Useful for semester to semester transitions
     * 
     * @return void Returns Nothing
     */
    public function reset_availability_for_all_users() {
        $this->db->query('
            UPDATE users
            SET has_confirmed_existing_data = 0, available_to_serve = 0'
        );
    }
}