-- industry.co.zw Database Schema
-- Synchronized with production export

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `industry_co_zw`
--
CREATE DATABASE IF NOT EXISTS `industry_co_zw` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `industry_co_zw`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industries`
--

CREATE TABLE `industries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `opportunities` text DEFAULT NULL,
  `key_industries` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cifoz_categories`
--

CREATE TABLE `cifoz_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `slug` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` tinyint(1) DEFAULT 1 COMMENT '1=parent, 2=sub, 3=sub-sub',
  `description` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_level` (`level`),
  CONSTRAINT `cifoz_categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `cifoz_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `industry_id` int(11) NOT NULL,
  `province_id` int(11) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `stakeholder` varchar(20) DEFAULT NULL,
  `listing_type` varchar(20) DEFAULT 'industry',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `showcase_tier` enum('platinum','gold','silver') DEFAULT NULL,
  `showcase_order` int(11) DEFAULT 0,
  `showcase_active` tinyint(1) DEFAULT 0,
  `showcase_tagline` varchar(255) DEFAULT NULL,
  `showcase_full_description` text DEFAULT NULL,
  `cifoz_category_raw` varchar(500) DEFAULT NULL,
  `cifoz_member_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_stakeholder` (`stakeholder`),
  KEY `idx_industry` (`industry_id`),
  KEY `idx_province` (`province_id`),
  KEY `idx_listing_type` (`listing_type`),
  KEY `idx_stakeholder_type` (`stakeholder`,`listing_type`),
  KEY `idx_cifoz_member_type` (`cifoz_member_type`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `companies_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stakeholder` varchar(20) NOT NULL,
  `type` enum('logo','banner','flyer','poster') NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `clicks` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_stakeholder_type` (`stakeholder`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `organizer` varchar(20) NOT NULL,
  `event_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_organizer_date` (`organizer`,`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE `tenders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `tender_number` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `issuing_organization` varchar(200) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `submission_requirements` text DEFAULT NULL,
  `eligibility_criteria` text DEFAULT NULL,
  `closing_date` date NOT NULL,
  `bid_opening_date` date DEFAULT NULL,
  `document_url` varchar(255) DEFAULT NULL,
  `document_url2` varchar(255) DEFAULT NULL,
  `document_url3` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_closing_date` (`closing_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

CREATE TABLE `exports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(200) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `specs` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `moq` int(11) DEFAULT 1,
  `company` varchar(200) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `reviews` int(11) DEFAULT 0,
  `exports_to` varchar(500) DEFAULT NULL,
  `certifications` varchar(500) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_cifoz_categories`
--

CREATE TABLE `company_cifoz_categories` (
  `company_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`company_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `company_cifoz_categories_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `company_cifoz_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `cifoz_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industry_news`
--

CREATE TABLE `industry_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `industry_slug` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `source` varchar(200) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_sponsored` tinyint(1) DEFAULT 0,
  `views` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `industry_slug` (`industry_slug`),
  KEY `publish_date` (`publish_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industry_showcase`
--

CREATE TABLE `industry_showcase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `industry_slug` varchar(50) NOT NULL,
  `company_id` int(11) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `tier` enum('platinum','gold','silver') DEFAULT 'silver',
  `custom_headline` varchar(200) DEFAULT NULL,
  `custom_description` text DEFAULT NULL,
  `custom_image` varchar(255) DEFAULT NULL,
  `featured_until` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `industry_slug` (`industry_slug`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `industry_showcase_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showcase_banners`
--

CREATE TABLE `showcase_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `link_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `showcase_banners_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showcase_flyers`
--

CREATE TABLE `showcase_flyers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `tier` enum('platinum','gold') DEFAULT 'gold',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `showcase_flyers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showcase_gallery`
--

CREATE TABLE `showcase_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `showcase_gallery_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showcase_posters`
--

CREATE TABLE `showcase_posters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `showcase_posters_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showcase_videos`
--

CREATE TABLE `showcase_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `video_url` varchar(500) NOT NULL,
  `embed_code` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `showcase_videos_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `embed_url` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `recaptcha_score` decimal(3,2) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- SEED DATA
--

INSERT INTO `industries` (`id`, `slug`, `name`, `icon`, `description`, `display_order`) VALUES
(1, 'auto', 'Auto', '🚗', 'Automotive industry including vehicle sales, repairs, and parts manufacturing', 1),
(2, 'accommodation', 'Accommodation', '🏨', 'Hotels, lodges, and accommodation services across Zimbabwe', 2),
(3, 'agriculture', 'Agriculture', '🌾', 'Farming, crop production, livestock, and agricultural services', 3),
(4, 'banking-finance', 'Banking & Finance', '🏦', 'Banks, microfinance, insurance, and financial services', 4),
(5, 'biotechnology', 'Biotechnology', '🧬', 'Biotech research, development, and applications', 5),
(6, 'construction', 'Construction', '🏗️', 'Building, civil engineering, and construction services', 6),
(7, 'education', 'Education', '📚', 'Schools, universities, colleges, and educational services', 7),
(8, 'energy-power', 'Energy & Power', '⚡', 'Electricity generation, distribution, and renewable energy', 8),
(9, 'healthcare', 'Healthcare', '🏥', 'Hospitals, clinics, pharmaceutical, and medical services', 9),
(10, 'manufacturing', 'Manufacturing', '🏭', 'Industrial manufacturing and production', 10),
(11, 'mining', 'Mining', '⛏️', 'Mineral extraction, mining operations, and quarrying', 11),
(12, 'technology-ict', 'Technology & ICT', '💻', 'Information technology, software, and telecommunications', 12),
(13, 'tourism-hospitality', 'Tourism & Hospitality', '🎯', 'Tourism operators, travel agencies, and hospitality', 13),
(14, 'transport-logistics', 'Transport & Logistics', '🚛', 'Transportation, logistics, and supply chain services', 14);

INSERT INTO `provinces` (`id`, `slug`, `name`, `opportunities`, `key_industries`, `display_order`) VALUES
(1, 'harare', 'Harare', 'Capital city with diverse business opportunities, financial services hub, and growing tech sector', 'Banking & Finance, Technology & ICT, Manufacturing, Construction', 1),
(2, 'bulawayo', 'Bulawayo', 'Industrial hub with strong manufacturing base, cultural tourism, and educational institutions', 'Manufacturing, Education, Tourism & Hospitality, Transport & Logistics', 2),
(3, 'manicaland', 'Manicaland', 'Agricultural heartland with timber, tea, coffee production, and tourism potential', 'Agriculture, Mining, Tourism & Hospitality, Education', 3),
(4, 'mashonaland-central', 'Mashonaland Central', 'Mining region with agricultural potential, tobacco farming, and mineral deposits', 'Mining, Agriculture, Manufacturing, Construction', 4),
(5, 'mashonaland-east', 'Mashonaland East', 'Agricultural production, horticulture, and proximity to Harare markets', 'Agriculture, Manufacturing, Transport & Logistics, Education', 5),
(6, 'mashonaland-west', 'Mashonaland West', 'Tourism attractions, Lake Kariba, mining operations, and commercial farming', 'Tourism & Hospitality, Mining, Agriculture, Energy & Power', 6),
(7, 'masvingo', 'Masvingo', 'Great Zimbabwe heritage site, agriculture, and growing industrial base', 'Tourism & Hospitality, Agriculture, Mining, Construction', 7),
(8, 'matabeleland-north', 'Matabeleland North', 'Victoria Falls tourism, wildlife conservation, coal mining, and timber', 'Tourism & Hospitality, Mining, Agriculture, Construction', 8),
(9, 'matabeleland-south', 'Matabeleland South', 'Ranching, mining, border trade with South Africa and Botswana', 'Mining, Agriculture, Transport & Logistics, Manufacturing', 9),
(10, 'midlands', 'Midlands', 'Central location advantage, mining, manufacturing, and educational institutions', 'Mining, Manufacturing, Education, Agriculture', 10);

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `email`) VALUES
(1, 'admin', '$2y$10$u8Ue4voXrXHfOm2kgVSLJeSOP5NY2p7XiKUIfNpaiSaPcDMMzdN6m', 'admin@industry.co.zw');

COMMIT;
