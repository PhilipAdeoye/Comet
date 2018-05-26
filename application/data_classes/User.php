<?php

namespace data_classes;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of User
 *
 * @author Philip
 */
class User {

    public $id;
    public $email;
    public $password;
    public $last_name;
    public $first_name;
    public $admin;
    public $training_level_id;
    public $partner_id;
    public $preferred_location_id;
    public $estimated_graduation_year;
    public $phone_number;
    public $pager_number;
    public $interpreter;
    public $available_to_serve;
    public $created_on;
    public $modified_on;
    public $password_expires_on;
    public $temporary_auth_token;
    public $temporary_auth_token_expires_on;
    public $has_confirmed_existing_data;
    public $has_accepted_media_release_form;
    public $accepted_media_release_form_on;
    public $birth_month;
    public $birth_year;
    public $ethnicity;
    public $gender;
}
