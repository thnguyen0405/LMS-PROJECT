-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 27, 2025 at 10:50 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eduzora_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `full_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `full_name`, `email`, `photo`, `password`, `token`, `role`, `status`) VALUES
(1, 'John Doe', 'admin@gmail.com', '1696430317.jpg', '$2y$10$ZWxErZpXCc8M34cN57tA.OD0b/n/w5CjCZITFXtoObQ3xMkkiiPL6', '1743071416', 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`) VALUES
(1, 'Web Design', 'fas fa-laptop-code'),
(2, 'Web Development', 'fas fa-code'),
(3, 'Online Business', 'fas fa-chart-line'),
(4, 'Search Engine', 'fas fa-search'),
(5, 'Landing Page', 'fas fa-graduation-cap'),
(6, 'Sales Technique', 'fas fa-code'),
(7, 'App Development', 'fas fa-tablet-alt'),
(8, 'Digital Marketing', 'fas fa-camera'),
(10, 'Graphic Design', 'fas fa-paint-brush'),
(11, 'UI UX Design', 'fas fa-mobile-alt'),
(12, 'Technology', 'fas fa-robot'),
(13, 'AI Robot', 'fas fa-robot');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `coupon_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percentage` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `course_id`, `coupon_name`, `discount_percentage`, `start_date`, `end_date`) VALUES
(1, 3, 'ABC', 20, '2025-03-26', '2025-03-30'),
(2, 3, 'NICE', 100, '2025-03-26', '2025-03-30'),
(3, 7, 'TODAY', 50, '2025-03-26', '2025-03-30'),
(4, 7, 'FULL', 100, '2025-03-26', '2025-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `price_old` text COLLATE utf8mb4_unicode_ci,
  `category_id` int NOT NULL,
  `level_id` int NOT NULL,
  `language_id` int NOT NULL,
  `instructor_id` int NOT NULL,
  `total_student` int NOT NULL,
  `total_rating` int NOT NULL,
  `total_rating_score` int NOT NULL,
  `average_rating` float NOT NULL,
  `featured_photo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_banner` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_video_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_video_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_video` int NOT NULL,
  `total_resource` int NOT NULL,
  `total_video_second` int NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `slug`, `description`, `price`, `price_old`, `category_id`, `level_id`, `language_id`, `instructor_id`, `total_student`, `total_rating`, `total_rating_score`, `average_rating`, `featured_photo`, `featured_banner`, `featured_video_type`, `featured_video_content`, `total_video`, `total_resource`, `total_video_second`, `updated_at`, `status`) VALUES
(3, 'Marketing Course', 'marketing-course', '<h2>Description</h2>\r\n<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.</p>\r\n<h2>What you will learn</h2>\r\n<p>Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.<br /><br />Ad sit omnes scaevola. Atqui consequat suscipiantur ei eam. Sed everti fierent aliquando in, agam recusabo ut qui. Nam sale elitr antiopam te, aliquip imperdiet deseruisse nam te, scripta deserunt vel an. An has nostro facilis torquatos, cu ridens suscipiantur vim. Et ullum nihil labitur est. Sapientem efficiendi per id.</p>', 39, '49', 3, 3, 1, 1, 4, 2, 9, 4.5, 'course_featured_photo_1742670540.jpg', 'course_featured_banner_1742670540.jpg', 'youtube', 'eBm3svj5GPc', 4, 1, 1150, '2025-03-26 11:50:46', 'Active'),
(4, 'Search Engine Basic', 'seo-basic', '<p>This is a basic SEO course.</p>', 9, '', 8, 1, 1, 2, 3, 0, 0, 0, 'course_featured_photo_1742717351.jpg', 'course_featured_banner_1742717351.jpg', 'vimeo', '167788267', 2, 0, 1377, '2025-03-24 09:33:31', 'Active'),
(7, 'Laravel Basic to Advanced', 'laravel-basic-to-advanced', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 0, '', 2, 1, 2, 1, 1, 1, 2, 2, 'course_featured_photo_1743115290.jpg', 'course_featured_banner_1742937591.jpg', 'youtube', 'asasasas', 1, 0, 223, '2025-03-27 22:41:14', 'Active'),
(8, 'Python Web Course', 'python-web-course', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 79, '', 2, 3, 1, 1, 0, 0, 0, 0, 'course_featured_photo_1743115403.jpg', 'course_featured_banner_1743115403.jpg', 'youtube', '7DvfbgQtKhs', 2, 0, 750, '2025-03-27 22:43:23', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`) VALUES
(1, 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea?', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.'),
(2, 'Tale tation tamquam eam ex, lorem facer laboramus at sit?', 'Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.'),
(3, 'Ei vix docendi fabellas, vix in falli aeque?', 'Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.'),
(4, 'Ad sit omnes scaevola atqui consequat?', 'Ad sit omnes scaevola. Atqui consequat suscipiantur ei eam. Sed everti fierent aliquando in, agam recusabo ut qui. Nam sale elitr antiopam te, aliquip imperdiet deseruisse nam te, scripta deserunt vel an. An has nostro facilis torquatos, cu ridens suscipiantur vim. Et ullum nihil labitur est. Sapientem efficiendi per id.'),
(5, 'Vel explicari imperdiet ei, est graece apeirian?', 'Vel explicari imperdiet ei, est graece apeirian cu, ne ius rebum putent sensibus. Pro lorem essent possim ut, cum mazim evertitur dissentiet eu. Te nam labore aeterno. Eum libris theophrastus cu, qui omnis tempor nominavi an, cum in utamur vocibus voluptua. No omittam menandri accusata eum, his fabellas dissentiunt ex.');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` mediumtext COLLATE utf8mb4_unicode_ci,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_course` int NOT NULL,
  `total_rating` int NOT NULL,
  `total_rating_score` int NOT NULL,
  `average_rating` float NOT NULL,
  `token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `designation`, `email`, `photo`, `password`, `phone`, `address`, `country`, `state`, `city`, `zip`, `biography`, `website`, `facebook`, `twitter`, `linkedin`, `instagram`, `total_course`, `total_rating`, `total_rating_score`, `average_rating`, `token`, `status`) VALUES
(1, 'Smith Cooper', 'Web Developer', 'smith@gmail.com', 'instructor_1742550018.jpg', '$2y$10$8BqevgTyr42Mxg.msnC/uuLimAR61PED5TvorJGIr/jBDtGI2dqLW', '219-869-8234', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.smith.com', '#', '#', '#', '#', 3, 3, 11, 3.66667, '', 1),
(2, 'Samuel Bart', 'Marketer', 'samuel@gmail.com', '', '$2y$10$yloPmKvH55Bosc2n2WQ6COqnChFtY9Z02t9CC0fBs1fHhlALk9OBm', '220-869-8734', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.samuel.com', '#', '#', '#', '#', 2, 0, 0, 0, '', 1),
(5, 'Gray Stafford', 'Web Designer', 'gray@gmail.com', 'instructor_1742932999.jpg', '$2y$10$GDDmxKxD8pv0GLP5MH/8tuNKLVotdokEZDlIVjBrlIMinBq27pNga', '219-869-8734', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.gray.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1),
(6, 'Derek Johnson', 'Local Businessman', 'derek@gmail.com', '', '$2y$10$NYiMExLsvEJGC8W38WcK1uII9EIj3QVnwa4EjhEWqZhATnNWK0XTu', '219-866-8739', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.derek.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1),
(7, 'Hector Harmon', 'Technical Writer', 'hector@gmail.com', '', '$2y$10$FhnMIPnsYhpdKAXBMBZQI.zCX3j/NnjENKpKzM3WoOaIV/8MZlIFu', '219-866-8738', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.hector.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1),
(8, 'Erin Conrad', 'Graphic Designer', 'erin@gmail.com', '', '$2y$10$/kvz3DIHJID1eVXtcePfXuLHsI8CvogregWvrzLR0EhYISbfgjblu', '219-866-8736', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.erin.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1),
(9, 'Naida Russell', 'Product Designer', 'naida@gmail.com', '', '$2y$10$J6vlucAZg.IEgM8id2ImfO9Ta1NfuCSjR4qsm60jYLIoohRRtt8XS', '219-866-8735', '268 Duffy Street', '', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.naida.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1),
(10, 'Milton C. Perkins', 'Article Writer', 'milton@gmail.com', '', '$2y$10$9DYQ7Zj1MUr7Nrr9Js464OPuFsi5SePjAX5NgZrdIcXzU.4oo2eQK', '219-866-8734', '268 Duffy Street', 'USA', 'IN', 'Rensselaer', '47978', 'Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.\r\n\r\nTale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.', 'https://www.milton.com', '#', '#', '#', '#', 0, 0, 0, 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(1, 'English'),
(2, 'Arabic'),
(3, 'Chinese'),
(4, 'Bengali');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `module_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_second` int NOT NULL,
  `resource_content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_preview` int NOT NULL,
  `item_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `module_id`, `name`, `lesson_type`, `video_type`, `video_content`, `duration_second`, `resource_content`, `is_preview`, `item_order`) VALUES
