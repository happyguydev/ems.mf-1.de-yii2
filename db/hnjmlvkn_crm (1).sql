-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2021 at 06:48 PM
-- Server version: 10.3.24-MariaDB-cll-lve
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hnjmlvkn_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activity_log`
--

CREATE TABLE `tbl_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `action` varchar(100) DEFAULT NULL,
  `model_id` int(11) DEFAULT 0,
  `model` varchar(50) DEFAULT NULL,
  `data` longtext DEFAULT NULL,
  `date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_activity_log`
--

INSERT INTO `tbl_activity_log` (`id`, `user_id`, `action`, `model_id`, `model`, `data`, `date_time`) VALUES
(1, 1, 'created', 2, 'User', '{\"to_user\":0,\"title\":\"Naum Gu\\u00dfinsky\",\"type\":null}', '2021-04-06 15:16:29'),
(2, 2, 'created', 1, 'Customer', '{\"to_user\":0,\"title\":\"Fatmir Binaku\",\"type\":null}', '2021-04-06 15:22:38'),
(3, 2, 'added', 0, 'Media', '{\"to_user\":2,\"title\":\"German \",\"type\":\"file\"}', '2021-04-06 15:28:00'),
(4, 2, 'added', 0, 'Media', '{\"to_user\":2,\"title\":\"Sch\\u00e4den April 2021\",\"type\":\"folder\"}', '2021-04-06 15:28:45'),
(5, 2, 'shared', 0, 'Media', '{\"to_user\":\"1\",\"title\":\"Sch\\u00e4den April 2021\",\"type\":\"folder\"}', '2021-04-06 15:29:16'),
(6, 2, 'created', 1, 'Task', '{\"to_user\":2,\"title\":\"Bitte Email an Colin schreiben.....\",\"type\":null}', '2021-04-06 15:32:26'),
(7, 2, 'added comment', 1, 'Task', '{\"to_user\":2,\"title\":\"Bitte Email an Colin schreiben.....\",\"type\":null}', '2021-04-06 15:32:39'),
(8, 2, 'updated', 1, 'Task', '{\"to_user\":2,\"title\":\"Bitte Email an Colin schreiben.....\",\"type\":null}', '2021-04-06 15:32:48'),
(9, 2, 'created', 1, 'Leave', '{\"to_user\":0,\"title\":null,\"type\":null}', '2021-04-06 15:37:42'),
(10, 1, 'created', 1, 'Appointment', '{\"to_user\":0,\"title\":\"test\",\"type\":null}', '2021-04-06 17:55:25'),
(11, 1, 'updated', 1, 'Appointment', '{\"to_user\":0,\"title\":\"test\",\"type\":null}', '2021-04-06 17:56:10'),
(12, 1, 'moved', 0, 'Media', '{\"to_user\":0,\"title\":\"German \",\"type\":\"image\"}', '2021-04-07 14:43:02'),
(13, 1, 'moved', 0, 'Media', '{\"to_user\":0,\"title\":\"German \",\"type\":\"image\"}', '2021-04-07 14:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointment`
--

CREATE TABLE `tbl_appointment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `group_id` int(11) DEFAULT 0,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `bg_color` varchar(30) DEFAULT NULL,
  `text_color` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_appointment`
--

INSERT INTO `tbl_appointment` (`id`, `title`, `group_id`, `start_date_time`, `end_date_time`, `bg_color`, `text_color`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'test', 1, '2021-04-06 14:30:19', '2021-04-06 14:55:54', '#00ff6e', '#ffffff', '2021-04-06 12:25:25', 1, '2021-04-06 17:56:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointment_assignee`
--

CREATE TABLE `tbl_appointment_assignee` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_appointment_assignee`
--

INSERT INTO `tbl_appointment_assignee` (`id`, `appointment_id`, `user_id`) VALUES
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attachment`
--

CREATE TABLE `tbl_attachment` (
  `id` int(11) NOT NULL,
  `relation_id` varchar(30) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `type` enum('task','project','leave') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `date` date DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_in_ip` varchar(150) DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `check_out_ip` varchar(150) DEFAULT NULL,
  `check_in_location` varchar(255) DEFAULT NULL,
  `check_out_location` varchar(255) DEFAULT NULL,
  `work_duration` varchar(100) DEFAULT NULL,
  `start_date_time` int(11) DEFAULT NULL,
  `stop_date_time` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auth_assignment`
--

CREATE TABLE `tbl_auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_auth_assignment`
--

INSERT INTO `tbl_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '1', NULL),
('user', '2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auth_item`
--

CREATE TABLE `tbl_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 DEFAULT NULL,
  `rule_name` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `data` text CHARACTER SET utf8 DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_auth_item`
--

INSERT INTO `tbl_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, NULL, NULL, NULL, NULL, 2021),
('create_activity_log', 2, NULL, NULL, NULL, NULL, NULL),
('create_appointment', 2, NULL, NULL, NULL, NULL, NULL),
('create_attendance', 2, NULL, NULL, NULL, NULL, NULL),
('create_chat', 2, NULL, NULL, NULL, NULL, NULL),
('create_customer', 2, NULL, NULL, NULL, NULL, NULL),
('create_employee_leave', 2, NULL, NULL, NULL, NULL, NULL),
('create_leave', 2, NULL, NULL, NULL, NULL, NULL),
('create_mail_box', 2, NULL, NULL, NULL, NULL, NULL),
('create_media', 2, NULL, NULL, NULL, NULL, NULL),
('create_project', 2, NULL, NULL, NULL, NULL, NULL),
('create_reports', 2, NULL, NULL, NULL, NULL, NULL),
('create_task', 2, NULL, NULL, NULL, NULL, NULL),
('delete_activity_log', 2, NULL, NULL, NULL, NULL, NULL),
('delete_appointment', 2, NULL, NULL, NULL, NULL, NULL),
('delete_attendance', 2, NULL, NULL, NULL, NULL, NULL),
('delete_chat', 2, NULL, NULL, NULL, NULL, NULL),
('delete_customer', 2, NULL, NULL, NULL, NULL, NULL),
('delete_employee_leave', 2, NULL, NULL, NULL, NULL, NULL),
('delete_leave', 2, NULL, NULL, NULL, NULL, NULL),
('delete_mail_box', 2, NULL, NULL, NULL, NULL, NULL),
('delete_media', 2, NULL, NULL, NULL, NULL, NULL),
('delete_project', 2, NULL, NULL, NULL, NULL, NULL),
('delete_reports', 2, NULL, NULL, NULL, NULL, NULL),
('delete_task', 2, NULL, NULL, NULL, NULL, NULL),
('export_attendance_report', 2, NULL, NULL, NULL, NULL, NULL),
('export_customer_report', 2, NULL, NULL, NULL, NULL, NULL),
('export_leave_report', 2, NULL, NULL, NULL, NULL, NULL),
('export_project_report', 2, NULL, NULL, NULL, NULL, NULL),
('export_task_report', 2, NULL, NULL, NULL, NULL, NULL),
('Praktikanten', 1, NULL, NULL, NULL, 2021, 2021),
('Testing', 1, NULL, NULL, NULL, 2021, 2021),
('update_activity_log', 2, NULL, NULL, NULL, NULL, NULL),
('update_appointment', 2, NULL, NULL, NULL, NULL, NULL),
('update_attendance', 2, NULL, NULL, NULL, NULL, NULL),
('update_chat', 2, NULL, NULL, NULL, NULL, NULL),
('update_customer', 2, NULL, NULL, NULL, NULL, NULL),
('update_employee_leave', 2, NULL, NULL, NULL, NULL, NULL),
('update_leave', 2, NULL, NULL, NULL, NULL, NULL),
('update_mail_box', 2, NULL, NULL, NULL, NULL, NULL),
('update_media', 2, NULL, NULL, NULL, NULL, NULL),
('update_project', 2, NULL, NULL, NULL, NULL, NULL),
('update_reports', 2, NULL, NULL, NULL, NULL, NULL),
('update_task', 2, NULL, NULL, NULL, NULL, NULL),
('user', 1, NULL, NULL, NULL, NULL, 2021),
('view_activity_log', 2, NULL, NULL, NULL, NULL, NULL),
('view_appointment', 2, NULL, NULL, NULL, NULL, NULL),
('view_attendance', 2, NULL, NULL, NULL, NULL, NULL),
('view_attendance_report', 2, NULL, NULL, NULL, NULL, NULL),
('view_chat', 2, NULL, NULL, NULL, NULL, NULL),
('view_customer', 2, NULL, NULL, NULL, NULL, NULL),
('view_customer_report', 2, NULL, NULL, NULL, NULL, NULL),
('view_employee_leave', 2, NULL, NULL, NULL, NULL, NULL),
('view_leave', 2, NULL, NULL, NULL, NULL, NULL),
('view_leave_report', 2, NULL, NULL, NULL, NULL, NULL),
('view_mail_box', 2, NULL, NULL, NULL, NULL, NULL),
('view_media', 2, NULL, NULL, NULL, NULL, NULL),
('view_project', 2, NULL, NULL, NULL, NULL, NULL),
('view_project_report', 2, NULL, NULL, NULL, NULL, NULL),
('view_reports', 2, NULL, NULL, NULL, NULL, NULL),
('view_task', 2, NULL, NULL, NULL, NULL, NULL),
('view_task_report', 2, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auth_item_child`
--

CREATE TABLE `tbl_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_auth_item_child`
--

INSERT INTO `tbl_auth_item_child` (`parent`, `child`) VALUES
('admin', 'create_appointment'),
('admin', 'create_chat'),
('admin', 'create_customer'),
('admin', 'create_leave'),
('admin', 'create_mail_box'),
('admin', 'create_media'),
('admin', 'create_project'),
('admin', 'create_task'),
('admin', 'delete_appointment'),
('admin', 'delete_chat'),
('admin', 'delete_customer'),
('admin', 'delete_leave'),
('admin', 'delete_mail_box'),
('admin', 'delete_media'),
('admin', 'delete_project'),
('admin', 'delete_task'),
('admin', 'export_attendance_report'),
('admin', 'export_customer_report'),
('admin', 'export_leave_report'),
('admin', 'export_project_report'),
('admin', 'export_task_report'),
('admin', 'Praktikanten'),
('admin', 'update_appointment'),
('admin', 'update_chat'),
('admin', 'update_customer'),
('admin', 'update_leave'),
('admin', 'update_mail_box'),
('admin', 'update_media'),
('admin', 'update_project'),
('admin', 'update_task'),
('admin', 'view_appointment'),
('admin', 'view_attendance_report'),
('admin', 'view_chat'),
('admin', 'view_customer'),
('admin', 'view_customer_report'),
('admin', 'view_leave'),
('admin', 'view_leave_report'),
('admin', 'view_mail_box'),
('admin', 'view_media'),
('admin', 'view_project'),
('admin', 'view_project_report'),
('admin', 'view_task'),
('admin', 'view_task_report'),
('Praktikanten', 'create_chat'),
('Praktikanten', 'create_media'),
('Praktikanten', 'delete_chat'),
('Praktikanten', 'update_chat'),
('Praktikanten', 'update_media'),
('Praktikanten', 'view_chat'),
('Praktikanten', 'view_customer'),
('Praktikanten', 'view_media'),
('Testing', 'create_activity_log'),
('Testing', 'create_appointment'),
('Testing', 'create_attendance'),
('Testing', 'create_customer'),
('Testing', 'create_employee_leave'),
('Testing', 'create_mail_box'),
('Testing', 'create_media'),
('Testing', 'create_project'),
('Testing', 'create_reports'),
('Testing', 'create_task'),
('Testing', 'update_activity_log'),
('Testing', 'update_appointment'),
('Testing', 'update_attendance'),
('Testing', 'update_customer'),
('Testing', 'update_employee_leave'),
('Testing', 'update_mail_box'),
('Testing', 'update_media'),
('Testing', 'update_project'),
('Testing', 'update_reports'),
('Testing', 'update_task'),
('Testing', 'view_activity_log'),
('Testing', 'view_appointment'),
('Testing', 'view_attendance'),
('Testing', 'view_customer'),
('Testing', 'view_employee_leave'),
('Testing', 'view_mail_box'),
('Testing', 'view_media'),
('Testing', 'view_project'),
('Testing', 'view_reports'),
('Testing', 'view_task'),
('user', 'create_appointment'),
('user', 'create_chat'),
('user', 'create_customer'),
('user', 'create_leave'),
('user', 'create_mail_box'),
('user', 'create_media'),
('user', 'create_project'),
('user', 'create_task'),
('user', 'delete_appointment'),
('user', 'delete_chat'),
('user', 'delete_customer'),
('user', 'delete_leave'),
('user', 'delete_mail_box'),
('user', 'delete_media'),
('user', 'delete_project'),
('user', 'delete_task'),
('user', 'export_attendance_report'),
('user', 'export_customer_report'),
('user', 'export_project_report'),
('user', 'update_appointment'),
('user', 'update_chat'),
('user', 'update_customer'),
('user', 'update_leave'),
('user', 'update_mail_box'),
('user', 'update_media'),
('user', 'update_project'),
('user', 'update_task'),
('user', 'view_appointment'),
('user', 'view_attendance_report'),
('user', 'view_chat'),
('user', 'view_customer'),
('user', 'view_customer_report'),
('user', 'view_leave'),
('user', 'view_leave_report'),
('user', 'view_mail_box'),
('user', 'view_media'),
('user', 'view_project'),
('user', 'view_project_report'),
('user', 'view_task'),
('user', 'view_task_report');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_auth_rule`
--

CREATE TABLE `tbl_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text CHARACTER SET utf8 DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_calendar_group`
--

CREATE TABLE `tbl_calendar_group` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `bg_color` varchar(100) DEFAULT '#000',
  `text_color` varchar(100) DEFAULT '#fff',
  `date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_calendar_group`
--

INSERT INTO `tbl_calendar_group` (`id`, `title`, `bg_color`, `text_color`, `date`, `created_by`) VALUES
(1, 'Privat', '#00c7fd', '#000000', '2021-04-06', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_calender_group_assignee`
--

CREATE TABLE `tbl_calender_group_assignee` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat`
--

CREATE TABLE `tbl_chat` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 1,
  `is_file` tinyint(1) NOT NULL DEFAULT 0,
  `is_group` tinyint(2) NOT NULL DEFAULT 0,
  `is_deleted_by_sender` tinyint(1) DEFAULT 0,
  `is_deleted_by_receiver` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_chat`
--

INSERT INTO `tbl_chat` (`id`, `sender_id`, `receiver_id`, `text`, `is_new`, `is_file`, `is_group`, `is_deleted_by_sender`, `is_deleted_by_receiver`, `created_at`, `created_by`) VALUES
(1, 2, 1, 'adhjhdgahjfks', 0, 0, 0, 0, 0, '2021-04-06 15:41:42', NULL),
(2, 2, 1, 'audio_1617703946532.mp3', 0, 1, 0, 0, 0, '2021-04-06 15:42:29', 2),
(3, 1, 2, 'hi', 1, 0, 0, 0, 0, '2021-04-06 16:19:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_group`
--

CREATE TABLE `tbl_chat_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `group_icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_chat_group`
--

INSERT INTO `tbl_chat_group` (`id`, `name`, `group_icon`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'wrkfjlksdjflkj', NULL, '2021-04-06 12:31:15', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_group_unread_count`
--

CREATE TABLE `tbl_chat_group_unread_count` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `total` int(11) NOT NULL DEFAULT 0,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_group_user`
--

CREATE TABLE `tbl_chat_group_user` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `added_by` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contract_attachment`
--

CREATE TABLE `tbl_contract_attachment` (
  `id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL DEFAULT 0,
  `file_name` varchar(255) NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country`
--

CREATE TABLE `tbl_country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso_code` varchar(2) NOT NULL,
  `status` smallint(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_country`
--

INSERT INTO `tbl_country` (`id`, `name`, `iso_code`, `status`) VALUES
(1, 'Afghanistan', 'AF', 1),
(2, 'Albania', 'AL', 1),
(3, 'Algeria', 'DZ', 1),
(4, 'American Samoa', 'AS', 1),
(5, 'Andorra', 'AD', 1),
(6, 'Angola', 'AO', 1),
(7, 'Anguilla', 'AI', 1),
(8, 'Antarctica', 'AQ', 1),
(9, 'Antigua and Barbuda', 'AG', 1),
(10, 'Argentina', 'AR', 1),
(11, 'Armenia', 'AM', 1),
(12, 'Aruba', 'AW', 1),
(13, 'Australia', 'AU', 1),
(14, 'Austria', 'AT', 1),
(15, 'Azerbaijan', 'AZ', 1),
(16, 'Bahamas', 'BS', 1),
(17, 'Bahrain', 'BH', 1),
(18, 'Bangladesh', 'BD', 1),
(19, 'Barbados', 'BB', 1),
(20, 'Belarus', 'BY', 1),
(21, 'Belgium', 'BE', 1),
(22, 'Belize', 'BZ', 1),
(23, 'Benin', 'BJ', 1),
(24, 'Bermuda', 'BM', 1),
(25, 'Bhutan', 'BT', 1),
(26, 'Bolivia', 'BO', 1),
(27, 'Bosnia and Herzegovina', 'BA', 1),
(28, 'Botswana', 'BW', 1),
(29, 'Bouvet Island', 'BV', 1),
(30, 'Brazil', 'BR', 1),
(31, 'British Indian Ocean Territory', 'IO', 1),
(32, 'Brunei Darussalam', 'BN', 1),
(33, 'Bulgaria', 'BG', 1),
(34, 'Burkina Faso', 'BF', 1),
(35, 'Burundi', 'BI', 1),
(36, 'Cambodia', 'KH', 1),
(37, 'Cameroon', 'CM', 1),
(38, 'Canada', 'CA', 1),
(39, 'Cape Verde', 'CV', 1),
(40, 'Cayman Islands', 'KY', 1),
(41, 'Central African Republic', 'CF', 1),
(42, 'Chad', 'TD', 1),
(43, 'Chile', 'CL', 1),
(44, 'China', 'CN', 1),
(45, 'Christmas Island', 'CX', 1),
(46, 'Cocos (Keeling) Islands', 'CC', 1),
(47, 'Colombia', 'CO', 1),
(48, 'Comoros', 'KM', 1),
(49, 'Congo', 'CG', 1),
(50, 'Cook Islands', 'CK', 1),
(51, 'Costa Rica', 'CR', 1),
(52, 'Cote D\'Ivoire', 'CI', 1),
(53, 'Croatia', 'HR', 1),
(54, 'Cuba', 'CU', 1),
(55, 'Cyprus', 'CY', 1),
(56, 'Czech Republic', 'CZ', 1),
(57, 'Denmark', 'DK', 1),
(58, 'Djibouti', 'DJ', 1),
(59, 'Dominica', 'DM', 1),
(60, 'Dominican Republic', 'DO', 1),
(61, 'East Timor', 'TL', 1),
(62, 'Ecuador', 'EC', 1),
(63, 'Egypt', 'EG', 1),
(64, 'El Salvador', 'SV', 1),
(65, 'Equatorial Guinea', 'GQ', 1),
(66, 'Eritrea', 'ER', 1),
(67, 'Estonia', 'EE', 1),
(68, 'Ethiopia', 'ET', 1),
(69, 'Falkland Islands (Malvinas)', 'FK', 1),
(70, 'Faroe Islands', 'FO', 1),
(71, 'Fiji', 'FJ', 1),
(72, 'Finland', 'FI', 1),
(74, 'France, Metropolitan', 'FR', 1),
(75, 'French Guiana', 'GF', 1),
(76, 'French Polynesia', 'PF', 1),
(77, 'French Southern Territories', 'TF', 1),
(78, 'Gabon', 'GA', 1),
(79, 'Gambia', 'GM', 1),
(80, 'Georgia', 'GE', 1),
(81, 'Germany', 'DE', 1),
(82, 'Ghana', 'GH', 1),
(83, 'Gibraltar', 'GI', 1),
(84, 'Greece', 'GR', 1),
(85, 'Greenland', 'GL', 1),
(86, 'Grenada', 'GD', 1),
(87, 'Guadeloupe', 'GP', 1),
(88, 'Guam', 'GU', 1),
(89, 'Guatemala', 'GT', 1),
(90, 'Guinea', 'GN', 1),
(91, 'Guinea-Bissau', 'GW', 1),
(92, 'Guyana', 'GY', 1),
(93, 'Haiti', 'HT', 1),
(94, 'Heard and Mc Donald Islands', 'HM', 1),
(95, 'Honduras', 'HN', 1),
(96, 'Hong Kong', 'HK', 1),
(97, 'Hungary', 'HU', 1),
(98, 'Iceland', 'IS', 1),
(99, 'India', 'IN', 1),
(100, 'Indonesia', 'ID', 1),
(101, 'Iran (Islamic Republic of)', 'IR', 1),
(102, 'Iraq', 'IQ', 1),
(103, 'Ireland', 'IE', 1),
(104, 'Israel', 'IL', 1),
(105, 'Italy', 'IT', 1),
(106, 'Jamaica', 'JM', 1),
(107, 'Japan', 'JP', 1),
(108, 'Jordan', 'JO', 1),
(109, 'Kazakhstan', 'KZ', 1),
(110, 'Kenya', 'KE', 1),
(111, 'Kiribati', 'KI', 1),
(112, 'North Korea', 'KP', 1),
(113, 'South Korea', 'KR', 1),
(114, 'Kuwait', 'KW', 1),
(115, 'Kyrgyzstan', 'KG', 1),
(116, 'Lao People\'s Democratic Republic', 'LA', 1),
(117, 'Latvia', 'LV', 1),
(118, 'Lebanon', 'LB', 1),
(119, 'Lesotho', 'LS', 1),
(120, 'Liberia', 'LR', 1),
(121, 'Libyan Arab Jamahiriya', 'LY', 1),
(122, 'Liechtenstein', 'LI', 1),
(123, 'Lithuania', 'LT', 1),
(124, 'Luxembourg', 'LU', 1),
(125, 'Macau', 'MO', 1),
(126, 'FYROM', 'MK', 1),
(127, 'Madagascar', 'MG', 1),
(128, 'Malawi', 'MW', 1),
(129, 'Malaysia', 'MY', 1),
(130, 'Maldives', 'MV', 1),
(131, 'Mali', 'ML', 1),
(132, 'Malta', 'MT', 1),
(133, 'Marshall Islands', 'MH', 1),
(134, 'Martinique', 'MQ', 1),
(135, 'Mauritania', 'MR', 1),
(136, 'Mauritius', 'MU', 1),
(137, 'Mayotte', 'YT', 1),
(138, 'Mexico', 'MX', 1),
(139, 'Micronesia, Federated States of', 'FM', 1),
(140, 'Moldova, Republic of', 'MD', 1),
(141, 'Monaco', 'MC', 1),
(142, 'Mongolia', 'MN', 1),
(143, 'Montserrat', 'MS', 1),
(144, 'Morocco', 'MA', 1),
(145, 'Mozambique', 'MZ', 1),
(146, 'Myanmar', 'MM', 1),
(147, 'Namibia', 'NA', 1),
(148, 'Nauru', 'NR', 1),
(149, 'Nepal', 'NP', 1),
(150, 'Netherlands', 'NL', 1),
(151, 'Netherlands Antilles', 'AN', 1),
(152, 'New Caledonia', 'NC', 1),
(153, 'New Zealand', 'NZ', 1),
(154, 'Nicaragua', 'NI', 1),
(155, 'Niger', 'NE', 1),
(156, 'Nigeria', 'NG', 1),
(157, 'Niue', 'NU', 1),
(158, 'Norfolk Island', 'NF', 1),
(159, 'Northern Mariana Islands', 'MP', 1),
(160, 'Norway', 'NO', 1),
(161, 'Oman', 'OM', 1),
(162, 'Pakistan', 'PK', 1),
(163, 'Palau', 'PW', 1),
(164, 'Panama', 'PA', 1),
(165, 'Papua New Guinea', 'PG', 1),
(166, 'Paraguay', 'PY', 1),
(167, 'Peru', 'PE', 1),
(168, 'Philippines', 'PH', 1),
(169, 'Pitcairn', 'PN', 1),
(170, 'Poland', 'PL', 1),
(171, 'Portugal', 'PT', 1),
(172, 'Puerto Rico', 'PR', 1),
(173, 'Qatar', 'QA', 1),
(174, 'Reunion', 'RE', 1),
(175, 'Romania', 'RO', 1),
(176, 'Russian Federation', 'RU', 1),
(177, 'Rwanda', 'RW', 1),
(178, 'Saint Kitts and Nevis', 'KN', 1),
(179, 'Saint Lucia', 'LC', 1),
(180, 'Saint Vincent and the Grenadines', 'VC', 1),
(181, 'Samoa', 'WS', 1),
(182, 'San Marino', 'SM', 1),
(183, 'Sao Tome and Principe', 'ST', 1),
(184, 'Saudi Arabia', 'SA', 1),
(185, 'Senegal', 'SN', 1),
(186, 'Seychelles', 'SC', 1),
(187, 'Sierra Leone', 'SL', 1),
(188, 'Singapore', 'SG', 1),
(189, 'Slovak Republic', 'SK', 1),
(190, 'Slovenia', 'SI', 1),
(191, 'Solomon Islands', 'SB', 1),
(192, 'Somalia', 'SO', 1),
(193, 'South Africa', 'ZA', 1),
(194, 'South Georgia &amp; South Sandwich Islands', 'GS', 1),
(195, 'Spain', 'ES', 1),
(196, 'Sri Lanka', 'LK', 1),
(197, 'St. Helena', 'SH', 1),
(198, 'St. Pierre and Miquelon', 'PM', 1),
(199, 'Sudan', 'SD', 1),
(200, 'Suriname', 'SR', 1),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 1),
(202, 'Swaziland', 'SZ', 1),
(203, 'Sweden', 'SE', 1),
(204, 'Switzerland', 'CH', 1),
(205, 'Syrian Arab Republic', 'SY', 1),
(206, 'Taiwan', 'TW', 1),
(207, 'Tajikistan', 'TJ', 1),
(208, 'Tanzania, United Republic of', 'TZ', 1),
(209, 'Thailand', 'TH', 1),
(210, 'Togo', 'TG', 1),
(211, 'Tokelau', 'TK', 1),
(212, 'Tonga', 'TO', 1),
(213, 'Trinidad and Tobago', 'TT', 1),
(214, 'Tunisia', 'TN', 1),
(215, 'Turkey', 'TR', 1),
(216, 'Turkmenistan', 'TM', 1),
(217, 'Turks and Caicos Islands', 'TC', 1),
(218, 'Tuvalu', 'TV', 1),
(219, 'Uganda', 'UG', 1),
(220, 'Ukraine', 'UA', 1),
(221, 'United Arab Emirates', 'AE', 1),
(222, 'United Kingdom', 'GB', 1),
(223, 'USA', 'US', 1),
(224, 'United States Minor Outlying Islands', 'UM', 1),
(225, 'Uruguay', 'UY', 1),
(226, 'Uzbekistan', 'UZ', 1),
(227, 'Vanuatu', 'VU', 1),
(228, 'Vatican City State (Holy See)', 'VA', 1),
(229, 'Venezuela', 'VE', 1),
(230, 'Viet Nam', 'VN', 1),
(231, 'Virgin Islands (British)', 'VG', 1),
(232, 'Virgin Islands (U.S.)', 'VI', 1),
(233, 'Wallis and Futuna Islands', 'WF', 1),
(234, 'Western Sahara', 'EH', 1),
(235, 'Yemen', 'YE', 1),
(237, 'Democratic Republic of Congo', 'CD', 1),
(238, 'Zambia', 'ZM', 1),
(239, 'Zimbabwe', 'ZW', 1),
(242, 'Montenegro', 'ME', 1),
(243, 'Serbia', 'RS', 1),
(244, 'Aaland Islands', 'AX', 1),
(245, 'Bonaire, Sint Eustatius and Saba', 'BQ', 1),
(246, 'Curacao', 'CW', 1),
(247, 'Palestinian Territory, Occupied', 'PS', 1),
(248, 'South Sudan', 'SS', 1),
(249, 'St. Barthelemy', 'BL', 1),
(250, 'St. Martin (French part)', 'MF', 1),
(251, 'Canary Islands', 'IC', 1),
(252, 'Ascension Island (British)', 'AC', 1),
(253, 'Kosovo, Republic of', 'XK', 1),
(254, 'Isle of Man', 'IM', 1),
(255, 'Tristan da Cunha', 'TA', 1),
(256, 'Guernsey', 'GG', 1),
(257, 'Jersey', 'JE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id` int(11) NOT NULL,
  `customer_number` varchar(20) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `fax` varchar(30) NOT NULL,
  `assistant_name` varchar(255) DEFAULT NULL,
  `assistant_phone` varchar(15) DEFAULT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `country` varchar(30) NOT NULL,
  `other_address` text DEFAULT NULL,
  `other_city` varchar(50) DEFAULT NULL,
  `other_state` varchar(50) DEFAULT NULL,
  `other_postal_code` varchar(15) DEFAULT NULL,
  `other_country` varchar(30) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `account_owner` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `vat_id_number` varchar(100) DEFAULT NULL,
  `tax_number` varchar(100) DEFAULT NULL,
  `sepa_direct_debit_mandate` varchar(100) DEFAULT NULL,
  `date_of_sepa_direct_debit_mandate` date DEFAULT NULL,
  `swift_bic` varchar(50) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `blz` varchar(100) DEFAULT NULL,
  `status` smallint(2) DEFAULT 1,
  `trash` smallint(2) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT 1,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `xing` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`id`, `customer_number`, `first_name`, `last_name`, `company_name`, `email`, `phone`, `mobile`, `dob`, `fax`, `assistant_name`, `assistant_phone`, `address`, `city`, `state`, `postal_code`, `country`, `other_address`, `other_city`, `other_state`, `other_postal_code`, `other_country`, `website`, `linkedin`, `facebook`, `twitter`, `description`, `account_owner`, `bank_name`, `iban`, `vat_id_number`, `tax_number`, `sepa_direct_debit_mandate`, `date_of_sepa_direct_debit_mandate`, `swift_bic`, `account_number`, `blz`, `status`, `trash`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_by`, `deleted_at`, `xing`, `instagram`) VALUES
(1, '273894', 'Fatmir', 'Binaku', 'FIXATO', 'binaku@mf-1.de', '8457938479', '', '1970-01-01', '', '', '', 'sdifskj', 'Frankfurt', 'Hessen', '60111', 'Germany', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1970-01-01', '', '', '', 1, 0, '2021-04-06 09:52:38', 2, '2021-04-06 15:22:38', NULL, 0, NULL, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_contract`
--

CREATE TABLE `tbl_customer_contract` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `bill_number` varchar(50) DEFAULT NULL,
  `contract_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `issue_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` smallint(2) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_client`
--

CREATE TABLE `tbl_email_client` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `imap_host_url` varchar(255) NOT NULL COMMENT 'Imap host:port',
  `smtp_host` varchar(100) DEFAULT NULL,
  `smtp_port` varchar(10) DEFAULT NULL,
  `smtp_encryption` enum('tls','ssl') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_email_client`
--

INSERT INTO `tbl_email_client` (`id`, `title`, `imap_host_url`, `smtp_host`, `smtp_port`, `smtp_encryption`) VALUES
(1, 'GMAIL', 'imap.gmail.com:993', 'smtp.gmail.com', '587', 'tls'),
(2, 'YAHOO', 'imap.mail.yahoo.com:993', 'smtp.mail.yahoo.com', '587', 'tls'),
(3, 'Microsoft  Server', 'outlook.office365.com:993', 'smtp.office365.com', '587', 'tls'),
(4, 'ionos', 'imap.ionos.de:993', 'smtp.ionos.de', '587', 'tls'),
(5, 'Ionos Exchange sevre', 'exchange2019.ionos.de:993', 'smtp.exchange2019.ionos.de', '587', 'tls');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_email_folder`
--

CREATE TABLE `tbl_email_folder` (
  `id` int(11) NOT NULL,
  `user_email_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_general_setting`
--

CREATE TABLE `tbl_general_setting` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `type_label` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `setting_label` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `setting_value` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_general_setting`
--

INSERT INTO `tbl_general_setting` (`id`, `type_name`, `type_label`, `setting_name`, `setting_label`, `setting_value`) VALUES
(1, 'general', 'General', 'site_name', 'Site Name', 'WESEBO'),
(8, 'general', 'General', 'from_email', 'From Email', 'admin@faystech.com'),
(9, 'general', 'General', 'from_name', 'From Name', 'EMS'),
(10, 'general', 'General', 'allowed_extensions', 'Allowed Extensions', 'png,jpg,svg'),
(11, 'local', 'Local', 'date_format', 'System Date Format', 'd.m.Y'),
(12, 'local', 'Local', 'currency_code', 'System Currency Code', 'EUR'),
(13, 'local', 'Local', 'currency_symbol', 'System Currency Symbol', '€'),
(14, 'local', 'Local', 'time_format', 'System Time Format', 'H:i');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave`
--

CREATE TABLE `tbl_leave` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(150) NOT NULL,
  `remaining_leaves` int(11) DEFAULT 0,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `is_full_day` tinyint(2) DEFAULT 1,
  `half_day` varchar(40) DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `first_month_hour` varchar(100) DEFAULT '0.00',
  `second_month_hour` varchar(100) DEFAULT '0.00',
  `status` smallint(2) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `relation_id` varchar(50) DEFAULT NULL,
  `created_location` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_leave`
--

INSERT INTO `tbl_leave` (`id`, `leave_type`, `remaining_leaves`, `from_date`, `to_date`, `is_full_day`, `half_day`, `no_of_days`, `first_month_hour`, `second_month_hour`, `status`, `reason`, `relation_id`, `created_location`, `created_at`, `created_by`) VALUES
(1, 'Working From Home', 0, '2021-04-06', '2021-04-06', 1, 'full-day', 0, '0.00', '0.00', NULL, 'CORONA', NULL, NULL, '2021-04-06 10:07:42', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login_log`
--

CREATE TABLE `tbl_login_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_at` datetime DEFAULT NULL,
  `logout_at` datetime DEFAULT NULL,
  `login_ip` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_login_log`
--

INSERT INTO `tbl_login_log` (`log_id`, `user_id`, `login_at`, `logout_at`, `login_ip`) VALUES
(3, 1, '2021-04-07 13:14:30', '2021-04-06 16:20:44', '79.234.95.11'),
(18, 2, '2021-04-06 17:38:34', '2021-04-06 17:43:23', '103.102.234.241');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logo_setting`
--

CREATE TABLE `tbl_logo_setting` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `setting_value` text CHARACTER SET utf8 DEFAULT NULL,
  `setting_size` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_logo_setting`
--

INSERT INTO `tbl_logo_setting` (`id`, `setting_name`, `setting_value`, `setting_size`) VALUES
(1, 'Logo', 'logo.svg', 140);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mailbox`
--

CREATE TABLE `tbl_mailbox` (
  `id` int(11) NOT NULL,
  `email_client_id` int(11) DEFAULT NULL,
  `user_email_id` int(11) DEFAULT NULL,
  `folder_id` int(11) NOT NULL,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from` varchar(255) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `email_date` varchar(255) DEFAULT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  `body` longblob DEFAULT NULL,
  `status` int(11) NOT NULL,
  `email_size` int(11) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `msgno` varchar(255) DEFAULT NULL,
  `recent` varchar(255) NOT NULL DEFAULT '0',
  `flagged` int(11) NOT NULL DEFAULT 0,
  `bookmarked` int(11) NOT NULL DEFAULT 0,
  `answered` int(11) NOT NULL DEFAULT 0,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `seen` int(11) NOT NULL DEFAULT 0,
  `draft` int(11) NOT NULL DEFAULT 0,
  `trashed` int(11) NOT NULL DEFAULT 0,
  `udate` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media`
--

CREATE TABLE `tbl_media` (
  `id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8 DEFAULT NULL,
  `alternate_text` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `caption` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `extension` varchar(4) CHARACTER SET utf8 NOT NULL,
  `status` smallint(6) DEFAULT 1,
  `media_folder_id` int(11) NOT NULL DEFAULT 0,
  `create_for` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_media`
--

INSERT INTO `tbl_media` (`id`, `title`, `description`, `alternate_text`, `caption`, `file_name`, `thumb`, `extension`, `status`, `media_folder_id`, `create_for`, `created_by`, `created_at`) VALUES
(1, 'German ', NULL, 'German ', 'German ', '1617703080.rtf', 'file.png', 'rtf', 1, 0, NULL, 2, '2021-04-06 15:28:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media_folder`
--

CREATE TABLE `tbl_media_folder` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `status` smallint(6) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_media_folder`
--

INSERT INTO `tbl_media_folder` (`id`, `parent_id`, `name`, `sort_order`, `status`, `created_by`, `created_at`) VALUES
(1, 0, 'Schäden April 2021', 1, 1, 2, '2021-04-06 15:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media_permission`
--

CREATE TABLE `tbl_media_permission` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_folder` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_media_permission`
--

INSERT INTO `tbl_media_permission` (`id`, `file_id`, `user_id`, `is_folder`, `created_at`, `created_by`) VALUES
(1, 1, 2, 0, '2021-04-06 09:58:00', 2),
(2, 1, 2, 1, '2021-04-06 09:58:45', 2),
(3, 1, 1, 1, '2021-04-06 09:59:16', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_items`
--

CREATE TABLE `tbl_menu_items` (
  `id` int(11) NOT NULL,
  `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_menu_items`
--

INSERT INTO `tbl_menu_items` (`id`, `data`) VALUES
(1, '[{\"deleted\":0,\"new\":1,\"slug\":\"https://www.carvita.eu/live/app/public/login.xsp\",\"name\":\"Carvita\",\"id\":\"new-677\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification`
--

CREATE TABLE `tbl_notification` (
  `id` int(11) NOT NULL,
  `type` int(5) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `modal` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `action` varchar(20) DEFAULT NULL COMMENT 'accept,reject,exchange',
  `message` text CHARACTER SET utf8 DEFAULT NULL,
  `read` tinyint(2) NOT NULL DEFAULT 1,
  `create_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_notification`
--

INSERT INTO `tbl_notification` (`id`, `type`, `user_id`, `modal`, `item_id`, `action`, `message`, `read`, `create_date`) VALUES
(1, 1, 1, 'media', 0, 'share_media', 'Naum Gußinsky has shared a folder Schäden April 2021 with you.', 0, '2021-04-06 15:29:16'),
(2, 1, 1, 'task/view', 1, 'add_task', 'Naum Gußinsky has added one new task.', 0, '2021-04-06 15:32:26'),
(3, 1, 2, 'task/view', 1, 'task_comment', 'Naum Gußinsky  has added a comment at task.', 0, '2021-04-06 15:32:39'),
(4, 1, 1, 'task/view', 1, 'update_task', 'Naum Gußinsky has made few changes in Bitte Email an Colin schreiben..... task.', 0, '2021-04-06 15:32:48'),
(5, 1, 1, 'leave/index', 1, 'leave_application', 'Naum Gußinsky has applied for leave.', 0, '2021-04-06 15:37:42'),
(6, 1, 2, 'calendar/index', 0, 'assign_appointment', 'A new appointment test assigned to you by Admin Test1.', 1, '2021-04-06 17:55:25'),
(7, 1, 2, 'calendar/index', 0, 'update_appointment', 'There are few changes made in appointmenttest by Admin Test1.', 1, '2021-04-06 17:56:10'),
(8, 1, 1, 'chat/index/group', 1, 'add_chat_group', 'You have been added to wrkfjlksdjflkj chat group by Admin Test1.', 1, '2021-04-06 18:01:15'),
(9, 1, 1, 'chat/index', 0, 'remove_chat_group', 'You have been removed from wrkfjlksdjflkj chat group by Admin Test1.', 0, '2021-04-06 18:01:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project`
--

CREATE TABLE `tbl_project` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longblob DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` varchar(30) DEFAULT NULL,
  `billing_type` varchar(30) NOT NULL,
  `estimated_hours` decimal(10,2) DEFAULT NULL,
  `payment_description` text DEFAULT NULL,
  `relation_id` varchar(30) DEFAULT NULL,
  `status` enum('pending','in-progress','delay','completed') NOT NULL DEFAULT 'pending',
  `trash` smallint(2) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_project_assignee`
--

CREATE TABLE `tbl_project_assignee` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task`
--

CREATE TABLE `tbl_task` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longblob NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('upcoming','in-progress','completed') NOT NULL DEFAULT 'upcoming',
  `trash` smallint(2) DEFAULT 0,
  `relation_id` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_task`
--

INSERT INTO `tbl_task` (`id`, `name`, `description`, `start_date`, `end_date`, `status`, `trash`, `relation_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'Bitte Email an Colin schreiben.....', 0x3c703e6a666a646b67686a6b6864676b6a6466686b6c6a646667686c6466686b6a686c6a6b3c2f703e, '2021-04-06', '2021-04-06', 'completed', 0, NULL, '2021-04-06 10:02:26', 2, '2021-04-06 15:32:48', 2, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task_assignee`
--

CREATE TABLE `tbl_task_assignee` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `assign_to` int(11) NOT NULL DEFAULT 0,
  `assign_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_task_assignee`
--

INSERT INTO `tbl_task_assignee` (`id`, `task_id`, `assign_to`, `assign_at`) VALUES
(2, 1, 2, '2021-04-06 15:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_task_discussion`
--

CREATE TABLE `tbl_task_discussion` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `is_file` tinyint(1) DEFAULT 0,
  `comment` text CHARACTER SET utf8 DEFAULT NULL,
  `attach_file` varchar(255) DEFAULT NULL,
  `status` enum('active','deactive') NOT NULL DEFAULT 'active',
  `create_date` datetime DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_task_discussion`
--

INSERT INTO `tbl_task_discussion` (`id`, `task_id`, `is_file`, `comment`, `attach_file`, `status`, `create_date`, `create_by`, `update_by`, `update_date`) VALUES
(1, 1, 0, 'Habs erledigt', NULL, 'active', '2021-04-06 15:32:39', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `user_role` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `key_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `first_name`, `last_name`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `phone`, `profile_picture`, `verified`, `status`, `user_role`, `reset_key`, `key_date`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Test1', 'admin@test.com', 'ld6XrLadQBakdmPsRCqKUxXX2oDbqXBM', '$2y$13$K8ySF73yElwFUNeKviFXkerLQ7iuirbDVxXZH48Pe8mUyv3IzDGCK', 'TElDXFC5wuhcYFS4HBawtoKLq6BtdAKq_1423459939', 'admin@test.com', '9876543224', 'user-1.jpg', 1, 1, 'admin', '1', '2021-02-13 10:54:49', '2021-02-13 05:24:49', '2021-03-30 20:19:48'),
(2, 'Naum', 'Gußinsky', 'ng@wesebo.de', 'Jb7M8IGT_VVe0XVDerQUdPyB5zo3XyrD', '$2y$13$mfC2viO027K9v7vH.uyPFevskIGcp36JOfKeqgBp2aaDL74qMj3pK', NULL, 'ng@wesebo.de', '017642479895', NULL, 1, 1, 'user', NULL, NULL, '2021-04-06 09:46:24', '2021-04-06 15:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_detail`
--

CREATE TABLE `tbl_user_detail` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `email_signature` text DEFAULT NULL,
  `working_hours` varchar(255) DEFAULT NULL,
  `allowed_leave_hours` varchar(255) DEFAULT NULL,
  `remaining_leave_hours` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_detail`
--

INSERT INTO `tbl_user_detail` (`id`, `user_id`, `email_signature`, `working_hours`, `allowed_leave_hours`, `remaining_leave_hours`) VALUES
(1, 1, '', '4', '2', '2'),
(2, 2, '<p  style=\"font-family:Verdana, Helvetica, sans-serif;\">Mit freundlichen Grüßen</p>\r\n-- \r\n<br />\r\n<table style=\"font-family:Verdana, Helvetica, sans-serif;\" cellpadding=\"0\" cellspacing=\"0\">\r\n <tbody>\r\n  <tr>\r\n   <td style=\"width:140px; padding:0; font-family:Verdana; text-align:center; vertical-align:middle; height: 113px;\" valign=\"middle\" width=\"140\">\r\n    <a href=\"https://www.wesebo.de/\" target=\"_blank\">\r\n    <img alt=\"Werbeagentur Frankfurt, Webdesign Frankfurt, SEO Frankfurt, suchmaschinenoptimierung Frankfurt, Webdesign Agentur Frankfurt, SEO Agentur Frankfurt, suchmaschinenoptimierung Agentur Frankfurt \" width=\"100\" height=\"100\" border=\"0\" style=\"width:100px; height:100px; border-radius:50px; border:0;\"  src=\"https://wesebo.de/Email-Signature/sign_wesebo.jpg\"></a>\r\n   </td>\r\n   <td style=\"font-family:Verdana; border-bottom:2px solid #c2976b; padding:0; vertical-align:top; height: 113px;\" valign=\"top\">	\r\n    <table style=\"font-family:Verdana, Helvetica, sans-serif;\" cellpadding=\"0\" cellspacing=\"0\">\r\n     <tbody>\r\n      <tr>\r\n       <td style=\"font-family:Verdana; padding-bottom:6px; padding-top:0; padding-left:0; padding-right:0; vertical-align:top;\" valign=\"top\">\r\n        <strong><span style=\"font-family:Verdana; color:#000000; font-size:14pt;\">Babak Gavadji</span></strong><br>    <span style=\"font-family:Verdana; color:#3a3a3a; font-size:10pt;\">CCO</span>	\r\n       </td>	    \r\n      </tr>	    \r\n      <tr>     \r\n       <td style=\"font-family:Verdana; padding-bottom:6px; padding-top:0; padding-left:0; padding-right:0; line-height:18px; vertical-align:top;\" valign=\"top\">    \r\n        <span style=\"font-family:Verdana; font-size:10pt;\">E-Mail:\r\n		<a href=\"mailto:info@wesebo.de\" style=\"text-decoration:none; color:black\">bg@wesebo.de</a><br> Tel.: +49 69 255 \r\n		3839 1 | Mobil: 0177 789 0722</span> \r\n       </td>\r\n      </tr>\r\n      <tr>     \r\n       <td style=\"font-family:Verdana; padding-bottom:6px; padding-top:0; padding-left:0; padding-right:0; line-height:18px; vertical-align:top;\" valign=\"top\">    \r\n        <span style=\"font-family:Verdana; font-size:10pt;\">Wesebo®️ Werbeagentur</span><br> \r\n        <span style=\"font-family:Verdana; font-size:10pt;\">Friedensstr. 3, 60311 Frankfurt am Main</span> \r\n       </td>\r\n      </tr>\r\n     </tbody>\r\n    </table> 								\r\n   </td>	\r\n  </tr>\r\n  <tr>\r\n   <td style=\"font-family:Verdana; width:140px; padding-top:6px; padding-left:0; padding-right:0; text-align:center; vertical-align:middle;\" valign=\"middle\" width=\"140\">	\r\n    <span><a href=\"https://www.facebook.com/wesebo.de\" target=\"_blank\"> <img border=\"0\" width=\"16\" alt=\"Facebook icon\" style=\"border:0; height:16px; width:16px\" src=\"https://wesebo.de/Email-Signature/fb.png\"></a></span>\r\n    <span><a href=\"https://twitter.com/wesebo\" target=\"_blank\"><img border=\"0\" width=\"16\" alt=\"Twitter icon\" style=\"border:0; height:16px; width:16px\" src=\"https://wesebo.de/Email-Signature/tt.png\"></a></span>\r\n    <span><a href=\"https://www.instagram.com/wesebo/\" target=\"_blank\"><img border=\"0\" width=\"16\" alt=\"Instagram icon\" style=\"border:0; height:16px; width:16px\" src=\"https://wesebo.de/Email-Signature/it.png\"></a></span>\r\n   </td>\r\n   <td style=\"padding-top:6px; padding-bottom:0; padding-left:0; padding-right:0; font-family:Verdana; vertical-align:middle;\" valign=\"middle\">\r\n    <a href=\"https://www.wesebo.de/\" style=\"color:#c2976b; font-size:16px; text-decoration:none\" target=\"_blank\">www.wesebo.de</a>\r\n   </td>\r\n  </tr>\r\n </tbody>\r\n</table>\r\n<br>\r\n<h4 style=\"font-family:Verdana, Helvetica, sans-serif;\">Haftungsausschluss</h4>\r\n<p style=\"font-family:Verdana, Helvetica, sans-serif; text-align:justify; font-size:12px; margin-top:-15px\">\r\nDieses Dokument ist ausschliesslich für den Adressaten bestimmt. Der Inhalt \r\ndieser E-Mail bleibt solange unverbindlich, bis Sie eine schriftliche \r\nBestätigung über den Inhalt erhalten. Ausserdem lehnt unser Unternehmen jede \r\nVerantwortung und jegliche Regressansprüche ab, solange Sie ohne schriftliche \r\nBestätigung aufgrund dieser E-Mail agieren/reagieren oder davon absehen.<br />\r\nFalls Sie diese E-Mail-Nachricht versehentlich bekommen haben, rufen Sie bitte \r\nunverzüglich an und löschen Sie diese Nachricht von Ihrem Computer.<br />\r\nJegliche Art von Reproduktion, Verbreitung, Vervielfältigung, Modifikation, \r\nVerteilung und/oder Publikation dieser E-Mail-Nachricht ist strengstens \r\nverboten.</p>\r\n\r\n\r\n<h4 style=\"font-family:Verdana, Helvetica, sans-serif;\">Legal disclaimer</h4>\r\n<p style=\"font-family:Verdana, Helvetica, sans-serif; text-align:justify; font-size:12px; margin-top:-15px\">\r\nThis document should only be read by those persons to whom it is addressed and \r\nis not intended to be relied upon by any person without subsequent written \r\nconfirmation of its content. Accordingly, our company disclaim all \r\nresponsibility and accept no liability (including in negligence) for the \r\nconsequences for any person acting, or refraining from acting, on such \r\ninformation prior to the receipt by those persons of subsequent written \r\nconfirmation.<br />\r\nIf you have received this email message in error, please notify us immediately \r\nby telephone. Please also destroy and delete the message from your computer.<br />\r\nAny form of reproduction, dissemination, copying, disclosure, modification, \r\ndistribution and/or publication of this email message is strictly prohibited.</p>', '0', '8', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_email`
--

CREATE TABLE `tbl_user_email` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_client_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 1,
  `last_sync` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user_email`
--

INSERT INTO `tbl_user_email` (`id`, `user_id`, `email_client_id`, `email`, `password`, `created_at`, `updated_at`, `status`, `last_sync`) VALUES
(8, 1, 5, 'vertrieb@mf-1.de', 'YW1wNTcuR2dzcmghVGpj', '2021-04-06 16:08:43', '2021-04-06 16:08:43', 1, '2021-04-06 16:08:43'),
(9, 1, 4, 'mt@wesebo.de', 'YWdkeSMjV2F0V2Q5cVZRSw==', '2021-04-06 16:09:13', '2021-04-06 16:09:13', 1, '2021-04-06 16:09:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_activity_log`
--
ALTER TABLE `tbl_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_appointment_assignee`
--
ALTER TABLE `tbl_appointment_assignee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_attachment`
--
ALTER TABLE `tbl_attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_auth_assignment`
--
ALTER TABLE `tbl_auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `tbl_auth_item`
--
ALTER TABLE `tbl_auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `tbl_auth_item_child`
--
ALTER TABLE `tbl_auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`),
  ADD KEY `parent` (`parent`),
  ADD KEY `child_2` (`child`);

--
-- Indexes for table `tbl_auth_rule`
--
ALTER TABLE `tbl_auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `tbl_calendar_group`
--
ALTER TABLE `tbl_calendar_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_calender_group_assignee`
--
ALTER TABLE `tbl_calender_group_assignee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tbl_chat_group_unread_count`
--
ALTER TABLE `tbl_chat_group_unread_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_chat_group_user`
--
ALTER TABLE `tbl_chat_group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `tbl_contract_attachment`
--
ALTER TABLE `tbl_contract_attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_contract`
--
ALTER TABLE `tbl_customer_contract`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tbl_email_client`
--
ALTER TABLE `tbl_email_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_email_folder`
--
ALTER TABLE `tbl_email_folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_leave`
--
ALTER TABLE `tbl_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_login_log`
--
ALTER TABLE `tbl_login_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `tbl_logo_setting`
--
ALTER TABLE `tbl_logo_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mailbox`
--
ALTER TABLE `tbl_mailbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tbl_media_folder`
--
ALTER TABLE `tbl_media_folder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_media_permission`
--
ALTER TABLE `tbl_media_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project`
--
ALTER TABLE `tbl_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_project_assignee`
--
ALTER TABLE `tbl_project_assignee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_task_assignee`
--
ALTER TABLE `tbl_task_assignee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tbl_task_discussion`
--
ALTER TABLE `tbl_task_discussion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role` (`user_role`);

--
-- Indexes for table `tbl_user_detail`
--
ALTER TABLE `tbl_user_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user_email`
--
ALTER TABLE `tbl_user_email`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_activity_log`
--
ALTER TABLE `tbl_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_appointment_assignee`
--
ALTER TABLE `tbl_appointment_assignee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_attachment`
--
ALTER TABLE `tbl_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_calendar_group`
--
ALTER TABLE `tbl_calendar_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_calender_group_assignee`
--
ALTER TABLE `tbl_calender_group_assignee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_chat_group_unread_count`
--
ALTER TABLE `tbl_chat_group_unread_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chat_group_user`
--
ALTER TABLE `tbl_chat_group_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_contract_attachment`
--
ALTER TABLE `tbl_contract_attachment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_country`
--
ALTER TABLE `tbl_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_customer_contract`
--
ALTER TABLE `tbl_customer_contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_email_client`
--
ALTER TABLE `tbl_email_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_email_folder`
--
ALTER TABLE `tbl_email_folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_general_setting`
--
ALTER TABLE `tbl_general_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_leave`
--
ALTER TABLE `tbl_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_login_log`
--
ALTER TABLE `tbl_login_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_logo_setting`
--
ALTER TABLE `tbl_logo_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_mailbox`
--
ALTER TABLE `tbl_mailbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_media`
--
ALTER TABLE `tbl_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_media_folder`
--
ALTER TABLE `tbl_media_folder`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_media_permission`
--
ALTER TABLE `tbl_media_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_menu_items`
--
ALTER TABLE `tbl_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_notification`
--
ALTER TABLE `tbl_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_project`
--
ALTER TABLE `tbl_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_project_assignee`
--
ALTER TABLE `tbl_project_assignee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_task`
--
ALTER TABLE `tbl_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_task_assignee`
--
ALTER TABLE `tbl_task_assignee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_task_discussion`
--
ALTER TABLE `tbl_task_discussion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user_detail`
--
ALTER TABLE `tbl_user_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user_email`
--
ALTER TABLE `tbl_user_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_auth_item_child`
--
ALTER TABLE `tbl_auth_item_child`
  ADD CONSTRAINT `tbl_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `tbl_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `tbl_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  ADD CONSTRAINT `fa_chat_group_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_chat_group_user`
--
ALTER TABLE `tbl_chat_group_user`
  ADD CONSTRAINT `tbl_chat_group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `tbl_chat_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_chat_group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_chat_group_user_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_task_assignee`
--
ALTER TABLE `tbl_task_assignee`
  ADD CONSTRAINT `tbl_task_assignee_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tbl_task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
