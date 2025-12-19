-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 11, 2025 at 10:53 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukesps`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_type` enum('image','text','banner','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'banner',
  `target_audience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('top','below_header','above_footer','left_sidebar','right_sidebar','top_slider') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'top',
  `target_pages` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('active','pending','inactive','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `daily_budget` decimal(10,2) DEFAULT NULL,
  `impressions` int NOT NULL DEFAULT '0',
  `clicks` int NOT NULL DEFAULT '0',
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_campaign_id` bigint UNSIGNED DEFAULT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `placement_order` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slider_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slider_description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_slider_featured` tinyint(1) NOT NULL DEFAULT '0',
  `slider_order` int NOT NULL DEFAULT '0',
  `slider_metadata` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ads_ad_campaign_id_foreign` (`ad_campaign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ad_campaigns`
--

DROP TABLE IF EXISTS `ad_campaigns`;
CREATE TABLE IF NOT EXISTS `ad_campaigns` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('draft','active','paused','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `type` enum('banner','interstitial','rewarded_video','native') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'banner',
  `position` enum('top','bottom','middle','sidebar','popup') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'top',
  `target_audience` json DEFAULT NULL,
  `target_pages` json DEFAULT NULL,
  `target_locations` json DEFAULT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `daily_budget` decimal(10,2) DEFAULT NULL,
  `spent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `impressions` int NOT NULL DEFAULT '0',
  `clicks` int NOT NULL DEFAULT '0',
  `ctr` decimal(5,2) NOT NULL DEFAULT '0.00',
  `cpc` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cpm` decimal(8,2) NOT NULL DEFAULT '0.00',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `ad_unit_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ad_code` text COLLATE utf8mb4_unicode_ci,
  `creative_data` json DEFAULT NULL,
  `impression_limit` int DEFAULT NULL,
  `click_limit` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ad_campaigns_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affiliated_courses`
--

DROP TABLE IF EXISTS `affiliated_courses`;
CREATE TABLE IF NOT EXISTS `affiliated_courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `prerequisites` text COLLATE utf8mb4_unicode_ci,
  `syllabus` text COLLATE utf8mb4_unicode_ci,
  `fee` decimal(10,2) DEFAULT NULL,
  `skills_covered` json DEFAULT NULL,
  `career_outcomes` json DEFAULT NULL,
  `status` enum('draft','published','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `max_enrollment` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `university_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliated_courses_university_id_foreign` (`university_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `affiliated_courses`
--

INSERT INTO `affiliated_courses` (`id`, `title`, `description`, `course_image`, `level`, `duration`, `start_date`, `end_date`, `prerequisites`, `syllabus`, `fee`, `skills_covered`, `career_outcomes`, `status`, `is_featured`, `max_enrollment`, `deleted_at`, `created_at`, `updated_at`, `university_id`) VALUES
(1, 'Alias quasi distinctio ex sit.', 'Sit ut qui est suscipit. Amet earum alias et sed vero. Sit magni vel doloribus ut fugit accusantium.', NULL, 'beginner', 23, '2026-01-21', '2026-04-08', 'Voluptatibus ullam quis perferendis velit. Necessitatibus quis qui tempora. Nostrum placeat repudiandae blanditiis est. Aut ipsa eveniet sit eum possimus recusandae et.', 'Eaque numquam rem velit repudiandae. Eum molestias velit velit nihil omnis. Inventore possimus eligendi saepe architecto a voluptatem.', 3349.87, '\"[\\\"sed\\\",\\\"corporis\\\",\\\"placeat\\\",\\\"vel\\\",\\\"voluptate\\\"]\"', '\"[\\\"voluptatem\\\",\\\"accusantium\\\",\\\"quo\\\"]\"', 'completed', 0, 75, NULL, '2025-12-07 17:46:00', '2025-12-07 17:46:00', 1),
(2, 'Itaque enim fugiat dolore perspiciatis.', 'Aliquam minima sed fuga et. Unde sapiente ad quis. Ducimus provident molestiae omnis nihil eligendi aliquam dolores.', NULL, 'beginner', 18, '2026-01-11', '2026-03-23', 'Ipsam nobis officia sint est nulla dolore. Quo tempora natus quo voluptas iste. Aut veniam veritatis magnam nam rem odio.', 'Et voluptatum quis error ut eveniet enim. Quis rem quo deleniti aperiam in. Accusamus enim odit amet autem. Quia ab dolor maxime ad.', 4726.27, '\"[\\\"ratione\\\",\\\"sint\\\",\\\"nam\\\",\\\"nisi\\\",\\\"voluptatem\\\"]\"', '\"[\\\"fuga\\\",\\\"occaecati\\\",\\\"distinctio\\\"]\"', 'published', 0, 83, NULL, '2025-12-07 17:46:00', '2025-12-07 17:46:00', 2),
(3, 'In sed rerum est veritatis.', 'Illum eum et id in fugit. Nulla eaque quisquam est et molestias temporibus. Eos cum beatae fugit cumque aut ut fuga voluptas.', NULL, 'all_levels', 12, '2026-01-31', '2026-02-11', 'Voluptatum mollitia ratione maxime ea fugiat. Dolores ducimus tenetur nam voluptate fugit possimus. Error quisquam inventore tempore inventore. Ducimus deleniti labore iste enim at ratione sunt.', 'Omnis debitis in voluptatem quos facere. Inventore tenetur commodi fuga ut dolorem. Ut ab similique voluptas molestias inventore aut.', 2946.63, '\"[\\\"et\\\",\\\"laborum\\\",\\\"culpa\\\",\\\"aliquam\\\",\\\"consequatur\\\"]\"', '\"[\\\"voluptatem\\\",\\\"at\\\",\\\"rerum\\\"]\"', 'draft', 0, 94, NULL, '2025-12-07 17:46:01', '2025-12-07 17:46:01', 3),
(4, 'Test Course', 'Test Description', NULL, 'beginner', 10, '2026-02-11', '2026-03-23', 'Dolores in itaque dolorem atque sit sit voluptates maxime. Iusto ipsa quo aut maiores iusto atque qui tempora. Ipsum ea debitis corrupti.', 'Voluptas sunt consequatur quae porro mollitia. Illo commodi ducimus libero exercitationem neque exercitationem. Excepturi commodi cum rerum. Laboriosam quisquam cum dolor sed. Alias autem et inventore.', 4292.73, '\"[\\\"consectetur\\\",\\\"nihil\\\",\\\"quas\\\",\\\"excepturi\\\",\\\"aperiam\\\"]\"', '\"[\\\"est\\\",\\\"accusamus\\\",\\\"eaque\\\"]\"', 'published', 0, 32, NULL, '2025-12-07 17:46:01', '2025-12-07 17:46:01', 4),
(5, 'Iste minus autem qui nihil qui vel.', 'Qui beatae natus odit veritatis. Et quo molestias non distinctio impedit voluptatum voluptatem. Officia nemo at quo error officia. Consequatur totam velit delectus omnis sequi est error.', NULL, 'all_levels', 22, '2026-01-25', '2026-05-26', 'Provident perferendis qui et minima vel eos. Molestiae corporis voluptatibus officiis et eius autem ut explicabo. Qui molestias sequi itaque iure est voluptas dolor dignissimos. Earum est sed et.', 'Id laborum soluta est molestias. Voluptate ut mollitia sit aperiam sunt sunt quam. Aut vero tenetur modi blanditiis aut. Possimus nihil tenetur ducimus voluptatum nobis.', 4433.40, '\"[\\\"recusandae\\\",\\\"possimus\\\",\\\"dolore\\\",\\\"impedit\\\",\\\"aperiam\\\"]\"', '\"[\\\"eos\\\",\\\"suscipit\\\",\\\"at\\\"]\"', 'completed', 0, 30, NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04', 5);

-- --------------------------------------------------------

--
-- Table structure for table `affiliated_course_enrollments`
--

DROP TABLE IF EXISTS `affiliated_course_enrollments`;
CREATE TABLE IF NOT EXISTS `affiliated_course_enrollments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `affiliated_course_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enrollment_status` enum('enrolled','in_progress','completed','dropped','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `completion_percentage` int NOT NULL DEFAULT '0',
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliated_course_enrollments_affiliated_course_id_foreign` (`affiliated_course_id`),
  KEY `affiliated_course_enrollments_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `affiliated_course_enrollments`
--

INSERT INTO `affiliated_course_enrollments` (`id`, `affiliated_course_id`, `user_id`, `enrollment_date`, `enrollment_status`, `completion_percentage`, `grade`, `certificate_path`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2025-12-07 18:46:00', 'completed', 65, 'C', 'https://kassulke.com/voluptatum-eius-ex-est-commodi-sequi-vitae.html', NULL, '2025-12-07 17:46:00', '2025-12-07 17:46:00'),
(2, 5, 26, '2025-12-07 18:46:04', 'completed', 30, 'F', 'http://www.schmeler.com/rerum-consequatur-in-enim-laboriosam-aut-ea-similique-ut', NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

DROP TABLE IF EXISTS `assessments`;
CREATE TABLE IF NOT EXISTS `assessments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('practical','theoretical','portfolio','interview') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'theoretical',
  `duration` int DEFAULT NULL,
  `total_points` int NOT NULL DEFAULT '100',
  `requirements` json DEFAULT NULL,
  `instructions` json DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED NOT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessments_created_by_foreign` (`created_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_attempts`
--

DROP TABLE IF EXISTS `assessment_attempts`;
CREATE TABLE IF NOT EXISTS `assessment_attempts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `assessment_id` bigint UNSIGNED NOT NULL,
  `score` int DEFAULT NULL,
  `max_score` int NOT NULL DEFAULT '0',
  `status` enum('in_progress','completed','graded','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `attempts` int NOT NULL DEFAULT '1',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `graded_at` timestamp NULL DEFAULT NULL,
  `graded_by` bigint UNSIGNED DEFAULT NULL,
  `grade_notes` text COLLATE utf8mb4_unicode_ci,
  `user_answers` json DEFAULT NULL,
  `feedback` json DEFAULT NULL,
  `is_passed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_attempts_user_id_foreign` (`user_id`),
  KEY `assessment_attempts_assessment_id_foreign` (`assessment_id`),
  KEY `assessment_attempts_graded_by_foreign` (`graded_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_post_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `author_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_blog_post_id_foreign` (`blog_post_id`),
  KEY `blog_comments_user_id_foreign` (`user_id`),
  KEY `blog_comments_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `blog_category_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_user_id_foreign` (`user_id`),
  KEY `blog_posts_blog_category_id_foreign` (`blog_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `certificate_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `assessment_id` bigint UNSIGNED NOT NULL,
  `assessment_attempt_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `score` decimal(5,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `certificate_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_certificate_id_unique` (`certificate_id`),
  KEY `certificates_user_id_foreign` (`user_id`),
  KEY `certificates_assessment_id_foreign` (`assessment_id`),
  KEY `certificates_assessment_attempt_id_foreign` (`assessment_attempt_id`),
  KEY `certificates_verified_by_foreign` (`verified_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `user_id`, `read_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Maryam White', 'sgoldner@mayert.com', 'Sunt aliquid non ut doloremque architecto et.', 'Animi modi laborum a pariatur voluptates ipsam placeat. Dolor id aut deleniti. Minus esse quidem commodi aspernatur. Aut voluptatem dolor dolores esse.', 'closed', 27, NULL, NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `continent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `continent`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Palestinian Territories', 'CO', 'Europe', 1, '2025-12-07 17:46:00', '2025-12-07 17:46:00'),
(2, 'Ukraine', 'CN', 'Antarctica', 1, '2025-12-07 17:46:00', '2025-12-07 17:46:00'),
(3, 'Palau', 'TJ', 'South America', 1, '2025-12-07 17:46:01', '2025-12-07 17:46:01'),
(4, 'Uzbekistan', 'SO', 'Europe', 1, '2025-12-07 17:46:01', '2025-12-07 17:46:01'),
(5, 'Egypt', 'TV', 'Europe', 1, '2025-12-07 17:46:04', '2025-12-07 17:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `level` enum('beginner','intermediate','advanced','all_levels') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all_levels',
  `instructor_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `course_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_status` enum('draft','published','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `premium_fee` decimal(10,2) DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `max_enrollment` int DEFAULT NULL,
  `prerequisites` text COLLATE utf8mb4_unicode_ci,
  `syllabus` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_instructor_id_foreign` (`instructor_id`),
  KEY `courses_premium_created_at_idx` (`is_premium`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `duration`, `level`, `instructor_id`, `start_date`, `end_date`, `course_image`, `course_status`, `is_premium`, `premium_fee`, `premium_expires_at`, `max_enrollment`, `prerequisites`, `syllabus`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Quia sit aspernatur tempora expedita unde debitis vitae.', 'Eum et est quos et facere nihil nam. Qui rerum voluptates pariatur enim. Quo est nulla harum. Repellendus velit repudiandae consequatur corrupti cupiditate.', 4, 'beginner', 2, '2026-03-01', '2026-06-02', NULL, 'cancelled', 0, NULL, NULL, 83, 'Omnis non aspernatur numquam qui blanditiis est dolorum. Voluptatem et in dolores dolorum doloribus officiis. Et magni sequi quis.', 'Quis est porro perspiciatis delectus commodi a. Hic qui rerum numquam sint facere quaerat. Impedit minima ipsam officiis laboriosam debitis. Nostrum ut animi non blanditiis est et sed.', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL),
(2, 'Qui iusto aut possimus doloribus labore vel fugiat.', 'Debitis odit iste at numquam quis eos quae et. Quia vero impedit eos quasi itaque nesciunt.', 12, 'intermediate', 3, '2026-02-22', '2026-03-23', NULL, 'cancelled', 0, NULL, NULL, 70, 'Cupiditate modi aperiam officia officia reprehenderit ut. Repellat consectetur temporibus excepturi architecto. Temporibus quaerat provident error.', 'Magnam repudiandae autem quidem dolorem in sit. Nihil itaque voluptatem fugit doloribus quidem delectus qui non. Voluptas ipsum qui molestiae dolores.', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL),
(3, 'Ducimus aut corporis quas beatae odio autem voluptas.', 'Accusamus porro nisi illum quo. Quos enim itaque dolores illo inventore eum. Quo sit perspiciatis id molestiae rerum in excepturi.', 19, 'intermediate', 5, '2026-02-10', '2026-06-02', NULL, 'published', 0, NULL, NULL, 68, 'Accusamus porro error cumque voluptatem quo dolore doloremque. Aut ducimus repudiandae excepturi ut. In ex excepturi laborum magnam ullam magnam et. Nobis dicta aut quibusdam aut consequatur est.', 'Sed ut pariatur aliquam vel autem minima. Tempora aut aut totam tempore doloremque nihil distinctio eius. In quis quod est delectus tempora aut asperiores. Sunt doloremque praesentium non ducimus quia eaque.', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL),
(4, 'Test Course', 'Test Description', 10, 'beginner', 6, '2026-01-22', '2026-04-11', NULL, 'published', 0, NULL, NULL, 86, 'At iusto sed vel voluptatem. Voluptatem aut ut quia nostrum. Commodi tempore laborum velit aut.', 'Aut in minus placeat eligendi voluptatum. Cupiditate et nam earum qui sunt aut. Dignissimos reprehenderit deleniti quae placeat qui tempore unde. Aperiam alias dolorum officia officia autem commodi numquam.', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL),
(5, 'Eum explicabo nulla ut a velit aut at.', 'Quia sunt autem optio culpa sunt cum. Autem laudantium voluptatum minus asperiores laboriosam qui velit.', 19, 'beginner', 20, '2026-01-21', '2026-03-29', NULL, 'cancelled', 0, NULL, NULL, 39, 'Sint ipsa ipsa nesciunt sit doloremque animi. Incidunt minima voluptatem quo modi. Fugiat corrupti quis et soluta dolor. Pariatur voluptas quod quidem non voluptatum necessitatibus tenetur maxime.', 'Quia laboriosam nisi rerum non. Repellendus rerum sed repellendus magni corrupti eum. Facere iusto expedita sit quis et hic. Quia quod quis quia excepturi qui commodi est.', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL),
(6, 'Ut voluptatem earum soluta qui accusantium vero voluptatem.', 'Excepturi repudiandae et ipsam ut ut perspiciatis voluptas fugit. Soluta doloremque libero fugiat. Quia sed fugiat voluptas.', 17, 'intermediate', 22, '2026-03-07', '2026-04-14', NULL, 'published', 0, NULL, NULL, 38, 'Dolores tenetur quo dolorem. Voluptas sapiente a deserunt similique fuga. Veniam nemo asperiores delectus inventore maiores debitis.', 'Itaque nam maiores omnis quod. Fugit natus harum a unde nihil. Exercitationem perspiciatis voluptatibus quasi praesentium. Placeat ipsam impedit dolor voluptatem omnis natus et.', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

DROP TABLE IF EXISTS `course_enrollments`;
CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enrollment_status` enum('enrolled','in_progress','completed','dropped','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `completion_percentage` int NOT NULL DEFAULT '0',
  `grade` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_enrollments_course_id_foreign` (`course_id`),
  KEY `course_enrollments_student_id_foreign` (`student_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`id`, `course_id`, `student_id`, `enrollment_date`, `enrollment_status`, `completion_percentage`, `grade`, `certificate_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 4, '2025-11-10 03:12:03', 'in_progress', 75, 'A', NULL, '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL),
(2, 6, 21, '2025-11-21 03:05:14', 'dropped', 89, 'D', NULL, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_levels`
--

DROP TABLE IF EXISTS `course_levels`;
CREATE TABLE IF NOT EXISTS `course_levels` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_levels_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cv_uploads`
--

DROP TABLE IF EXISTS `cv_uploads`;
CREATE TABLE IF NOT EXISTS `cv_uploads` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf',
  `file_size` int NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `extracted_skills` json NOT NULL,
  `work_experience` json NOT NULL,
  `education` json NOT NULL,
  `contact_info` json NOT NULL,
  `languages` json NOT NULL,
  `cv_completeness_score` double DEFAULT NULL,
  `last_parsed_at` timestamp NULL DEFAULT NULL,
  `auto_parse_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `last_position_applied` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_count` int NOT NULL DEFAULT '0',
  `last_application_at` timestamp NULL DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `desired_salary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `featured_until` timestamp NULL DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `overall_score` double DEFAULT NULL,
  `match_scores` json DEFAULT NULL,
  `relevance_score` int DEFAULT NULL,
  `parsed_data` json DEFAULT NULL,
  `last_viewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cv_uploads_user_id_index` (`user_id`),
  KEY `cv_uploads_is_public_index` (`is_public`),
  KEY `cv_uploads_is_featured_index` (`is_featured`),
  KEY `cv_uploads_location_index` (`location`),
  KEY `cv_uploads_status_index` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_participants` int DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `event_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_status` enum('draft','published','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `premium_fee` decimal(10,2) DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_created_by_foreign` (`created_by`),
  KEY `events_premium_created_at_idx` (`is_premium`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_date`, `end_date`, `location`, `max_participants`, `registration_deadline`, `event_image`, `event_status`, `is_premium`, `premium_fee`, `premium_expires_at`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Earum labore porro quae molestiae dolores nobis.', 'Dolorem et aut voluptatibus est unde. Esse quis non atque magnam quia. Incidunt unde odio officiis quibusdam et dolores ea.', '2026-01-03 23:04:06', '2026-02-03 00:14:35', '659 Willms Ridges\nLake Jed, VT 41907-7027', 387, '2025-12-23 22:07:41', NULL, 'draft', 0, NULL, NULL, 7, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(2, 'Placeat maiores odit perspiciatis ducimus reprehenderit velit.', 'Explicabo dignissimos sed sunt sed impedit occaecati. Praesentium quia dolor eum sed quis. Est sit officia natus nobis voluptates libero alias.', '2025-12-16 02:56:21', '2026-01-30 19:27:59', '20678 Durgan Burg Suite 430\nPort Roderickmouth, WY 16493', 119, '2025-12-22 20:41:23', NULL, 'cancelled', 0, NULL, NULL, 8, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(3, 'Facere commodi qui sit.', 'Officia praesentium nobis velit quis dolorem repellat atque. Odio nihil omnis sit commodi quis. Consequatur excepturi repellendus cumque voluptate nihil.', '2026-01-05 01:46:49', '2026-01-11 10:29:55', '4706 Donavon Street\nAdelabury, AR 06976', 291, '2025-12-17 16:37:09', NULL, 'draft', 0, NULL, NULL, 10, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(4, 'Eaque quo voluptatem id necessitatibus est est.', 'Similique laudantium consequuntur dignissimos tempore sed. Autem praesentium perspiciatis asperiores aut dolorem aut. Sunt aut consequatur ut similique perspiciatis. Rerum aut corporis molestiae odit beatae.', '2025-12-21 09:18:50', '2026-01-07 04:29:16', '32365 Carter Locks Suite 818\nHyattbury, CO 00195', 18, '2025-12-19 06:46:06', NULL, 'completed', 0, NULL, NULL, 17, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL),
(5, 'Rerum eius unde a commodi quasi.', 'Inventore debitis in qui ab. Enim eos aut esse modi tenetur eum sed. Culpa id qui optio a corporis eligendi mollitia rerum.', '2025-12-27 09:38:06', '2026-01-01 05:55:47', '2728 Marquis Lights Apt. 768\nWalterchester, KY 61378-6072', 49, '2025-12-20 04:10:08', NULL, 'cancelled', 0, NULL, NULL, 19, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

DROP TABLE IF EXISTS `event_registrations`;
CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` enum('registered','confirmed','attended','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'registered',
  `payment_status` enum('pending','paid','refunded','free') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_registrations_event_id_foreign` (`event_id`),
  KEY `event_registrations_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`id`, `event_id`, `user_id`, `registration_date`, `attendance_status`, `payment_status`, `payment_amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 9, '2025-11-22 01:36:54', 'attended', 'pending', NULL, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(2, 5, 18, '2025-11-12 03:42:39', 'registered', 'paid', NULL, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_contents`
--

DROP TABLE IF EXISTS `hero_contents`;
CREATE TABLE IF NOT EXISTS `hero_contents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `content_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_alerts`
--

DROP TABLE IF EXISTS `job_alerts`;
CREATE TABLE IF NOT EXISTS `job_alerts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Job Alert',
  `criteria` json NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `frequency` enum('immediate','daily','weekly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_run_at` timestamp NULL DEFAULT NULL,
  `last_notification_at` timestamp NULL DEFAULT NULL,
  `matched_jobs_count` int NOT NULL DEFAULT '0',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_alerts_user_id_is_active_index` (`user_id`,`is_active`),
  KEY `job_alerts_last_run_at_index` (`last_run_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

DROP TABLE IF EXISTS `job_applications`;
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_id` bigint UNSIGNED NOT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cover_letter` text COLLATE utf8mb4_unicode_ci,
  `resume_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_status` enum('pending','reviewed','shortlisted','rejected','hired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `applied_position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_applications_job_id_foreign` (`job_id`),
  KEY `job_applications_applicant_id_foreign` (`applicant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_id`, `applicant_id`, `application_date`, `cover_letter`, `resume_path`, `application_status`, `applied_position`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 13, '2025-12-06 19:18:04', 'Reprehenderit necessitatibus consequatur voluptas voluptas labore ea voluptatem quibusdam. Rerum minus veritatis enim iste eos magnam laudantium. Ut ullam iure ullam voluptas. Asperiores possimus perspiciatis labore repellat tempora.', 'http://www.cummings.info/', 'pending', NULL, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(2, 6, 24, '2025-11-23 03:45:01', 'Dolores neque minima vel eos qui nesciunt. Ut culpa sequi in vitae perferendis qui sit. Itaque ut tempora fuga enim sed harum id. Aut sequi atque et quis est. Exercitationem quia qui id est ut quisquam.', 'http://www.erdman.org/alias-quis-voluptates-nemo-eos', 'pending', NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_listings`
--

DROP TABLE IF EXISTS `job_listings`;
CREATE TABLE IF NOT EXISTS `job_listings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsibilities` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `job_type` enum('full_time','part_time','contract','internship','remote') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full_time',
  `experience_level` enum('entry','mid','senior','executive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'entry',
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posted_by` bigint UNSIGNED NOT NULL,
  `application_deadline` date DEFAULT NULL,
  `job_status` enum('draft','published','closed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `premium_fee` decimal(10,2) DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_listings_posted_by_foreign` (`posted_by`),
  KEY `job_listings_premium_created_at_idx` (`is_premium`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_listings`
--

INSERT INTO `job_listings` (`id`, `title`, `description`, `requirements`, `responsibilities`, `salary_min`, `salary_max`, `job_type`, `experience_level`, `location`, `posted_by`, `application_deadline`, `job_status`, `is_premium`, `premium_fee`, `premium_expires_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Roofer', 'Illo temporibus in molestiae veritatis. Incidunt sed fuga deleniti.', 'Nemo qui et nam sint sequi. In ea ipsa excepturi et. Ipsam ipsa error totam harum.', 'Sint eaque asperiores aut aut. Ipsam aut sit nostrum atque. Nisi delectus ea at omnis.', 32832.69, 65968.56, 'remote', 'senior', 'South Raphael', 11, '2026-01-13', 'draft', 0, NULL, NULL, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(2, 'Administrative Support Supervisors', 'Adipisci adipisci voluptas est voluptas. Adipisci reiciendis eaque ut quos. Voluptatem recusandae facere non nam qui rerum. Sint eum recusandae sit saepe.', 'Minus ipsam sunt rerum adipisci maiores voluptas autem. Fugiat qui unde dolores aut temporibus maiores numquam unde. Sed omnis doloremque sint. Facere nisi quia nulla quasi veniam.', 'Blanditiis eligendi voluptates nesciunt ad repellat quae. Qui est quaerat fugit iste. Unde et repellat a.', 35121.41, 115536.94, 'part_time', 'senior', 'West Benjaminfort', 12, '2026-02-17', 'published', 0, NULL, NULL, '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL),
(3, 'Welfare Eligibility Clerk', 'Explicabo omnis necessitatibus doloribus aliquid earum dicta rerum. Molestias quis ut recusandae consequuntur. Nostrum omnis hic dolorem quia.', 'Et voluptatibus magni iure repellendus ducimus nobis ipsa. Et aut sint placeat pariatur asperiores natus amet. Est veniam qui architecto quisquam quo dignissimos. Voluptas excepturi harum ea et qui harum possimus in. Ullam sint exercitationem necessitatibus labore id.', 'Autem aut expedita quis architecto debitis voluptatum nobis. Deserunt aut est nihil unde dolor explicabo voluptatem sequi. Quis voluptatem dolores accusantium consequuntur omnis similique.', 37099.53, 110957.39, 'full_time', 'senior', 'Elmerton', 14, '2026-02-18', 'cancelled', 0, NULL, NULL, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL),
(4, 'Test Job', 'Test Description', 'Enim perspiciatis quo odio culpa ad. Quo et assumenda possimus aut nihil delectus. Placeat aliquam non aut ut ab et.', 'Dolores accusantium occaecati deleniti consequatur. Non est sit iste quo quod iure. Distinctio laudantium et maiores dolor voluptas. Aliquam mollitia amet veniam corporis qui aut.', 38261.22, 66724.02, 'full_time', 'mid', 'West Gillian', 15, '2026-01-19', 'published', 0, NULL, NULL, '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL),
(5, 'Media and Communication Worker', 'Aut quia commodi animi repellat quae consequuntur quia. Laborum doloremque error quis. Numquam quaerat recusandae voluptates fugit.', 'Illum necessitatibus nisi et et. Sed dolorum sint est consectetur voluptatem. In hic facere commodi aperiam voluptates omnis. Sint eius ut quo aliquam magnam sit laudantium.', 'Totam sed totam aut et aspernatur culpa tempore. Voluptas voluptas corrupti illum aut vero. Ut architecto harum illo nobis est et quo. Doloribus vero possimus nam accusamus molestias aut.', 43417.20, 91758.68, 'contract', 'senior', 'Moniqueberg', 23, '2026-02-08', 'published', 0, NULL, NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL),
(6, 'System Administrator', 'Quo est eos tempore aut aliquam. Sed est quia nostrum sint consequatur. Ut est laudantium vel labore possimus. Accusantium beatae ut ullam porro et occaecati recusandae. Nostrum autem facere consequuntur quae ut voluptatem ut.', 'Velit voluptatem et sed voluptates excepturi qui. Consequatur quia non et distinctio nobis qui. Dolor quibusdam expedita quidem.', 'Commodi assumenda dolores dolorum minima. Quae voluptatum voluptatem distinctio ad. Distinctio quas maxime ut. Expedita aut consequatur tempore rerum.', 49976.60, 94434.80, 'internship', 'senior', 'West Jaylenborough', 25, '2026-01-31', 'closed', 0, NULL, NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_28_222853_create_user_profiles_table', 1),
(5, '2025_11_28_222855_create_events_table', 1),
(6, '2025_11_28_222858_create_event_registrations_table', 1),
(7, '2025_11_28_222903_create_courses_table', 1),
(8, '2025_11_28_222905_create_course_enrollments_table', 1),
(9, '2025_11_28_222907_create_jobs_table', 1),
(10, '2025_11_28_222910_create_job_applications_table', 1),
(11, '2025_11_28_223020_add_soft_deletes_to_users_table', 1),
(12, '2025_11_29_005000_add_fields_to_user_profiles_table', 1),
(13, '2025_11_29_005031_create_affiliated_courses_table', 1),
(14, '2025_11_29_005119_create_affiliated_course_enrollments_table', 1),
(15, '2025_11_29_005156_create_faqs_table', 1),
(16, '2025_11_29_005224_create_contact_messages_table', 1),
(17, '2025_11_29_005255_create_ads_table', 1),
(18, '2025_11_29_010515_add_role_to_users_table', 1),
(19, '2025_11_29_010628_create_assessments_table', 1),
(20, '2025_11_29_010715_create_questions_table', 1),
(21, '2025_11_29_010739_create_assessment_attempts_table', 1),
(22, '2025_11_29_010807_create_certificates_table', 1),
(23, '2025_11_29_010840_create_payment_gateways_table', 1),
(24, '2025_11_29_010902_create_transactions_table', 1),
(25, '2025_11_29_010925_create_ad_campaigns_table', 1),
(26, '2025_11_29_011508_create_testimonials_table', 1),
(27, '2025_11_29_011526_create_pages_table', 1),
(28, '2025_11_29_011546_create_course_filters_table', 1),
(29, '2025_11_29_011610_update_affiliated_courses_table_add_university_id', 1),
(30, '2025_11_29_222404_create_settings_table', 1),
(31, '2025_11_29_222416_add_is_admin_to_users_table', 1),
(32, '2025_11_30_141900_add_slider_fields_to_ads_table', 1),
(33, '2025_11_30_144858_create_site_settings_table', 1),
(34, '2025_12_01_025214_create_hero_contents_table', 1),
(35, '2025_12_01_132043_create_blog_categories_table', 1),
(36, '2025_12_01_132047_create_blog_posts_table', 1),
(37, '2025_12_01_132051_create_blog_comments_table', 1),
(38, '2025_12_01_132936_create_support_categories_table', 1),
(39, '2025_12_01_132942_create_support_tickets_table', 1),
(40, '2025_12_01_132948_create_support_replies_table', 1),
(41, '2025_12_03_110432_add_premium_features_to_content_tables', 1),
(42, '2025_12_03_114436_add_user_role_specific_fields', 1),
(43, '2025_12_03_142248_create_subscriptions_table', 1),
(44, '2025_12_03_223352_create_cv_uploads_table', 1),
(45, '2025_12_03_232814_add_premium_fields_to_content_tables', 1),
(46, '2025_12_04_055527_add_cv_scoring_and_enhancements_to_cv_uploads_table', 1),
(47, '2025_12_04_060110_update_cv_uploads_table_for_enhanced_features', 1),
(48, '2025_12_04_060742_create_job_alerts_table', 1),
(49, 'add_is_featured_column_to_affiliated_courses', 2),
(50, '2025_12_07_221050_add_is_featured_to_affiliated_courses_table', 3),
(51, '2025_12_08_091058_add_is_active_to_ads_table', 4),
(52, '2025_12_08_091500_add_target_pages_to_ads_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` json DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `type` enum('contact','services','about_us','custom') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'custom',
  `settings` json DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_created_by_foreign` (`created_by`),
  KEY `pages_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

DROP TABLE IF EXISTS `payment_gateways`;
CREATE TABLE IF NOT EXISTS `payment_gateways` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credentials` json NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `supported_currencies` json DEFAULT NULL,
  `supported_countries` json DEFAULT NULL,
  `transaction_fee_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `transaction_fee_fixed` decimal(10,2) NOT NULL DEFAULT '0.00',
  `additional_config` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_gateways_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `assessment_id` bigint UNSIGNED NOT NULL,
  `question_text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('multiple_choice','true_false','short_answer','essay','practical_task') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'multiple_choice',
  `options` json DEFAULT NULL,
  `correct_answer` json NOT NULL,
  `points` int NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `explanation` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_assessment_id_foreign` (`assessment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_logo', NULL, '2025-12-07 17:45:55', '2025-12-07 17:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `label`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Your Site Name', 'text', 'Site Name', 'The name of your website', 1, 1, '2025-12-07 17:45:55', '2025-12-07 17:45:55'),
(2, 'site_description', 'A description of your site', 'textarea', 'Site Description', 'A brief description of your website', 2, 1, '2025-12-07 17:45:55', '2025-12-07 17:45:55'),
(3, 'site_logo', 'logos/tBBf7iUzEXJrH7a8QrukIrQyvJyubMRvdYdRc9a0.jpg', 'image', 'Site Logo', 'Upload your site logo', 3, 1, '2025-12-07 17:45:55', '2025-12-08 10:23:09'),
(4, 'about_us', 'Information about our company', 'textarea', 'About Us', 'Content for the About Us page', 4, 1, '2025-12-07 17:45:55', '2025-12-07 17:45:55'),
(5, 'services_info', 'Information about our services', 'textarea', 'Services Information', 'Content for the Services page', 5, 1, '2025-12-07 17:45:55', '2025-12-07 17:45:55'),
(6, 'contact_content', 'Information for the contact page', 'text', NULL, NULL, 0, 1, '2025-12-08 10:23:09', '2025-12-08 10:23:09'),
(7, 'contact_email', 'support@example.com', 'text', NULL, NULL, 0, 1, '2025-12-08 10:23:09', '2025-12-08 10:23:09'),
(8, 'contact_phone', '+1 (123) 456-7890', 'text', NULL, NULL, 0, 1, '2025-12-08 10:23:09', '2025-12-08 10:23:09'),
(9, 'contact_address', '123 Main St, City, Country', 'text', NULL, NULL, 0, 1, '2025-12-08 10:23:09', '2025-12-08 10:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_gateway` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `features` json DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_packages`
--

DROP TABLE IF EXISTS `subscription_packages`;
CREATE TABLE IF NOT EXISTS `subscription_packages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration_days` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_packages`
--

INSERT INTO `subscription_packages` (`id`, `name`, `role_type`, `type`, `price`, `features`, `description`, `duration_days`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Basic Student', 'student', 'one_time', 9.99, '\"[\\\"access_to_all_courses\\\",\\\"event_registration\\\",\\\"job_applications\\\"]\"', 'Access to all courses and events for 30 days', 30, 1, 1, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(2, 'Premium Student', 'student', 'monthly', 19.99, '\"[\\\"access_to_all_courses\\\",\\\"event_registration\\\",\\\"job_applications\\\",\\\"premium_support\\\",\\\"analytics_dashboard\\\"]\"', 'Monthly access to all features', 30, 1, 2, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(3, 'Basic Recruiter', 'recruiter', 'monthly', 49.99, '\"[\\\"post_jobs\\\",\\\"view_applications\\\",\\\"premium_support\\\"]\"', 'Post unlimited jobs with basic features', 30, 1, 3, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(4, 'Premium Recruiter', 'recruiter', 'monthly', 99.99, '\"[\\\"post_jobs\\\",\\\"view_applications\\\",\\\"analytics_dashboard\\\",\\\"promoted_content\\\",\\\"premium_support\\\"]\"', 'Full recruiter suite with analytics', 30, 1, 4, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(5, 'Basic University', 'university_manager', 'yearly', 499.99, '\"[\\\"create_courses\\\",\\\"manage_students\\\",\\\"host_events\\\",\\\"analytics_dashboard\\\"]\"', 'Basic university package with course creation', 365, 1, 5, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(6, 'Premium University', 'university_manager', 'yearly', 999.99, '\"[\\\"create_courses\\\",\\\"manage_students\\\",\\\"host_events\\\",\\\"analytics_dashboard\\\",\\\"promoted_content\\\",\\\"premium_support\\\"]\"', 'Complete university platform with advanced features', 365, 1, 6, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(7, 'Event Manager Basic', 'event_hoster', 'monthly', 29.99, '\"[\\\"host_events\\\",\\\"register_attendees\\\",\\\"analytics_dashboard\\\"]\"', 'Host events with basic analytics', 30, 1, 7, '2025-12-08 08:06:44', '2025-12-08 08:06:44'),
(8, 'Event Manager Pro', 'event_hoster', 'monthly', 59.99, '\"[\\\"host_events\\\",\\\"register_attendees\\\",\\\"analytics_dashboard\\\",\\\"promoted_content\\\",\\\"premium_support\\\"]\"', 'Professional event hosting with premium features', 30, 1, 8, '2025-12-08 08:06:44', '2025-12-08 08:06:44');

-- --------------------------------------------------------

--
-- Table structure for table `support_categories`
--

DROP TABLE IF EXISTS `support_categories`;
CREATE TABLE IF NOT EXISTS `support_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `support_categories_slug_unique` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_replies`
--

DROP TABLE IF EXISTS `support_replies`;
CREATE TABLE IF NOT EXISTS `support_replies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `support_ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_internal_note` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_replies_support_ticket_id_foreign` (`support_ticket_id`),
  KEY `support_replies_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `support_category_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','on_hold','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_tickets_user_id_foreign` (`user_id`),
  KEY `support_tickets_support_category_id_foreign` (`support_category_id`),
  KEY `support_tickets_assigned_to_foreign` (`assigned_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `rating` int DEFAULT NULL,
  `testimonial_date` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_gateway` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `fees` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL,
  `metadata` json DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_transaction_id_unique` (`transaction_id`),
  KEY `transactions_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acronym` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating` decimal(3,2) DEFAULT NULL,
  `programs` json DEFAULT NULL,
  `admissions` json DEFAULT NULL,
  `facilities` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `universities_country_id_foreign` (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `name`, `acronym`, `logo`, `location`, `country_id`, `description`, `website`, `email`, `phone`, `is_active`, `rating`, `programs`, `admissions`, `facilities`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Cremin, Osinski and Langworth', 'gte', 'http://www.reynolds.biz/voluptas-molestiae-minus-neque-recusandae.html', 'Port Alekborough', 1, 'Rerum in consequatur modi. Non voluptatum cupiditate optio. Rerum itaque deleniti unde iure saepe modi corrupti dolorum. Sed ratione quam aliquam quia consectetur.', 'http://www.osinski.com/maxime-inventore-fuga-ea-ducimus-eveniet-odit-nemo-nobis.html', 'swaelchi@bradtke.com', '+1-423-467-1662', 1, 4.31, '\"[\\\"unde\\\",\\\"enim\\\",\\\"eaque\\\",\\\"quis\\\",\\\"dicta\\\"]\"', '\"[\\\"laudantium\\\",\\\"culpa\\\",\\\"laudantium\\\",\\\"illum\\\",\\\"minus\\\"]\"', '\"[\\\"odit\\\",\\\"animi\\\",\\\"repellendus\\\",\\\"dolor\\\",\\\"minima\\\"]\"', NULL, '2025-12-07 17:46:00', '2025-12-07 17:46:00'),
(2, 'Willms, Kutch and Daugherty', 'gio', 'http://davis.info/quo-assumenda-deleniti-laborum-id-numquam-libero', 'Jonathantown', 2, 'Eos voluptates qui aliquam aliquam dignissimos. Et provident ut maiores ad ducimus. Doloribus impedit accusamus nostrum exercitationem dolore.', 'http://www.schultz.org/nihil-ipsum-rerum-adipisci-quia.html', 'cjakubowski@schuppe.net', '870.996.0622', 1, 3.36, '\"[\\\"dolor\\\",\\\"non\\\",\\\"eius\\\",\\\"sapiente\\\",\\\"vel\\\"]\"', '\"[\\\"aliquam\\\",\\\"quis\\\",\\\"laboriosam\\\",\\\"dolorem\\\",\\\"nobis\\\"]\"', '\"[\\\"delectus\\\",\\\"dicta\\\",\\\"voluptatem\\\",\\\"delectus\\\",\\\"sed\\\"]\"', NULL, '2025-12-07 17:46:00', '2025-12-07 17:46:00'),
(3, 'Senger-Zieme', 'osk', 'http://www.schroeder.net/et-nobis-sed-et-autem-omnis-autem-error.html', 'Lake Delmer', 3, 'Corporis est autem labore velit. Veritatis nulla nam qui iusto mollitia at. Dolorum sint quia officiis error et rerum harum optio.', 'https://denesik.org/repellat-ea-doloribus-culpa-et.html', 'brandon30@yahoo.com', '+1.413.283.6563', 1, 3.29, '\"[\\\"et\\\",\\\"quia\\\",\\\"excepturi\\\",\\\"quasi\\\",\\\"consequuntur\\\"]\"', '\"[\\\"enim\\\",\\\"est\\\",\\\"minima\\\",\\\"ut\\\",\\\"quo\\\"]\"', '\"[\\\"eum\\\",\\\"est\\\",\\\"dolor\\\",\\\"dicta\\\",\\\"optio\\\"]\"', NULL, '2025-12-07 17:46:01', '2025-12-07 17:46:01'),
(4, 'Hackett, Wunsch and Weissnat', 'vpe', 'http://hahn.net/minima-corrupti-quasi-nobis-consequatur', 'McLaughlinton', 4, 'Porro quasi et sapiente cumque tempore porro. Aliquid esse labore est quos sapiente.', 'http://mckenzie.biz/voluptas-reiciendis-labore-asperiores-a-illum-perspiciatis.html', 'xrutherford@hotmail.com', '256.639.5104', 1, 2.56, '\"[\\\"id\\\",\\\"rerum\\\",\\\"aut\\\",\\\"quia\\\",\\\"fugit\\\"]\"', '\"[\\\"cumque\\\",\\\"tenetur\\\",\\\"adipisci\\\",\\\"libero\\\",\\\"quis\\\"]\"', '\"[\\\"sed\\\",\\\"reiciendis\\\",\\\"quod\\\",\\\"rerum\\\",\\\"possimus\\\"]\"', NULL, '2025-12-07 17:46:01', '2025-12-07 17:46:01'),
(5, 'Kuhn, Collier and Bergstrom', 'xzn', 'http://yost.com/nisi-rerum-tenetur-vel-ea-iusto', 'West Karleyfort', 5, 'Fugit dignissimos nobis odio. Aut quo quod aut impedit eius fugit. Consequatur quia id ea aliquam libero.', 'http://www.lind.com/modi-cumque-assumenda-repellat-dolorem-distinctio-atque', 'aliza60@johns.org', '1-360-225-3348', 1, 2.96, '\"[\\\"omnis\\\",\\\"et\\\",\\\"suscipit\\\",\\\"magni\\\",\\\"commodi\\\"]\"', '\"[\\\"error\\\",\\\"sed\\\",\\\"eos\\\",\\\"voluptatem\\\",\\\"reiciendis\\\"]\"', '\"[\\\"quam\\\",\\\"voluptatibus\\\",\\\"veniam\\\",\\\"odit\\\",\\\"dolor\\\"]\"', NULL, '2025-12-07 17:46:04', '2025-12-07 17:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `role` enum('student','recruiter','university_manager','event_hoster','job_seeker','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `role`, `is_admin`) VALUES
(1, 'Justina Cronin', 'jewell30@example.com', '2025-12-07 17:46:00', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'cFZT1GvX61', '2025-12-07 17:46:00', '2025-12-07 17:46:00', NULL, 'student', 0),
(2, 'Maria Kuhlman', 'vcartwright@example.org', '2025-12-07 17:46:01', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '436Qnn1Npl', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL, 'student', 0),
(3, 'Ms. Pattie Thiel', 'ythiel@example.net', '2025-12-07 17:46:01', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'dV6dXKOMc6', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL, 'student', 0),
(4, 'Bernie Collins', 'vergie.wolff@example.net', '2025-12-07 17:46:01', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'TXZaSK12BO', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL, 'student', 0),
(5, 'Miss Idella Marvin Sr.', 'louie14@example.com', '2025-12-07 17:46:01', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'w9sCXG1dHr', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL, 'student', 0),
(6, 'Brenden Thiel', 'luettgen.rosa@example.org', '2025-12-07 17:46:01', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'JJXnNuBHsX', '2025-12-07 17:46:01', '2025-12-07 17:46:01', NULL, 'student', 0),
(7, 'Ms. Lisette Farrell', 'beer.mack@example.net', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'yIG6hZBAtw', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(8, 'Sophia Muller', 'raphael.bahringer@example.org', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'hxWEyKiqtW', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(9, 'Dr. Dean Rath Sr.', 'malvina29@example.org', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'uTOxokPj0Q', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(10, 'Miss Lacey Gibson', 'upadberg@example.net', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'EIXOkX5Nwh', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(11, 'Ardella Greenholt', 'rachael.stracke@example.net', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'xORRKjNFfA', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(12, 'Davin Harber', 'kessler.mikayla@example.com', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '8oTYfyn8Su', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(13, 'Mr. Luther Lehner', 'feest.grayce@example.net', '2025-12-07 17:46:02', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'aCvdS19Tbp', '2025-12-07 17:46:02', '2025-12-07 17:46:02', NULL, 'student', 0),
(14, 'Deion Wiza', 'bill28@example.org', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '7SgujdOTwt', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(15, 'Josh Koepp', 'laurie.luettgen@example.org', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'oKmPELiEzn', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(16, 'Korbin Okuneva', 'adolphus99@example.net', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'gnj8WzbUay', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(17, 'Johnnie Pollich', 'balistreri.delfina@example.org', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '0hCPIoc7cg', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(18, 'Samanta Bailey', 'shields.mariela@example.org', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'NQ0h7kbjZq', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(19, 'Marco Swaniawski', 'upouros@example.net', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'kMEWS6O2nO', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(20, 'Mr. Otho Hammes Jr.', 'keeling.kasandra@example.com', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'NoWS9p2rn7', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(21, 'Dr. Laurence Kshlerin MD', 'christophe63@example.org', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'zALefVh67l', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(22, 'Dr. Sidney Schultz', 'kerluke.dulce@example.com', '2025-12-07 17:46:03', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'BH8glxrtHT', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, 'student', 0),
(23, 'Mrs. Skyla Legros DVM', 'mfay@example.com', '2025-12-07 17:46:04', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'k5zb13ESMY', '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL, 'student', 0),
(24, 'Joaquin Stokes', 'west.jayda@example.com', '2025-12-07 17:46:04', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'TThWbzRk3v', '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL, 'student', 0),
(25, 'Dr. Faustino Stiedemann Jr.', 'lratke@example.org', '2025-12-07 17:46:04', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '1ZVoBhUffh', '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL, 'student', 0),
(26, 'Darby Wiza Sr.', 'amanda46@example.org', '2025-12-07 17:46:04', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', 'aus6z6Ts5Z', '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL, 'student', 0),
(27, 'Leonard O\'Conner', 'gjacobson@example.net', '2025-12-07 17:46:04', '$2y$12$fQk/HctCXBWlxS83xAeK4eZdJDy7vbodQ.QVabyic/H2mQWCTHq2a', '8yKh5itbGg', '2025-12-07 17:46:04', '2025-12-07 17:46:04', NULL, 'student', 0),
(28, 'Azuka', 'gooption@yahoo.com', NULL, '$2y$12$EYUlRlx/lcF1iE2bz/UTIOqq7sYQXA/zIiVjA.5ymHNt4VQz1WG3u', NULL, '2025-12-07 21:16:32', '2025-12-07 21:16:32', NULL, 'event_hoster', 0),
(29, 'Test User', 'test@example.com', '2025-12-08 08:06:42', '$2y$12$ahYIpJAajHyvlF8lZ1wmnuxAIBXqJ3APYOUnTVujvp30I2H0BgHx6', 'dfgf6iS199', '2025-12-08 08:06:43', '2025-12-08 08:06:43', NULL, 'student', 0),
(30, 'Admin User', 'admin@example.com', NULL, '$2y$12$hcl7k56bf.qcOQnMqcNGUOvYnBjq04SC62iyBzlkd4QD1PE/wCpP6', NULL, '2025-12-08 08:07:24', '2025-12-08 08:07:24', NULL, 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `skills` json DEFAULT NULL,
  `interests` json DEFAULT NULL,
  `education_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_description` text COLLATE utf8mb4_unicode_ci,
  `university_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_contact_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_contact_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_description` text COLLATE utf8mb4_unicode_ci,
  `company_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_industry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_contact_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_contact_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_description` text COLLATE utf8mb4_unicode_ci,
  `organization_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_contact_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_contact_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_contact_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `linkedin_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portfolio_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profiles_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `address`, `date_of_birth`, `created_at`, `updated_at`, `deleted_at`, `skills`, `interests`, `education_level`, `resume_path`, `university_name`, `university_description`, `university_logo`, `university_website`, `university_contact_person`, `university_contact_email`, `university_contact_phone`, `university_address`, `company_name`, `company_description`, `company_logo`, `company_website`, `company_industry`, `company_size`, `company_contact_person`, `company_contact_email`, `company_contact_phone`, `company_address`, `organization_name`, `organization_description`, `organization_logo`, `organization_website`, `organization_contact_person`, `organization_contact_email`, `organization_contact_phone`, `organization_address`, `bio`, `linkedin_url`, `portfolio_url`) VALUES
(1, 16, 'Ivah', 'Brakus', '689-569-6752', '11576 Nicolas Ridges\nLittelton, MN 20830', '1996-11-07', '2025-12-07 17:46:03', '2025-12-07 17:46:03', NULL, '\"[\\\"ullam\\\",\\\"perspiciatis\\\",\\\"inventore\\\",\\\"totam\\\",\\\"ratione\\\"]\"', '\"[\\\"qui\\\",\\\"maxime\\\",\\\"ab\\\",\\\"at\\\",\\\"eius\\\"]\"', 'high_school', 'https://www.volkman.com/consequatur-sint-commodi-quaerat-et-ducimus-error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cv_uploads`
--
ALTER TABLE `cv_uploads` ADD FULLTEXT KEY `cv_uploads_summary_fulltext` (`summary`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