(16, 3, 10, 'Welcome to Marketing Course', 'video', 'youtube', 'R0X96DoEJeU', 438, '', 1, 1),
(23, 7, 12, 'BB', 'video', 'youtube', 'NZS-q33qlkg', 223, '', 1, 1),
(24, 3, 10, 'What you will learn in this course', 'video', 'youtube', 'ieFVstRCZE8', 223, '', 1, 2),
(25, 3, 13, 'Marketing Basic', 'video', 'youtube', 'ieFVstRCZE8', 24, '', 0, 1),
(26, 3, 13, 'Difference between sales and marketing', 'video', 'youtube', 'ieFVstRCZE8', 465, '', 0, 2),
(27, 3, 13, 'Marketing Lesson Cheatsheet', 'resource', '', '', 0, 'lesson_resource_1742984561.zip', 0, 3),
(28, 4, 3, 'Welcome to SEO Course of 2025', 'video', 'vimeo', '167788267', 154, '', 1, 1),
(29, 4, 4, 'Is SEO still effective?', 'video', 'vimeo', '167788267', 1223, '', 0, 1),
(30, 8, 14, 'Welcome to New World of Python', 'video', 'youtube', 'IximpjtBbvk', 465, '', 1, 1),
(31, 8, 15, 'In 2025, how python course will work', 'video', 'youtube', 'IximpjtBbvk', 285, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `name`) VALUES
(1, 'Beginner'),
(2, 'Intermediate'),
(3, 'Expert');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `student_id` int NOT NULL,
  `instructor_id` int NOT NULL,
  `sender` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `course_id`, `student_id`, `instructor_id`, `sender`, `message`, `message_date_time`) VALUES
