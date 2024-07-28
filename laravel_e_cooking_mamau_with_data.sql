-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2024 at 02:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_e_cooking_mamau`
--

-- --------------------------------------------------------

--
-- Table structure for table `available_schedules`
--

CREATE TABLE `available_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `available_schedules`
--

INSERT INTO `available_schedules` (`id`, `schedule`, `created_at`, `updated_at`) VALUES
(1, '2024-07-28 20:00:00', '2024-07-28 04:13:58', '2024-07-28 04:13:58'),
(2, '2024-07-29 20:14:00', '2024-07-28 04:14:09', '2024-07-28 04:14:09'),
(3, '2024-07-30 20:14:00', '2024-07-28 04:14:16', '2024-07-28 04:14:16'),
(4, '2024-07-31 20:00:00', '2024-07-28 04:14:27', '2024-07-28 04:14:27'),
(5, '2024-08-05 20:00:00', '2024-07-28 04:14:38', '2024-07-28 04:14:38'),
(6, '2024-08-06 20:00:00', '2024-07-28 04:14:51', '2024-07-28 04:14:51'),
(7, '2024-08-07 20:00:00', '2024-07-28 04:15:03', '2024-07-28 04:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `baked_goods`
--

CREATE TABLE `baked_goods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `is_available` tinyint(4) NOT NULL DEFAULT 1,
  `description` varchar(255) DEFAULT NULL,
  `weight_gram` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `baked_goods`
--

INSERT INTO `baked_goods` (`id`, `name`, `price`, `is_available`, `description`, `weight_gram`, `created_at`, `updated_at`) VALUES
(11, 'Banana Cake', 150.00, 1, 'A moist and flavorful cake made with ripe bananas, perfect for a sweet treat any time of the day.', 500, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(12, 'Brownies', 200.00, 1, 'Rich, fudgy squares loaded with chocolate goodness, a delightful indulgence for any chocolate lover.', 50, '2024-07-28 03:55:53', '2024-07-28 04:02:53'),
(13, 'Crinkles', 150.00, 1, 'Soft and chewy cookies rolled in powdered sugar, with a delectable chocolate center.', 25, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(14, 'Yema Cake', 250.00, 1, 'A decadent cake topped with creamy yema frosting, offering a unique and delightful taste experience.', 600, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(15, 'Puto', 100.00, 1, 'Soft, fluffy steamed rice cakes that are a classic Filipino snack, perfect for any occasion.', 30, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(16, 'Chocolate Cake', 300.00, 1, 'A decadent, moist cake layered with rich chocolate frosting, ideal for celebrations or a sweet craving.', 700, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(17, 'Polvoron', 50.00, 1, 'Crunchy, sweet, and buttery shortbread-like treats that melt in your mouth.', 20, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(18, 'Buko Pie', 350.00, 1, 'A traditional Filipino pie filled with tender young coconut meat and creamy custard, encased in a flaky crust.', 800, '2024-07-28 03:55:53', '2024-07-28 04:04:49'),
(19, 'Beef Empanada', 180.00, 1, 'Savory pastries filled with flavorful ground beef and vegetables, wrapped in a golden, flaky crust.', 150, '2024-07-28 03:55:53', '2024-07-28 03:55:53'),
(21, 'Bibingka', 120.00, 1, 'Traditional Filipino rice cakes with a slightly charred top, often enjoyed during the holiday season.', 150, '2024-07-28 03:55:53', '2024-07-28 03:55:53');

-- --------------------------------------------------------

--
-- Table structure for table `baked_good_images`
--

CREATE TABLE `baked_good_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `id_baked_goods` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `baked_good_images`
--

INSERT INTO `baked_good_images` (`id`, `image_path`, `is_thumbnail`, `id_baked_goods`, `created_at`, `updated_at`) VALUES
(2, 'uploaded_files/1722168145_66a63351f3346.jpg', 0, 11, '2024-07-28 04:02:26', '2024-07-28 04:02:40'),
(3, 'uploaded_files/1722168146_66a6335202e22.jpeg', 1, 11, '2024-07-28 04:02:26', '2024-07-28 04:02:40'),
(4, 'uploaded_files/1722168173_66a6336d18c1b.jpeg', 0, 12, '2024-07-28 04:02:53', '2024-07-28 04:03:01'),
(5, 'uploaded_files/1722168173_66a6336d1bf37.jpeg', 0, 12, '2024-07-28 04:02:53', '2024-07-28 04:03:01'),
(6, 'uploaded_files/1722168173_66a6336d1ed4a.jpeg', 1, 12, '2024-07-28 04:02:53', '2024-07-28 04:03:01'),
(7, 'uploaded_files/1722168194_66a633821f0eb.jpeg', 0, 13, '2024-07-28 04:03:14', '2024-07-28 04:03:14'),
(8, 'uploaded_files/1722168194_66a6338222033.jpg', 0, 13, '2024-07-28 04:03:14', '2024-07-28 04:03:14'),
(9, 'uploaded_files/1722168211_66a63393a2d39.jpg', 0, 14, '2024-07-28 04:03:31', '2024-07-28 04:03:35'),
(10, 'uploaded_files/1722168211_66a63393a60be.jpg', 1, 14, '2024-07-28 04:03:31', '2024-07-28 04:03:35'),
(11, 'uploaded_files/1722168226_66a633a25a5d2.jpg', 1, 15, '2024-07-28 04:03:46', '2024-07-28 04:03:55'),
(12, 'uploaded_files/1722168226_66a633a25d364.jpg', 0, 15, '2024-07-28 04:03:46', '2024-07-28 04:03:55'),
(13, 'uploaded_files/1722168244_66a633b4c2b18.jpg', 0, 16, '2024-07-28 04:04:04', '2024-07-28 04:04:04'),
(14, 'uploaded_files/1722168267_66a633cbf2fd6.jpg', 0, 17, '2024-07-28 04:04:28', '2024-07-28 04:04:28'),
(15, 'uploaded_files/1722168289_66a633e1615a4.jpg', 0, 18, '2024-07-28 04:04:49', '2024-07-28 04:04:49'),
(16, 'uploaded_files/1722168305_66a633f1612d3.jpg', 0, 19, '2024-07-28 04:05:05', '2024-07-28 04:05:05'),
(18, 'uploaded_files/1722168812_66a635ec346b0.jpg', 0, 21, '2024-07-28 04:13:32', '2024-07-28 04:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `baked_good_ingredients`
--

CREATE TABLE `baked_good_ingredients` (
  `id_baked_goods` bigint(20) UNSIGNED NOT NULL,
  `id_ingredients` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `baked_good_ingredients`
--

INSERT INTO `baked_good_ingredients` (`id_baked_goods`, `id_ingredients`, `qty`, `created_at`, `updated_at`) VALUES
(11, 1, 2, '2024-07-28 04:06:54', '2024-07-28 04:06:54'),
(11, 4, 3, '2024-07-28 04:06:54', '2024-07-28 04:06:54'),
(11, 19, 1, '2024-07-28 04:06:54', '2024-07-28 04:06:54'),
(12, 2, 4, '2024-07-28 04:07:25', '2024-07-28 04:07:25'),
(12, 5, 12, '2024-07-28 04:07:25', '2024-07-28 04:07:25'),
(12, 8, 5, '2024-07-28 04:07:25', '2024-07-28 04:07:25'),
(12, 12, 3, '2024-07-28 04:07:25', '2024-07-28 04:07:25'),
(13, 2, 3, '2024-07-28 04:07:43', '2024-07-28 04:07:43'),
(13, 3, 5, '2024-07-28 04:07:43', '2024-07-28 04:07:43'),
(14, 7, 6, '2024-07-28 04:08:14', '2024-07-28 04:08:14'),
(14, 9, 4, '2024-07-28 04:08:14', '2024-07-28 04:08:14'),
(14, 11, 2, '2024-07-28 04:08:14', '2024-07-28 04:08:14'),
(15, 10, 2, '2024-07-28 04:08:41', '2024-07-28 04:08:41'),
(15, 13, 3, '2024-07-28 04:08:41', '2024-07-28 04:08:41'),
(15, 16, 15, '2024-07-28 04:08:41', '2024-07-28 04:08:41'),
(16, 10, 3, '2024-07-28 04:09:03', '2024-07-28 04:09:03'),
(16, 11, 20, '2024-07-28 04:09:03', '2024-07-28 04:09:03'),
(17, 2, 2, '2024-07-28 04:09:20', '2024-07-28 04:09:20'),
(17, 3, 5, '2024-07-28 04:09:20', '2024-07-28 04:09:20'),
(18, 4, 3, '2024-07-28 04:09:56', '2024-07-28 04:09:56'),
(18, 5, 4, '2024-07-28 04:09:56', '2024-07-28 04:09:56'),
(18, 17, 5, '2024-07-28 04:09:56', '2024-07-28 04:09:56'),
(19, 18, 3, '2024-07-28 04:10:13', '2024-07-28 04:10:13'),
(19, 19, 5, '2024-07-28 04:10:13', '2024-07-28 04:10:13'),
(21, 10, 2, '2024-07-28 04:13:32', '2024-07-28 04:13:32'),
(21, 14, 3, '2024-07-28 04:13:32', '2024-07-28 04:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `fname`, `lname`, `contact`, `address`, `barangay`, `city`, `landmark`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 'Justine Juliana', 'Balla', '09999999999', 'Acacia Estate', 'Ususan', 'Taguig City', 'Vista Mall', 2, '2024-07-28 04:35:12', '2024-07-28 04:35:12'),
(2, 'Daniel', 'Cabasa', '09999999999', '10 Narra St. Engr\'s Hills', 'North Signal', 'Taguig City', 'Army\'s Angels Integrated School', 3, '2024-07-28 04:35:45', '2024-07-28 04:35:45'),
(3, 'Rean Joy', 'Cicat', '09999999999', 'Bluebus', 'BGC', 'Taguig City', 'Market Market', 4, '2024-07-28 04:36:37', '2024-07-28 04:36:37');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_baked_good` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_code` varchar(255) NOT NULL,
  `percent` int(11) NOT NULL,
  `max_number_buyer` int(11) DEFAULT NULL,
  `min_order_price` decimal(12,2) DEFAULT NULL,
  `is_one_time_use` tinyint(4) NOT NULL,
  `discount_start` date NOT NULL,
  `discount_end` date NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_code`, `percent`, `max_number_buyer`, `min_order_price`, `is_one_time_use`, `discount_start`, `discount_end`, `image_path`, `max_discount_amount`, `created_at`, `updated_at`) VALUES
('buy_buy_buy', 20, 10, 100.00, 0, '2024-07-14', '2024-08-03', 'uploaded_files/1722169791.png', 800.00, '2024-07-28 04:29:51', '2024-07-28 04:39:48'),
('cooking_mamau', 30, 50, 20.00, 1, '2024-07-14', '2024-08-08', 'uploaded_files/1722169823.png', 1200.00, '2024-07-28 04:30:23', '2024-07-28 04:39:31'),
('first_time', 40, 50, 100.00, 1, '2024-07-14', '2024-08-10', 'uploaded_files/1722169718.png', 300.00, '2024-07-28 04:28:38', '2024-07-28 04:39:15'),
('suki', 10, 1000, 0.00, 0, '2024-07-14', '2024-08-09', 'uploaded_files/1722169869.png', 500.00, '2024-07-28 04:31:09', '2024-07-28 04:38:50'),
('welcome', 5, 1000, 0.00, 1, '2024-07-14', '2024-08-10', 'uploaded_files/1722169754.png', 1000.00, '2024-07-28 04:29:14', '2024-07-28 04:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `unit`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'Banana', 'cup', 'uploaded_files/ingredients/banana.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(2, 'All-purpose flour', 'cups', 'uploaded_files/ingredients/all-purpose-flour.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(3, 'Sugar', 'cup', 'uploaded_files/ingredients/sugar.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(4, 'Eggs', 'large', 'uploaded_files/ingredients/eggs.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(5, 'Baking soda', 'tsp', 'uploaded_files/ingredients/baking-soda.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(6, 'Baking powder', 'tsp', 'uploaded_files/ingredients/baking-powder.png', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(7, 'Salt', 'tsp', 'uploaded_files/ingredients/salt.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(8, 'Vanilla extract', 'tsp', 'uploaded_files/ingredients/vanilla-extract.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(9, 'Butter', 'cup', 'uploaded_files/ingredients/butter.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(10, 'Milk', 'cup', 'uploaded_files/ingredients/milk.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(11, 'Cocoa powder', 'cup', 'uploaded_files/ingredients/cocoa-powder.png', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(12, 'Vegetable oil', 'cup', 'uploaded_files/ingredients/vegetable-oil.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(13, 'Powdered sugar', 'cup', 'uploaded_files/ingredients/powdered-sugar.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(14, 'Egg yolks', 'large', 'uploaded_files/ingredients/egg-yolks.jpeg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(15, 'Evaporated milk', 'tsp', 'uploaded_files/ingredients/evaporate-milk.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(16, 'Rice flour', 'cup', 'uploaded_files/ingredients/rice-flour.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(17, 'Coconut milk', 'cup', 'uploaded_files/ingredients/coconut-milk.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(18, 'Water', 'cup', 'uploaded_files/ingredients/water.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56'),
(19, 'Cornstarch', 'cup', 'uploaded_files/ingredients/cornstarch.jpg', '2024-07-28 03:28:56', '2024-07-28 03:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(86, '0001_01_01_000001_create_cache_table', 1),
(87, '0001_01_01_000002_create_jobs_table', 1),
(88, '2014_10_12_000000_create_users_table', 1),
(89, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(90, '2024_03_28_071245_create_buyers_table', 1),
(91, '2024_03_28_071436_create_discounts_table', 1),
(92, '2024_03_28_071546_create_available_schedules_table', 1),
(93, '2024_03_29_161659_create_baked_goods_table', 1),
(94, '2024_03_29_161757_create_baked_good_images_table', 1),
(95, '2024_03_30_071850_create_ingredients_table', 1),
(96, '2024_03_30_071851_create_baked_good_ingredients_table', 1),
(97, '2024_03_31_071612_create_orders_table', 1),
(98, '2024_03_31_071700_create_payments_table', 1),
(99, '2024_03_31_071926_create_ordered_goods_table', 1),
(100, '2024_03_31_072356_create_order_reviews_table', 1),
(101, '2024_03_31_072424_create_review_images_table', 1),
(102, '2024_06_24_135410_create_cart_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ordered_goods`
--

CREATE TABLE `ordered_goods` (
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_baked_goods` bigint(20) UNSIGNED NOT NULL,
  `price_per_good` decimal(12,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ordered_goods`
--

INSERT INTO `ordered_goods` (`id_order`, `id_baked_goods`, `price_per_good`, `qty`, `created_at`, `updated_at`) VALUES
(1, 14, 250.00, 3, '2024-07-28 04:39:56', '2024-07-28 04:39:56'),
(1, 16, 300.00, 1, '2024-07-28 04:39:56', '2024-07-28 04:39:56'),
(1, 21, 120.00, 1, '2024-07-28 04:39:56', '2024-07-28 04:39:56'),
(2, 12, 200.00, 2, '2024-07-28 04:41:18', '2024-07-28 04:41:18'),
(2, 18, 350.00, 5, '2024-07-28 04:41:18', '2024-07-28 04:41:18'),
(3, 11, 150.00, 6, '2024-07-28 04:42:34', '2024-07-28 04:42:34'),
(3, 17, 50.00, 4, '2024-07-28 04:42:34', '2024-07-28 04:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `buyer_note` varchar(255) DEFAULT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT 50.00,
  `discount_code` varchar(255) DEFAULT NULL,
  `id_schedule` bigint(20) UNSIGNED NOT NULL,
  `id_buyer` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_status`, `buyer_note`, `buyer_name`, `delivery_address`, `email_address`, `shipping_cost`, `discount_code`, `id_schedule`, `id_buyer`, `created_at`, `updated_at`) VALUES
(1, 'Delivered', 'Pakibalot po ng maayos', 'Daniel Cabasa', '10 Narra St. Engr\'s Hills North Signal Taguig City. Near Army\'s Angels Integrated School', 'daniel.cabasa@tup.edu.ph', 50.00, 'cooking_mamau', 1, 2, '2024-07-28 04:39:56', '2024-07-28 04:42:45'),
(2, 'Pending', 'Mas less sugar po sa brownies', 'Justine Juliana Balla', 'Acacia Estate Ususan Taguig City. Near Vista Mall', 'justinejuliana.balla@tup.edu.ph', 50.00, NULL, 2, 1, '2024-07-28 04:41:18', '2024-07-28 04:41:18'),
(3, 'Pending', 'Sana po masarap', 'Rean Joy Cicat', 'Bluebus BGC Taguig City. Near Market Market', 'reanjoy.cicat@tup.edu.ph', 50.00, NULL, 2, 3, '2024-07-28 04:42:34', '2024-07-28 04:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_reviews`
--

CREATE TABLE `order_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `id_baked_goods` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mode` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `id_order` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `mode`, `amount`, `id_order`, `created_at`, `updated_at`) VALUES
(1, 'GCash', 869.00, 1, '2024-07-28 04:39:56', '2024-07-28 04:39:56'),
(2, 'GCash', 2200.00, 2, '2024-07-28 04:41:18', '2024-07-28 04:41:18'),
(3, 'GCash', 1150.00, 3, '2024-07-28 04:42:34', '2024-07-28 04:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `id_review` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `is_activated` tinyint(4) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `profile_image_path`, `is_admin`, `is_activated`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'cookingmamau@gmail.com', '2024-07-28 02:14:05', '$2y$10$Bwlh3d40OhzmCsicttfif./qDos2KBF2fL0BlWoC8aH/axBsgU6fi', NULL, 1, 1, NULL, '2024-07-28 02:14:05', '2024-07-28 02:14:05'),
(2, 'justinejuliana.balla@tup.edu.ph', NULL, '$2y$10$Y2Iiu1P71LLGDeugiGUpY.KZcsNbFnbdssm67LF7NJCvhzsH8F/0m', 'uploaded_files/1722170112.jpg', 0, 1, NULL, '2024-07-28 04:35:12', '2024-07-28 04:35:12'),
(3, 'daniel.cabasa@tup.edu.ph', NULL, '$2y$10$/shAMwplXRRYvpb/gmbf7.UWe.uvcL2k/QLlXItycOp/pLlPz2svS', 'uploaded_files/1722170145.jpg', 0, 1, NULL, '2024-07-28 04:35:45', '2024-07-28 04:35:45'),
(4, 'reanjoy.cicat@tup.edu.ph', NULL, '$2y$10$U4KTS4YP/x2CVDMyysLwjO5CgqdE8ufM/5fEiJAGsPHNQbGcelgsy', NULL, 0, 1, NULL, '2024-07-28 04:36:37', '2024-07-28 04:36:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `available_schedules`
--
ALTER TABLE `available_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `baked_goods`
--
ALTER TABLE `baked_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `baked_good_images`
--
ALTER TABLE `baked_good_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `baked_good_images_id_baked_goods_foreign` (`id_baked_goods`);

--
-- Indexes for table `baked_good_ingredients`
--
ALTER TABLE `baked_good_ingredients`
  ADD PRIMARY KEY (`id_baked_goods`,`id_ingredients`),
  ADD KEY `baked_good_ingredients_id_baked_goods_index` (`id_baked_goods`),
  ADD KEY `baked_good_ingredients_id_ingredients_index` (`id_ingredients`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyers_id_user_index` (`id_user`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_id_user_index` (`id_user`),
  ADD KEY `cart_items_id_baked_good_index` (`id_baked_good`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordered_goods`
--
ALTER TABLE `ordered_goods`
  ADD PRIMARY KEY (`id_order`,`id_baked_goods`),
  ADD KEY `ordered_goods_id_baked_goods_foreign` (`id_baked_goods`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_id_schedule_index` (`id_schedule`),
  ADD KEY `orders_id_buyer_index` (`id_buyer`),
  ADD KEY `orders_discount_code_index` (`discount_code`);

--
-- Indexes for table `order_reviews`
--
ALTER TABLE `order_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_reviews_id_order_id_baked_goods_foreign` (`id_order`,`id_baked_goods`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_id_order_index` (`id_order`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_images_id_review_index` (`id_review`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `available_schedules`
--
ALTER TABLE `available_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `baked_goods`
--
ALTER TABLE `baked_goods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `baked_good_images`
--
ALTER TABLE `baked_good_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_reviews`
--
ALTER TABLE `order_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baked_good_images`
--
ALTER TABLE `baked_good_images`
  ADD CONSTRAINT `baked_good_images_id_baked_goods_foreign` FOREIGN KEY (`id_baked_goods`) REFERENCES `baked_goods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `baked_good_ingredients`
--
ALTER TABLE `baked_good_ingredients`
  ADD CONSTRAINT `baked_good_ingredients_id_baked_goods_foreign` FOREIGN KEY (`id_baked_goods`) REFERENCES `baked_goods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `baked_good_ingredients_id_ingredients_foreign` FOREIGN KEY (`id_ingredients`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_id_baked_good_foreign` FOREIGN KEY (`id_baked_good`) REFERENCES `baked_goods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ordered_goods`
--
ALTER TABLE `ordered_goods`
  ADD CONSTRAINT `ordered_goods_id_baked_goods_foreign` FOREIGN KEY (`id_baked_goods`) REFERENCES `baked_goods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordered_goods_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_discount_code_foreign` FOREIGN KEY (`discount_code`) REFERENCES `discounts` (`discount_code`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_id_buyer_foreign` FOREIGN KEY (`id_buyer`) REFERENCES `buyers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_id_schedule_foreign` FOREIGN KEY (`id_schedule`) REFERENCES `available_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_reviews`
--
ALTER TABLE `order_reviews`
  ADD CONSTRAINT `order_reviews_id_order_id_baked_goods_foreign` FOREIGN KEY (`id_order`,`id_baked_goods`) REFERENCES `ordered_goods` (`id_order`, `id_baked_goods`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_id_order_foreign` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_id_review_foreign` FOREIGN KEY (`id_review`) REFERENCES `order_reviews` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
