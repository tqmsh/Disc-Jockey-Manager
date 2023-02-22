-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2023 at 09:35 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `promplanner_orchid`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcments`
--

CREATE TABLE `announcments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender` bigint(20) UNSIGNED NOT NULL,
  `schools` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `users` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `globals` tinyint(4) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `state` tinyint(4) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachmentable`
--

CREATE TABLE `attachmentable` (
  `id` int(10) UNSIGNED NOT NULL,
  `attachmentable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachmentable_id` int(10) UNSIGNED NOT NULL,
  `attachment_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint(20) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `catagory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `clicks` bigint(20) UNSIGNED NOT NULL,
  `impressions` bigint(20) UNSIGNED NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(9, 'Venue', 1, '2023-01-09 03:16:56', '2023-01-09 03:16:56'),
(10, 'Event Planner', 1, '2023-01-09 03:17:16', '2023-01-09 03:17:16'),
(11, 'Disc Jockey', 1, '2023-01-09 03:17:27', '2023-01-09 03:17:27'),
(12, 'Audio-Visual', 1, '2023-01-09 03:17:36', '2023-01-09 03:17:36'),
(13, 'Photographers', 1, '2023-01-09 03:17:42', '2023-01-09 03:17:42'),
(14, 'Videographer', 1, '2023-01-09 03:20:29', '2023-01-09 03:20:29'),
(15, 'Caterer', 1, '2023-01-09 03:20:35', '2023-01-09 03:20:35'),
(17, 'Suggestion From Vendor', 1, '2023-01-13 03:55:42', '2023-01-14 03:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_creator` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_start_time` datetime DEFAULT NULL,
  `event_finish_time` datetime DEFAULT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_address` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_zip_postal` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_info` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_rules` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_creator`, `school_id`, `region_id`, `venue_id`, `event_name`, `event_start_time`, `event_finish_time`, `school`, `event_address`, `event_zip_postal`, `event_info`, `event_rules`, `created_at`, `updated_at`) VALUES
(6, 108, 32, 12, 9, 'Cool Kidz Party', '2022-11-11 15:05:41', '2022-11-18 15:05:41', 'Cool School', '789 Cool Street', 'ILK OL8', 'Cool dresses ', 'ONLY COOL KIDS ALLOWED', '2022-11-04 19:05:41', '2022-11-15 01:34:49'),
(13, 13, 53, 1, NULL, 'Colonel By\'s Main Event', '2022-11-21 12:00:00', '2022-11-22 12:00:00', 'Colonel By Secondary School', '2381 Ogilvie Rd', 'K1J 7N4', 'Formal Attire', 'No Violence', '2022-11-20 11:16:51', '2022-11-20 11:16:51'),
(14, 13, 51, 1, NULL, 'Digitera\'s Main DJ Event', '2022-11-20 12:00:00', '2022-11-26 12:00:00', 'Digitera School of Digital Marketing & Software', '1125 Colonel By Dr Rm 102', 'K1S 5B6', 'PART ON!!!!', 'No rules', '2022-11-20 11:25:03', '2022-11-20 11:25:03'),
(15, 151, 51, 1, NULL, 'The Perfect Event For You!', '2022-12-02 12:00:00', '2022-12-03 12:00:00', 'Digitera School of Digital Marketing & Software', '123 Hey Road', 'KIU 84O', 'I ain\'t got nothing', 'None', '2022-12-02 01:03:59', '2022-12-02 01:03:59'),
(17, 151, 51, 1, 9, 'Test with venue', '2023-01-10 12:00:00', '2023-01-20 12:00:00', 'Digitera School of Digital Marketing & Software', NULL, NULL, 'Test with venue', 'Test with venue', '2023-01-17 01:14:07', '2023-01-17 01:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE `event_attendees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ticketstatus` varchar(255) NOT NULL DEFAULT 'Unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_attendees`
--

INSERT INTO `event_attendees` (`id`, `user_id`, `event_id`, `table_id`, `ticketstatus`, `created_at`, `updated_at`) VALUES
(1, 146, 14, 1, 'Unpaid', '2023-02-12 19:55:58', NULL),
(2, 152, 14, 2, 'Paid', '2023-02-12 19:56:39', '2023-02-23 00:39:01'),
(7, 169, 14, NULL, 'Paid', '2023-02-13 01:57:06', '2023-02-23 01:29:29'),
(8, 170, 14, NULL, 'Unpaid', '2023-02-13 01:57:06', '2023-02-13 01:57:06'),
(9, 155, 14, NULL, 'Paid', '2023-02-14 19:52:46', NULL),
(10, 155, 15, NULL, 'Unpaid', '2023-02-14 19:53:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_bids`
--

CREATE TABLE `event_bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `event_date` datetime NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `notes` longtext DEFAULT NULL,
  `contact_instructions` longtext DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `url` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_bids`
--

INSERT INTO `event_bids` (`id`, `user_id`, `event_id`, `region_id`, `package_id`, `category_id`, `event_date`, `school_name`, `notes`, `contact_instructions`, `company_name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(2, 197, 14, 1, 3, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'Just buy this license please', 'Email: info@digitera.agency', 'Money Jockeys', 'https://promplanner.app/', 2, '2023-02-01 00:53:31', '2023-02-01 00:53:31'),
(3, 197, 13, 1, 1, 11, '2022-11-21 12:00:00', 'Colonel By Secondary School', 'This will be the best venue trust me bro', 'Phone: 654-785-1289', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-01 00:56:23', '2023-02-01 00:56:23'),
(4, 197, 14, 1, 2, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'Notes', 'Contact Instructions', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-02 23:39:42', '2023-02-09 01:15:50'),
(7, 197, 14, 1, 1, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', NULL, NULL, 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-03 02:43:50', '2023-02-09 01:18:44'),
(8, 197, 13, 1, 3, 11, '2022-11-21 12:00:00', 'Colonel By Secondary School', 'notes', 'contact info', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-07 04:26:43', '2023-02-07 04:26:43'),
(9, 197, 14, 1, 6, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'HERE ARE SOME NOTES', 'HERE IS SOME CONTACT INFO', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-09 01:23:21', '2023-02-09 01:23:21'),
(10, 197, 15, 1, 1, 11, '2022-12-02 12:00:00', 'Digitera School of Digital Marketing & Software', 'fgdfg', 'dfgdfg', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-15 01:04:19', '2023-02-15 01:05:56'),
(11, 197, 15, 1, 3, 11, '2022-12-02 12:00:00', 'Digitera School of Digital Marketing & Software', 'dfgdfg', 'fdgdfg', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-15 01:04:37', '2023-02-15 01:04:37'),
(12, 197, 17, 1, 3, 11, '2023-01-10 12:00:00', 'Digitera School of Digital Marketing & Software', 'dsfdsfsd', 'fsdfsdf', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-15 01:04:50', '2023-02-15 01:04:50'),
(13, 197, 17, 1, 2, 11, '2023-01-10 12:00:00', 'Digitera School of Digital Marketing & Software', 'dfsdf', 'sdfsdf', 'Money Jockeys', 'https://promplanner.app/', 2, '2023-02-15 01:05:02', '2023-02-15 01:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE `invitation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event` bigint(20) UNSIGNED NOT NULL,
  `recipient` bigint(20) UNSIGNED NOT NULL,
  `inviter` bigint(20) UNSIGNED NOT NULL,
  `expiry` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `localadmins`
--

CREATE TABLE `localadmins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_status` int(1) DEFAULT 0,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `localadmins`
--

INSERT INTO `localadmins` (`id`, `user_id`, `school_id`, `account_status`, `firstname`, `lastname`, `phonenumber`, `email`, `school`, `created_at`, `updated_at`) VALUES
(18, 108, 53, 1, 'Camille', 'Williams', '(187) 264-8912', 'camillewilliams@gmail.com', 'Colonel By Secondary School', '2022-10-13 19:11:55', '2022-11-19 04:25:53'),
(24, 114, 5, 1, 'Sigrid', 'Lindgren', '(951) 460-6134', 'manuela.williamson@example.com', 'Bayer LLC', '2022-10-13 19:11:55', '2022-11-19 04:25:53'),
(44, 144, 53, 1, 'Permissions', 'Permissions', '(456) 456-4546', 'Permissions@Permissions.com', 'Colonel By Secondary School', '2022-11-19 04:27:23', '2022-11-19 04:40:43'),
(45, 151, 51, 1, 'Local Admin 1', 'Local Admin 1', '(546) 456-4564', 'localadmin001@promplanner.com', 'Digitera School of Digital Marketing & Software', '2022-12-02 00:58:48', '2022-12-10 22:26:44'),
(49, 185, 53, 1, 'RoleUser Perms', 'RoleUser Perms', '(556) 466-4645', 'RoleUserPerms@RoleUserPerms.com', 'Colonel By Secondary School', '2023-01-07 23:45:06', '2023-01-07 23:45:06'),
(51, 192, 53, 1, 'PendingTest', 'PendingTest', '(778) 979-7979', 'PendingTest@PendingTest.com', 'Colonel By Secondary School', '2023-01-10 23:54:52', '2023-01-10 23:55:32'),
(52, 193, 53, 1, 'Local admin Import 1', 'efwefwef', '12345678910', 'import111@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(53, 194, 53, 1, 'Local admin Import 2', 'wefwef', '9632587459', 'import222@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(54, 195, 53, 1, 'Local admin Import 3', 'wefwef', '3698745236', 'import333@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53');

--
-- Triggers `localadmins`
--
DELIMITER $$
CREATE TRIGGER `delete_localadmin` BEFORE DELETE ON `localadmins` FOR EACH ROW DELETE FROM users WHERE id = old.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2015_04_12_000000_create_orchid_users_table', 1),
(4, '2015_10_19_214424_create_orchid_roles_table', 1),
(5, '2015_10_19_214425_create_orchid_role_users_table', 1),
(6, '2016_08_07_125128_create_orchid_attachmentstable_table', 1),
(7, '2017_09_17_125801_create_notifications_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2022_10_09_174931_create_seating_table', 1),
(11, '2022_10_09_175646_create_students_table', 1),
(12, '2022_10_09_180046_create_schools_table', 1),
(13, '2022_10_09_180611_create_sessions_table', 1),
(14, '2022_10_09_182548_create_music_table', 1),
(15, '2022_10_09_183020_create_localadmin_table', 1),
(16, '2022_10_09_184840_create_events_table', 1),
(17, '2022_10_09_202900_create_food_table', 1),
(18, '2022_10_09_203255_create_invitation_table', 1),
(19, '2022_10_09_204620_create_announcments_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requester` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artist` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remix` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Greater Toronto Area', NULL, NULL),
(2, 'Test Region', NULL, NULL),
(7, 'Greater Toronto Region', '2023-01-13 01:35:11', '2023-01-13 01:35:11'),
(8, 'Region 1', '2023-01-13 02:24:36', '2023-01-13 02:24:36'),
(9, 'Region 2', '2023-01-13 02:24:36', '2023-01-13 02:24:36'),
(10, 'Region 3', '2023-01-13 02:24:36', '2023-01-13 02:24:36'),
(11, 'Region 4', '2023-01-13 02:24:36', '2023-01-13 02:24:36'),
(12, 'Region 5', '2023-01-13 02:24:36', '2023-01-13 02:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Super Admin', '{\"platform.systems.roles\":true,\"platform.systems.users\":true,\"platform.systems.attachment\":true,\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22'),
(2, 'Local Admin', 'Local Admin', '{\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22'),
(3, 'Student', 'Student', '{\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22'),
(4, 'Vendor', 'Vendor', '{\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22'),
(5, 'Teacher', 'Teacher', '{\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_users`
--

CREATE TABLE `role_users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_users`
--

INSERT INTO `role_users` (`user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(13, 1, NULL, NULL),
(52, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(67, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(68, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(72, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(73, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(108, 2, '2023-01-07 00:48:19', '2023-01-07 00:48:19'),
(114, 2, '2023-01-07 00:48:19', '2023-01-07 00:48:19'),
(124, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(125, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(132, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(134, 5, '2023-01-07 00:50:45', '2023-01-07 00:50:45'),
(140, 5, '2023-01-07 00:50:45', '2023-01-07 00:50:45'),
(144, 2, '2023-01-07 00:48:19', '2023-01-07 00:48:19'),
(145, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(146, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(151, 2, '2023-01-07 00:48:19', '2023-01-07 00:48:19'),
(152, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(154, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(155, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(169, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(170, 3, '2023-01-07 00:49:36', '2023-01-07 00:49:36'),
(181, 4, '2023-01-07 00:50:25', '2023-01-07 00:50:25'),
(185, 2, '2023-01-07 23:45:06', '2023-01-07 23:45:06'),
(188, 4, '2023-01-09 04:14:52', '2023-01-09 04:14:52'),
(190, 4, '2023-01-09 04:18:15', '2023-01-09 04:18:15'),
(192, 2, '2023-01-10 23:55:32', '2023-01-10 23:55:32'),
(193, 2, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(194, 2, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(195, 2, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(196, 4, '2023-01-17 01:10:39', '2023-01-17 01:10:39'),
(197, 4, '2023-01-21 02:42:36', '2023-01-21 02:42:36'),
(198, 3, '2023-02-14 23:43:02', '2023-02-14 23:43:02'),
(200, 4, '2023-02-16 23:50:12', '2023-02-16 23:50:12'),
(201, 4, '2023-02-16 23:50:12', '2023-02-16 23:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nces_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_municipality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metropolitan_region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `county` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_board` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_students` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `nces_id`, `teacher_id`, `region_id`, `school_name`, `country`, `city_municipality`, `metropolitan_region`, `state_province`, `county`, `school_board`, `address`, `zip_postal`, `phone_number`, `fax`, `website`, `school_data`, `total_students`, `created_at`, `updated_at`) VALUES
(1, NULL, 108, 1, 'Hansen, Morissette and Lemke', 'Germany', NULL, NULL, 'Oklahoma', 'Carleton County', 'OCDSB', '615 Candelario Radial Apt. 441Rolandoview, KS 27688', 'K1J 7N4', '(183) 098-8514', '3413923', NULL, NULL, NULL, '2022-10-16 21:13:36', '2022-10-25 22:02:27'),
(2, NULL, 108, 10, 'Stracke, Gleason and Kertzmann', 'South Georgia and the South Sandwich Islands', NULL, NULL, 'Florida', 'Carleton County', 'OCDSB', '728 Eudora Way\nGleasontown, MA 14333-3992', 'K1J 7N4', '+16783196782', '70560378', NULL, NULL, NULL, '2022-10-16 21:13:36', '2022-10-16 21:13:36'),
(3, NULL, 108, 1, 'Torphy-Cole', 'Philippines', NULL, NULL, 'South Carolina', 'Carleton County', 'OCDSB', '3922 Mitchell Ford Apt. 215\nMoiseshaven, PA 29093', 'K1J 7N4', '(539) 767-7757', '92060', NULL, NULL, NULL, '2022-10-16 21:13:36', '2022-10-16 21:13:36'),
(4, NULL, 108, 1, 'Metz, Wiza and Larkin', 'Solomon Islands', NULL, NULL, 'New York', 'Carleton County', 'OCDSB', '32941 Adrianna Oval Suite 689\nCalebstad, MA 76865-1243', 'K1J 7N4', '+1 (781) 916-1916', '954', NULL, NULL, NULL, '2022-10-16 21:13:36', '2022-10-16 21:13:36'),
(5, NULL, 108, 1, 'Bayer LLC', 'Pakistan', 'Toronto', NULL, 'Montana', 'Carleton County', 'MVPVIP', '2042 Mervin Village Apt. 230Sarinashire, NY 53851-5569', 'K1J 7N4', '(143) 284-3874', '7237130', NULL, NULL, '1028', '2022-10-16 21:13:36', '2022-11-27 20:45:35'),
(7, NULL, 108, 1, 'Welch Inc', 'Germany', NULL, NULL, 'Connecticut', 'Carleton County', 'OCDSB', '84619 Daugherty Centers\nLake Douglas, MT 03301', 'K1J 7N4', '1-260-439-8476', '30810077', NULL, NULL, NULL, '2022-10-16 21:13:36', '2022-10-16 21:13:36'),
(11, NULL, 108, 1, 'Cormier, Schaden and Kirlin', 'Iran', NULL, NULL, 'Delaware', 'Carleton County', 'OCDSB', '5668 Tressa Court Suite 148\nWest Jada, NV 04934', 'K1J 7N4', '(440) 450-3291', '5', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(12, NULL, 108, 1, 'Gottlieb-Kuhic', 'India', NULL, NULL, 'West Virginia', 'Carleton County', 'OCDSB', '17666 Auer Wall\nKshlerinhaven, NJ 30242-8206', 'K1J 7N4', '(484) 521-1162', '515', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(13, NULL, 108, 1, 'Trantow, Schuppe and Crist', 'Equatorial Guinea', NULL, NULL, 'Rhode Island', 'Carleton County', 'OCDSB', '265 Nitzsche Meadows\nGoodwinmouth, MT 88992', 'K1J 7N4', '(682) 747-3046', '14611', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(14, NULL, 108, 12, 'Renner-Mitchell', 'Cape Verde', NULL, NULL, 'Alabama', 'Carleton County', 'OCDSB', '6444 Yasmin Locks\nWest Floyd, PA 57742-0863', 'K1J 7N4', '309-846-9100', '99', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(15, NULL, 108, 1, 'Hyatt, Terry and Rice', 'Niue', NULL, NULL, 'Louisiana', 'Carleton County', 'OCDSB', '987 Nicolas Unions\nMargarettborough, DC 52919', 'K1J 7N4', '(760) 802-7707', '626506351', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(16, NULL, 108, 9, 'Waelchi PLC', 'Bahamas', NULL, NULL, 'Texas', 'Carleton County', 'OCDSB', '1299 Jaunita Street\nEast Ettieton, TN 56557', 'K1J 7N4', '1-585-759-8721', '7601', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(17, NULL, 108, 1, 'Purdy, Grimes and Collier', 'Mauritius', NULL, NULL, 'Nebraska', 'Carleton County', 'OCDSB', '780 Bailey Fields\nGottliebport, PA 32939-4568', 'K1J 7N4', '+1-248-403-8298', '898078', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(18, NULL, 108, 1, 'Stokes-Welch', 'Costa Rica', NULL, NULL, 'New Hampshire', 'Carleton County', 'OCDSB', '86451 Domenica Route Suite 494\nNew Luciousstad, TN 15152-4743', 'K1J 7N4', '708-254-9843', '454748', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(19, NULL, 108, 1, 'Corkery Group', 'Tunisia', NULL, NULL, 'West Virginia', 'Carleton County', 'TDSB', '79013 Wehner Summit Apt. 025\nEast Adellburgh, KY 67028-2589', 'K1J 7N4', '508.426.3950', '5', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(20, NULL, 108, 1, 'Swaniawski PLC', 'Lithuania', NULL, NULL, 'Louisiana', 'Carleton County', 'OCDSB', '76789 Wolff Haven\nPaulinemouth, IL 34359-7900', 'K1J 7N4', '+1.678.525.7561', '9279348', NULL, NULL, NULL, '2022-10-16 22:33:16', '2022-10-16 22:33:16'),
(21, NULL, 108, 1, 'White, Schiller and Upton', 'Slovakia (Slovak Republic)', NULL, NULL, 'Arkansas', 'Carleton County', 'OCDSB', '2078 Hermiston Trafficway\nHalvorsonland, NV 41175-6793', 'K1J 7N4', '+1 (725) 255-6311', '682375028', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(22, NULL, 108, 1, 'Hamill Ltd', 'Micronesia', NULL, NULL, 'Colorado', 'Carleton County', 'OCDSB', '26917 Runte Pike\nLiafort, SC 51168', 'K1J 7N4', '234-938-2022', '16', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(23, NULL, 108, 1, 'Mayer-Flatley', 'Canada', NULL, NULL, 'New Hampshire', 'Carleton County', 'OCDSB', '835 Ryann Meadow Suite 366\nTrompshire, HI 79903', 'K1J 7N4', '1-864-954-0032', '15', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(24, NULL, 108, 1, 'Fritsch Inc', 'Brunei Darussalam', NULL, NULL, 'Michigan', 'Carleton County', 'OCDSB', '4051 Hills Field\nJohnstonstad, MT 54271', 'K1J 7N4', '364-756-7851', '13797940', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(25, NULL, 108, 1, 'Kohler-Fahey', 'Saint Vincent and the Grenadines', NULL, NULL, 'Colorado', 'Carleton County', 'OCDSB', '27276 Noel Freeway Suite 224\nEast Lottieshire, IL 57193-3689', 'K1J 7N4', '(267) 407-7167', '675726694', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(27, NULL, 108, 1, 'Rosenbaum-Ward', 'Papua New Guinea', NULL, NULL, 'Rhode Island', 'Carleton County', 'OCDSB', '55005 Miller Passage Apt. 991\nRomagueraport, SC 56295-6289', 'K1J 7N4', '520.477.2083', '9312', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(28, NULL, 108, 1, 'Witting-Leuschke', 'Belize', NULL, NULL, 'Hawaii', 'Carleton County', 'OCDSB', '3275 Medhurst Mission\nDickinsonshire, OH 65092-7777', 'K1J 7N4', '+13416954586', '450', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(29, NULL, 108, 1, 'Hilpert, Graham and Simonis', 'Heard Island and McDonald Islands', NULL, NULL, 'District of Columbia', 'Carleton County', 'OCDSB', '3742 Riley Village\nMontanaburgh, AK 81250', 'K1J 7N4', '715.203.8868', '73963078', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(30, NULL, 108, 1, 'McKenzie-Gerlach', 'Haiti', NULL, NULL, 'Vermont', 'Carleton County', 'OCDSB', '29770 DuBuque Landing\nPort Willard, GA 20902-2532', 'K1J 7N4', '1-361-763-9845', '177408588', NULL, NULL, NULL, '2022-10-16 23:28:14', '2022-10-16 23:28:14'),
(31, NULL, 108, 1, 'Fritsch and Sons', 'Somalia', NULL, NULL, 'Wisconsin', 'Carleton County', 'OCDSB', '23811 Elenor Ferry Apt. 924\nPort Martachester, WA 93256-0211', 'K1J 7N4', '+18204179980', '225610041', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(32, NULL, 108, 12, 'Cool School', 'Comoros', NULL, NULL, 'New York', 'Carleton County', 'OCDSB', '33989 Okey RouteUbaldoburgh, HI 83577', 'K1J 7N4', '(178) 599-3091', '59', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-11-04 22:44:25'),
(35, NULL, 108, 1, 'Kihn-Hoppe', 'Myanmar', NULL, NULL, 'Michigan', 'Carleton County', 'OCDSB', '78705 Elaina Plains\nOsinskitown, CO 43978-0728', 'K1J 7N4', '+1 (682) 333-0594', '590', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(36, NULL, 108, 1, 'Daniel, Hauck and Hammes', 'Bouvet Island (Bouvetoya)', NULL, NULL, 'Delaware', 'Carleton County', 'OCDSB', '8532 Drake Estate\nLake Emory, VT 95844-5519', 'K1J 7N4', '+1-445-664-3082', '2018', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(37, NULL, 108, 1, 'Emard, Nolan and McCullough', 'United States Virgin Islands', NULL, NULL, 'Nevada', 'Carleton County', 'OCDSB', '901 Jacobi Path\nNew Noemyland, TN 90570-6445', 'K1J 7N4', '(435) 758-3192', '3', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(38, NULL, 108, 1, 'Lakin, Auer and Aufderhar', 'Namibia', NULL, NULL, 'Ohio', 'Carleton County', 'OCDSB', '5866 Hahn Knolls Suite 419\nJohnnieville, MN 78694', 'K1J 7N4', '+1-907-531-0592', '305201', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(39, NULL, 108, 1, 'Frami and Sons', 'French Polynesia', NULL, NULL, 'Mississippi', 'Carleton County', 'OCDSB', '3501 Cormier Common Suite 508\nCarterland, AR 20047', 'K1J 7N4', '+1.352.368.6148', '85', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(40, NULL, 108, 1, 'Herman LLC', 'Western Sahara', NULL, NULL, 'South Dakota', 'Carleton County', 'OCDSB', '97484 Bartell Locks Apt. 277\nEast Vitoborough, GA 21690-9245', 'K1J 7N4', '1-412-545-4718', '720', NULL, NULL, NULL, '2022-10-17 20:39:55', '2022-10-17 20:39:55'),
(41, NULL, 73, 1, 'Johns-Daugherty', 'USA', 'Imagine Island', NULL, 'Hawaii', 'Carleton County', 'OCDSB', '16788 Homenick Extensions Suite 511Williamsonmouth, TX 34874', 'K1J 7N4', '(166) 750-0439', '9913', NULL, NULL, '1028', '2022-10-17 20:40:39', '2022-11-09 04:02:47'),
(42, NULL, 108, 2, 'Hintz, Hudson and Gutkowski', 'Cameroon', 'GTA', NULL, 'Arizona', 'Carleton County', 'OCDSB', '361 Champlin StreetsSouth Jaunitaland, ME 16227-5190', 'K1J 7N4', '(121) 788-3598', '6426814', NULL, NULL, '213', '2022-10-17 20:40:39', '2022-12-24 01:35:02'),
(43, NULL, 108, 1, 'DuBuque-Macejkovic', 'Saint Lucia', NULL, NULL, 'South Carolina', 'Carleton County', 'OCDSB', '509 Glenna MotorwayPort Kaylah, NH 82633-1366', 'K1J 7N4', '(194) 945-8859', '889', NULL, NULL, '12', '2022-10-17 20:40:39', '2022-11-09 04:03:25'),
(44, NULL, 108, 1, 'Johnston Ltd', 'California', NULL, NULL, 'Idaho', 'Carleton County', 'OCDSB', '494 Cummerata Forest Suite 548Baumbachmouth, NC 50972-6709', 'K1J 7N4', '(143) 444-4937', '31', NULL, NULL, '1258', '2022-10-17 20:40:39', '2022-11-09 04:06:26'),
(45, NULL, 108, 1, 'Graham, Deckow and Skiles', 'San Francisco', NULL, NULL, 'New York', 'Carleton County', 'OCDSB', '184 Torrey Station Suite 566Kemmerberg, MN 16474', 'K1J 7N4', '(479) 960-3988', '936', NULL, NULL, '1478', '2022-10-17 20:40:39', '2022-11-09 04:09:30'),
(46, NULL, 108, 1, 'Jaskolski-Schiller', 'American Samoa', NULL, NULL, 'North Dakota', 'Carleton County', 'OCDSB', '17827 Williamson Junctions Apt. 801\nNew Deondre, LA 24437-3750', 'K1J 7N4', '+1 (517) 951-5106', '222014', NULL, NULL, NULL, '2022-10-17 20:40:39', '2022-10-17 20:40:39'),
(47, NULL, 108, 1, 'Sporer, Langworth and Goyette', 'Wallis and Futuna', NULL, NULL, 'Wyoming', 'Carleton County', 'OCDSB', '4887 Koepp Vista Apt. 203\nLake Lillian, SC 87700-0777', 'K1J 7N4', '239-243-7080', '88', NULL, NULL, NULL, '2022-10-17 20:40:39', '2022-10-17 20:40:39'),
(51, NULL, 108, 1, 'Digitera School of Digital Marketing & Software', 'Canada', 'Ottawa', NULL, 'Ontario', 'Carleton County', 'MVPVIP', '1125 Colonel By Dr Rm 102', 'K1S 5B6', '(613) 801-2900', '456789123', NULL, NULL, '5462', '2022-10-25 22:35:56', '2022-11-09 04:01:23'),
(53, NULL, 108, 1, 'Colonel By Secondary School', 'Canada', 'Ottawa', 'Greater Ottawa Metropolitan Area', 'Ontario', 'Carleton County', 'OCDSB', '2381 Ogilvie Rd, Gloucester', 'K1J 7N4', '(613) 745-9411', NULL, 'http://colonelby.com/', 'http://colonelby.com/', '1110', '2022-11-09 03:07:47', '2022-11-09 04:00:41'),
(58, '456564564', 140, 1, 'Bayer LLC', 'ertter', 'Ottawa', 'dfgdfg', 'Ontario', 'Los Angeles County', 'OCDSB', 'fdg', 'dfgd', '(546) 446-4665', '54654', 'fgd', 'gdfgdf', '1025', '2022-11-14 23:12:55', '2022-11-14 23:12:55'),
(65, '250861001374', NULL, 2, 'Newton North High', 'USA', 'Newtonville', 'Boston', 'MA', 'Middlesex', 'Newton School District', '457 Walnut Street', '02460', '(617) 559-5200', '6175595204', 'https://www.newton.k12.ma.us/nnhs', 'https://nces.ed.gov/ccd/schoolsearch/school_detail.asp?Search=1&InstName=newton&State=25&SchoolType=1&SchoolType=2&SchoolType=3&SchoolType=4&SpecificSchlTypes=all&IncGrade=-1&LoGrade=-1&HiGrade=-1&ID=250861001374', '2073', '2022-12-12 00:55:19', '2022-12-24 01:34:45'),
(66, '', NULL, 1, 'Newton Central High', 'USA', 'Newtonville', 'Boston', 'MA', 'Middlesex', 'Newton School District', '100 Walnut Street-Annex A', '02460', '(617) 559-6016', NULL, 'https://www.newton.k12.ma.us/central', NULL, NULL, '2022-12-12 00:55:19', '2022-12-12 01:00:54'),
(67, '250861001375', NULL, 1, 'Newton South High', 'USA', 'Newton Centre', 'Boston', 'MA', 'Middlesex', 'Newton School District', '140 Brandeis Rd', '02459', '(617) 559-6700', NULL, 'https://www.newton.k12.ma.us/nshs', 'https://nces.ed.gov/ccd/schoolsearch/school_detail.asp?Search=1&DistrictID=2508610&SchoolPageNum=2&ID=250861001375', '1869', '2022-12-12 00:55:19', '2022-12-12 01:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `seatings`
--

CREATE TABLE `seatings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seatings`
--

INSERT INTO `seatings` (`id`, `event_id`, `tablename`, `created_at`, `updated_at`) VALUES
(1, 14, 'The DJ\'s Table', '2023-02-22 18:22:32', NULL),
(2, 14, 'Cool Kidz Table', '2023-02-22 19:38:37', NULL),
(3, 14, 'Testing adding table from local admin', '2023-02-23 00:58:53', '2023-02-23 00:58:53'),
(4, 14, 'This is a edited table', '2023-02-23 01:09:46', '2023-02-23 01:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade` smallint(2) DEFAULT NULL,
  `phonenumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_status` int(1) DEFAULT 0,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allergies` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `school_id`, `firstname`, `lastname`, `grade`, `phonenumber`, `email`, `account_status`, `school`, `allergies`, `created_at`, `updated_at`) VALUES
(105, 145, 53, 'John', 'Smith', 12, '(465) 987-9797', 'johnsmith@gmail.com', 1, 'Colonel By Secondary School', NULL, '2022-11-20 11:14:59', '2022-11-22 19:30:57'),
(106, 146, 51, 'Jane', 'Doe', 10, '(456) 879-4564', 'janedoe@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Peanuts', '2022-11-20 11:23:42', '2022-11-26 22:17:01'),
(107, 152, 51, 'Hey', 'Man', 12, '(546) 465-6464', 'heyman@heyman.com', 1, 'Digitera School of Digital Marketing & Software', 'Hey Man', '2022-12-02 01:04:53', '2022-12-04 21:44:41'),
(109, 154, 51, 'retert', 'ert', 9, '(546) 464-6465', 'loca65+ladmin001@promplanner.com', 1, 'Digitera School of Digital Marketing & Software', '55', '2022-12-02 01:13:35', '2022-12-02 01:14:41'),
(110, 155, 51, 'Student 1', 'Student 1', 12, '(546) 897-8921', 'student001@promplanner.com', 1, 'Digitera School of Digital Marketing & Software', 'Peanuts', '2022-12-05 18:58:15', '2022-12-05 19:00:01'),
(121, 169, 51, 'Import 1', 'efwefwef', 9, '12345678910', 'import1@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Nuts', '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(122, 170, 51, 'Import 2', 'wefwef', 10, '9632587459', 'import2@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Nutseeee', '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(124, 198, 51, 'Zg man', 'Big man tings', 10, '(612) 354-8954', 'bigman@tings.com', 1, NULL, 'Bad Grades', '2023-02-14 23:43:02', '2023-02-15 01:25:38');

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `delete_students` BEFORE DELETE ON `students` FOR EACH ROW DELETE FROM users WHERE id = old.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student_bids`
--

CREATE TABLE `student_bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `notes` longtext DEFAULT NULL,
  `contact_instructions` longtext DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `url` longtext DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_bids`
--

INSERT INTO `student_bids` (`id`, `user_id`, `student_id`, `region_id`, `package_id`, `category_id`, `school_name`, `notes`, `contact_instructions`, `company_name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 197, 110, 1, 6, 11, 'Digitera School of Digital Marketing & Software', 'Notes', 'Contact Instructions', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-02 19:14:07', '2023-02-02 19:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(10) UNSIGNED DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currentPlan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_status` int(1) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `firstname`, `lastname`, `email`, `phonenumber`, `role`, `country`, `currentPlan`, `account_status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(13, 'Big Man Admin 🔥', '', '', 'superadmin@gmail.com', NULL, 1, NULL, NULL, 1, NULL, '$2y$10$kShmCgweW1ieZg4S6Lf.dOwDT0xhVN9Gb62l8doUSo56qcsWoR9Ee', 'gOhWehg41IUMFqjIFSVd4rqL9d7ZZfVXREdEzlMCBmNtWao4vz2fV3TjUxlL', '2022-10-16 21:27:25', '2022-11-21 20:58:32'),
(52, 'Kavon Reinger', 'This is another test for the user', 'test', 'test@example.net', NULL, 3, 'Zimbabwe', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'xekMstwSjQ', '2022-10-17 20:39:55', '2022-10-18 00:15:02'),
(67, 'Prof. Clare Turcotte', 'Ellis', 'Huels', 'williamson@example.net', NULL, 3, 'Germany', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'EFxEkuvIMI', '2022-10-17 20:39:55', '2022-11-13 01:28:54'),
(68, 'Jude Nicolas', 'Gabriel', 'Prosacco', 'koss.gerald@example.com', NULL, 3, 'Philippines', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mOY699FaIx', '2022-10-17 20:39:55', '2022-11-15 00:18:28'),
(72, 'Alfonzo Conn', 'Kyra', 'Schneider', 'ndietrich@example.org', NULL, 3, 'Canada', NULL, 0, '2022-10-17 20:40:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sZVhTrQr0D', '2022-10-17 20:40:39', '2022-11-15 00:34:27'),
(73, 'Mrs. Lauren Rau', 'Anais', 'Waelchi', 'claire39@example.com', NULL, 3, 'Germany', NULL, 0, '2022-10-17 20:40:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LwRVkEPdFT', '2022-10-17 20:40:39', '2022-11-02 21:38:59'),
(108, 'Kade Abbott', 'Camille', 'Williams', 'camillewilliams@gmail.com', '+1-872-648-9121', 2, 'Canada', NULL, 1, '2022-10-19 21:08:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'C5iVHaFVKr', '2022-10-19 21:08:55', '2022-11-19 04:25:53'),
(114, 'Maxie Boyer', 'Sigrid', 'Lindgren', 'manuela.williamson@example.com', '951.460.6134', 2, 'Pakistan', NULL, 1, '2022-10-19 21:09:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jxguMsVoxm', '2022-10-19 21:09:00', '2022-11-19 04:25:53'),
(124, 'coolkid', 'Cool', 'Kid', 'coolkid@coolkid.com', '45689645', 3, 'Comoros', NULL, 0, NULL, '', NULL, '2022-10-26 22:55:10', '2022-11-09 00:22:29'),
(125, 'New Student', 'Sanjay', 'Patel', 'sanjaypatel@gmail.com', '45678123', 3, 'Canada', NULL, 0, NULL, '', NULL, NULL, '2022-11-09 03:37:18'),
(132, 'Testing add student', 'John', 'Connors', 'johnconnors@gmail.com', '(564) 645-5646', 3, 'Canada', NULL, 0, NULL, '$2y$10$glFxuDOe8HbDTv41xXlRyOtbOZrtu7EtIEjv/Ul/Pv7M95cDOSJZS', NULL, '2022-11-04 23:28:42', '2022-11-15 00:57:04'),
(134, NULL, 'Test', 'Test', 'hi@hii.com', '(456) 564-654_', 5, 'Test', NULL, 0, NULL, NULL, NULL, '2022-11-10 23:38:37', '2022-11-10 23:38:37'),
(140, 'dfgdfggdfgdfg', 'dfgdfg', 'gdfgdfg', 'dfgddgf@jkjhjk.com', '(554) 654-5464', 5, 'ertter', NULL, 0, NULL, NULL, NULL, '2022-11-14 23:12:55', '2022-11-14 23:12:55'),
(144, 'Permissions', 'Permissions', 'Permissions', 'Permissions@Permissions.com', '(456) 456-4546', 2, 'Canada', NULL, 1, NULL, '$2y$10$/jt9btmMXXBr15i6c0ClKO/73wbbKMzqzB/2pFyQojQ618PM7ZesG', '2HSxoF6o2OGRx8MZjoAIlCoZzGWJzcZ4lh97iNgEMv9FBl3scogjDDzb3kWz', '2022-11-19 04:27:23', '2022-11-19 04:40:43'),
(145, 'Big Man John', 'John', 'Smith', 'johnsmith@gmail.com', '(465) 987-9797', 3, 'Canada', NULL, 1, NULL, '$2y$10$9ZXaWr1D1gJZ5LUk88KWUuJccYYU9rx9OvjDaVIKtdE/dapiLl0J.', NULL, '2022-11-20 11:14:59', '2022-11-22 19:30:57'),
(146, 'Jane Doe', 'Jane', 'Doe', 'janedoe@gmail.com', '(456) 879-4564', 3, 'Canada', NULL, 1, NULL, '$2y$10$PwXqJuw5RkpNkuWF38Wuquldp2SwT0kBBogY6QgYKRkDQzeNyxhQG', NULL, '2022-11-20 11:23:42', '2022-11-26 22:17:01'),
(148, 'Super Admin 1', 'Admin001', 'Admin001', 'admin001@promplanner.com', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$l7WjvuqK6ZCPGmLKLFy0y.287OiRMarJO6UzjDJlv1LIi57oouvl2', 'v5MeVDCjb01jQacTOWLm4xjN85KKqmNUgV3gNYBHuqmqsXtMebtldhExOI7J', NULL, NULL),
(149, 'Super Admin 2', 'Admin002', 'Admin002', 'admin002@promplanner.com', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$ul5yg6bZ47cb4ObQpFy3fO0MR6WjyMts7D6hEkU6ukKFMPPE0gAuu', 'dhiYncQ7UYPaA4x88h4k6HUYP4JPc91YuJcSODgbZbGvUs1kA1SUZxmi7vIh', NULL, NULL),
(150, 'Super Admin 3', 'Admin003', 'Admin003', '		\r\nadmin003@promplanner.com\r\n', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$OSjHjhsYws3ep7ces2HtCOGu/Q62Ki6ud8Zrk2BFG6RTSiCnYBKmS', 'lhf0VN0la6IXJ0JfPPqomBG8cpxodRAxARKMhQbkUybsz2oOpvNY7JwBvslc', NULL, NULL),
(151, 'Local Admin 1', 'Local Admin 1', 'Local Admin 1', 'localadmin001@promplanner.com', '(546) 456-4564', 2, 'Canada', NULL, 1, NULL, '$2y$10$nE2mIZ/TMlBq7SC6m/yPnetNlGmODb4GSy3MrIpFf9zRDhWocqCOG', 'zGXSOzjIcykVhLR3rojys7GCPWiNEQc366R4Qn3UBN5aryfNeGjU6KrhyovA', '2022-12-02 00:58:48', '2022-12-10 22:26:44'),
(152, 'heyman', 'Hey', 'Man', 'heyman@heyman.com', '(546) 465-6464', 3, 'Canada', NULL, 1, NULL, '$2y$10$D4sD55GOTHr6hIrxQsG7L.rsn6uwLgUFCC0yXs.m0Fbk33WzyItxC', NULL, '2022-12-02 01:04:53', '2022-12-04 21:44:41'),
(154, 'localadmin001@promplanner.com', 'retert', 'ert', 'loca65+ladmin001@promplanner.com', '(546) 464-6465', 3, 'Canada', NULL, 1, NULL, '$2y$10$OXW.OPZd1NBpH0fTrqoQjOnxs9V07Rbn.H47yBrg.bcvDYp.0ZH4O', NULL, '2022-12-02 01:13:35', '2022-12-02 01:14:41'),
(155, 'Student 1', 'Student 1', 'Student 1', 'student001@promplanner.com', '(546) 897-8921', 3, 'Canada', NULL, 1, NULL, '$2y$10$N5f8aqLb2QVc09MgKc1hSuxlyWJeb522cBHpSHE2vb15WjZi5VAUa', '1FjMSKCZ0D0PgElUurhxQq3HwdG8cixHuXMn79vBwB6yvjrFUZZSuh581plR', '2022-12-05 18:58:15', '2022-12-05 19:00:01'),
(169, 'Import 1', 'Import 1', 'efwefwef', 'import1@gmail.com', '12345678910', 3, 'Canada', NULL, 1, NULL, '$2y$10$.Tzvl/8uuCeVHLxilSrk9ewJmOAvnVJH5c1L4PD2JocHkVTxg63xe', NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(170, 'Import 2', 'Import 2', 'wefwef', 'import2@gmail.com', '9632587459', 3, 'Canada', NULL, 1, NULL, '$2y$10$zDlcJegCYOuBoqkeQpKKieBlT8I5nmcpxgzOsjBCqeccgYOwqJPNi', NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(181, 'sdfsdfsdf', 'Update Vendor', 'Update Vendor', 'UpdateVendor@hotmail.com', '(455) 674-9877', 4, 'Costa Rica', NULL, 1, NULL, '$2y$10$DYa3I4sOLm31EwAPq0xzbesh8Mp2RYSlw8bbZoJawiKypoAUfASqm', NULL, '2022-12-26 02:01:31', '2023-01-09 04:21:18'),
(185, 'RoleUser Perms', 'RoleUser Perms', 'RoleUser Perms', 'RoleUserPerms@RoleUserPerms.com', '(556) 466-4645', 2, 'Canada', NULL, 1, NULL, '$2y$10$iKr.VLPa.6bID.dCmNHuVuJZ1YESN0Nl6wKrYahO2nlbXtxoJBoNW', '21lrVpmJjszaOvkwJQGHQuB19ZfXYqe7MN9HKAGg9T91RX4mJRNaWmSu1KBZ', '2023-01-07 23:45:06', '2023-01-07 23:45:06'),
(188, 'Joe ', 'Joe ', 'Biden', 'bidenjoe@isuck.com', 'dssdddsddss', 4, 'USA', NULL, 1, NULL, '$2y$10$SUrjCNKKrpkznsPR4RPUmOPGPZnHT9uWvFarPqtzxlYKUCDn8n46i', NULL, '2023-01-09 04:14:52', '2023-01-09 04:14:52'),
(190, 'Donald ', 'Donald ', 'Trump', 'trumpman@maga.com', 'dssdddsddss', 4, 'USA', NULL, 1, NULL, '$2y$10$cqjoswklZ4erE4sZu5eE6OpdlfN7yQSTG9bCRmZtFIcLGw1MwGaOC', NULL, '2023-01-09 04:18:15', '2023-01-09 04:18:15'),
(192, 'PendingTest', 'PendingTest', 'PendingTest', 'PendingTest@PendingTest.com', '(778) 979-7979', 2, 'Canada', NULL, 1, NULL, '$2y$10$0DWsyZHm9E7ctCLAC2/2J..f6fPNGB1MsZc.D/H2Gz4lP7tR7rmOO', 'xSFMpXUv8nzIK8K5hiccHK9KtSGgRgkDnHC2fSVPIYEiFuCQFzZ957zfxcAi', '2023-01-10 23:54:52', '2023-01-10 23:55:32'),
(193, 'Local admin Import 1', 'Local admin Import 1', 'efwefwef', 'import111@gmail.com', '12345678910', 2, 'Canada', NULL, 1, NULL, '$2y$10$fBB56NJVIq2jzE5bUG4fPOSFcAEzFgs6WXy23DfxaoZfMuKdcFIIa', NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(194, 'Local admin Import 2', 'Local admin Import 2', 'wefwef', 'import222@gmail.com', '9632587459', 2, 'Canada', NULL, 1, NULL, '$2y$10$mlixKkSmH03ulCZn8K49ke4y1Gmbolu2WFfansuifJp2QvOmb3WiW', NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(195, 'Local admin Import 3', 'Local admin Import 3', 'wefwef', 'import333@gmail.com', '3698745236', 2, 'Canada', NULL, 1, NULL, '$2y$10$N4kQN9DKvJ1b87ycpA2aO.Gp55U3JV0.MyCO.0Er7wpsyuINGd1B2', NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(196, 'Ling Long', 'Trump Man', 'Ling Long', 'donaldtrump@trump.com', '(454) 546-4566', 4, 'USA', NULL, 1, NULL, '$2y$10$/0C9DuazC.JKb6Zh/uKhZuGPnjsKNvjsFzxjEDvuYdV4uN77dt4TO', NULL, '2023-01-17 01:10:39', '2023-01-17 01:10:39'),
(197, 'Vendor001', 'Vendor001', 'Vendor001', 'vendor001@promplanner.com', '(454) 654-6546', 4, 'Canada', NULL, 1, NULL, '$2y$10$RCkk.xuRaueua/7bkthq7OJjLnwmjfPPMYbuI06Xckubita5l0LrW', 'YA4Ae9U7MVlrfhIoK25qIb4WtyIJpOMB6ZPdbSnF8tqCUYalPZPu38axzXlM', '2023-01-21 02:41:46', '2023-01-21 02:42:36'),
(198, 'bigman101', 'Zg man', 'Big man tings', 'bigman@tings.com', '(612) 354-8954', 3, 'Canada', NULL, 1, NULL, '$2y$10$B10Kj5SYsPn6EKwkyQRbPO7UmY/YqPWkzzzyiU0dZ3DWx.xLRnJpy', NULL, '2023-02-14 23:43:02', '2023-02-15 01:25:38'),
(199, NULL, 'Imran', 'Khan', 'imranbadrun@hotmail.com', '(613) 404-6926', NULL, 'Canada', NULL, 0, NULL, NULL, NULL, '2023-02-16 23:41:34', '2023-02-16 23:41:34'),
(200, NULL, 'Imran', 'Khan', 'imranbaterrtedrun@hotmail.com', '(613) 404-6926', NULL, 'Canada', NULL, 1, NULL, NULL, NULL, '2023-02-16 23:44:11', '2023-02-16 23:50:12'),
(201, NULL, 'Imran', 'Khan', 'imranbaterrtasdedrun@hotmail.com', '(613) 404-6926', NULL, 'Canada', NULL, 1, NULL, NULL, NULL, '2023-02-16 23:44:37', '2023-02-16 23:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_status` int(1) NOT NULL DEFAULT 0,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `category_id`, `account_status`, `company_name`, `address`, `city`, `state_province`, `country`, `zip_postal`, `phonenumber`, `website`, `email`, `created_at`, `updated_at`) VALUES
(2, 181, 13, 1, 'Update Vendor', 'Update Vendor', 'Ottawa', 'Ontario', 'Costa Rica', 'K1K 2G6', '(455) 674-9877', 'https://laravel.com/docs/9.x/requests#retrieving-a-portion-of-the-input-data', 'UpdateVendor@hotmail.com', '2022-12-26 02:01:31', '2023-01-09 04:21:18'),
(6, 188, 9, 1, 'Bidenn Corporatione', '4735 lol street', 'nah', 'nah', 'USA', '2353', 'dssdddsddss', 'https://www.youtube.com/', 'bidenjoe@isuck.com', '2023-01-09 04:14:52', '2023-01-09 04:14:52'),
(8, 190, 10, 1, 'Trump Party', '87 Dolla Dolla Bills', 'Money Land', 'fsdfs', 'USA', 'sdfsdf', 'dssdddsddss', 'https://www.youtube.com/', 'trumpman@maga.com', '2023-01-09 04:18:15', '2023-01-09 04:18:15'),
(9, 196, 9, 1, 'Trump Venue Industries', '123 MAGA', 'New York', 'Oklahoma', 'USA', '4567', '(454) 546-4566', 'https://www.donaldjtrump.com/', 'donaldtrump@trump.com', '2023-01-17 01:10:39', '2023-01-17 01:10:39'),
(10, 197, 11, 1, 'Money Jockeys', '420 Money Lane', 'Ottawa', 'Ontario', 'Canada', 'LOL 8HA', '(454) 654-6546', 'https://promplanner.app/', 'vendor001@promplanner.com', '2023-01-21 02:41:46', '2023-01-21 02:42:36'),
(11, 200, 9, 1, 'NEXGRILL', '580 Brunel Street', 'Ottawa', 'Ontario', 'Canada', 'K1K 2G6', '(613) 404-6926', 'https://www.ciena.com/', 'imranbaterrtedrun@hotmail.com', '2023-02-16 23:44:11', '2023-02-16 23:50:12'),
(12, 201, 10, 1, 'sdadasd', '580 Brunel Street', 'Ottawa', 'Ontario', 'Canada', 'K1K 2G6', '(613) 404-6926', 'https://amazon.com', 'imranbaterrtasdedrun@hotmail.com', '2023-02-16 23:44:37', '2023-02-16 23:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_packages`
--

CREATE TABLE `vendor_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `price` decimal(10,0) UNSIGNED NOT NULL,
  `url` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendor_packages`
--

INSERT INTO `vendor_packages` (`id`, `user_id`, `package_name`, `description`, `price`, `url`, `created_at`, `updated_at`) VALUES
(1, 197, 'Executive Venue', 'Best of the best venue!', '99999999', 'https://promplanner.app/', '2023-01-26 18:17:56', '2023-01-27 00:21:31'),
(2, 197, 'Laid Back Venue', 'Venue for your laid back needs!', '199', 'https://promvendors.com/', '2023-01-26 18:17:56', NULL),
(3, 197, 'LIFETIME VENDOR LICENSE', 'Purchasing this License gives the Vendor unrestricted LIFETIME access to the Vendor portal of Prom Planner for one region and one business category ONLY. If multiple regions are needed, then a separate license for each region must be purchased.', '2990', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-01-26 23:19:21', '2023-01-26 23:19:21'),
(6, 197, 'Lux Tuxs', 'Luxury Tuxedos for the prom king!', '250', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-02-03 00:13:56', '2023-02-03 00:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_paid_regions`
--

CREATE TABLE `vendor_paid_regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vendor_paid_regions`
--

INSERT INTO `vendor_paid_regions` (`id`, `user_id`, `region_id`, `created_at`, `updated_at`) VALUES
(1, 197, 1, '2023-01-31 05:00:00', NULL),
(3, 197, 9, '2023-02-14 21:19:16', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcments`
--
ALTER TABLE `announcments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcments_sender_foreign` (`sender`);

--
-- Indexes for table `attachmentable`
--
ALTER TABLE `attachmentable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachmentable_attachmentable_type_attachmentable_id_index` (`attachmentable_type`,`attachmentable_id`),
  ADD KEY `attachmentable_attachment_id_foreign` (`attachment_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_index` (`catagory_id`),
  ADD KEY `region_index` (`region_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_school_index` (`school`),
  ADD KEY `event_creator` (`event_creator`),
  ADD KEY `event_creator_2` (`event_creator`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `school` (`school`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`,`table_id`),
  ADD KEY `event_attendees_ibfk_3` (`table_id`);

--
-- Indexes for table `event_bids`
--
ALTER TABLE `event_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `package_id` (`package_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_event_id_foreign` (`event_id`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_recipient_foreign` (`recipient`),
  ADD KEY `invitation_inviter_foreign` (`inviter`),
  ADD KEY `invitation_event_foreign` (`event`);

--
-- Indexes for table `localadmins`
--
ALTER TABLE `localadmins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `localadmin_user_id_foreign` (`user_id`),
  ADD KEY `localadmin_school_index` (`school`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`id`),
  ADD KEY `music_requester_foreign` (`requester`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_users_role_id_foreign` (`role_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schools_school_name_index` (`school_name`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `region_index` (`region_id`);

--
-- Indexes for table `seatings`
--
ALTER TABLE `seatings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `school` (`school`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `student_bids`
--
ALTER TABLE `student_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`student_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `package_id` (`package_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `catagory_id` (`category_id`);

--
-- Indexes for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vendor_paid_regions`
--
ALTER TABLE `vendor_paid_regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `region_id` (`region_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcments`
--
ALTER TABLE `announcments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachmentable`
--
ALTER TABLE `attachmentable`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `event_attendees`
--
ALTER TABLE `event_attendees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_bids`
--
ALTER TABLE `event_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `localadmins`
--
ALTER TABLE `localadmins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `seatings`
--
ALTER TABLE `seatings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `student_bids`
--
ALTER TABLE `student_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor_paid_regions`
--
ALTER TABLE `vendor_paid_regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcments`
--
ALTER TABLE `announcments`
  ADD CONSTRAINT `announcments_sender_foreign` FOREIGN KEY (`sender`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attachmentable`
--
ALTER TABLE `attachmentable`
  ADD CONSTRAINT `attachmentable_attachment_id_foreign` FOREIGN KEY (`attachment_id`) REFERENCES `attachments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`catagory_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_event_creator_foreign` FOREIGN KEY (`event_creator`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venue_id` FOREIGN KEY (`venue_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `event_attendees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_attendees_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_attendees_ibfk_3` FOREIGN KEY (`table_id`) REFERENCES `seatings` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `event_bids`
--
ALTER TABLE `event_bids`
  ADD CONSTRAINT `category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_bids_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_bids_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `event_bids_ibfk_3` FOREIGN KEY (`package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_event_foreign` FOREIGN KEY (`event`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invitation_inviter_foreign` FOREIGN KEY (`inviter`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invitation_recipient_foreign` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `localadmins`
--
ALTER TABLE `localadmins`
  ADD CONSTRAINT `localadmin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `localadmins_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_requester_foreign` FOREIGN KEY (`requester`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_users`
--
ALTER TABLE `role_users`
  ADD CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schools`
--
ALTER TABLE `schools`
  ADD CONSTRAINT `schools_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `schools_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `seatings`
--
ALTER TABLE `seatings`
  ADD CONSTRAINT `seatings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_bids`
--
ALTER TABLE `student_bids`
  ADD CONSTRAINT `student_bids_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bids_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bids_ibfk_3` FOREIGN KEY (`package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bids_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `role_id` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `catagory_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  ADD CONSTRAINT `vendor_packages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vendor_paid_regions`
--
ALTER TABLE `vendor_paid_regions`
  ADD CONSTRAINT `vendor_paid_regions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_paid_regions_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