(2, 3, 2, 1, 'student', 'I want to discuss you something, will you have time please for me in the today night?', '2025-03-27 13:38:22'),
(3, 7, 2, 1, 'student', 'Why this course title name if only \"BBB\"?', '2025-03-27 13:39:27'),
(4, 3, 1, 1, 'student', 'I am new in php. Can you please guide me about something?', '2025-03-27 13:40:12'),
(5, 3, 2, 1, 'instructor', 'Yes. You can discuss. Today night I a free. ', '2025-03-27 17:05:50'),
(6, 3, 2, 1, 'student', 'Great! Thank you. My Skype: david_man_999', '2025-03-27 17:12:24'),
(7, 3, 2, 1, 'instructor', 'Awesome! I will add you there.', '2025-03-27 17:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int NOT NULL,
  `course_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_video` int NOT NULL,
  `total_resource` int NOT NULL,
  `total_video_second` int NOT NULL,
  `item_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `course_id`, `name`, `total_video`, `total_resource`, `total_video_second`, `item_order`) VALUES
(3, 4, 'Introduction', 1, 0, 154, 1),
(4, 4, 'What is SEO?', 1, 0, 1223, 2),
(10, 3, 'Instroduction to Course', 2, 0, 661, 1),
(12, 7, 'AA', 1, 0, 223, 1),
(13, 3, 'What is Marketing', 2, 1, 489, 2),
(14, 8, 'Introduction to Python', 1, 0, 465, 1),
(15, 8, 'Why Python Course?', 1, 0, 285, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int NOT NULL,
  `payment_method` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_paid` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `student_id`, `payment_method`, `total_paid`, `payment_status`, `payment_date`) VALUES
