-- phpMyAdmin SQL Dump
-- version 4.0.10.19
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2017 at 11:35 PM
-- Server version: 5.1.73
-- PHP Version: 5.4.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vcp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL COMMENT 'a name for the action that occured',
  `description` text,
  `subject` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `placeholders` text COMMENT 'a comma-delimited list of the placeholders e.g. @{firstname} that get replaced with actual values when the emails get sent',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Preset emails that get sent out when certain actions occur' AUTO_INCREMENT=7 ;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `name`, `description`, `subject`, `message`, `placeholders`) VALUES
(1, 'next_day_reminder', 'Sent to remind a user about an opportunity that they are signed up for happening the following day', 'Reminder: Volunteering Tomorrow', 'Hi @{first_name},\n\nJust a friendly reminder that you signed up to volunteer as a/an @{role_description} at @{location_name} tomorrow. \n\nThe address is @{address}. We would love you to show up at or before @{start_time}, and we should be done by @{end_time}.\n\nSee you tomorrow! And thanks for being a part of our volunteer community.', '@{first_name},@{role_description},@{location_name},@{address},@{start_time},@{end_time}'),
(2, 'new_user_registered', 'Welcome message sent to a new user right after they register on the site', 'Welcome to Our Volunteer Community', 'Hi @{first_name}!\n\nThank you for registering to volunteer at our clinic. Please Sign Up for a volunteer opportunity at your earliest convenience.\n\nWe''re stoked to have you!', '@{first_name}'),
(3, 'user_details_changed', 'Sent to a user when their account details are updated', 'Important information about your account', 'Hi @{first_name},\n\nYour account details were recently changed. If you didn''t make this change yourself or authorize it, please contact us.\n\nThank you.', '@{first_name}'),
(4, 'user_password_changed', 'Sent to a user when their password is changed', 'Your password has been changed', 'Hi @{first_name},\n\nYour password has been changed recently.\nIf you didn''t make this change or authorize it, please let us know so that we can investigate the issue.\n\nThank you.', '@{first_name}'),
(5, 'user_is_scheduled', 'Confirmation email sent to the user who signs up for (or is assigned) an upcoming volunteer opportunity', 'Volunteer Scheduling Confirmation', 'Hello @{first_name}, \n\nThis is a confirmation email for your volunteer experience at the clinic.\nDate: @{date} from @{start_time} to @{end_time}\nRole: @{role_description}\nLocation: @{location_name}\nAddress: @{address}\n\n@{role_email_text}\n\n@{training_level_email_text}\n\n@{location_email_text}\n\nThank you for being an important part of the Clinic Mission!', '@{first_name},@{date},@{start_time},@{end_time},@{role_description},@{location_name},@{address},@{role_email_text},@{training_level_email_text},@{location_email_text}'),
(6, 'user_is_no_longer_scheduled', 'Confirmation email sent to the user who cancels an upcoming opportunity (or has their opportunity cancelled)', 'Volunteer Cancellation Notice', 'Hi @{first_name},\n\nThis is confirmation that you are no longer scheduled to serve as a/an @{role_description} on @{date} at @{location_name}.\n\nNote: if this cancellation is less than a week prior to your scheduled date, please find a replacement volunteer and let your clinic managers know as soon as possible.\n\nThank you.', '@{first_name},@{date},@{role_description},@{location_name}');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
