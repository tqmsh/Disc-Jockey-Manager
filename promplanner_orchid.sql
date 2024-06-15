-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 11:50 PM
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
-- Table structure for table `actual_expenses_revenues`
--

CREATE TABLE `actual_expenses_revenues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `universal_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=Expense 2=Revenue',
  `budget` int(11) NOT NULL,
  `actual` int(11) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_updated_user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `name`, `original_name`, `mime`, `extension`, `size`, `sort`, `path`, `description`, `alt`, `hash`, `disk`, `user_id`, `group`, `created_at`, `updated_at`) VALUES
(1, 'a4b29c00710298646405c7ca5535dda6ab65f242', 'blob', 'image/png', 'png', 74718, 0, '2023/04/01/', NULL, NULL, '6850fa9058d6e985ca87b4685def5399a0d04b3f', 'public', 197, NULL, '2023-04-02 01:54:16', '2023-04-02 01:54:16'),
(2, '70cd04ef5f2b392b3915d88b25e1b5d97bf3c8f2', 'blob', 'image/png', 'png', 72181, 0, '2023/04/01/', NULL, NULL, 'ecd6a53f960de0ead42a91b5426a822857ba1042', 'public', 197, NULL, '2023-04-02 01:54:42', '2023-04-02 01:54:42'),
(3, 'cbb4e0a60e7ceda65a8e4eed3e3b40de4619e701', 'blob', 'image/png', 'png', 501165, 0, '2023/04/01/', NULL, NULL, 'ccb91a47ecaa1c4807e96b848c656921a871f985', 'public', 197, NULL, '2023-04-02 02:06:05', '2023-04-02 02:06:05'),
(4, '7f2d7fcd7d30d3f1532c9b9a35561b53674a4c10', 'blob', 'image/png', 'png', 176700, 0, '2023/04/01/', NULL, NULL, '578f95740c2211bf4955b8edd879771f3b7e697c', 'public', 197, NULL, '2023-04-02 02:19:59', '2023-04-02 02:19:59'),
(5, 'c3d747174c6eea3d559272a19839a0451e2ce209', 'blob', 'image/png', 'png', 174821, 0, '2023/04/01/', NULL, NULL, 'fc25fbe0d7467c7b20cc7eceafcd9cce1720568c', 'public', 197, NULL, '2023-04-02 02:23:03', '2023-04-02 02:23:03'),
(6, 'f5e1a9911a4c30c23bb99710cfc14598541da310', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 'public', 197, NULL, '2023-04-30 00:33:48', '2023-04-30 00:33:48'),
(7, 'f5e1a9911a4c30c23bb99710cfc14598541da310', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 'public', 197, NULL, '2023-04-30 00:42:34', '2023-04-30 00:42:34'),
(8, '879e8b0af0f1924afda678d457d0e44ebde571be', 'blob', 'image/png', 'png', 496287, 0, '2023/04/29/', NULL, NULL, 'db5f628b8d4498f2d0fb8a5cedb31413593f652a', 's3', 197, NULL, '2023-04-30 01:19:30', '2023-04-30 01:19:30'),
(9, 'b5aef958984fc23ff742e0f9322195ea519c4947', 'blob', 'image/png', 'png', 499819, 0, '2023/04/29/', NULL, NULL, '7faa25b77ec6909328ce7f2a9bc410dfc2626de8', 's3', 197, NULL, '2023-04-30 01:23:27', '2023-04-30 01:23:27'),
(10, 'b5aef958984fc23ff742e0f9322195ea519c4947', 'blob', 'image/png', 'png', 499819, 0, '2023/04/29/', NULL, NULL, '7faa25b77ec6909328ce7f2a9bc410dfc2626de8', 's3', 197, NULL, '2023-04-30 01:24:08', '2023-04-30 01:24:08'),
(11, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-04-30 01:29:08', '2023-04-30 01:29:08'),
(12, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-04-30 01:33:58', '2023-04-30 01:33:58'),
(13, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-02 05:09:20', '2023-05-02 05:09:20'),
(14, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-02 05:09:37', '2023-05-02 05:09:37'),
(15, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-02 05:13:28', '2023-05-02 05:13:28'),
(16, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-06 02:36:40', '2023-05-06 02:36:40'),
(17, '88f015fe7a6a21fad9fadb1a5f3df8cd1d997297', 'blob', 'image/png', 'png', 174821, 0, '2023/05/05/', NULL, NULL, 'fc25fbe0d7467c7b20cc7eceafcd9cce1720568c', 's3', 197, NULL, '2023-05-06 02:37:08', '2023-05-06 02:37:08'),
(18, '88f015fe7a6a21fad9fadb1a5f3df8cd1d997297', 'blob', 'image/png', 'png', 174821, 0, '2023/05/05/', NULL, NULL, 'fc25fbe0d7467c7b20cc7eceafcd9cce1720568c', 's3', 197, NULL, '2023-05-06 02:37:23', '2023-05-06 02:37:23'),
(19, 'f5e1a9911a4c30c23bb99710cfc14598541da310', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 'public', 197, NULL, '2023-05-06 02:40:59', '2023-05-06 02:40:59'),
(20, '8a23a60868e0718f900643cd986b6bf8d1f49cab', 'blob', 'image/png', 'png', 66684, 0, '2023/05/05/', NULL, NULL, '0ae7e00c7bad99c91749cf01da436fcb6931da2f', 's3', 197, NULL, '2023-05-06 02:47:35', '2023-05-06 02:47:35'),
(21, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-06 02:58:22', '2023-05-06 02:58:22'),
(22, 'd5f278d0bdf077f92f71a43f59925ee23d8020a8', 'blob', 'image/png', 'png', 510241, 0, '2023/04/29/', NULL, NULL, '12e491e184d5c077a9effc4b40738916ea72c3e4', 's3', 197, NULL, '2023-05-06 03:11:09', '2023-05-06 03:11:09'),
(23, '88f015fe7a6a21fad9fadb1a5f3df8cd1d997297', 'blob', 'image/png', 'png', 174821, 0, '2023/05/05/', NULL, NULL, 'fc25fbe0d7467c7b20cc7eceafcd9cce1720568c', 's3', 197, NULL, '2023-05-06 03:13:46', '2023-05-06 03:13:46'),
(24, '88f015fe7a6a21fad9fadb1a5f3df8cd1d997297', 'blob', 'image/png', 'png', 174821, 0, '2023/05/05/', NULL, NULL, 'fc25fbe0d7467c7b20cc7eceafcd9cce1720568c', 's3', 197, NULL, '2023-05-10 05:48:18', '2023-05-10 05:48:18'),
(25, '0dea34232d3627e5b0696b6981ece68f0a04180a', 'blob', 'image/png', 'png', 181603, 0, '2023/05/10/', NULL, NULL, '4975c7ab2b33e226198492b8b658aab51b94177a', 's3', 197, NULL, '2023-05-10 05:49:40', '2023-05-10 05:49:40'),
(26, '0dea34232d3627e5b0696b6981ece68f0a04180a', 'blob', 'image/png', 'png', 181603, 0, '2023/05/10/', NULL, NULL, '4975c7ab2b33e226198492b8b658aab51b94177a', 's3', 197, NULL, '2023-05-10 05:52:03', '2023-05-10 05:52:03'),
(27, '0dea34232d3627e5b0696b6981ece68f0a04180a', 'blob', 'image/png', 'png', 181603, 0, '2023/05/10/', NULL, NULL, '4975c7ab2b33e226198492b8b658aab51b94177a', 's3', 197, NULL, '2023-05-10 05:52:15', '2023-05-10 05:52:15'),
(28, '156117b9cf81bb92cb7f570d8e37b924a53081a5', 'blob', 'image/png', 'png', 6787, 0, '2023/05/16/', NULL, NULL, '37d6341f27b6400b21b205a3691ad29e2e1ac986', 's3', 151, NULL, '2023-05-17 02:57:28', '2023-05-17 02:57:28'),
(29, '46e76235fc90dac205ececa672511b29415e500a', 'blob', 'image/png', 'png', 6465, 0, '2023/05/16/', NULL, NULL, '5ec44a0b19c5a1ceac409ea81c671fc3b163745b', 's3', 151, NULL, '2023-05-17 02:57:49', '2023-05-17 02:57:49'),
(30, 'bf86e0e2cf74cf56d92347c8be9ab2016d9389b3', 'blob', 'image/png', 'png', 21664, 0, '2023/05/16/', NULL, NULL, 'fa202d6b2db6c56b038e8bff40008058e1401e52', 's3', 151, NULL, '2023-05-17 02:59:11', '2023-05-17 02:59:11'),
(31, '0a38e1ace233f2262a8ba707d988217e0608b4d7', 'blob', 'image/png', 'png', 21770, 0, '2023/05/16/', NULL, NULL, 'd82befd581de7058a5d5a415037b920e782dba39', 's3', 151, NULL, '2023-05-17 03:01:12', '2023-05-17 03:01:12'),
(32, '2b614f23e9221f3d26e9a9bef0f7d7b520d0f875', 'blob', 'image/png', 'png', 156528, 0, '2023/05/16/', NULL, NULL, '9ceb5ebd2dfd63248649f5ade433b198efcc1767', 's3', 151, NULL, '2023-05-17 03:20:04', '2023-05-17 03:20:04'),
(33, 'ce5f9e9244120560da60f0e21be141f6755d5a9a', 'blob', 'image/png', 'png', 156937, 0, '2023/05/16/', NULL, NULL, '71f54a1faaaf9fb1b9fe9e70756502c9092da50b', 's3', 151, NULL, '2023-05-17 03:20:47', '2023-05-17 03:20:47'),
(34, '0df7b45311efa3aa3ca4604f6461edf63a131fff', 'blob', 'image/png', 'png', 31102, 0, '2023/05/19/', NULL, NULL, 'd1e462c548b63674ba1ab67398e8647b2c21d2b1', 's3', 155, NULL, '2023-05-20 02:31:23', '2023-05-20 02:31:23'),
(35, '122af13d234088648f95f9fa5b74e0382c40d920', 'blob', 'image/png', 'png', 26222, 0, '2023/05/19/', NULL, NULL, '839801f44a372d3995520d4b7066a728b21b291a', 's3', 155, NULL, '2023-05-20 02:33:35', '2023-05-20 02:33:35'),
(36, 'bcc98b69978ec2e3435637329eb40e4fb2cf037e', 'blob', 'image/png', 'png', 156824, 0, '2023/05/19/', NULL, NULL, '8b853a4433ef395fbe0cd44dbd3d2492ed726927', 's3', 13, NULL, '2023-05-20 03:00:12', '2023-05-20 03:00:12'),
(37, '3d06b57245183dadac464b4dfdc726256abee4b7', 'blob', 'image/png', 'png', 131044, 0, '2023/06/03/', NULL, NULL, '82e2d837f57f06e286e1d1dcb0dc442b83e6fb49', 's3', 151, NULL, '2023-06-03 05:10:23', '2023-06-03 05:10:23'),
(38, '808d685e7fc685c509e18174e00c98c92487d183', 'blob', 'image/png', 'png', 165097, 0, '2023/07/15/', NULL, NULL, 'cbb7ef0d36b5f0500427bc3c1fdf055e87885c9f', 's3', 197, NULL, '2023-07-15 23:00:28', '2023-07-15 23:00:28'),
(39, '8c8eaea188336a6e6b0b62d8f6fbd91e05e3e02e', 'blob', 'image/png', 'png', 137153, 0, '2023/07/15/', NULL, NULL, '9840c6bae169b0f81b84b7e76cfd4d8f3a380966', 's3', 197, NULL, '2023-07-15 23:00:54', '2023-07-15 23:00:54'),
(40, 'd986f0eaef32fdb913996c61d012d6c8f9355140', 'blob', 'image/png', 'png', 26811, 0, '2023/07/18/', NULL, NULL, 'e456b60c9e25606ec6b83407ead8ddb6907eff16', 's3', 207, NULL, '2023-07-19 02:49:24', '2023-07-19 02:49:24'),
(41, 'e005a00ee9db618684c3941b9c75802a6cdf7126', 'blob', 'image/png', 'png', 193814, 0, '2023/07/26/', NULL, NULL, 'd043166072b445c7e347b70530734fc7d08e3daa', 's3', 155, NULL, '2023-07-27 00:07:33', '2023-07-27 00:07:33'),
(42, '7c9fd9b7e89f8ea9d1a36db131d8ef29bb91c0b3', 'blob', 'image/png', 'png', 181419, 0, '2023/07/26/', NULL, NULL, 'b3b05fd2a4bce4269d81a890fb70065b97b2b2ce', 's3', 155, NULL, '2023-07-27 00:09:56', '2023-07-27 00:09:56'),
(43, '0fb183b30780f3187a289b61139a4fe541ece7bf', 'blob', 'image/png', 'png', 181474, 0, '2023/07/26/', NULL, NULL, 'faf2b8001543fb93ecec5d544aa1446c651941d3', 's3', 155, NULL, '2023-07-27 00:12:59', '2023-07-27 00:12:59'),
(44, '5d319809776e0c74261959111804364ba8dab325', 'New Headshot.png', 'image/png', 'png', 636108, 0, '2023/07/26/', NULL, NULL, '56685a00fd07f39ce35d978c92708c997e6b7ad4', 's3', 155, NULL, '2023-07-27 00:24:00', '2023-07-27 00:24:00'),
(45, '61d3b66840ae4ecdf2a548e14b326b78c6d774ca', 'blob', 'image/png', 'png', 181393, 0, '2023/07/26/', NULL, NULL, 'a2a319f7d20a037bc3c03d13c2e0fa9c2998e31b', 's3', 155, NULL, '2023-07-27 00:25:11', '2023-07-27 00:25:11'),
(46, '81aefdc357ee04799497945b0bafc60ee4dbddc3', 'blob', 'image/png', 'png', 181586, 0, '2023/07/26/', NULL, NULL, 'd453b962eac178b8301e18f981d3981515f44838', 's3', 155, NULL, '2023-07-27 00:29:36', '2023-07-27 00:29:36'),
(47, 'a34f761465e26ea91b209734aea4c613ea582f71', 'blob', 'image/png', 'png', 26511, 0, '2023/07/26/', NULL, NULL, '8e0f739e4ccc24cbdf32e545bb598021f9039b2d', 's3', 155, NULL, '2023-07-27 00:29:55', '2023-07-27 00:29:55'),
(48, '0fb183b30780f3187a289b61139a4fe541ece7bf', 'blob', 'image/png', 'png', 181474, 0, '2023/07/26/', NULL, NULL, 'faf2b8001543fb93ecec5d544aa1446c651941d3', 's3', 155, NULL, '2023-07-27 00:31:29', '2023-07-27 00:31:29'),
(49, 'af96a924c0b693dc229ddd94deb360d80d6e2d89', 'blob', 'image/png', 'png', 181420, 0, '2023/07/26/', NULL, NULL, '5146598f9597372e4a058b4ade92b8560638dced', 's3', 155, NULL, '2023-07-27 01:02:50', '2023-07-27 01:02:50'),
(50, '3e2aab53dbe9d616a43f1012e7e011674cee6d9f', 'blob', 'image/png', 'png', 181057, 0, '2023/07/26/', NULL, NULL, 'c19a482d0e8d804465e9308149c72d714a802974', 's3', 155, NULL, '2023-07-27 01:03:51', '2023-07-27 01:03:51'),
(51, '5d319809776e0c74261959111804364ba8dab325', 'New Headshot.png', 'image/png', 'png', 636108, 0, '2023/07/26/', NULL, NULL, '56685a00fd07f39ce35d978c92708c997e6b7ad4', 's3', 155, NULL, '2023-07-27 01:18:01', '2023-07-27 01:18:01'),
(52, '0cafd71f5f8b04a3737c5e2ea6d4e77e7edc2e25', 'blob', 'image/png', 'png', 181391, 0, '2023/07/26/', NULL, NULL, '55ae841c127863227fe436d989b2e598b5744a82', 's3', 155, NULL, '2023-07-27 01:19:05', '2023-07-27 01:19:05'),
(53, '93f0be7d06d1b9b978ae8a14142cb33b26c8c327', 'blob', 'image/png', 'png', 181537, 0, '2023/07/26/', NULL, NULL, '3ce0d19e60df317135044bc4c1f03a4243804017', 's3', 155, NULL, '2023-07-27 01:20:11', '2023-07-27 01:20:11'),
(54, '9363c181d9acbbdf4bdb266ab259c5ccebbdd96a', 'blob', 'image/png', 'png', 181646, 0, '2023/07/26/', NULL, NULL, 'd0e314e5b3d698bbf154ddb5347acb5bc3e014a2', 's3', 155, NULL, '2023-07-27 01:23:53', '2023-07-27 01:23:53'),
(55, '2a1fe7c7d61a9b7e8a089b48d10a7ea5a23c12d2', 'blob', 'image/png', 'png', 181412, 0, '2023/07/26/', NULL, NULL, '85836731aa9811e88227e673ea17c60461639763', 's3', 155, NULL, '2023-07-27 01:28:29', '2023-07-27 01:28:29'),
(56, 'a16dd23d9874710210b4e904ccfc9cdb92de2675', 'blob', 'image/png', 'png', 181364, 0, '2023/07/26/', NULL, NULL, 'df92d134e4a90840d539eed4cfb86981f7d68d5d', 's3', 155, NULL, '2023-07-27 01:35:07', '2023-07-27 01:35:07'),
(57, 'dc9f6ecc4d6256cce8aedde687c679ce5449d8bd', 'blob', 'image/png', 'png', 10962, 0, '2023/07/29/', NULL, NULL, '4d9d7e21d56d2a925179d3057199beed590b575a', 's3', 209, NULL, '2023-07-29 23:42:26', '2023-07-29 23:42:26'),
(58, 'a31851351a89bac6cf6a4e9eec212f27b2438d46', 'fspace.png', 'image/png', 'png', 97890, 0, '2023/08/22/', NULL, NULL, '46266990c83fbdba377d82f775b1ecc38a4420f5', 'local', 155, NULL, '2023-08-22 21:28:18', '2023-08-22 21:28:18'),
(59, '8a4789a60096a653e5cfb8c5e418d20486da13ad', 'Farhan and Barrak.jpg', 'image/jpeg', 'jpg', 571561, 0, '2023/08/22/', NULL, NULL, 'a00c536f7695dff23d6dcef980ad48956448d437', 'local', 155, NULL, '2023-08-22 21:29:46', '2023-08-22 21:29:46'),
(60, '8a4789a60096a653e5cfb8c5e418d20486da13ad', 'Farhan and Barrak.jpg', 'image/jpeg', 'jpg', 571561, 0, '2023/08/22/', NULL, NULL, 'a00c536f7695dff23d6dcef980ad48956448d437', 'local', 155, NULL, '2023-08-22 21:33:28', '2023-08-22 21:33:28'),
(61, '68ef3eed55791280eeae2a787e71335068157825', 'blob', 'image/png', 'png', 239734, 0, '2023/08/28/', NULL, NULL, '3c5eeaa5857f3495194d2cd0a67144ad1139890a', 's3', 151, NULL, '2023-08-28 04:48:19', '2023-08-28 04:48:19'),
(62, 'ae58d833a27d7bc7ef0bc0b1c5103ddc95c6b168', 'blob', 'image/png', 'png', 7569, 0, '2023/08/28/', NULL, NULL, '29f9756acddc9bdcce659203be4c7648144e4aa0', 's3', 151, NULL, '2023-08-28 04:51:50', '2023-08-28 04:51:50'),
(63, 'e12426dfc2977104cda561d4ad24edb67786e9f2', 'blob', 'image/png', 'png', 7581, 0, '2023/08/28/', NULL, NULL, 'fefe01c04c64235c87c20347da31645c8f53a2a4', 's3', 151, NULL, '2023-08-28 04:52:25', '2023-08-28 04:52:25'),
(64, '2f341160a9b269ca5c79bf25b50e76b235b49df8', 'blob', 'image/png', 'png', 24607, 0, '2023/08/28/', NULL, NULL, '1d9141ef30eb2e5cf25e12a7eab49485b2298c91', 's3', 151, NULL, '2023-08-28 04:54:31', '2023-08-28 04:54:31'),
(65, '0a69957c87819dd38fe7cd73913234b30abea5ed', 'blob', 'image/png', 'png', 28790, 0, '2023/08/28/', NULL, NULL, '1b8cfc06d99d504cf2aa582fdd53daadff00f942', 's3', 151, NULL, '2023-08-28 04:57:35', '2023-08-28 04:57:35'),
(66, 'fe0a0a4e8a463231045ebce6e6ea05df8f1c1f5f', 'blob', 'image/png', 'png', 14913, 0, '2023/08/28/', NULL, NULL, '61af797526c25d2180d6319d4cb1b47726e7920c', 's3', 151, NULL, '2023-08-28 05:01:53', '2023-08-28 05:01:53'),
(67, 'bd035504fdca10e633e7b10baebd34641458b155', 'blob', 'image/png', 'png', 27789, 0, '2023/08/28/', NULL, NULL, '3c1e4963488cb4002a10c8f39614839b963953c2', 's3', 151, NULL, '2023-08-28 05:02:08', '2023-08-28 05:02:08'),
(68, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', NULL, NULL, '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 05:05:56', '2023-08-28 05:05:56'),
(69, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', NULL, NULL, '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 05:06:44', '2023-08-28 05:06:44'),
(70, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', NULL, NULL, '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 05:09:04', '2023-08-28 05:09:04'),
(71, '9f1a95e6c45a39825aff5e835b0e51d5fbce3b52', 'blob', 'image/png', 'png', 28823, 0, '2023/08/28/', NULL, NULL, '05d0aef3731937ea7f48308d7bce951f2e920fe2', 's3', 151, NULL, '2023-08-28 05:10:12', '2023-08-28 05:10:12'),
(72, 'c89d39d417a7d8d0963b786c1e210fd148fb6377', 'blob', 'image/png', 'png', 28255, 0, '2023/08/28/', NULL, NULL, '73b786567b47dc27ed7cf148e4b829ae070cf070', 's3', 151, NULL, '2023-08-28 05:12:41', '2023-08-28 05:12:41'),
(73, '4a30b80b7d16c70ab5645babb8b5f6e5b0f1b085', 'blob', 'image/png', 'png', 28350, 0, '2023/08/28/', NULL, NULL, '6dff8370ae1386acedd4431c03653aef561d33eb', 's3', 151, NULL, '2023-08-28 19:21:27', '2023-08-28 19:21:27'),
(74, '4a30b80b7d16c70ab5645babb8b5f6e5b0f1b085', 'blob', 'image/png', 'png', 28350, 0, '2023/08/28/', NULL, NULL, '6dff8370ae1386acedd4431c03653aef561d33eb', 's3', 151, NULL, '2023-08-28 19:21:28', '2023-08-28 19:21:28'),
(75, 'bbd2a3ab6aca4495d1b078989a79b6659e8cf8dc', 'blob', 'image/png', 'png', 27910, 0, '2023/08/28/', NULL, NULL, 'ef6b3d84ac360775ae384ba25471f563ed72850d', 's3', 151, NULL, '2023-08-28 19:23:37', '2023-08-28 19:23:37'),
(76, '889f8e911479d2faae976664f9399050289fb813', 'blob', 'image/png', 'png', 24309, 0, '2023/08/28/', NULL, NULL, '663388f97203a7e42a8d268924a614d60b111fe2', 's3', 151, NULL, '2023-08-28 19:24:18', '2023-08-28 19:24:18'),
(77, 'bfa0548711d04861e7a89cf8ca975b9cb21d79d4', 'blob', 'image/png', 'png', 28366, 0, '2023/08/28/', NULL, NULL, '664629d0d58cc9e21be41e6284ea2853c6527ca7', 's3', 151, NULL, '2023-08-28 19:27:05', '2023-08-28 19:27:05'),
(78, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', 'dasdasdasd', 'asdasdas', '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 19:27:54', '2023-08-28 19:28:05'),
(79, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', NULL, NULL, '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 19:29:14', '2023-08-28 19:29:14'),
(80, 'dfbd2a0cd9d72ab6c0e2130a0f4a2ad67ef62416', 'Pepperoni_Pizza.jpg', 'image/jpeg', 'jpg', 580953, 0, '2023/08/28/', NULL, NULL, '723100b7572c24726804753b4260d3a967cb17a5', 's3', 151, NULL, '2023-08-28 19:30:29', '2023-08-28 19:30:29'),
(81, '7464ca12fd1fcdba248e37be3a2488edee10eb87', 'blob', 'image/png', 'png', 27541, 0, '2023/08/28/', NULL, NULL, 'f2269a04a2800e78fd6cdaed77d9779c2474e8d6', 's3', 151, NULL, '2023-08-28 19:31:03', '2023-08-28 19:31:03'),
(82, '26c8fe9b3e5c304bfbbf1c39fbc045c721e7d05b', 'blob', 'image/png', 'png', 27868, 0, '2023/08/28/', NULL, NULL, '5db0f76f06d86628ba4f9b78c701b1053e0b9c2f', 's3', 151, NULL, '2023-08-28 19:37:21', '2023-08-28 19:37:21'),
(83, 'd32d8a23bc5d5142875e17a3ba5b2d48811e0958', 'blob', 'image/png', 'png', 2699235, 0, '2023/08/28/', NULL, NULL, 'aa814d9dd04ef7faca5f1b27ae745dfc40a847ad', 's3', 151, NULL, '2023-08-28 19:39:42', '2023-08-28 19:39:42'),
(84, '9bf017af2ea6055377e7c30fe3da3ed6a86774ea', 'blob', 'image/png', 'png', 2296389, 0, '2023/08/28/', NULL, NULL, 'decd16d8f0bd7db52c05eabda4dad6a8f265997e', 's3', 151, NULL, '2023-08-28 19:41:09', '2023-08-28 19:41:09'),
(85, '4b258f9ee8911a9f3e76e88d8d1ab6462e2e2790', 'blob', 'image/png', 'png', 2282812, 0, '2023/08/28/', NULL, NULL, '784d5a737a8cb8054aab998bad0793fc00058746', 's3', 151, NULL, '2023-08-28 19:48:31', '2023-08-28 19:48:31'),
(86, 'bada0d067ee1cda08adcd86c7f373469cae9228e', 'blob', 'image/png', 'png', 2084604, 0, '2023/08/28/', NULL, NULL, 'f8cc5745a08188c4ad0c9a21c01370e68762c6f4', 's3', 151, NULL, '2023-08-28 19:49:51', '2023-08-28 19:49:51'),
(87, '2ceb104c3fb5502a82b49e24a5cd18cf97b3574c', 'blob', 'image/png', 'png', 2221813, 0, '2023/08/28/', NULL, NULL, '7897650ee3ed3769b35babac92ecfe9004a89e28', 's3', 151, NULL, '2023-08-28 19:51:47', '2023-08-28 19:51:47'),
(88, '6cf9468c8a98f5307f49a79d1e11a95812797bbf', 'venture-tech-website-favicon-white.webp', 'image/webp', 'webp', 588, 0, '2023/08/30/', NULL, NULL, 'f197f1c90d8e5787577ee098992b688a3025ec02', 's3', 151, NULL, '2023-08-30 21:02:19', '2023-08-30 21:02:19'),
(89, '437a8a40b2383cab2df953c300cc12299ad93c53', 'blob', 'image/png', 'png', 3417441, 0, '2023/08/30/', NULL, NULL, 'c7c8d4f84c394265251ad6549bd418b9261663f2', 's3', 151, NULL, '2023-08-31 00:41:41', '2023-08-31 00:41:41'),
(90, '819278e0a85660c4daf58c4b477f56a93814b1aa', 'blob', 'image/png', 'png', 2074669, 0, '2023/08/30/', NULL, NULL, '47660063ee2a716d78e2178b9fe40b6f01e91d30', 's3', 151, NULL, '2023-08-31 00:43:39', '2023-08-31 00:43:39'),
(91, '765075f3cbb6b2c78211bd5aa47864e4dbd9c974', 'blob', 'image/png', 'png', 170735, 0, '2023/12/23/', NULL, NULL, '71a97677d1049bb110a59cb4b6044db0692fceab', 's3', 151, NULL, '2023-12-24 03:14:47', '2023-12-24 03:14:47'),
(92, '222f05cc2d7902d9851e5f78b7c23ddca6f79af8', 'blob', 'image/png', 'png', 128456, 0, '2024/02/21/', NULL, NULL, '9a5d18db6eb182ccd06d8e1dc5b0a5e2d9188c7a', 's3', 13, NULL, '2024-02-22 00:49:28', '2024-02-22 00:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `beauty_groups`
--

CREATE TABLE `beauty_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_user_id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dropoff_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depart_time` datetime NOT NULL,
  `dropoff_time` datetime NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beauty_groups`
--

INSERT INTO `beauty_groups` (`id`, `creator_user_id`, `school_id`, `name`, `capacity`, `date`, `pickup_location`, `dropoff_location`, `depart_time`, `dropoff_time`, `notes`, `created_at`, `updated_at`) VALUES
(2, 207, 51, 'tryrty', 18, '2023-08-15 00:00:00', 'Colonel By SS', '1234 High Lane67i657i567i67i', '2023-08-08 12:00:00', '2023-08-08 12:00:00', 'rtyrtyrtytrytyuytui67i67i57i', '2023-08-01 21:26:31', '2023-08-22 20:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `beauty_group_bids`
--

CREATE TABLE `beauty_group_bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `beauty_group_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_instructions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beauty_group_bids`
--

INSERT INTO `beauty_group_bids` (`id`, `user_id`, `beauty_group_id`, `region_id`, `package_id`, `category_id`, `school_name`, `notes`, `contact_instructions`, `company_name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 210, 2, 1, 9, 19, 'Digitera School of Digital Marketing & Software', 'heyeyeyeyeyeye', 'heyeyeyeyeyeye', 'Salon Pros', 'https://amazon.com', 1, '2023-08-01 22:41:42', '2023-08-01 23:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `beauty_group_members`
--

CREATE TABLE `beauty_group_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `beauty_group_id` bigint(20) UNSIGNED NOT NULL,
  `invitee_user_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beauty_group_members`
--

INSERT INTO `beauty_group_members` (`id`, `beauty_group_id`, `invitee_user_id`, `status`, `paid`, `created_at`, `updated_at`) VALUES
(2, 2, 207, 1, 0, '2023-08-01 21:29:30', '2023-08-01 21:30:46'),
(3, 2, 155, 1, 0, '2023-08-22 20:32:02', '2023-08-22 20:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `bug_reports`
--

CREATE TABLE `bug_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_user_id` bigint(20) UNSIGNED NOT NULL,
  `reporter_role` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
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
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clicks` bigint(20) UNSIGNED NOT NULL,
  `impressions` bigint(20) UNSIGNED NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `user_id`, `category_id`, `region_id`, `title`, `image`, `website`, `clicks`, `impressions`, `active`, `gender`, `created_at`, `updated_at`) VALUES
(8, 197, 11, 1, 'Best campaign ever', 'https://test-promplanner.s3.ca-central-1.amazonaws.com/2023/05/10/0dea34232d3627e5b0696b6981ece68f0a04180a.png', 'https://orchid.software/en/docs/table/', 0, 0, 1, 'all', '2023-05-10 05:49:50', '2023-05-10 05:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidate_bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candidate_video_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `user_id`, `candidate_name`, `candidate_bio`, `candidate_video_url`, `election_id`, `position_id`, `created_at`, `updated_at`) VALUES
(4, 146, 'Jane Doe', 'gdfgdfgdfgdg', NULL, 4, 5, '2023-06-26 21:07:20', '2023-06-26 21:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) DEFAULT 0,
  `order_num` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `order_num`, `created_at`, `updated_at`) VALUES
(9, 'Venue', 1, 0, '2023-01-09 03:16:56', '2023-01-09 03:16:56'),
(10, 'Event Planner', 1, 0, '2023-01-09 03:17:16', '2023-01-09 03:17:16'),
(11, 'Disc Jockey', 1, 0, '2023-01-09 03:17:27', '2023-01-09 03:17:27'),
(12, 'Audio-Visual', 1, 0, '2023-01-09 03:17:36', '2023-01-09 03:17:36'),
(13, 'Photographers', 1, 0, '2023-01-09 03:17:42', '2023-01-09 03:17:42'),
(14, 'Videographer', 1, 0, '2023-01-09 03:20:29', '2023-01-09 03:20:29'),
(15, 'Caterer', 1, 0, '2023-01-09 03:20:35', '2023-01-09 03:20:35'),
(17, 'Suggestion From Vendor', 1, 0, '2023-01-13 03:55:42', '2023-01-14 03:42:50'),
(18, 'Limo', 1, 0, NULL, NULL),
(19, 'Salon', 1, 10, '2023-08-01 22:07:59', '2023-08-01 22:07:59'),
(20, 'Test Category', 0, 0, '2024-02-26 06:04:01', '2024-02-26 06:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `checklists`
--

CREATE TABLE `checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `checklist_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checklist_users`
--

CREATE TABLE `checklist_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `checklist_id` bigint(20) UNSIGNED NOT NULL,
  `checklist_item_id` bigint(20) UNSIGNED NOT NULL,
  `checklist_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `couples`
--

CREATE TABLE `couples` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id_1` bigint(20) UNSIGNED NOT NULL,
  `student_user_id_2` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `couple_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `couple_requests`
--

CREATE TABLE `couple_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_user_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `display_ads`
--

CREATE TABLE `display_ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `portal` tinyint(4) NOT NULL,
  `ad_index` tinyint(4) NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `square` tinyint(4) NOT NULL,
  `route_uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dresses`
--

CREATE TABLE `dresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `model_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`colours`)),
  `sizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`sizes`)),
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dress_wishlist`
--

CREATE TABLE `dress_wishlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dress_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `election_name`, `event_id`, `school_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 'Test', 15, 51, '2023-04-27 12:00:00', '2023-04-28 12:00:00', '2023-04-27 02:49:15', '2023-04-27 02:49:15'),
(2, 'Big Man', 13, 53, '2023-05-05 00:41:38', '2023-05-05 00:41:38', NULL, NULL),
(4, 'Election 1', 14, 51, '2023-06-02 12:00:00', '2023-06-03 12:00:00', '2023-06-03 05:28:38', '2023-06-03 05:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `election_votes`
--

CREATE TABLE `election_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `voter_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `election_votes`
--

INSERT INTO `election_votes` (`id`, `election_id`, `position_id`, `candidate_id`, `voter_user_id`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 4, 155, '2023-06-26 21:08:47', '2023-06-26 21:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `election_winners`
--

CREATE TABLE `election_winners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `regional_experience` tinyint(4) NOT NULL COMMENT '1 = Yes 0 = No',
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `open` tinyint(1) DEFAULT 1 COMMENT '1 = Open, 0 = Closed',
  `capacity` int(11) DEFAULT NULL,
  `ticket_price` float DEFAULT NULL,
  `interested_vendor_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interested_vendor_categories`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_creator`, `school_id`, `region_id`, `venue_id`, `event_name`, `event_start_time`, `event_finish_time`, `school`, `event_address`, `event_zip_postal`, `event_info`, `event_rules`, `open`, `capacity`, `ticket_price`, `interested_vendor_categories`, `created_at`, `updated_at`) VALUES
(6, 108, 32, 12, 9, 'Cool Kidz Party', '2022-11-11 15:05:41', '2022-11-18 15:05:41', 'Cool School', '789 Cool Street', 'ILK OL8', 'Cool dresses ', 'ONLY COOL KIDS ALLOWED', 1, 100, 10.5, NULL, '2022-11-04 19:05:41', '2022-11-15 01:34:49'),
(13, 13, 53, 1, NULL, 'Colonel By\'s Main Event', '2022-11-21 12:00:00', '2022-11-22 12:00:00', 'Colonel By Secondary School', '2381 Ogilvie Rd', 'K1J 7N4', 'Formal Attire', 'No Violence', 1, 100, 10.5, NULL, '2022-11-20 11:16:51', '2022-11-20 11:16:51'),
(14, 13, 51, 1, NULL, 'Digitera\'s Main DJ Event', '2022-11-20 12:00:00', '2022-11-26 12:00:00', 'Digitera School of Digital Marketing & Software', '1125 Colonel By Dr Rm 102', 'K1S 5B6', 'PART ON!!!!', 'No rules', 1, 100, 10.5, NULL, '2022-11-20 11:25:03', '2022-11-20 11:25:03'),
(15, 151, 51, 1, NULL, 'The Perfect Event For You!', '2022-12-02 12:00:00', '2022-12-03 12:00:00', 'Digitera School of Digital Marketing & Software', '123 Hey Road', 'KIU 84O', 'I ain\'t got nothing', 'None', 1, 100, 10.5, NULL, '2022-12-02 01:03:59', '2022-12-02 01:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `events_historical_records`
--

CREATE TABLE `events_historical_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `venue_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `venue_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disc_jockey_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disc_jockey_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photobooth_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photobooth_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photographer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photographer_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videographer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videographer_notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

CREATE TABLE `event_attendees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `inviter_user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ticketstatus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Unpaid',
  `table_approved` tinyint(1) DEFAULT 0,
  `invitation_status` tinyint(1) NOT NULL DEFAULT 0,
  `invited` tinyint(1) NOT NULL DEFAULT 0,
  `checked_in` tinyint(1) NOT NULL DEFAULT 0,
  `ticket_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_attendees`
--

INSERT INTO `event_attendees` (`id`, `user_id`, `inviter_user_id`, `event_id`, `table_id`, `ticketstatus`, `table_approved`, `invitation_status`, `invited`, `checked_in`, `ticket_code`, `created_at`, `updated_at`) VALUES
(29, 145, 151, 14, 10, 'Unpaid', 1, 1, 0, 0, NULL, '2023-08-09 02:35:23', '2024-02-25 04:37:30'),
(30, 146, 151, 14, 10, 'Unpaid', 1, 1, 0, 0, NULL, '2023-08-09 02:35:23', '2024-02-25 04:39:42'),
(32, 152, 151, 14, NULL, 'Unpaid', 1, 0, 1, 0, NULL, '2023-08-09 02:48:58', '2023-08-09 02:48:58'),
(33, 154, 151, 14, NULL, 'Unpaid', 1, 0, 1, 0, NULL, '2023-08-09 02:48:58', '2023-08-09 02:48:58'),
(34, 155, 151, 14, 9, 'Unpaid', 1, 1, 1, 0, NULL, '2023-08-09 03:18:44', '2024-02-26 05:22:22'),
(35, 207, 151, 14, NULL, 'Unpaid', 1, 1, 1, 0, NULL, '2023-08-09 03:18:45', '2023-08-25 01:01:34'),
(36, 213, 13, 15, NULL, 'Unpaid', 1, 1, 1, 0, NULL, '2023-08-26 01:36:24', '2023-08-26 01:40:46'),
(37, 155, 151, 14, 10, 'Unpaid', 0, 0, 0, 0, NULL, '2024-02-26 05:41:08', '2024-02-26 05:41:08');

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
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_instructions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_bids`
--

INSERT INTO `event_bids` (`id`, `user_id`, `event_id`, `region_id`, `package_id`, `category_id`, `event_date`, `school_name`, `notes`, `contact_instructions`, `company_name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(2, 197, 14, 1, 3, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'Just buy this license please', 'Email: info@digitera.agency', 'Money Jockeys', 'https://promplanner.app/', 2, '2023-02-01 00:53:31', '2023-02-01 00:53:31'),
(3, 197, 13, 1, 1, 11, '2022-11-21 12:00:00', 'Colonel By Secondary School', 'This will be the best venue trust me bro', 'Phone: 654-785-1289', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-01 00:56:23', '2023-02-01 00:56:23'),
(4, 197, 14, 1, 2, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'Notes', 'Contact Instructions', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-02 23:39:42', '2023-02-09 01:15:50'),
(7, 197, 14, 1, 1, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', NULL, NULL, 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-03 02:43:50', '2023-02-09 01:18:44'),
(8, 197, 13, 1, 3, 11, '2022-11-21 12:00:00', 'Colonel By Secondary School', 'notes', 'contact info', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-07 04:26:43', '2023-02-07 04:26:43'),
(9, 197, 14, 1, 6, 11, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'Edited by super admin', 'HERE IS SOME CONTACT INFO', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-02-09 01:23:21', '2023-03-01 01:42:39'),
(10, 197, 15, 1, 1, 11, '2022-12-02 12:00:00', 'Digitera School of Digital Marketing & Software', 'fgdfg', 'dfgdfg', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-15 01:04:19', '2023-02-15 01:05:56'),
(12, 197, NULL, 1, 3, 11, '2023-01-10 12:00:00', 'Digitera School of Digital Marketing & Software', 'dsfdsfsd', 'fsdfsdf', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-02-15 01:04:50', '2023-03-01 01:29:01'),
(13, 197, NULL, 1, 2, 11, '2023-01-10 12:00:00', 'Digitera School of Digital Marketing & Software', 'dfsdf', 'sdfsdf', 'Money Jockeys', 'https://promplanner.app/', 2, '2023-02-15 01:05:02', '2023-02-15 01:06:06'),
(16, 197, 14, 1, 7, 18, '2022-11-20 12:00:00', 'Digitera School of Digital Marketing & Software', 'sdfsdf', 'dsfsdf', 'Money Jockeys', 'https://promplanner.app/', 0, '2023-08-19 06:17:43', '2023-08-19 06:17:43');

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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vegetarian` tinyint(1) NOT NULL DEFAULT 0,
  `vegan` tinyint(1) NOT NULL DEFAULT 0,
  `halal` tinyint(1) NOT NULL DEFAULT 0,
  `gluten_free` tinyint(1) NOT NULL DEFAULT 0,
  `kosher` tinyint(1) NOT NULL DEFAULT 0,
  `nut_free` tinyint(1) NOT NULL DEFAULT 0,
  `dairy_free` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `event_id`, `name`, `description`, `image`, `vegetarian`, `vegan`, `halal`, `gluten_free`, `kosher`, `nut_free`, `dairy_free`, `created_at`, `updated_at`) VALUES
(6, 14, 'Pepperoni Pizza', 'Pepperoni pizza with onions, mushrooms, olives, peppers and extra cheese', 'https://promplanner.s3.amazonaws.com/2023/08/28/bada0d067ee1cda08adcd86c7f373469cae9228e.png', 0, 0, 0, 0, 0, 1, 0, '2023-08-28 19:50:15', '2023-08-28 20:23:38'),
(8, 14, 'Steak', 'Pan seared steak seasoned with salt, pepper, rosemary and seared in butter.', 'https://promplanner.s3.amazonaws.com/2023/08/30/437a8a40b2383cab2df953c300cc12299ad93c53.png', 0, 0, 1, 1, 0, 1, 1, '2023-08-31 00:42:27', '2023-08-31 00:42:27'),
(9, 14, 'Pasta', 'Buttery pasta with sausages and basil', 'https://promplanner.s3.amazonaws.com/2023/08/30/819278e0a85660c4daf58c4b477f56a93814b1aa.png', 0, 0, 0, 0, 0, 1, 1, '2023-08-31 00:44:12', '2023-08-31 00:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordering` double DEFAULT NULL,
  `guide_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `ordering`, `guide_name`, `category`, `created_at`, `updated_at`) VALUES
(1, 1, 'Legalities of Prom', 2, '2023-03-27 20:09:17', '2023-03-30 04:42:17'),
(2, 1.2, 'How to Choose the Best Music for Prom', 2, '2023-03-28 01:07:57', '2023-03-28 01:07:57'),
(4, 1.1, 'How to Choose the Best Food for Prom', 2, '2023-03-30 04:30:50', '2023-03-30 04:30:50'),
(8, 1.3, 'Course for the students', 3, '2023-04-08 04:30:27', '2023-04-08 04:30:27'),
(9, 1.4, 'Course for the vendors', 4, '2023-04-08 04:30:42', '2023-04-08 04:30:42');

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
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordering` double DEFAULT NULL,
  `lesson_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `guide_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `ordering`, `lesson_name`, `lesson_description`, `lesson_content`, `section_id`, `guide_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'How to not hire stupid vendors', 'Here is a description, JUST DONT DO IT', '', 1, 1, '2023-03-30 01:35:07', NULL),
(2, 1, 'Get Student Surveys', 'This is the best description to ever exist', '', 3, 2, '2023-03-30 01:37:50', NULL),
(4, 1, 'Best lesssososos', 'Best descriptojsjkjsk', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quisque id diam vel quam elementum pulvinar etiam non quam. In nibh mauris cursus mattis molestie a iaculis. Eget nullam non nisi est sit. Sed libero enim sed faucibus turpis. Aliquet nibh praesent tristique magna sit amet purus gravida. Donec ultrices tincidunt arcu non sodales neque sodales. Nibh sit amet commodo nulla facilisi. Pretium lectus quam id leo in vitae turpis. Est sit amet facilisis magna etiam. Justo eget magna fermentum iaculis. Diam ut venenatis tellus in metus vulputate eu scelerisque. Neque volutpat ac tincidunt vitae semper quis. Malesuada fames ac turpis egestas maecenas pharetra. Vel pharetra vel turpis nunc eget. Blandit cursus risus at ultrices mi tempus imperdiet nulla.\r\n\r\nEuismod lacinia at quis risus. Egestas sed sed risus pretium. Lectus urna duis convallis convallis. Quam vulputate dignissim suspendisse in est ante in. Nisl tincidunt eget nullam non. Sollicitudin aliquam ultrices sagittis orci a scelerisque purus. Tempor commodo ullamcorper a lacus vestibulum sed arcu. Egestas egestas fringilla phasellus faucibus. Elit eget gravida cum sociis natoque penatibus et. Nulla at volutpat diam ut venenatis. Morbi tempus iaculis urna id volutpat lacus. Elit at imperdiet dui accumsan sit amet.\r\n\r\nCras pulvinar mattis nunc sed blandit libero volutpat. Cursus sit amet dictum sit amet justo donec enim diam. Erat pellentesque adipiscing commodo elit at imperdiet. Scelerisque eu ultrices vitae auctor eu augue. Id diam maecenas ultricies mi eget mauris pharetra et. Cras adipiscing enim eu turpis egestas pretium aenean. Vitae congue mauris rhoncus aenean vel elit scelerisque. Turpis egestas sed tempus urna et pharetra pharetra massa. Enim nec dui nunc mattis enim. Ullamcorper a lacus vestibulum sed. Non curabitur gravida arcu ac. Aenean et tortor at risus viverra adipiscing. At volutpat diam ut venenatis tellus. Proin libero nunc consequat interdum varius sit amet mattis. Quam lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit. Odio morbi quis commodo odio aenean sed adipiscing. Posuere ac ut consequat semper viverra nam.\r\n\r\nArcu felis bibendum ut tristique et egestas quis. Pulvinar proin gravida hendrerit lectus. Suspendisse sed nisi lacus sed. Ut pharetra sit amet aliquam id diam maecenas. Id porta nibh venenatis cras sed. Sit amet cursus sit amet dictum sit. Leo integer malesuada nunc vel risus commodo viverra maecenas. Facilisi etiam dignissim diam quis enim. Purus sit amet volutpat consequat mauris nunc congue nisi. In fermentum et sollicitudin ac. Ullamcorper a lacus vestibulum sed arcu non. Id aliquet risus feugiat in ante metus dictum at tempor. Enim sit amet venenatis urna cursus.\r\n\r\nTristique senectus et netus et. Nunc aliquet bibendum enim facilisis gravida neque convallis. Id diam vel quam elementum pulvinar etiam. Viverra nibh cras pulvinar mattis nunc sed blandit libero. Scelerisque eleifend donec pretium vulputate sapien nec sagittis aliquam malesuada. Quis ipsum suspendisse ultrices gravida dictum. Auctor elit sed vulputate mi sit amet. Duis ultricies lacus sed turpis tincidunt id. Neque vitae tempus quam pellentesque nec nam aliquam. Sed turpis tincidunt id aliquet risus feugiat in ante. Tincidunt tortor aliquam nulla facilisi cras fermentum. Purus faucibus ornare suspendisse sed nisi. Id velit ut tortor pretium viverra. Massa sapien faucibus et molestie ac feugiat sed lectus vestibulum. Suspendisse sed nisi lacus sed viverra. Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis. Vel facilisis volutpat est velit egestas dui id. Sed odio morbi quis commodo odio aenean sed adipiscing. Lobortis scelerisque fermentum dui faucibus in ornare quam viverra orci. Enim blandit volutpat maecenas volutpat blandit aliquam.', 2, 1, '2023-04-05 22:49:43', '2023-04-05 22:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `limo_groups`
--

CREATE TABLE `limo_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `creator_user_id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` tinyint(4) NOT NULL DEFAULT 50,
  `date` datetime NOT NULL,
  `pickup_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dropoff_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depart_time` datetime NOT NULL,
  `dropoff_time` datetime NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `limo_groups`
--

INSERT INTO `limo_groups` (`id`, `creator_user_id`, `school_id`, `name`, `capacity`, `date`, `pickup_location`, `dropoff_location`, `depart_time`, `dropoff_time`, `notes`, `created_at`, `updated_at`) VALUES
(14, 155, 51, 'The Best Limo Group', 17, '2023-08-23 00:00:00', 'Colonel By SS', '1234 High Lane', '2023-08-23 12:00:00', '2023-08-11 12:00:00', 'sfdsdfsdf', '2023-08-22 20:42:06', '2023-08-22 20:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `limo_group_bids`
--

CREATE TABLE `limo_group_bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `limo_group_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_instructions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `limo_group_members`
--

CREATE TABLE `limo_group_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `limo_group_id` bigint(20) UNSIGNED NOT NULL,
  `invitee_user_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `limo_group_members`
--

INSERT INTO `limo_group_members` (`id`, `limo_group_id`, `invitee_user_id`, `status`, `paid`, `created_at`, `updated_at`) VALUES
(29, 14, 155, 1, 0, '2023-08-22 20:42:06', '2023-08-22 20:42:06'),
(30, 14, 207, 1, 0, '2023-08-22 20:42:16', '2023-08-22 20:43:36'),
(31, 14, 213, 1, 0, '2023-08-22 20:47:55', '2023-08-22 20:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `localadmins`
--

CREATE TABLE `localadmins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_status` int(11) DEFAULT 0,
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
(45, 151, 51, 1, 'Local Admin 1', 'Local Admin 1', '(546) 456-4564', 'localadmin001@promplanner.com', 'Digitera School of Digital Marketing & Software', '2022-12-02 00:58:48', '2023-12-24 03:14:48'),
(49, 185, 53, 1, 'RoleUser Perms', 'RoleUser Perms', '(556) 466-4645', 'RoleUserPerms@RoleUserPerms.com', 'Colonel By Secondary School', '2023-01-07 23:45:06', '2023-01-07 23:45:06'),
(51, 192, 53, 1, 'PendingTest', 'PendingTest', '(778) 979-7979', 'PendingTest@PendingTest.com', 'Colonel By Secondary School', '2023-01-10 23:54:52', '2023-01-10 23:55:32'),
(52, 193, 53, 1, 'Local admin Import 1', 'efwefwef', '12345678910', 'import111@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(53, 194, 53, 1, 'Local admin Import 2', 'wefwef', '9632587459', 'import222@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(54, 195, 53, 1, 'Local admin Import 3', 'wefwef', '3698745236', 'import333@gmail.com', 'Colonel By Secondary School', '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(55, 208, 53, 1, 'Farhan', 'Khan', '(123) 456-7890', 'farhan.k2005@gmail.com', 'Colonel By Secondary School', '2023-07-19 21:00:54', '2023-07-19 21:00:54'),
(56, 214, 51, 0, 'zdasd', 'asdasdasd', '(544) 564-5465', 'faasdasdasdnasdasd05@gmail.com', 'Digitera School of Digital Marketing & Software', '2024-01-13 04:44:05', '2024-01-13 04:44:05');

--
-- Triggers `localadmins`
--
DELIMITER $$
CREATE TRIGGER `delete_localadmin` BEFORE DELETE ON `localadmins` FOR EACH ROW DELETE FROM users WHERE id = old.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `login_ads`
--

CREATE TABLE `login_ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `portal` tinyint(4) NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_as`
--

CREATE TABLE `login_as` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `la_key` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `portal` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `national_candidates`
--

CREATE TABLE `national_candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidate_bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `national_elections`
--

CREATE TABLE `national_elections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `national_election_votes`
--

CREATE TABLE `national_election_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `voter_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `national_election_winners`
--

CREATE TABLE `national_election_winners` (
  `id` bigint(20) NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `national_positions`
--

CREATE TABLE `national_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dashboard` tinyint(3) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `dashboard`, `title`, `subtitle`, `url`, `created_at`, `updated_at`) VALUES
(1, 2, 'This is a Test BOY', 'This is a test test test test test test test', 'https://www.youtube.com/watch?v=KNuoGeD9Qeo&t=122s', '2024-04-15 05:26:28', '2024-04-15 05:26:28'),
(2, 3, 'TESSSSST', 'TESSSSSTTESSSSSTTESSSSSTTESSSSST', NULL, '2024-04-28 03:16:33', '2024-04-28 03:16:33');

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

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0296cb67-2af0-46bf-bac3-1c63b651bd16', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 148, '{\"time\":\"2023-08-19T01:12:19.572227Z\",\"type\":\"info\",\"title\":\"New Vendor Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingvendors\"}', NULL, '2023-08-19 05:12:19', '2023-08-19 05:12:19'),
('0561f6ab-9745-4251-a4aa-3651145f8a50', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 193, '{\"time\":\"2024-01-12T23:48:11.312637Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('05c8ff35-afc0-45fa-aafd-9040b9895767', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 144, '{\"time\":\"2024-01-12T23:48:11.305711Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('0a27b85a-0658-4b38-88fe-af729d634867', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 197, '{\"time\":\"2023-08-05T18:28:46.999825Z\",\"type\":\"info\",\"title\":\"Student Bid Declined!\",\"message\":\"Your bid for Student 1Student 1 has been declined!\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/bids\\/history\"}', '2023-08-05 22:32:34', '2023-08-05 22:28:47', '2023-08-05 22:32:34'),
('0ea810c2-fa23-4234-8aa1-a0ec5f06436c', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2023-08-24T21:01:35.715283Z\",\"type\":\"info\",\"title\":\"Event Invitation Accepted\",\"message\":\"Hi Hi has accepted your invitation to Digitera\'s Main DJ Event.\",\"action\":\"\\/admin\\/events\\/students\\/14\"}', '2023-08-26 01:16:26', '2023-08-25 01:01:35', '2023-08-26 01:16:26'),
('1ee9658e-c159-41a0-befc-7af88d95e93b', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2024-01-12T23:44:06.211089Z\",\"type\":\"info\",\"title\":\"New Local Admin Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingvendors\"}', NULL, '2024-01-13 04:44:06', '2024-01-13 04:44:06'),
('1ffca082-acc1-4dc9-810d-f3234b16668a', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 150, '{\"time\":\"2023-08-19T01:12:19.580661Z\",\"type\":\"info\",\"title\":\"New Vendor Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingvendors\"}', NULL, '2023-08-19 05:12:19', '2023-08-19 05:12:19'),
('2f9a6b32-9c9d-4403-8765-0d7d0c2a8a48', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 108, '{\"time\":\"2024-01-12T23:48:11.303021Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('3343ca3e-7498-48fd-a7ca-e7af75302c9e', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 148, '{\"time\":\"2024-01-12T23:48:11.293661Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('4386ac9b-aac4-479c-80e9-f839c7c6f907', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 149, '{\"time\":\"2024-01-12T23:44:06.261009Z\",\"type\":\"info\",\"title\":\"New Local Admin Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingvendors\"}', NULL, '2024-01-13 04:44:06', '2024-01-13 04:44:06'),
('524e57bf-30ae-4e4d-8e6c-655f07a134de', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 194, '{\"time\":\"2024-01-12T23:48:11.314627Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('580fb7f9-5f9f-4dbf-9a8b-dfb78b2e04de', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 185, '{\"time\":\"2024-01-12T23:48:11.307642Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('69c0120a-85e1-47de-b511-206bf3509568', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 13, '{\"time\":\"2024-01-12T23:48:11.271550Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('7af33cc6-8454-4f1e-beef-347f5f0d33b6', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2023-08-19T01:51:11.001733Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingstudents\"}', '2023-08-26 01:16:03', '2023-08-19 05:51:11', '2023-08-26 01:16:03'),
('7dddd16a-2b2f-4ea5-9bde-8886dc289f45', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 148, '{\"time\":\"2024-01-12T23:44:06.258043Z\",\"type\":\"info\",\"title\":\"New Local Admin Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingvendors\"}', NULL, '2024-01-13 04:44:06', '2024-01-13 04:44:06'),
('7f8cce26-a9f8-4e16-b88d-ada05a569239', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 197, '{\"time\":\"2023-07-29T20:03:09.477733Z\",\"type\":\"info\",\"title\":\"Limo Group Bid Changed\",\"message\":\"Your bid for theBest Limo Group Editedlimo group has been chnaged. Please contact the limo group owner for more information.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/bids\\/history\"}', '2023-07-30 00:08:45', '2023-07-30 00:03:09', '2023-07-30 00:08:45'),
('8bb08253-3a9d-48eb-a3e1-ea1a5da58ffd', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2023-08-19T01:12:19.499838Z\",\"type\":\"info\",\"title\":\"New Vendor Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingvendors\"}', '2023-08-19 05:13:09', '2023-08-19 05:12:19', '2023-08-19 05:13:09'),
('93c76c20-7678-4720-80a5-541c23abf6b9', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 197, '{\"time\":\"2023-08-19T02:15:53.972713Z\",\"type\":\"info\",\"title\":\"Event Bid Rejected\",\"message\":\"Your bid for Digitera\'s Main DJ Event has been rejected!\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/bid\\/history\"}', '2023-10-01 02:48:19', '2023-08-19 06:15:53', '2023-10-01 02:48:19'),
('968a6138-ab6f-48df-9eeb-676dac91e051', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2023-08-25T21:40:46.311698Z\",\"type\":\"info\",\"title\":\"Event Invitation Accepted\",\"message\":\"Farhan Work has accepted your invitation to The Perfect Event For You!.\",\"action\":\"\\/admin\\/events\\/students\\/15\"}', '2023-08-26 01:41:33', '2023-08-26 01:40:46', '2023-08-26 01:41:33'),
('9bc268da-dec1-4a8a-85c4-c4bbc9de71ba', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 150, '{\"time\":\"2024-01-12T23:48:11.299733Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('a28b63cf-ec8c-4ba7-906e-32f2bc00fe68', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 195, '{\"time\":\"2024-01-12T23:48:11.317272Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('a44c7f6a-ef30-4db4-928c-803a5d99080d', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 213, '{\"time\":\"2023-08-25T21:36:24.565712Z\",\"type\":\"info\",\"title\":\"You have been invited to an event\",\"message\":\"You have been invited to the event by the Prom Committee. Please check the event page for more details.\",\"action\":\"\\/admin\\/events\"}', '2023-08-26 01:37:06', '2023-08-26 01:36:24', '2023-08-26 01:37:06'),
('ac1963fc-e445-4ab2-9e46-cd44297ee761', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 155, '{\"time\":\"2023-08-22T16:40:06.483151Z\",\"type\":\"info\",\"title\":\"You have been invited to join a limo group!\",\"message\":\"You have been invited to join a limo group by Hi Hi. Please check your limo group invitations page to accept or reject the invitation.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/limo-groups\"}', '2023-08-22 20:44:00', '2023-08-22 20:40:06', '2023-08-22 20:44:00'),
('ae038fc6-0248-49e8-ac35-c4408e08d911', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 150, '{\"time\":\"2023-08-19T01:51:11.025434Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingstudents\"}', NULL, '2023-08-19 05:51:11', '2023-08-19 05:51:11'),
('bf0ddfa9-9355-40a1-ad2a-03ca3a89546e', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 150, '{\"time\":\"2024-01-12T23:44:06.265266Z\",\"type\":\"info\",\"title\":\"New Local Admin Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingvendors\"}', NULL, '2024-01-13 04:44:06', '2024-01-13 04:44:06'),
('c01fd227-e853-4ae1-9728-6f164e6fba0b', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 13, '{\"time\":\"2023-08-09T22:13:02.217038Z\",\"type\":\"info\",\"title\":\"Event Invitation Accepted\",\"message\":\"Student 1 Student 1 has accepted your invitation to Digitera\'s Main DJ Event.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/events\\/students\\/14\"}', '2023-08-10 02:14:50', '2023-08-10 02:13:02', '2023-08-10 02:14:50'),
('c3ae3a46-1a60-42ef-990a-02f5bc9bba25', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 149, '{\"time\":\"2024-01-12T23:48:11.297035Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('ca7ccec5-91d3-4117-8977-18f9b4fdde87', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 149, '{\"time\":\"2023-08-19T01:12:19.576583Z\",\"type\":\"info\",\"title\":\"New Vendor Registered\",\"message\":\"A new vendor has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingvendors\"}', NULL, '2023-08-19 05:12:19', '2023-08-19 05:12:19'),
('dcb154ce-e0ad-478e-b510-628de52943a3', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 148, '{\"time\":\"2023-08-19T01:51:11.019033Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingstudents\"}', NULL, '2023-08-19 05:51:11', '2023-08-19 05:51:11'),
('e9f09d93-843b-44f4-a2c7-3908250576ec', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 208, '{\"time\":\"2024-01-12T23:48:11.319349Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('eb2721ee-faca-4183-861d-ebbadbb68844', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 213, '{\"time\":\"2023-08-22T16:47:55.122415Z\",\"type\":\"info\",\"title\":\"You have been invited to join a limo group!\",\"message\":\"You have been invited to join a limo group by Student 1 Student 1. Please check your limo group invitations page to accept or reject the invitation.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/limo-groups\"}', '2023-08-22 20:48:24', '2023-08-22 20:47:55', '2023-08-22 20:48:24'),
('f24ce8e3-bf71-4271-9290-af13a7d9aa40', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'Orchid\\Platform\\Models\\User', 192, '{\"time\":\"2024-01-12T23:48:11.310237Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"\\/admin\\/pendingstudents\"}', NULL, '2024-01-13 04:48:11', '2024-01-13 04:48:11'),
('f77c6856-9f8d-4765-a4c2-5ef5caa3f00b', 'Orchid\\Platform\\Notifications\\DashboardMessage', 'App\\Models\\User', 149, '{\"time\":\"2023-08-19T01:51:11.022380Z\",\"type\":\"info\",\"title\":\"New Student Registered\",\"message\":\"A new student has registered. Please approve or deny their account.\",\"action\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/pendingstudents\"}', NULL, '2023-08-19 05:51:11', '2023-08-19 05:51:11');

-- --------------------------------------------------------

--
-- Table structure for table `no_play_songs`
--

CREATE TABLE `no_play_songs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `song_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `no_play_songs`
--

INSERT INTO `no_play_songs` (`id`, `song_id`, `event_id`, `created_at`, `updated_at`) VALUES
(5, 1, 14, '2023-06-16 00:35:11', '2023-06-16 00:35:11');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payment_amount` decimal(10,0) NOT NULL DEFAULT 0,
  `credits_given` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `payment_amount`, `credits_given`, `created_at`, `updated_at`) VALUES
(1, 197, '29', 10, '2023-10-01 02:43:53', '2023-10-01 02:43:53');

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

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 205, 'myapptoken', '4b7efea4c0cc4a346ab9f248e2e328a96622d334c9ccfdf4f11771e7a95392c6', '[\"*\"]', NULL, NULL, '2023-03-27 00:12:32', '2023-03-27 00:12:32'),
(2, 'App\\Models\\User', 205, 'myapptoken', '92ccaee44e20abc7e97f6614df419be5d12c2fec9039f90889fef6e3897841cd', '[\"*\"]', NULL, NULL, '2023-03-27 01:00:15', '2023-03-27 01:00:15'),
(3, 'App\\Models\\User', 205, 'myapptoken', 'ecc18c18bc10519193071f6c2948ee9be3037061bc429a9c57f13e815d7aeda1', '[\"*\"]', NULL, NULL, '2023-03-27 14:45:02', '2023-03-27 14:45:02'),
(4, 'App\\Models\\User', 205, 'myapptoken', '47495b9907931fdddd422460c5b233cc6944e06194bd4855223037e01010574a', '[\"*\"]', NULL, NULL, '2023-03-27 14:45:52', '2023-03-27 14:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approved` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `poll_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_name`, `election_id`, `created_at`, `updated_at`) VALUES
(1, 'Test', 1, '2023-04-27 02:49:40', '2023-04-27 02:49:40'),
(2, 'Test', 1, '2023-04-27 02:49:51', '2023-04-27 02:49:51'),
(5, 'King', 4, '2023-06-04 19:22:53', '2023-06-26 21:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `promfluencers`
--

CREATE TABLE `promfluencers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snapchat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promfluencers`
--

INSERT INTO `promfluencers` (`id`, `user_id`, `instagram`, `tiktok`, `snapchat`, `youtube`, `created_at`, `updated_at`) VALUES
(1, 155, 'farhan_khan435', NULL, NULL, NULL, '2024-04-28 03:10:15', '2024-04-28 03:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `regional_candidates`
--

CREATE TABLE `regional_candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `candidate_bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regional_elections`
--

CREATE TABLE `regional_elections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regional_election_votes`
--

CREATE TABLE `regional_election_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `voter_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regional_election_winners`
--

CREATE TABLE `regional_election_winners` (
  `id` int(11) NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `national_experience` tinyint(4) DEFAULT NULL COMMENT '1 = Yes 0 = No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regional_positions`
--

CREATE TABLE `regional_positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `permissions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Super Admin', '{\"platform.systems.roles\":true,\"platform.systems.users\":true,\"platform.systems.attachment\":true,\"platform.index\":true}', '2023-01-08 23:26:22', '2023-01-08 23:26:22'),
(2, 'Local Admin', 'Local Admin', '{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":\"1\"}', '2023-01-08 23:26:22', '2023-03-27 23:49:48'),
(3, 'Student', 'Student', '{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":\"1\"}', '2023-01-08 23:26:22', '2023-03-27 23:49:53'),
(4, 'Vendor', 'Vendor', '{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":\"1\"}', '2023-01-08 23:26:22', '2023-03-27 23:46:51'),
(5, 'Teacher', 'Teacher', '{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":\"1\"}', '2023-01-08 23:26:22', '2023-03-27 23:49:59');

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
(205, 3, '2023-03-27 00:13:31', '2023-03-27 00:13:31'),
(207, 3, '2023-06-15 23:59:27', '2023-06-15 23:59:27'),
(208, 2, '2023-07-19 21:00:54', '2023-07-19 21:00:54'),
(209, 4, '2023-07-29 23:39:18', '2023-07-29 23:39:18'),
(210, 4, '2023-08-01 22:31:18', '2023-08-01 22:31:18'),
(212, 3, '2023-08-19 05:51:55', '2023-08-19 05:51:55'),
(213, 3, '2023-08-22 20:46:58', '2023-08-22 20:46:58'),
(216, 3, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(217, 3, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(218, 3, '2024-02-25 04:50:21', '2024-02-25 04:50:21');

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
-- Table structure for table `school_dresses`
--

CREATE TABLE `school_dresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `dress_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seatings`
--

CREATE TABLE `seatings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` smallint(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seatings`
--

INSERT INTO `seatings` (`id`, `event_id`, `tablename`, `capacity`, `created_at`, `updated_at`) VALUES
(6, 15, 'yoyo Table', 15, '2023-03-03 00:52:42', '2023-03-03 00:52:42'),
(9, 14, 'Cool Kidz Table', 7, '2023-05-20 20:21:06', '2024-02-26 05:22:22'),
(10, 14, 'DJ\'s Table', 8, '2023-05-20 20:21:22', '2024-02-25 04:39:42'),
(11, 14, 'dfg', 10, '2023-05-20 20:26:20', '2023-05-20 21:33:46'),
(12, 14, 'test test', 5, '2023-05-20 20:36:10', '2023-05-20 20:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ordering` double DEFAULT NULL,
  `guide_id` bigint(20) UNSIGNED NOT NULL,
  `section_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `ordering`, `guide_id`, `section_name`, `created_at`, `updated_at`) VALUES
(1, 1.3, 1, 'Hiring Procedures', '2023-03-30 00:56:36', '2023-04-01 23:42:59'),
(2, 1.1, 1, 'Venue Legalities', '2023-03-30 00:57:09', '2023-04-01 23:42:33'),
(3, 1, 2, 'Students Taste', '2023-03-30 00:57:37', NULL),
(5, 1.2, 1, 'Test', '2023-03-30 05:58:32', '2023-03-30 05:58:32');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `time`, `role`, `created_at`, `updated_at`) VALUES
(1, 151, 116, '2', '2023-12-24 03:04:04', '2023-12-24 03:04:04'),
(2, 155, 0, '3', '2023-12-24 03:12:49', '2023-12-24 03:12:49'),
(3, 155, 0, '3', '2023-12-24 04:23:00', '2023-12-24 04:23:00'),
(4, 151, 55, '2', '2023-12-24 23:17:34', '2023-12-24 23:17:34'),
(5, 155, 104, '3', '2023-12-24 23:19:48', '2023-12-24 23:19:48'),
(6, 155, 5, '3', '2024-02-26 04:21:58', '2024-02-26 04:21:58'),
(7, 155, 49, '3', '2024-02-26 04:23:01', '2024-02-26 04:23:01'),
(8, 155, 775, '3', '2024-02-26 05:21:36', '2024-02-26 05:21:36'),
(9, 151, 24, '2', '2024-02-26 05:22:28', '2024-02-26 05:22:28'),
(10, 155, 2146, '3', '2024-02-26 05:58:35', '2024-02-26 05:58:35'),
(11, 13, 58, '1', '2024-04-15 05:26:44', '2024-04-15 05:26:44'),
(12, 13, 0, '1', '2024-04-19 03:14:37', '2024-04-19 03:14:37'),
(13, 13, 56, '1', '2024-04-28 03:16:44', '2024-04-28 03:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artists` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `explicit` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `title`, `artists`, `explicit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Baby Shark', 'Pinkfong', 0, 1, '2023-03-21 18:35:57', '2024-02-22 01:07:06'),
(2, 'Stronger', 'Kanye West', 0, 1, '2023-03-21 18:36:24', '2024-02-22 01:07:06'),
(3, 'Baby Love', 'Baby Love', 0, 1, '2023-03-21 18:36:24', '2024-02-22 01:07:06'),
(4, 'Truth Hurts', 'Lizzo', 0, 1, '2023-03-21 18:41:23', '2024-02-22 01:07:06'),
(5, 'Without You', 'Harry Nilsson', 0, 1, '2023-03-21 18:41:32', '2024-02-22 01:07:06'),
(6, 'Harry Nilsson', 'Harry Nilsson', 0, 1, '2023-03-21 18:41:38', '2024-02-22 01:04:32'),
(7, 'So What', 'Miles Davis', 0, 1, '2023-03-21 18:41:38', '2024-02-22 01:04:44'),
(8, 'Old Town Road', 'Lil Nas X', 0, 1, '2023-03-21 18:42:38', '2024-02-22 01:04:20');

-- --------------------------------------------------------

--
-- Table structure for table `song_requests`
--

CREATE TABLE `song_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `song_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
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
  `grade` smallint(6) DEFAULT NULL,
  `phonenumber` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_status` int(11) DEFAULT 0,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allergies` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interested_vendor_categories` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`interested_vendor_categories`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `school_id`, `firstname`, `lastname`, `grade`, `phonenumber`, `email`, `account_status`, `school`, `allergies`, `interested_vendor_categories`, `created_at`, `updated_at`) VALUES
(105, 145, 53, 'John', 'Smith', 12, '(465) 987-9797', 'johnsmith@gmail.com', 1, 'Colonel By Secondary School', NULL, NULL, '2022-11-20 11:14:59', '2023-06-15 23:57:13'),
(106, 146, 51, 'Jane', 'Doe', 10, '(456) 879-4564', 'janedoe@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Peanuts', NULL, '2022-11-20 11:23:42', '2022-11-26 22:17:01'),
(107, 152, 51, 'Hey', 'Man', 12, '(546) 465-6464', 'heyman@heyman.com', 1, 'Digitera School of Digital Marketing & Software', 'Dairy', NULL, '2022-12-02 01:04:53', '2022-12-04 21:44:41'),
(109, 154, 51, 'retert', 'ert', 9, '(546) 464-6465', 'loca65+ladmin001@promplanner.com', 1, 'Digitera School of Digital Marketing & Software', 'Peanuts', NULL, '2022-12-02 01:13:35', '2022-12-02 01:14:41'),
(110, 155, 51, 'Student 1', 'Student 1', 12, '(546) 897-8921', 'student001@promplanner.com', 1, 'Digitera School of Digital Marketing & Software', 'Peanuts', NULL, '2022-12-05 18:58:15', '2023-07-27 01:35:08'),
(121, 169, 51, 'Import 1', 'efwefwef', 9, '12345678910', 'import1@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Nuts', NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(122, 170, 51, 'Import 2', 'wefwef', 10, '9632587459', 'import2@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Nutseeee', NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(124, 198, 51, 'Zg man', 'Big man tings', 10, '(612) 354-8954', 'bigman@tings.com', 1, NULL, 'Bad Grades', NULL, '2023-02-14 23:43:02', '2023-02-15 01:25:38'),
(126, 205, 3, 'Ethan', 'Guan', 12, '613-287-1612', 'guanethan123@gmail.com', 1, 'Torphy-Cole', 'none', NULL, '2023-03-27 00:12:32', '2023-03-27 00:13:31'),
(127, 206, 51, 'dfgdfg', 'dfgdfg', 12, '(123) 456-1234', 'johnsmith@hotmales.com', 1, 'Digitera School of Digital Marketing & Software', NULL, NULL, '2023-06-15 23:55:18', '2023-06-15 23:55:18'),
(128, 207, 51, 'Hi', 'Hi', 12, '(554) 654-6546', 'Hi@Hi.com', 1, 'Digitera School of Digital Marketing & Software', NULL, NULL, '2023-06-15 23:59:27', '2023-07-19 02:49:26'),
(129, 212, 51, 'fgdfg', 'dfgdfg', 12, '(546) 456-4656', 'dfgdfg@ffssdfsd.com', 1, 'Digitera School of Digital Marketing & Software', 'None', NULL, '2023-08-19 05:51:10', '2023-08-19 05:51:55'),
(130, 213, 51, 'Farhan', 'Work', 12, '(546) 464-5645', 'farhan.work435@gmail.com', 1, 'Digitera School of Digital Marketing & Software', NULL, NULL, '2023-08-22 20:46:58', '2023-08-22 20:46:58'),
(131, 215, 53, 'dsasda', 'sdasd', 9, '(544) 564-5465', 'aasdasd@gmail.com', 0, 'Colonel By Secondary School', NULL, NULL, '2024-01-13 04:48:10', '2024-01-13 04:48:10'),
(132, 216, 51, 'John', 'Doe', 11, '(234) 567-8910', 'johndoe@gmail.com', 1, 'Digitera School of Digital Marketing & Software', 'Eggs', NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(133, 217, 51, 'Mary', 'Smith', 9, '(345) 678 9012', 'm.smith.44@yahoo.com', 1, 'Digitera School of Digital Marketing & Software', '', NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(134, 218, 51, 'Real', 'Person', 12, '(456)-789-1011', 'fake_email_address@example.net', 1, 'Digitera School of Digital Marketing & Software', 'Chestnuts and pecans', NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21');

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
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_instructions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_bids`
--

INSERT INTO `student_bids` (`id`, `user_id`, `student_user_id`, `region_id`, `package_id`, `category_id`, `school_name`, `notes`, `contact_instructions`, `company_name`, `url`, `status`, `created_at`, `updated_at`) VALUES
(4, 197, 155, 1, 6, 11, 'Digitera School of Digital Marketing & Software', 'tyrtyrtyrtyrtyrty', 'rtyrtyrtyrtyrtyrty', 'Money Jockeys', 'https://promplanner.app/', 2, '2023-04-14 04:44:53', '2023-08-05 22:28:46'),
(5, 197, 155, 1, 3, 11, 'Digitera School of Digital Marketing & Software', 'trywrtyretyertyer', 'tyertyretyertyerty', 'Money Jockeys', 'https://promplanner.app/', 1, '2023-04-14 04:45:03', '2023-04-14 04:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `student_poll_votes`
--

CREATE TABLE `student_poll_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `poll_id` bigint(20) UNSIGNED NOT NULL,
  `poll_options_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_specs`
--

CREATE TABLE `student_specs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `age` tinyint(4) DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hair_colour` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hair_style` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complexion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bust` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in cm',
  `waist` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in cm',
  `hips` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in cm',
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in cm',
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'in pounds',
  `lip_style` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eye_colour` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `universal_expenses_revenues`
--

CREATE TABLE `universal_expenses_revenues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=Expense 2=Revenue',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_updated_user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universal_expenses_revenues`
--

INSERT INTO `universal_expenses_revenues` (`id`, `name`, `type`, `created_at`, `updated_at`, `last_updated_user_id`) VALUES
(1, 'Tickets', 2, '2023-12-22 05:00:00', '0000-00-00 00:00:00', NULL),
(2, 'Food', 1, '2023-12-22 20:53:15', '2023-12-22 20:53:15', NULL);

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
  `account_status` int(11) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pfp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `firstname`, `lastname`, `email`, `phonenumber`, `role`, `country`, `currentPlan`, `account_status`, `email_verified_at`, `password`, `pfp`, `remember_token`, `created_at`, `updated_at`) VALUES
(13, 'Big Man Admin 🔥', '', '', 'superadmin@gmail.com', NULL, 1, NULL, NULL, 1, NULL, '$2y$10$kShmCgweW1ieZg4S6Lf.dOwDT0xhVN9Gb62l8doUSo56qcsWoR9Ee', 'https://promplanner.s3.amazonaws.com/2024/02/21/222f05cc2d7902d9851e5f78b7c23ddca6f79af8.png', 'iTJqVohCaB4PoXQ6maskVOI43gjxNivg7PLZO5eRT4XIXEjSCqydYfTryade', '2022-10-16 21:27:25', '2024-02-22 00:49:30'),
(52, 'Kavon Reinger', 'This is another test for the user', 'test', 'test@example.net', NULL, 3, 'Zimbabwe', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'xekMstwSjQ', '2022-10-17 20:39:55', '2022-10-18 00:15:02'),
(67, 'Prof. Clare Turcotte', 'Ellis', 'Huels', 'williamson@example.net', NULL, 3, 'Germany', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'EFxEkuvIMI', '2022-10-17 20:39:55', '2022-11-13 01:28:54'),
(68, 'Jude Nicolas', 'Gabriel', 'Prosacco', 'koss.gerald@example.com', NULL, 3, 'Philippines', NULL, 0, '2022-10-17 20:39:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'mOY699FaIx', '2022-10-17 20:39:55', '2022-11-15 00:18:28'),
(72, 'Alfonzo Conn', 'Kyra', 'Schneider', 'ndietrich@example.org', NULL, 3, 'Canada', NULL, 0, '2022-10-17 20:40:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'sZVhTrQr0D', '2022-10-17 20:40:39', '2022-11-15 00:34:27'),
(73, 'Mrs. Lauren Rau', 'Anais', 'Waelchi', 'claire39@example.com', NULL, 3, 'Germany', NULL, 0, '2022-10-17 20:40:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'LwRVkEPdFT', '2022-10-17 20:40:39', '2022-11-02 21:38:59'),
(108, 'Kade Abbott', 'Camille', 'Williams', 'camillewilliams@gmail.com', '+1-872-648-9121', 2, 'Canada', NULL, 1, '2022-10-19 21:08:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'C5iVHaFVKr', '2022-10-19 21:08:55', '2022-11-19 04:25:53'),
(114, 'Maxie Boyer', 'Sigrid', 'Lindgren', 'manuela.williamson@example.com', '951.460.6134', 2, 'Pakistan', NULL, 1, '2022-10-19 21:09:00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'jxguMsVoxm', '2022-10-19 21:09:00', '2022-11-19 04:25:53'),
(124, 'coolkid', 'Cool', 'Kid', 'coolkid@coolkid.com', '45689645', 3, 'Comoros', NULL, 0, NULL, '', NULL, NULL, '2022-10-26 22:55:10', '2022-11-09 00:22:29'),
(125, 'New Student', 'Sanjay', 'Patel', 'sanjaypatel@gmail.com', '45678123', 3, 'Canada', NULL, 0, NULL, '', NULL, NULL, NULL, '2022-11-09 03:37:18'),
(132, 'Testing add student', 'John', 'Connors', 'johnconnors@gmail.com', '(564) 645-5646', 3, 'Canada', NULL, 0, NULL, '$2y$10$glFxuDOe8HbDTv41xXlRyOtbOZrtu7EtIEjv/Ul/Pv7M95cDOSJZS', NULL, NULL, '2022-11-04 23:28:42', '2022-11-15 00:57:04'),
(134, NULL, 'Test', 'Test', 'hi@hii.com', '(456) 564-654_', 5, 'Test', NULL, 0, NULL, NULL, NULL, NULL, '2022-11-10 23:38:37', '2022-11-10 23:38:37'),
(140, 'dfgdfggdfgdfg', 'dfgdfg', 'gdfgdfg', 'dfgddgf@jkjhjk.com', '(554) 654-5464', 5, 'ertter', NULL, 0, NULL, NULL, NULL, NULL, '2022-11-14 23:12:55', '2022-11-14 23:12:55'),
(144, 'Permissions', 'Permissions', 'Permissions', 'Permissions@Permissions.com', '(456) 456-4546', 2, 'Canada', NULL, 1, NULL, '$2y$10$/jt9btmMXXBr15i6c0ClKO/73wbbKMzqzB/2pFyQojQ618PM7ZesG', NULL, '2HSxoF6o2OGRx8MZjoAIlCoZzGWJzcZ4lh97iNgEMv9FBl3scogjDDzb3kWz', '2022-11-19 04:27:23', '2022-11-19 04:40:43'),
(145, 'Big Man John', 'John', 'Smith', 'johnsmith@gmail.com', '(465) 987-9797', 3, 'Canada', NULL, 1, NULL, '$2y$10$9ZXaWr1D1gJZ5LUk88KWUuJccYYU9rx9OvjDaVIKtdE/dapiLl0J.', NULL, NULL, '2022-11-20 11:14:59', '2023-06-15 23:57:13'),
(146, 'Jane Doe', 'Jane', 'Doe', 'janedoe@gmail.com', '(456) 879-4564', 3, 'Canada', NULL, 1, NULL, '$2y$10$PwXqJuw5RkpNkuWF38Wuquldp2SwT0kBBogY6QgYKRkDQzeNyxhQG', NULL, NULL, '2022-11-20 11:23:42', '2022-11-26 22:17:01'),
(148, 'Super Admin 1', 'Admin001', 'Admin001', 'admin001@promplanner.com', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$l7WjvuqK6ZCPGmLKLFy0y.287OiRMarJO6UzjDJlv1LIi57oouvl2', NULL, 'v5MeVDCjb01jQacTOWLm4xjN85KKqmNUgV3gNYBHuqmqsXtMebtldhExOI7J', NULL, NULL),
(149, 'Super Admin 2', 'Admin002', 'Admin002', 'admin002@promplanner.com', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$ul5yg6bZ47cb4ObQpFy3fO0MR6WjyMts7D6hEkU6ukKFMPPE0gAuu', NULL, 'dhiYncQ7UYPaA4x88h4k6HUYP4JPc91YuJcSODgbZbGvUs1kA1SUZxmi7vIh', NULL, NULL),
(150, 'Super Admin 3', 'Admin003', 'Admin003', '		\r\nadmin003@promplanner.com\r\n', NULL, 1, NULL, NULL, 0, NULL, '$2y$10$OSjHjhsYws3ep7ces2HtCOGu/Q62Ki6ud8Zrk2BFG6RTSiCnYBKmS', NULL, 'lhf0VN0la6IXJ0JfPPqomBG8cpxodRAxARKMhQbkUybsz2oOpvNY7JwBvslc', NULL, NULL),
(151, 'Local Admin 1', 'Local Admin 1', 'Local Admin 1', 'localadmin001@promplanner.com', '(546) 456-4564', 2, 'Canada', NULL, 1, NULL, '$2y$10$nE2mIZ/TMlBq7SC6m/yPnetNlGmODb4GSy3MrIpFf9zRDhWocqCOG', 'https://promplanner.s3.amazonaws.com/2023/12/23/765075f3cbb6b2c78211bd5aa47864e4dbd9c974.png', 'eCGjCrIiSDEb35oh4GwyzeaUWBliTPGjfn2S2ReehSDBy0RCBg6TTgoBdcGK', '2022-12-02 00:58:48', '2023-12-24 03:14:48'),
(152, 'heyman', 'Hey', 'Man', 'heyman@heyman.com', '(546) 465-6464', 3, 'Canada', NULL, 1, NULL, '$2y$10$D4sD55GOTHr6hIrxQsG7L.rsn6uwLgUFCC0yXs.m0Fbk33WzyItxC', NULL, NULL, '2022-12-02 01:04:53', '2022-12-04 21:44:41'),
(154, 'localadmin001@promplanner.com', 'retert', 'ert', 'loca65+ladmin001@promplanner.com', '(546) 464-6465', 3, 'Canada', NULL, 1, NULL, '$2y$10$OXW.OPZd1NBpH0fTrqoQjOnxs9V07Rbn.H47yBrg.bcvDYp.0ZH4O', NULL, NULL, '2022-12-02 01:13:35', '2022-12-02 01:14:41'),
(155, 'Student 1', 'Student 1', 'Student 1', 'student001@promplanner.com', '(546) 897-8921', 3, 'Canada', NULL, 1, NULL, '$2y$10$N5f8aqLb2QVc09MgKc1hSuxlyWJeb522cBHpSHE2vb15WjZi5VAUa', 'https://promplanner.s3.amazonaws.com/2023/07/26/a16dd23d9874710210b4e904ccfc9cdb92de2675.png', 'Iy2F7twLWfUGNqj7G2RnoR8PsURkKUG0zf2oC1Ah0adO0Ywx7KSb68FFYmwC', '2022-12-05 18:58:15', '2023-07-27 01:35:08'),
(169, 'Import 1', 'Import 1', 'efwefwef', 'import1@gmail.com', '12345678910', 3, 'Canada', NULL, 1, NULL, '$2y$10$.Tzvl/8uuCeVHLxilSrk9ewJmOAvnVJH5c1L4PD2JocHkVTxg63xe', NULL, NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(170, 'Import 2', 'Import 2', 'wefwef', 'import2@gmail.com', '9632587459', 3, 'Canada', NULL, 1, NULL, '$2y$10$zDlcJegCYOuBoqkeQpKKieBlT8I5nmcpxgzOsjBCqeccgYOwqJPNi', NULL, NULL, '2022-12-10 22:23:29', '2022-12-10 22:23:29'),
(181, 'sdfsdfsdf', 'Update Vendor', 'Update Vendor', 'UpdateVendor@hotmail.com', '(455) 674-9877', 4, 'Costa Rica', NULL, 1, NULL, '$2y$10$DYa3I4sOLm31EwAPq0xzbesh8Mp2RYSlw8bbZoJawiKypoAUfASqm', NULL, NULL, '2022-12-26 02:01:31', '2023-01-09 04:21:18'),
(185, 'RoleUser Perms', 'RoleUser Perms', 'RoleUser Perms', 'RoleUserPerms@RoleUserPerms.com', '(556) 466-4645', 2, 'Canada', NULL, 1, NULL, '$2y$10$iKr.VLPa.6bID.dCmNHuVuJZ1YESN0Nl6wKrYahO2nlbXtxoJBoNW', NULL, '21lrVpmJjszaOvkwJQGHQuB19ZfXYqe7MN9HKAGg9T91RX4mJRNaWmSu1KBZ', '2023-01-07 23:45:06', '2023-01-07 23:45:06'),
(188, 'Joe ', 'Joe ', 'Biden', 'bidenjoe@isuck.com', 'dssdddsddss', 4, 'USA', NULL, 1, NULL, '$2y$10$SUrjCNKKrpkznsPR4RPUmOPGPZnHT9uWvFarPqtzxlYKUCDn8n46i', NULL, NULL, '2023-01-09 04:14:52', '2023-01-09 04:14:52'),
(190, 'Donald ', 'Donald ', 'Trump', 'trumpman@maga.com', 'dssdddsddss', 4, 'USA', NULL, 1, NULL, '$2y$10$cqjoswklZ4erE4sZu5eE6OpdlfN7yQSTG9bCRmZtFIcLGw1MwGaOC', NULL, NULL, '2023-01-09 04:18:15', '2023-01-09 04:18:15'),
(192, 'PendingTest', 'PendingTest', 'PendingTest', 'PendingTest@PendingTest.com', '(778) 979-7979', 2, 'Canada', NULL, 1, NULL, '$2y$10$0DWsyZHm9E7ctCLAC2/2J..f6fPNGB1MsZc.D/H2Gz4lP7tR7rmOO', NULL, 'xSFMpXUv8nzIK8K5hiccHK9KtSGgRgkDnHC2fSVPIYEiFuCQFzZ957zfxcAi', '2023-01-10 23:54:52', '2023-01-10 23:55:32'),
(193, 'Local admin Import 1', 'Local admin Import 1', 'efwefwef', 'import111@gmail.com', '12345678910', 2, 'Canada', NULL, 1, NULL, '$2y$10$fBB56NJVIq2jzE5bUG4fPOSFcAEzFgs6WXy23DfxaoZfMuKdcFIIa', NULL, NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(194, 'Local admin Import 2', 'Local admin Import 2', 'wefwef', 'import222@gmail.com', '9632587459', 2, 'Canada', NULL, 1, NULL, '$2y$10$mlixKkSmH03ulCZn8K49ke4y1Gmbolu2WFfansuifJp2QvOmb3WiW', NULL, NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(195, 'Local admin Import 3', 'Local admin Import 3', 'wefwef', 'import333@gmail.com', '3698745236', 2, 'Canada', NULL, 1, NULL, '$2y$10$N4kQN9DKvJ1b87ycpA2aO.Gp55U3JV0.MyCO.0Er7wpsyuINGd1B2', NULL, NULL, '2023-01-13 02:05:53', '2023-01-13 02:05:53'),
(196, 'Ling Long', 'Trump Man', 'Ling Long', 'donaldtrump@trump.com', '(454) 546-4566', 4, 'USA', NULL, 1, NULL, '$2y$10$/0C9DuazC.JKb6Zh/uKhZuGPnjsKNvjsFzxjEDvuYdV4uN77dt4TO', NULL, NULL, '2023-01-17 01:10:39', '2023-01-17 01:10:39'),
(197, 'Vendor001', 'Vendor001', 'Vendor001', 'vendor001@promplanner.com', '(454) 654-6546', 4, 'Canada', NULL, 1, NULL, '$2y$10$RCkk.xuRaueua/7bkthq7OJjLnwmjfPPMYbuI06Xckubita5l0LrW', 'https://promplanner.s3.amazonaws.com/2023/07/15/8c8eaea188336a6e6b0b62d8f6fbd91e05e3e02e.png', 'doDxBmSq9sOFd6alGWcZD2WkcqhEr0oHU4dP3zWAaHxs1EerXI5REFvtILwW', '2023-01-21 02:41:46', '2023-07-15 23:00:55'),
(198, 'bigman101', 'Zg man', 'Big man tings', 'bigman@tings.com', '(612) 354-8954', 3, 'Canada', NULL, 1, NULL, '$2y$10$B10Kj5SYsPn6EKwkyQRbPO7UmY/YqPWkzzzyiU0dZ3DWx.xLRnJpy', NULL, NULL, '2023-02-14 23:43:02', '2023-02-15 01:25:38'),
(205, 'etanguan', 'Ethan', 'Guan', 'guanethan123@gmail.com', '613-287-1612', 3, 'Philippines', NULL, 1, NULL, '$2y$10$NQk4fTkCwz5R4dvcSILnW.36rqdQkMgOvl6ewaZNJc7Auabs5.Kbq', NULL, NULL, '2023-03-27 00:12:32', '2023-03-27 00:13:31'),
(206, 'hgjh', 'dfgdfg', 'dfgdfg', 'johnsmith@hotmales.com', '(123) 456-1234', 3, 'Canada', NULL, 0, NULL, '$2y$10$M9d5cT6l4gKVgM2NLkoFCeBYZzDrQjelgu0NmyfIYH1S6UGAaIRPO', NULL, 'CUgw7kb0nu3boyDdLtQfaExJvTin7wKvvP8vcFFFMagnrgOXby2m2vxhk5Rp', '2023-06-15 23:55:18', '2023-06-15 23:55:18'),
(207, 'Hi', 'Hi', 'Hi', 'Hi@Hi.com', '(554) 654-6546', 3, 'Canada', NULL, 1, NULL, '$2y$10$hOcPNnJ12wXMcAzv9NYj2ulADXHPV28mqCUEaw4xdI6qXkIXR3GK.', 'https://promplanner.s3.amazonaws.com/2023/07/18/d986f0eaef32fdb913996c61d012d6c8f9355140.png', '7J14NyP2v8YTufWxVIb3GsVioGfbUPnMz4IEowNEQwtDhWMTe9Z0S0y1DDyq', '2023-06-15 23:59:27', '2023-07-19 02:49:26'),
(208, 'Farhan', 'Farhan', 'Khan', 'farhan.k2005@gmail.com', '(123) 456-7890', 2, 'Canada', NULL, 1, NULL, '$2y$10$5fxDoxckplZCoMUwV6wHHuaV.4qDveDqHEr//IoJGAaOQ.Oe9jCb2', NULL, NULL, '2023-07-19 21:00:54', '2023-07-19 21:00:54'),
(209, 'New Vendor', 'New', 'Vendor', 'newvendor@test.com', '(123) 456-789_', 4, 'Canada', NULL, 1, NULL, '$2y$10$1KevHCEsPX2kocUdG9lLYeSnWPix3we6uXKBCOAHUc3LovedO.bxy', 'https://promplanner.s3.amazonaws.com/2023/07/29/dc9f6ecc4d6256cce8aedde687c679ce5449d8bd.png', 'kRiO91CXPUcDNua1xBZDG9xDNYhUqg5842Hd1ogWTDKiBEgNOB7rI4iQeEEG', '2023-07-29 23:39:18', '2023-07-29 23:42:31'),
(210, 'superadmin@gmail.com', 'Salon', 'Vendor', 'salonpros@gmail.com', '(456) 123-7895', 4, 'Canada', NULL, 1, NULL, '$2y$10$kpY/qfOx4p91evC6Vl.SVeR8IvVFCEt.k02T3UPbtXm14SEZKgaa6', NULL, 'UkpxEz9xP81CMT1eErL9v8xuKzzcbERXqpUHtgcSFeeBDWAg5i0n2Wmh1of5', '2023-08-01 22:31:18', '2024-02-22 01:52:13'),
(211, 'NewMan', 'New', 'Man', 'NewManInc@gmail.com', '(445) 454-5454', 4, 'Canada', NULL, 0, NULL, '$2y$10$4shSu7Li3eH649d9Rn63TeuQm7wDdJQa0F4KsR7YVZbMeuqtLKdrm', NULL, NULL, '2023-08-19 05:12:18', '2023-08-19 05:12:18'),
(212, 'dfgdfg', 'fgdfg', 'dfgdfg', 'dfgdfg@ffssdfsd.com', '(546) 456-4656', 3, 'Canada', NULL, 1, NULL, '$2y$10$ZqnBQ2Oww4Yd2EBtivzcu.gGYnRou17XNQOYVaAyb8E2SbWYJba8S', NULL, NULL, '2023-08-19 05:51:10', '2023-08-19 05:51:55'),
(213, 'FWork', 'Farhan', 'Work', 'farhan.work435@gmail.com', '(546) 464-5645', 3, 'Canada', NULL, 1, NULL, '$2y$10$pmDpTtDHAF42eokADMX4P.fChup.NEK55lBc7PWJBfLGXobySEcKK', NULL, '3fhWZMjHuPjpLD1mzldKDEUlQZHhw0IBZiNDqOpgRSVk8diUGxwLS0AtwpdP', '2023-08-22 20:46:58', '2023-08-22 20:46:58'),
(214, 'asdasd', 'zdasd', 'asdasdasd', 'faasdasdasdnasdasd05@gmail.com', '(544) 564-5465', 2, 'Canada', NULL, 0, NULL, '$2y$10$DzF3pHPg0gO48QjjVP/ime8W8GzskUF1161SjJ9K5/EznrlY.3fpK', NULL, NULL, '2024-01-13 04:44:05', '2024-01-13 04:44:05'),
(215, 'asdasd', 'dsasda', 'sdasd', 'aasdasd@gmail.com', '(544) 564-5465', 3, 'Canada', NULL, 0, NULL, '$2y$10$prWDa4qLkTV26Iaux0QITOtqKGCo6869y355lfkmcKWfgJFL8RQXO', NULL, NULL, '2024-01-13 04:48:10', '2024-01-13 04:48:10'),
(216, 'John', 'John', 'Doe', 'johndoe@gmail.com', '(234) 567-8910', 3, 'Canada', NULL, 1, NULL, '$2y$10$vh68T2OFo10WcCP.OjfDLug4u.Go9QVzEWfPgZF/N/E4wVuaFgsN6', NULL, NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(217, 'Mary', 'Mary', 'Smith', 'm.smith.44@yahoo.com', '(345) 678 9012', 3, 'Canada', NULL, 1, NULL, '$2y$10$jXb/BCfILPEr1VsR.m0I6ezfrB1TYoNlwtKgO77mRaYAgVhJKsmVe', NULL, NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21'),
(218, 'Real', 'Real', 'Person', 'fake_email_address@example.net', '(456)-789-1011', 3, 'Canada', NULL, 1, NULL, '$2y$10$6aN7aF65E0b5LFGM.QUmEuXWp7tKx/v7czro4kFDobMY7kC.tj/aS', NULL, NULL, '2024-02-25 04:50:21', '2024-02-25 04:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_status` int(11) NOT NULL DEFAULT 0,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phonenumber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credits` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `category_id`, `account_status`, `company_name`, `address`, `city`, `state_province`, `country`, `zip_postal`, `phonenumber`, `website`, `email`, `credits`, `created_at`, `updated_at`) VALUES
(2, 181, 13, 1, 'Update Vendor', 'Update Vendor', 'Ottawa', 'Ontario', 'Costa Rica', 'K1K 2G6', '(455) 674-9877', 'https://laravel.com/docs/9.x/requests#retrieving-a-portion-of-the-input-data', 'UpdateVendor@hotmail.com', 0, '2022-12-26 02:01:31', '2023-01-09 04:21:18'),
(6, 188, 9, 1, 'Bidenn Corporatione', '4735 lol street', 'nah', 'nah', 'USA', '2353', 'dssdddsddss', 'https://www.youtube.com/', 'bidenjoe@isuck.com', 0, '2023-01-09 04:14:52', '2023-01-09 04:14:52'),
(8, 190, 10, 1, 'Trump Party', '87 Dolla Dolla Bills', 'Money Land', 'fsdfs', 'USA', 'sdfsdf', 'dssdddsddss', 'https://www.youtube.com/', 'trumpman@maga.com', 0, '2023-01-09 04:18:15', '2023-01-09 04:18:15'),
(9, 196, 9, 1, 'Trump Venue Industries', '123 MAGA', 'New York', 'Oklahoma', 'USA', '4567', '(454) 546-4566', 'https://www.donaldjtrump.com/', 'donaldtrump@trump.com', 0, '2023-01-17 01:10:39', '2023-01-17 01:10:39'),
(10, 197, 18, 1, 'Money Jockeys', '420 Money Lane', 'Ottawa', 'Ontario', 'Canada', 'LOL 8HA', '(454) 654-6546', 'https://promplanner.app/', 'vendor001@promplanner.com', 10, '2023-01-21 02:41:46', '2023-10-01 02:43:53'),
(14, 209, 18, 1, 'New Vendor LLC', '1234 Main St', 'Ottawa', 'Ontario', 'Canada', 'K1K 2G6', '(123) 456-789_', 'https://amazon.com', 'newvendor@test.com', 0, '2023-07-29 23:39:18', '2023-07-29 23:42:31'),
(15, 210, 19, 1, 'Salon Pros', 'Salon Orad', 'Ottawa', 'Ontario', 'Canada', 'K1A 7G7', '(456) 123-7895', 'https://amazon.com', 'salonpros@gmail.com', 0, '2023-08-01 22:31:18', '2023-08-01 22:31:18'),
(16, 211, 12, 0, 'NewMan Inc', 'NewManInc 123', 'Ottawa', 'Ontario', 'Canada', 'K1K2G6', '(445) 454-5454', 'https://promplanner.app/prom-planner-team/', 'NewManInc@gmail.com', 0, '2023-08-19 05:12:18', '2023-08-19 05:12:18');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_packages`
--

CREATE TABLE `vendor_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,0) UNSIGNED NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_packages`
--

INSERT INTO `vendor_packages` (`id`, `user_id`, `package_name`, `description`, `price`, `url`, `created_at`, `updated_at`) VALUES
(1, 197, 'Executive Venue', 'Best of the best venue!', '99999999', 'https://promplanner.app/', '2023-01-26 18:17:56', '2023-01-27 00:21:31'),
(2, 197, 'Laid Back Venue', 'Venue for your laid back needs!', '199', 'https://promvendors.com/', '2023-01-26 18:17:56', NULL),
(3, 197, 'LIFETIME VENDOR LICENSE', 'Purchasing this License gives the Vendor unrestricted LIFETIME access to the Vendor portal of Prom Planner for one region and one business category ONLY. If multiple regions are needed, then a separate license for each region must be purchased.', '2990', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-01-26 23:19:21', '2023-01-26 23:19:21'),
(6, 197, 'Lux Tuxs', 'Luxury Tuxedos for the prom king!', '250', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-02-03 00:13:56', '2023-02-03 00:13:56'),
(7, 197, 'Limo Package', 'Heres a descritopiojojsdfsdfsdfdfs', '500', 'https://github.com/fkhan613', '2023-07-16 01:30:56', '2023-07-16 01:30:56'),
(8, 209, 'Limo Package', 'Yoyoyo', '500', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-07-29 23:43:41', '2023-07-29 23:43:41'),
(9, 210, 'Salon Exec Package', 'yertyrtyrtyryyy', '500', 'https://promplanner.app/product/lifetime-vendor-license/', '2023-08-01 22:41:22', '2023-08-01 22:41:22');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_paid_regions`
--

INSERT INTO `vendor_paid_regions` (`id`, `user_id`, `region_id`, `created_at`, `updated_at`) VALUES
(4, 197, 1, '2023-04-11 21:58:41', NULL),
(5, 197, 2, '2023-07-29 23:16:47', '2023-07-29 23:16:47'),
(6, 188, 1, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(7, 188, 2, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(8, 188, 8, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(9, 196, 1, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(10, 196, 2, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(11, 196, 8, '2023-07-29 23:17:13', '2023-07-29 23:17:13'),
(12, 209, 1, '2023-07-29 23:39:41', '2023-07-29 23:39:41'),
(13, 209, 7, '2023-07-29 23:39:41', '2023-07-29 23:39:41'),
(14, 210, 1, '2023-08-01 22:31:18', '2023-08-01 22:31:18'),
(15, 210, 7, '2023-08-01 22:31:18', '2023-08-01 22:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `video_tutorials`
--

CREATE TABLE `video_tutorials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `portal` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actual_expenses_revenues`
--
ALTER TABLE `actual_expenses_revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universal_id` (`universal_id`,`event_id`),
  ADD KEY `last_updated_user_id` (`last_updated_user_id`),
  ADD KEY `event_id_ex_rev` (`event_id`);

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
-- Indexes for table `beauty_groups`
--
ALTER TABLE `beauty_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_user_id` (`creator_user_id`,`school_id`),
  ADD KEY `beauty_group_school_id` (`school_id`);

--
-- Indexes for table `beauty_group_bids`
--
ALTER TABLE `beauty_group_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`beauty_group_id`,`region_id`,`package_id`,`category_id`),
  ADD KEY `category_id_bid_rel2` (`category_id`),
  ADD KEY `beauty_group_id_bid_rel` (`beauty_group_id`),
  ADD KEY `package_id_bid_rel2` (`package_id`),
  ADD KEY `region_id_bid_rel2` (`region_id`);

--
-- Indexes for table `beauty_group_members`
--
ALTER TABLE `beauty_group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beauty_group_id` (`beauty_group_id`,`invitee_user_id`),
  ADD KEY `invitee_user_id2` (`invitee_user_id`);

--
-- Indexes for table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_user_id` (`reporter_user_id`,`reporter_role`),
  ADD KEY `reporter_role` (`reporter_role`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_index` (`category_id`),
  ADD KEY `region_index` (`region_id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`,`position_id`),
  ADD KEY `postion_id` (`position_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_id` (`checklist_id`);

--
-- Indexes for table `checklist_users`
--
ALTER TABLE `checklist_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_id` (`checklist_id`,`checklist_item_id`,`checklist_user_id`),
  ADD KEY `checklist_item_id_rel_z` (`checklist_item_id`),
  ADD KEY `checklist_user_id_rel_z` (`checklist_user_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_contracts` (`user_id`);

--
-- Indexes for table `couples`
--
ALTER TABLE `couples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_user_id_1` (`student_user_id_1`,`student_user_id_2`),
  ADD KEY `student_user_id_foreign_2` (`student_user_id_2`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `couple_requests`
--
ALTER TABLE `couple_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_user_id` (`owner_user_id`,`receiver_user_id`,`event_id`),
  ADD KEY `event` (`event_id`),
  ADD KEY `receiver` (`receiver_user_id`);

--
-- Indexes for table `display_ads`
--
ALTER TABLE `display_ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_name` (`portal`,`ad_index`,`campaign_id`,`region_id`),
  ADD KEY `display_ads_camp_id` (`campaign_id`),
  ADD KEY `display_ads_reg_id` (`region_id`),
  ADD KEY `route_uri` (`route_uri`);

--
-- Indexes for table `dresses`
--
ALTER TABLE `dresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`model_number`),
  ADD KEY `user_id_dress_index` (`user_id`),
  ADD KEY `model_number_index` (`model_number`);

--
-- Indexes for table `dress_wishlist`
--
ALTER TABLE `dress_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`dress_id`),
  ADD KEY `dress_wishlist_userid` (`user_id`),
  ADD KEY `dress_wishlist_dressid` (`dress_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`,`school_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `election_votes`
--
ALTER TABLE `election_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`,`position_id`,`candidate_id`,`voter_user_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `voter_user_id` (`voter_user_id`);

--
-- Indexes for table `election_winners`
--
ALTER TABLE `election_winners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`,`position_id`),
  ADD KEY `election_winner_position_id` (`position_id`);

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
-- Indexes for table `events_historical_records`
--
ALTER TABLE `events_historical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`,`table_id`),
  ADD KEY `event_attendees_ibfk_3` (`table_id`),
  ADD KEY `inviter_user_id_event_att` (`inviter_user_id`);

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
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_recipient_foreign` (`recipient`),
  ADD KEY `invitation_inviter_foreign` (`inviter`),
  ADD KEY `invitation_event_foreign` (`event`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id_lesson` (`section_id`),
  ADD KEY `course_id_lesson` (`guide_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `limo_groups`
--
ALTER TABLE `limo_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_user_id` (`creator_user_id`),
  ADD KEY `limo_group_school` (`school_id`);

--
-- Indexes for table `limo_group_bids`
--
ALTER TABLE `limo_group_bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`limo_group_id`,`region_id`,`package_id`,`category_id`),
  ADD KEY `limo_group_id_bid_rel` (`limo_group_id`),
  ADD KEY `package_id_bid_rel` (`package_id`),
  ADD KEY `category_id_bid_rel` (`category_id`),
  ADD KEY `region_id_bid_rel` (`region_id`);

--
-- Indexes for table `limo_group_members`
--
ALTER TABLE `limo_group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `limo_group_id` (`limo_group_id`),
  ADD KEY `invitee_user_id` (`invitee_user_id`);

--
-- Indexes for table `localadmins`
--
ALTER TABLE `localadmins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `localadmin_user_id_foreign` (`user_id`),
  ADD KEY `localadmin_school_index` (`school`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `login_ads`
--
ALTER TABLE `login_ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `login_as`
--
ALTER TABLE `login_as`
  ADD PRIMARY KEY (`id`),
  ADD KEY `la_key` (`la_key`,`user_id`,`portal`),
  ADD KEY `acc_user_id` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `national_candidates`
--
ALTER TABLE `national_candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`election_id`,`position_id`),
  ADD KEY `election_id_nat_cand` (`election_id`),
  ADD KEY `position_id_nat_cad` (`position_id`);

--
-- Indexes for table `national_elections`
--
ALTER TABLE `national_elections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `national_election_votes`
--
ALTER TABLE `national_election_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`,`position_id`,`candidate_id`,`voter_user_id`),
  ADD KEY `position_id_nat_votes` (`position_id`),
  ADD KEY `candidate_id_nat_votes` (`candidate_id`),
  ADD KEY `voter_user_id_nat_votes` (`voter_user_id`);

--
-- Indexes for table `national_election_winners`
--
ALTER TABLE `national_election_winners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`,`position_id`),
  ADD KEY `position_id_nat_winners` (`position_id`);

--
-- Indexes for table `national_positions`
--
ALTER TABLE `national_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `no_play_songs`
--
ALTER TABLE `no_play_songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `song_id_2` (`song_id`,`event_id`),
  ADD KEY `song_id` (`song_id`,`event_id`),
  ADD KEY `no-play-song-event` (`event_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`,`vendor_user_id`),
  ADD KEY `polls_ibfk_2` (`vendor_user_id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poll_id` (`poll_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `promfluencers`
--
ALTER TABLE `promfluencers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promfluencer_uder_id` (`user_id`);

--
-- Indexes for table `regional_candidates`
--
ALTER TABLE `regional_candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`election_id`,`position_id`),
  ADD KEY `election_id_reg_can` (`election_id`),
  ADD KEY `position_id_reg_can` (`position_id`);

--
-- Indexes for table `regional_elections`
--
ALTER TABLE `regional_elections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `regional_election_votes`
--
ALTER TABLE `regional_election_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`,`position_id`,`candidate_id`,`voter_user_id`),
  ADD KEY `position_id_reg_votes` (`position_id`),
  ADD KEY `candidate_id_reg_votes` (`candidate_id`),
  ADD KEY `voter_user_id_reg_votes` (`voter_user_id`);

--
-- Indexes for table `regional_election_winners`
--
ALTER TABLE `regional_election_winners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_id` (`candidate_id`,`position_id`),
  ADD KEY `position_id_reg_winners` (`position_id`);

--
-- Indexes for table `regional_positions`
--
ALTER TABLE `regional_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `election_id` (`election_id`);

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
-- Indexes for table `school_dresses`
--
ALTER TABLE `school_dresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_id` (`school_id`,`dress_id`),
  ADD UNIQUE KEY `school_id_2` (`school_id`,`user_id`),
  ADD KEY `event_id_dress` (`school_id`),
  ADD KEY `dress_id_dress` (`dress_id`),
  ADD KEY `user_id_dress` (`user_id`);

--
-- Indexes for table `seatings`
--
ALTER TABLE `seatings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id_section` (`guide_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_foreign` (`user_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`,`artists`);

--
-- Indexes for table `song_requests`
--
ALTER TABLE `song_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `song_id_2` (`song_id`,`event_id`,`user_id`),
  ADD KEY `song_id` (`song_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

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
  ADD KEY `user_id` (`user_id`,`student_user_id`),
  ADD KEY `student_id` (`student_user_id`),
  ADD KEY `package_id` (`package_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `student_poll_votes`
--
ALTER TABLE `student_poll_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`poll_id`,`poll_options_id`),
  ADD KEY `student_poll_votes_ibfk_2` (`poll_id`),
  ADD KEY `student_poll_votes_ibfk_3` (`poll_options_id`);

--
-- Indexes for table `student_specs`
--
ALTER TABLE `student_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_user_id_specs` (`student_user_id`);

--
-- Indexes for table `universal_expenses_revenues`
--
ALTER TABLE `universal_expenses_revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `last_updated_user_id` (`last_updated_user_id`);

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
-- Indexes for table `video_tutorials`
--
ALTER TABLE `video_tutorials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actual_expenses_revenues`
--
ALTER TABLE `actual_expenses_revenues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `beauty_groups`
--
ALTER TABLE `beauty_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `beauty_group_bids`
--
ALTER TABLE `beauty_group_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beauty_group_members`
--
ALTER TABLE `beauty_group_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bug_reports`
--
ALTER TABLE `bug_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `checklists`
--
ALTER TABLE `checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist_users`
--
ALTER TABLE `checklist_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `couples`
--
ALTER TABLE `couples`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `couple_requests`
--
ALTER TABLE `couple_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `display_ads`
--
ALTER TABLE `display_ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dresses`
--
ALTER TABLE `dresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dress_wishlist`
--
ALTER TABLE `dress_wishlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `election_votes`
--
ALTER TABLE `election_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `election_winners`
--
ALTER TABLE `election_winners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `events_historical_records`
--
ALTER TABLE `events_historical_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_attendees`
--
ALTER TABLE `event_attendees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `event_bids`
--
ALTER TABLE `event_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `limo_groups`
--
ALTER TABLE `limo_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `limo_group_bids`
--
ALTER TABLE `limo_group_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `limo_group_members`
--
ALTER TABLE `limo_group_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `localadmins`
--
ALTER TABLE `localadmins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `login_ads`
--
ALTER TABLE `login_ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_as`
--
ALTER TABLE `login_as`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `national_candidates`
--
ALTER TABLE `national_candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `national_elections`
--
ALTER TABLE `national_elections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `national_election_votes`
--
ALTER TABLE `national_election_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `national_election_winners`
--
ALTER TABLE `national_election_winners`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `national_positions`
--
ALTER TABLE `national_positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `no_play_songs`
--
ALTER TABLE `no_play_songs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `promfluencers`
--
ALTER TABLE `promfluencers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `regional_candidates`
--
ALTER TABLE `regional_candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regional_elections`
--
ALTER TABLE `regional_elections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regional_election_votes`
--
ALTER TABLE `regional_election_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regional_election_winners`
--
ALTER TABLE `regional_election_winners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regional_positions`
--
ALTER TABLE `regional_positions`
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
-- AUTO_INCREMENT for table `school_dresses`
--
ALTER TABLE `school_dresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seatings`
--
ALTER TABLE `seatings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `song_requests`
--
ALTER TABLE `song_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `student_bids`
--
ALTER TABLE `student_bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_poll_votes`
--
ALTER TABLE `student_poll_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_specs`
--
ALTER TABLE `student_specs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `universal_expenses_revenues`
--
ALTER TABLE `universal_expenses_revenues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_paid_regions`
--
ALTER TABLE `vendor_paid_regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `video_tutorials`
--
ALTER TABLE `video_tutorials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actual_expenses_revenues`
--
ALTER TABLE `actual_expenses_revenues`
  ADD CONSTRAINT `event_id_ex_rev` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `last_updated_user_id_actual` FOREIGN KEY (`last_updated_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `universal_id_rel` FOREIGN KEY (`universal_id`) REFERENCES `universal_expenses_revenues` (`id`);

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
-- Constraints for table `beauty_groups`
--
ALTER TABLE `beauty_groups`
  ADD CONSTRAINT `beauty_creator_user_id` FOREIGN KEY (`creator_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beauty_group_school_id` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beauty_group_bids`
--
ALTER TABLE `beauty_group_bids`
  ADD CONSTRAINT `beauty_group_id_bid_rel` FOREIGN KEY (`beauty_group_id`) REFERENCES `beauty_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_id_bid_rel2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_id_bid_rel2` FOREIGN KEY (`package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `region_id_bid_rel2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_bid_rel2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beauty_group_members`
--
ALTER TABLE `beauty_group_members`
  ADD CONSTRAINT `beauty_group_id` FOREIGN KEY (`beauty_group_id`) REFERENCES `beauty_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invitee_user_id2` FOREIGN KEY (`invitee_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD CONSTRAINT `reporter_role` FOREIGN KEY (`reporter_role`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `reporter_user_id` FOREIGN KEY (`reporter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidate_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `postion_id` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD CONSTRAINT `checklist_id_rel` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `checklist_users`
--
ALTER TABLE `checklist_users`
  ADD CONSTRAINT `checklist_id_rel_z` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `checklist_item_id_rel_z` FOREIGN KEY (`checklist_item_id`) REFERENCES `checklist_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `checklist_user_id_rel_z` FOREIGN KEY (`checklist_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `user_id_contract_rel` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `couples`
--
ALTER TABLE `couples`
  ADD CONSTRAINT `event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_user_id_foreign_1` FOREIGN KEY (`student_user_id_1`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_user_id_foreign_2` FOREIGN KEY (`student_user_id_2`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `couple_requests`
--
ALTER TABLE `couple_requests`
  ADD CONSTRAINT `event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `owner` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receiver` FOREIGN KEY (`receiver_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `display_ads`
--
ALTER TABLE `display_ads`
  ADD CONSTRAINT `display_ads_camp_id` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `display_ads_reg_id` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dresses`
--
ALTER TABLE `dresses`
  ADD CONSTRAINT `dresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dress_wishlist`
--
ALTER TABLE `dress_wishlist`
  ADD CONSTRAINT `dress_wishlist_dressid_rel` FOREIGN KEY (`dress_id`) REFERENCES `dresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dress_wishlist_userid_rel` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `elections`
--
ALTER TABLE `elections`
  ADD CONSTRAINT `elections_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `school_id` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `election_votes`
--
ALTER TABLE `election_votes`
  ADD CONSTRAINT `election_id` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `election_votes_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `position_id` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `voter_user_id` FOREIGN KEY (`voter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `election_winners`
--
ALTER TABLE `election_winners`
  ADD CONSTRAINT `election_winner_candidate_id` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `election_winner_position_id` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_event_creator_foreign` FOREIGN KEY (`event_creator`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venue_id` FOREIGN KEY (`venue_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `events_historical_records`
--
ALTER TABLE `events_historical_records`
  ADD CONSTRAINT `event_id_hist_rec` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_attendees`
--
ALTER TABLE `event_attendees`
  ADD CONSTRAINT `event_attendees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_attendees_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_attendees_ibfk_3` FOREIGN KEY (`table_id`) REFERENCES `seatings` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `inviter_user_id_event_att` FOREIGN KEY (`inviter_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION;

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
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `course_id_lesson` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `section_id_lesson` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `limo_groups`
--
ALTER TABLE `limo_groups`
  ADD CONSTRAINT `creator_user_id` FOREIGN KEY (`creator_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `limo_group_school_id_rel` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `limo_group_bids`
--
ALTER TABLE `limo_group_bids`
  ADD CONSTRAINT `category_id_bid_rel` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `limo_group_id_bid_rel` FOREIGN KEY (`limo_group_id`) REFERENCES `limo_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_id_bid_rel` FOREIGN KEY (`package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `region_id_bid_rel` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_bid_rel` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `limo_group_members`
--
ALTER TABLE `limo_group_members`
  ADD CONSTRAINT `invitee_user_id` FOREIGN KEY (`invitee_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `limo_group_id` FOREIGN KEY (`limo_group_id`) REFERENCES `limo_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `localadmins`
--
ALTER TABLE `localadmins`
  ADD CONSTRAINT `localadmin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `localadmins_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_ads`
--
ALTER TABLE `login_ads`
  ADD CONSTRAINT `camp_id` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_as`
--
ALTER TABLE `login_as`
  ADD CONSTRAINT `acc_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `national_candidates`
--
ALTER TABLE `national_candidates`
  ADD CONSTRAINT `election_id_nat_cand` FOREIGN KEY (`election_id`) REFERENCES `national_elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_nat_cad` FOREIGN KEY (`position_id`) REFERENCES `national_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_nat_cand` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `national_election_votes`
--
ALTER TABLE `national_election_votes`
  ADD CONSTRAINT `candidate_id_nat_votes` FOREIGN KEY (`candidate_id`) REFERENCES `national_candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `election_id_nat_votes` FOREIGN KEY (`election_id`) REFERENCES `national_elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_nat_votes` FOREIGN KEY (`position_id`) REFERENCES `national_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voter_user_id_nat_votes` FOREIGN KEY (`voter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `national_election_winners`
--
ALTER TABLE `national_election_winners`
  ADD CONSTRAINT `candidate_id_nat_winners` FOREIGN KEY (`candidate_id`) REFERENCES `national_candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_nat_winners` FOREIGN KEY (`position_id`) REFERENCES `national_positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `national_positions`
--
ALTER TABLE `national_positions`
  ADD CONSTRAINT `election_id_nat_pos` FOREIGN KEY (`election_id`) REFERENCES `national_elections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `no_play_songs`
--
ALTER TABLE `no_play_songs`
  ADD CONSTRAINT `no-play-saong-id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `no-play-song-event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_rel` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `polls`
--
ALTER TABLE `polls`
  ADD CONSTRAINT `polls_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `polls_ibfk_2` FOREIGN KEY (`vendor_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD CONSTRAINT `poll_options_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `promfluencers`
--
ALTER TABLE `promfluencers`
  ADD CONSTRAINT `promfluencer_uder_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regional_candidates`
--
ALTER TABLE `regional_candidates`
  ADD CONSTRAINT `election_id_reg_can` FOREIGN KEY (`election_id`) REFERENCES `regional_elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_reg_can` FOREIGN KEY (`position_id`) REFERENCES `regional_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_reg_can` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regional_elections`
--
ALTER TABLE `regional_elections`
  ADD CONSTRAINT `region_id_reg_elec` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regional_election_votes`
--
ALTER TABLE `regional_election_votes`
  ADD CONSTRAINT `candidate_id_reg_votes` FOREIGN KEY (`candidate_id`) REFERENCES `regional_candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `election_id_reg_votes` FOREIGN KEY (`election_id`) REFERENCES `regional_elections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_reg_votes` FOREIGN KEY (`position_id`) REFERENCES `regional_positions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voter_user_id_reg_votes` FOREIGN KEY (`voter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regional_election_winners`
--
ALTER TABLE `regional_election_winners`
  ADD CONSTRAINT `candidate_id_reg_winners` FOREIGN KEY (`candidate_id`) REFERENCES `regional_candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_id_reg_winners` FOREIGN KEY (`position_id`) REFERENCES `regional_positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `regional_positions`
--
ALTER TABLE `regional_positions`
  ADD CONSTRAINT `election_id_reg_elec` FOREIGN KEY (`election_id`) REFERENCES `regional_elections` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `school_dresses`
--
ALTER TABLE `school_dresses`
  ADD CONSTRAINT `dress_id_rel` FOREIGN KEY (`dress_id`) REFERENCES `dresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `school_id_rel` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_rel` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seatings`
--
ALTER TABLE `seatings`
  ADD CONSTRAINT `seatings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `course_id_section` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `song_requests`
--
ALTER TABLE `song_requests`
  ADD CONSTRAINT `song_requests_ibfk_1` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `song_requests_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `student_bids_ibfk_3` FOREIGN KEY (`package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_bids_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_user_id` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_poll_votes`
--
ALTER TABLE `student_poll_votes`
  ADD CONSTRAINT `student_poll_votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_poll_votes_ibfk_2` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_poll_votes_ibfk_3` FOREIGN KEY (`poll_options_id`) REFERENCES `poll_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_specs`
--
ALTER TABLE `student_specs`
  ADD CONSTRAINT `student_user_id_specs_rel` FOREIGN KEY (`student_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `universal_expenses_revenues`
--
ALTER TABLE `universal_expenses_revenues`
  ADD CONSTRAINT `user_id_last_updated` FOREIGN KEY (`last_updated_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