(1, '6bb22c3a94', 2, 'Stripe', '31', 'Completed', '2025-03-27'),
(2, '356f9406d2', 1, '', '0', 'Completed', '2025-03-27'),
(3, 'c8da5d8de1', 2, 'Stripe', '9', 'Completed', '2025-03-27'),
(4, '8323ade186', 4, 'Stripe', '48', 'Completed', '2025-03-27'),
(5, 'c02787dc9d', 5, 'Stripe', '48', 'Completed', '2025-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `course_price` int NOT NULL,
  `coupon_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` float NOT NULL,
  `final_price` int NOT NULL,
  `instructor_revenue` float NOT NULL,
  `admin_revenue` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_no`, `student_id`, `course_id`, `course_price`, `coupon_name`, `discount`, `final_price`, `instructor_revenue`, `admin_revenue`) VALUES
(1, '6bb22c3a94', 2, 3, 39, 'ABC', 7.8, 31, 9.3, 21.7),
(2, '6bb22c3a94', 2, 7, 0, '', 0, 0, 0, 0),
(3, '356f9406d2', 1, 3, 39, 'NICE', 39, 0, 0, 0),
(4, 'c8da5d8de1', 2, 4, 9, '', 0, 9, 2.7, 6.3),
(5, '8323ade186', 4, 4, 9, '', 0, 9, 2.7, 6.3),
(6, '8323ade186', 4, 3, 39, '', 0, 39, 11.7, 27.3),
(7, 'c02787dc9d', 5, 3, 39, '', 0, 39, 11.7, 27.3),
(8, 'c02787dc9d', 5, 4, 9, '', 0, 9, 2.7, 6.3);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `short_description`, `description`, `photo`, `post_date`) VALUES
(1, 'Partnering to create a strong community', 'partnering-create-strong-community', 'In order to create a good community we need to work together. We need to help, support each other and be respectful to each other.', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 'post_1743108985.jpg', '2025-03-27'),
(2, 'Turning your emergency donation into instant aid', 'turning-emergency', 'We are working hard to help the poor people. We are trying to provide them food, shelter, clothing, education and medical assistance.', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 'post_1743108551.jpg', '2025-03-27'),
(3, 'Charity provides educational boost for children', 'charity-for-children', 'In order boost the education of the children, we are providing them books, pens, pencils, notebooks and other necessary things. ', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 'post_1743109001.jpg', '2025-03-27'),
(4, 'Creating a Nice Website Layout', 'creating-nice-layout', 'In order to create a good community we need to work together. We need to help, support each other and be respectful to each other. ', '<p>Lorem ipsum dolor sit amet, ei magna nonumy epicurei sea. Velit denique maluisset mea ut, ex stet sententiae nam, pro an meis saepe noster. Erroribus liberavisse ne has, option meliore pertinacia has eu, eius tritani pri ut. Sit in option tamquam repudiare, eu dissentiunt vituperatoribus vix. Oporteat evertitur has ei, quot cibo splendide sit ei.<br /><br />Tale tation tamquam eam ex, lorem facer laboramus at sit. Et vel idque eloquentiam, sea saepe voluptaria an. Duo ei quot assum pertinax, ius stet eligendi definitionem ad. Sea ei dicant conceptam, probo malis abhorreant mea id. Harum scaevola nam ut, vel in discere qualisque adversarium, eruditi legendos gubergren pro at.<br /><br />Ei vix docendi fabellas, vix in falli aeque. Cu luptatum honestatis delicatissimi cum, vix in erat graeci electram. Vis cu quot ornatus docendi, per dicam dissentiunt no. Elit summo id mei. Electram pertinacia sed ea.</p>', 'post_1743108776.jpg', '2025-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `course_id`, `rating`, `comment`) VALUES
(1, 2, 3, 5, 'Excellent Course'),
(2, 2, 7, 2, 'I do not like this much.'),
(3, 1, 3, 4, 'I love this course. Very nice. But there are still some places to improve.');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `sales_commission` int NOT NULL,
  `minimum_withdraw_amount` int NOT NULL,
  `withdraw_fee` int NOT NULL,
  `logo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `favicon` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `sales_commission`, `minimum_withdraw_amount`, `withdraw_fee`, `logo`, `favicon`) VALUES
