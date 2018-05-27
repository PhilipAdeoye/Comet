-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 27, 2017 at 12:58 AM
-- Server version: 5.6.35
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `comet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `abilities`
--

CREATE TABLE `abilities` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `training_level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='the training levels that can perform each role';

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `opportunity_id` int(11) NOT NULL COMMENT 'The id of the opportunity the attendance record is capturing',
  `status` tinyint(1) NOT NULL COMMENT '1 or 0. 1 if the user was present, 0 if they were absent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Attendance records for signed-up opportunities';

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL COMMENT 'a name for the action that occured',
  `description` text,
  `subject` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `placeholders` text COMMENT 'a comma-delimited list of the placeholders e.g. @{firstname} that get replaced with actual values when the emails get sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Preset emails that get sent out when certain actions occur';

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'human-understandable name (as in "Barnes UMC")',
  `address` varchar(255) DEFAULT NULL COMMENT 'physical address to help one navigate to this location (as in "900 West 30th Street, 46208)',
  `email_text` text COMMENT 'text that will be emailed to users committed to opportunities at this location',
  `uses_media_release_form` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'whether or not the location uses a media release form',
  `media_release_form` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='physical locations where opportunities occur';

-- --------------------------------------------------------

--
-- Table structure for table `message_board`
--

CREATE TABLE `message_board` (
  `id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(300) DEFAULT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `role_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL COMMENT 'logically, these are individual roles to be filled by a single user',
  `description` text NOT NULL,
  `help_text` text NOT NULL COMMENT 'provides a short description of the role',
  `email_text` mediumtext COMMENT 'this is the text sent to users who sign up to fulfill this role'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training_levels`
--

CREATE TABLE `training_levels` (
  `id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'describes the training level as in "MS1" or "Licensed Physician"',
  `partner_id` smallint(6) NOT NULL,
  `email_text` mediumtext COMMENT 'this is the text sent to users who sign up for opportunities and are of this training_level'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table holding all the possible training levels';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `email` text NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL,
  `last_name` text NOT NULL,
  `first_name` text NOT NULL,
  `training_level_id` smallint(1) NOT NULL,
  `partner_id` tinyint(4) DEFAULT NULL,
  `preferred_location_id` tinyint(4) NOT NULL,
  `estimated_graduation_year` smallint(4) DEFAULT NULL,
  `phone_number` text NOT NULL,
  `pager_number` text NOT NULL,
  `interpreter` tinyint(1) NOT NULL,
  `available_to_serve` tinyint(1) NOT NULL,
  `password_expires_on` datetime NOT NULL DEFAULT '2035-12-25 02:12:45' COMMENT 'a date and time at which the user''s password should expire',
  `temporary_auth_token` varchar(256) DEFAULT NULL COMMENT 'a value that temporarily allows the user to change their password if they forgot it',
  `temporary_auth_token_expires_on` datetime DEFAULT NULL COMMENT 'when the temporary_auth_token expires',
  `has_confirmed_existing_data` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'when major changes are made and we want to make sure that users confirm their existing data before using the application. When a new user registers we automatically set this to true',
  `has_accepted_media_release_form` tinyint(1) DEFAULT NULL COMMENT '1 if the user has accepted the media release form, 0 if they rejected it, and NULL otherwise',
  `accepted_media_release_form_on` date DEFAULT NULL COMMENT 'date on which the user accepted the media release form',
  `birth_month` text,
  `birth_year` text,
  `ethnicity` text,
  `gender` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`opportunity_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `message_board`
--
ALTER TABLE `message_board`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique descriptions` (`description`(256));

--
-- Indexes for table `training_levels`
--
ALTER TABLE `training_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique names` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abilities`
--
ALTER TABLE `abilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message_board`
--
ALTER TABLE `message_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'logically, these are individual roles to be filled by a single user';
--
-- AUTO_INCREMENT for table `training_levels`
--
ALTER TABLE `training_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;