(1, 70, 20, 10, 'logo_1743114567.png', 'favicon_1743115032.png');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `photo`, `password`, `phone`, `address`, `country`, `state`, `city`, `zip`, `token`, `status`) VALUES
(1, 'Patrick', 'patrick@gmail.com', NULL, '$2y$10$2zAK6zAOZlH3N2ZrsoIFve2homBe0NVEcpF6IydNTlWP98tWwkzoe', '123-777-6666', 'ABC Steet', 'Australia', 'BN', 'Melbourne', '23887', '', 1),
(2, 'David', 'david@gmail.com', 'student_1742545957.jpg', '$2y$10$5RWV0ooox7ail71yIAzaL./UyWFeairOapiIq.TrGQrP.ik8/gvBW', '123-456-7777', '45 ABC Street', 'USA', 'CA', 'NYC', '34222', '14f44427dd634603df8a94442cf55635b168a6d6d9cea00b8ff8d470f57ed578', 1),
(4, 'Martino Rizzo', 'martino@gmail.com', 'student_1743093299.png', '$2y$10$R.LyplWA7JOKWyCQXDVLtOMkbje2NoSbJf0Lqg5eQrlcyrpmFsyk.', '345-343-3333', '450 Eagles Nest Drive', 'USA', 'CA', 'Redding', '96001', '', 1),
(5, 'Thomas Cook', 'cook@gmail.com', 'student_1743098376.png', '$2y$10$qkgf9ez959QjUcNlirTR4OPhprloWEB4R/bfJZRQ7KQSdLtSJ4lU2', '122930219301', 'AAA Street', 'USA', 'Derrif', 'LA', '91283', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `token`, `status`) VALUES
(2, 'subs1@gmail.com', '', 1),
(3, 'subs2@gmail.com', '', 1),
(4, 'subs3@gmail.com', 'd8534cd8b5ae3ab47a77eba4829ae1fe70758b996449567b68e7e7f7a17fc8a5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `photo`, `name`, `designation`, `comment`) VALUES
(1, 'testimonial_1743106997.jpg', 'Robert Krol', 'CEO, ABC Company', 'I\'ve been a supporter of this charity for many years and have seen the incredible impact they have on the lives of those in need. They provide essential services to vulnerable populations and work to make a difference in the world.'),
(2, 'testimonial_1743107025.jpg', 'Sal Harvey', 'CEO, XYZ Company', 'I\'ve been a supporter of this charity for many years and have seen the incredible impact they have on the lives of those in need. They provide essential services to vulnerable populations and work to make a difference in the world.'),
(3, 'testimonial_1743107051.jpg', 'Peter Smith', 'CEO, DFG Company', 'I\'ve been a supporter of this charity for many years and have seen the incredible impact they have on the lives of those in need. They provide essential services to vulnerable populations and work to make a difference in the world.');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `student_id`, `course_id`) VALUES
(4, 2, 3),
(5, 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` int NOT NULL,
  `instructor_id` int NOT NULL,
  `withdraw_method` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `withdraw_note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `withdraw_amount` int NOT NULL,
  `withdraw_request_date` datetime NOT NULL,
  `withdraw_approval_date` text COLLATE utf8mb4_unicode_ci,
  `withdraw_status` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraws`
--

INSERT INTO `withdraws` (`id`, `instructor_id`, `withdraw_method`, `withdraw_note`, `withdraw_amount`, `withdraw_request_date`, `withdraw_approval_date`, `withdraw_status`) VALUES
(2, 1, 'PayPal', 'Please send me money here: \r\nPayPal Email: test@gmail.com', 25, '2025-03-27 19:28:21', '2025-03-27 19:55:45', 'Approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
