-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 02:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_description` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `price`, `quantity`, `image_url`, `description`, `stock`) VALUES
(1, 'Wireless Bluetooth Headphones\r\n', 150.00, 17, 'https://i5.walmartimages.com/asr/10a8093a-677d-4130-8b3d-f0c789ad688b.e1ab3b57db55e342fd5828f86eaaf748.jpeg', 'Experience immersive sound with noise cancellation and up to 20 hours of battery life. Foldable design, perfect for travel and everyday use.', 19),
(2, 'iPhone 13 (128GB)', 180.00, 15, 'https://media.krefel.be/sys-master/products/9331630997534/1440x1440.51006250_01.webp', 'Apple’s powerful A15 Bionic chip, Super Retina XDR display, dual-camera system, and up to 19 hours of video playback.', 0),
(3, 'Air Jordan 1', 200.00, 15, 'https://sneakernews.com/wp-content/uploads/2018/05/off-white-air-jordan-1-unc-dark-powder-blue-cone-release.jpg', 'An iconic sneaker with a classic design and premium quality...', 0),
(4, 'Stuffed Teddy Bear – 30cm', 120.00, 20, 'https://th.bing.com/th/id/OIP.l_Fi2np0CHKPNSPadqFUpAAAAA?rs=1&pid=ImgDetMain', 'Soft, cuddly teddy bear for kids or gifts. Made from hypoallergenic materials, safe for all ages.', 0),
(5, 'Puma RS-X', 140.00, 18, 'https://tse3.mm.bing.net/th/id/OIP.4MbktK5n0mrN3ZQFPFRYIQAAAA?rs=1&pid=ImgDetMain', 'A retro-inspired sneaker with a bold design and support...', 0),
(6, 'Sterling Silver Infinity Necklace', 160.00, 19, 'https://i.etsystatic.com/5196049/r/il/45e7d4/1321139970/il_fullxfull.1321139970_kmjc.jpg', 'Delicate 925 silver chain with an infinity symbol pendant. Elegant and meaningful gift for special occasions.', 0),
(7, 'Mini Drone with Camera – Beginner Friendly', 180.00, 20, 'https://th.bing.com/th/id/OIP.uhfBFJWEEClmkmThHU1wEgHaHa?rs=1&pid=ImgDetMain', 'Lightweight quadcopter with HD camera, easy controls, and built-in altitude hold. Ideal for kids and first-time flyers.\r\n\r\n', 0),
(8, 'Foldable Laptop Table with Cup Holder', 150.00, 20, 'https://dukaan.b-cdn.net/1000x1000/webp/upload_file_service/f8d20dd2-a5d0-40de-80da-d9dd6006ab7c/1690702540224.jpeg', 'Space-saving design with sturdy legs, cup holder, and phone slot. Ideal for working or studying from bed or sofa.', 0),
(10, 'Canon PIXMA Inkjet Printer', 190.00, 20, 'https://th.bing.com/th/id/OIP.x7fcuBBZeo-WVFxi3DsBIwHaHa?rs=1&pid=ImgDetMain', 'All-in-one color printer for home or office use. Print, scan, and copy with affordable ink options.\r\n\r\n', 0),
(20, 'Puma Deviate Nitro 2', 170.00, 19, 'https://tse4.mm.bing.net/th/id/OIP.KVA1XN-Wauo5Qf1LYZSGZQAAAA?rs=1&pid=ImgDetMain', 'High-performance shoe built for speed and endurance.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `status`) VALUES
(1, '20221498@nbsc.edu.ph', '$2y$10$5YYwLl63NZjxJN3OXQh2deEi73ys489oO2LZnEXB51F.sZ/zActj2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `destination` varchar(150) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `order_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `item`, `quantity`, `destination`, `status`, `created_at`, `user_id`, `item_id`, `total`, `tracking_number`, `address`, `payment_method`, `order_date`) VALUES
(23, NULL, NULL, 1, NULL, 'completed', '2025-05-09 15:01:49', 12, 27, 120.00, NULL, '', '', NULL),
(24, 'jane', 'Adidas Ultraboost', 1, 'Default Address', 'Pending', '2025-05-09 10:25:53', 14, 2, 4200.00, '', '', '', NULL),
(25, 'jane', 'Adidas Ultraboost', 1, 'Default Address', 'Pending', '2025-05-09 10:25:57', 14, 2, 4200.00, '', '', '', NULL),
(26, NULL, NULL, 1, NULL, 'Pending', '2025-05-09 16:31:17', 14, 2, 180.00, NULL, '', '', NULL),
(27, NULL, NULL, 1, NULL, 'Pending', '2025-05-09 16:31:25', 14, 2, 180.00, NULL, '', '', NULL),
(28, 'jane', NULL, 2, NULL, 'Pending', '2025-05-09 16:34:05', 14, 3, 400.00, NULL, '', '', NULL),
(29, 'jane', NULL, 2, NULL, 'Pending', '2025-05-09 16:34:13', 14, 3, 400.00, NULL, '', '', NULL),
(30, 'jane', NULL, 3, NULL, 'Pending', '2025-05-09 16:36:54', 14, 2, 540.00, NULL, '', '', NULL),
(31, 'jane', NULL, 2, NULL, 'Pending', '2025-05-09 16:39:13', 14, 5, 280.00, NULL, '', '', NULL),
(32, 'jane', NULL, 2, NULL, 'Pending', '2025-05-09 16:44:00', 14, 5, 280.00, NULL, '', '', NULL),
(33, 'jane', NULL, 1, NULL, 'Pending', '2025-05-09 16:46:31', 14, 2, 180.00, NULL, '', '', NULL),
(34, 'jane', NULL, 1, NULL, 'Pending', '2025-05-09 16:48:50', 14, 2, 180.00, NULL, '', '', NULL),
(35, NULL, NULL, 1, NULL, 'pending', '2025-05-09 16:54:47', 14, 2, 180.00, NULL, 'agusan', 'paypal', NULL),
(36, NULL, NULL, 1, NULL, 'completed', '2025-05-09 23:00:26', 14, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(37, NULL, NULL, 3, NULL, 'pending', '2025-05-13 07:38:38', 17, 3, 600.00, NULL, 'agusan', 'paypal', NULL),
(38, NULL, NULL, 1, NULL, 'completed', '2025-05-13 08:00:24', 17, 2, 180.00, NULL, 'agusan', 'paypal', NULL),
(39, NULL, NULL, 1, NULL, 'pending', '2025-05-13 08:05:13', 17, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(40, NULL, NULL, 1, NULL, 'pending', '2025-05-13 08:25:02', 17, 1, 150.00, NULL, 'agusan', 'credit_card', NULL),
(41, NULL, NULL, 1, NULL, 'pending', '2025-05-13 08:35:08', 17, 1, 150.00, NULL, 'a', 'paypal', '2025-05-13 08:35:08'),
(42, NULL, NULL, 1, NULL, 'completed', '2025-05-13 08:49:38', 17, 1, 150.00, NULL, 'agusan', 'credit_card', '2025-05-13 08:49:38'),
(43, NULL, NULL, 1, NULL, 'completed', '2025-05-13 11:15:32', 17, 4, 120.00, NULL, 'agusan', 'paypal', '2025-05-13 11:15:32'),
(48, NULL, NULL, 1, NULL, 'completed', '2025-05-13 12:04:11', 17, 1, 150.00, NULL, 'cagayan', 'paypal', NULL),
(49, NULL, NULL, 1, NULL, 'completed', '2025-05-13 12:06:11', 17, 4, 120.00, NULL, 'guyguy', 'paypal', NULL),
(50, NULL, NULL, 5, NULL, 'pending', '2025-05-13 12:10:25', 18, 7, 900.00, NULL, 'gg', 'paypal', NULL),
(51, NULL, NULL, 1, NULL, 'pending', '2025-05-15 01:43:43', 31, 5, 140.00, NULL, 'damilag', 'paypal', NULL),
(52, NULL, NULL, 1, NULL, 'pending', '2025-05-15 01:44:59', 31, 8, 150.00, NULL, 'ubj', 'paypal', NULL),
(53, NULL, NULL, 1, NULL, 'pending', '2025-05-15 02:21:13', 33, 4, 120.00, NULL, 'awdaw', 'paypal', NULL),
(54, NULL, NULL, 1, NULL, 'pending', '2025-05-15 02:32:53', 34, 1, 150.00, NULL, 'awaw', 'credit_card', NULL),
(55, NULL, NULL, 1, NULL, 'pending', '2025-05-15 02:39:18', 34, 1, 150.00, NULL, 'awd', 'paypal', NULL),
(56, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:00:12', 34, 1, 150.00, NULL, 'adw', 'paypal', NULL),
(57, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:00:48', 34, 1, 150.00, NULL, 'adw', 'paypal', NULL),
(58, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:13:02', 34, 2, 180.00, NULL, 'awda', 'bank_transfer', NULL),
(59, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:15:49', 34, 2, 180.00, NULL, 'awda', 'bank_transfer', NULL),
(60, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:17:22', 34, 2, 180.00, NULL, 'awda', 'paypal', NULL),
(61, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:20:10', 34, 2, 180.00, NULL, 'awda', 'paypal', NULL),
(62, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:22:29', 34, 3, 200.00, NULL, '1', 'paypal', NULL),
(63, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:24:29', 34, 3, 200.00, NULL, '1', 'paypal', NULL),
(64, NULL, NULL, 1, NULL, 'pending', '2025-05-15 03:32:20', 34, 3, 200.00, NULL, '1', 'paypal', NULL),
(65, NULL, NULL, 1, NULL, 'pending', '2025-05-14 21:45:21', 34, 4, 120.00, NULL, 'awd', '', NULL),
(66, NULL, NULL, 1, NULL, 'pending', '2025-05-14 21:48:23', 34, 3, 200.00, NULL, 'as', '', NULL),
(67, NULL, NULL, 1, NULL, 'pending', '2025-05-14 21:58:02', 34, 4, 120.00, NULL, 'awd', 'credit_card', NULL),
(68, NULL, NULL, 1, NULL, 'pending', '2025-05-14 22:20:50', 34, 4, 120.00, NULL, 'damilag', 'paypal', NULL),
(69, NULL, NULL, 1, NULL, 'pending', '2025-05-14 22:24:31', 34, 20, 170.00, NULL, 'damilag', '', NULL),
(70, NULL, NULL, 1, NULL, 'pending', '2025-05-15 04:33:47', 34, 7, 180.00, NULL, 'a', '', NULL),
(71, NULL, NULL, 1, NULL, 'pending', '2025-05-15 04:40:35', 34, 6, 160.00, NULL, 'damilag', '', NULL),
(72, NULL, NULL, 1, NULL, 'pending', '2025-05-15 04:43:56', 34, 2, 180.00, NULL, 'damilag', '', NULL),
(73, NULL, NULL, 1, NULL, 'pending', '2025-05-15 04:49:24', 35, 4, 120.00, NULL, 'damilag', '', NULL),
(74, NULL, NULL, 1, NULL, 'pending', '2025-05-15 05:05:03', 35, 3, 200.00, NULL, 'awd', '', NULL),
(75, NULL, 'UltraBoost 21', 1, 'awd', 'pending', '2025-05-15 05:05:24', 35, 2, 180.00, '0', 'awd', 'bank_transfer', '2025-05-14 23:05:24'),
(76, NULL, NULL, 1, NULL, 'pending', '2025-05-15 05:15:40', 35, 2, 180.00, NULL, 'a', '', NULL),
(77, NULL, NULL, 2, NULL, 'Pending', '2025-05-15 05:24:27', 35, 1, 300.00, NULL, 'agusan', '', NULL),
(78, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:26:01', 35, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(79, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:33:33', 35, 3, 200.00, NULL, 'damilag', 'paypal', NULL),
(80, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:37:04', 35, 3, 200.00, NULL, 'damilag', 'paypal', NULL),
(81, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:37:57', 35, 3, 200.00, NULL, 'awd', 'paypal', NULL),
(82, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:40:14', 35, 2, 180.00, NULL, 'damilag', 'paypal', NULL),
(83, NULL, NULL, 2, NULL, 'pending', '2025-05-14 23:40:53', 35, 10, 380.00, NULL, 'adaw', 'paypal', NULL),
(84, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:43:25', 35, 2, 180.00, NULL, 'wda', 'paypal', NULL),
(85, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:44:56', 35, 5, 140.00, NULL, 'awd', 'paypal', NULL),
(86, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:45:47', 35, 2, 180.00, NULL, 'awd', 'paypal', NULL),
(87, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:46:00', 35, 4, 120.00, NULL, 'asx', 'paypal', NULL),
(88, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:49:12', 35, 2, 180.00, NULL, 'ad', 'paypal', NULL),
(89, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:51:05', 35, 2, 180.00, NULL, 'awd', 'paypal', NULL),
(90, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:51:23', 35, 4, 120.00, NULL, 'ad', 'paypal', NULL),
(91, NULL, NULL, 1, NULL, 'pending', '2025-05-14 23:54:20', 35, 7, 180.00, NULL, 'jkkj', 'paypal', NULL),
(101, NULL, NULL, 1, NULL, 'completed', '2025-05-15 09:03:16', 36, 1, 150.00, NULL, 'dawda', '', NULL),
(102, NULL, NULL, 1, NULL, 'cancelled', '2025-05-15 09:03:34', 36, 3, 200.00, NULL, '12', 'paypal', NULL),
(103, NULL, NULL, 1, NULL, 'Pending', '2025-05-15 09:42:25', 36, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(104, NULL, NULL, 2, NULL, 'Pending', '2025-05-15 09:45:09', 36, 2, 360.00, NULL, 'damilag', 'paypal', NULL),
(105, NULL, NULL, 1, NULL, 'pending', '2025-05-15 09:49:10', 36, 2, 180.00, NULL, 'libona', 'paypal', NULL),
(106, NULL, NULL, 2, NULL, 'completed', '2025-05-15 09:49:37', 36, 3, 400.00, NULL, 'sto nino', 'paypal', NULL),
(107, NULL, NULL, 1, NULL, 'cancelled', '2025-05-15 09:52:06', 36, 1, 150.00, NULL, 'tigbaw', 'paypal', NULL),
(108, NULL, NULL, 5, NULL, 'completed', '2025-05-15 09:52:39', 36, 7, 900.00, NULL, 'adaw', 'paypal', NULL),
(109, NULL, NULL, 3, NULL, 'completed', '2025-05-15 10:03:39', 37, 1, 450.00, NULL, 'Tigbaw', 'paypal', NULL),
(110, NULL, NULL, 1, NULL, 'completed', '2025-05-15 10:14:21', 37, 4, 120.00, NULL, 'Agusan', 'paypal', NULL),
(111, NULL, NULL, 1, NULL, 'completed', '2025-05-16 04:00:38', 38, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(112, NULL, NULL, 1, NULL, 'cancelled', '2025-05-16 05:14:09', 38, 1, 150.00, NULL, 'agusan', 'paypal', NULL),
(113, NULL, NULL, 1, NULL, 'cancelled', '2025-05-16 05:15:26', 38, 2, 180.00, NULL, 'awda', 'credit_card', NULL),
(115, NULL, NULL, 1, NULL, 'Pending', '2025-05-19 08:44:19', 39, 5, 140.00, NULL, 'agusan', 'paypal', NULL),
(118, NULL, NULL, 1, NULL, 'completed', '2025-05-19 16:08:15', 39, 20, 170.00, NULL, 'damilag', 'paypal', NULL),
(120, NULL, NULL, 5, NULL, 'cancelled', '2025-05-19 17:11:46', 41, 2, 900.00, NULL, 'agusn', 'paypal', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(10) UNSIGNED NOT NULL,
  `Username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CodeV` varchar(32) NOT NULL,
  `verification` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `Username`, `email`, `Password`, `CodeV`, `verification`, `created_at`) VALUES
(38, 'louella', '20212118@nbsc.edu.ph', '$2y$10$GnXDLcS2z9HGR3Pl/sgWL.WlH42/rkhLdvl8H7McmQ7UyY7SxcaGm', '964431', 1, '2025-05-16 01:57:51'),
(39, 'kristan', '20221498@nbsc.edu.ph', '$2y$10$CXK5Qm0WRRvbWVFoutiVROvJmU7z2F8nfk6ktAswE2RaBK5c508Y2', '946343', 1, '2025-05-19 06:33:33'),
(41, 'jolina', '20221102@nbsc.edu.ph', '$2y$10$3gXSt8LMr/HR7PrDuHEkK.a1XyNHb9pPUHOtq7LuJWVHY4osc6wGK', '528595', 1, '2025-05-19 15:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `size` decimal(5,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `order_id`, `name`, `brand`, `size`, `price`, `image_url`, `description`, `created_at`, `quantity`, `address`, `user_id`, `item_name`, `total_price`, `payment_method`, `status`) VALUES
(1, 100, 'Air Max 270', NULL, NULL, 150.00, NULL, NULL, '2025-05-14 19:44:11', 1, 'awd', NULL, NULL, NULL, NULL, NULL),
(2, 102, 'Air Jordan 1', NULL, NULL, 200.00, NULL, NULL, '2025-05-15 01:03:34', 1, '12', NULL, NULL, NULL, NULL, NULL),
(3, 103, 'Air Max 270', NULL, NULL, 150.00, NULL, NULL, '2025-05-15 01:42:25', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(4, 104, 'UltraBoost 21', NULL, NULL, 360.00, NULL, NULL, '2025-05-15 01:45:09', 2, 'damilag', NULL, NULL, NULL, NULL, NULL),
(5, 105, 'UltraBoost 21', NULL, NULL, 180.00, NULL, NULL, '2025-05-15 01:49:10', 1, 'libona', NULL, NULL, NULL, NULL, NULL),
(6, 106, 'Air Jordan 1', NULL, NULL, 400.00, NULL, NULL, '2025-05-15 01:49:37', 2, 'sto nino', NULL, NULL, NULL, NULL, NULL),
(7, 107, 'Air Max 270', NULL, NULL, 150.00, NULL, NULL, '2025-05-15 01:52:06', 1, 'tigbaw', NULL, NULL, NULL, NULL, NULL),
(8, 108, 'Adidas Ultraboost Light', NULL, NULL, 900.00, NULL, NULL, '2025-05-15 01:52:39', 5, 'adaw', NULL, NULL, NULL, NULL, NULL),
(9, 109, 'Air Max 270', NULL, NULL, 450.00, NULL, NULL, '2025-05-15 02:03:39', 3, 'Tigbaw', NULL, NULL, NULL, NULL, NULL),
(10, 110, 'Skechers GoRun', NULL, NULL, 120.00, NULL, NULL, '2025-05-15 02:14:21', 1, 'Agusan', NULL, NULL, NULL, NULL, NULL),
(11, 111, 'Air Max 270', NULL, NULL, 150.00, NULL, NULL, '2025-05-15 20:00:38', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(12, 112, 'Wireless Bluetooth Headphones\r\n', NULL, NULL, 150.00, NULL, NULL, '2025-05-15 21:14:09', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(13, 113, 'UltraBoost 21', NULL, NULL, 180.00, NULL, NULL, '2025-05-15 21:15:26', 1, 'awda', NULL, NULL, NULL, NULL, NULL),
(14, 114, 'Sterling Silver Infinity Necklace', NULL, NULL, 160.00, NULL, NULL, '2025-05-19 00:38:54', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(15, 115, 'Puma RS-X', NULL, NULL, 140.00, NULL, NULL, '2025-05-19 00:44:19', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(16, 116, 'Puma RS-X', NULL, NULL, 140.00, NULL, NULL, '2025-05-19 07:16:47', 1, 'agusan', NULL, NULL, NULL, NULL, NULL),
(17, 117, 'Wireless Bluetooth Headphones\r\n', NULL, NULL, 150.00, NULL, NULL, '2025-05-19 08:06:24', 1, 'aguasan', NULL, NULL, NULL, NULL, NULL),
(18, 118, 'Puma Deviate Nitro 2', NULL, NULL, 170.00, NULL, NULL, '2025-05-19 08:08:15', 1, 'damilag', NULL, NULL, NULL, NULL, NULL),
(19, 119, 'Wireless Bluetooth Headphones\r\n', NULL, NULL, 150.00, NULL, NULL, '2025-05-19 09:07:49', 1, 'damilag', NULL, NULL, NULL, NULL, NULL),
(20, 120, 'iPhone 13 (128GB)', NULL, NULL, 900.00, NULL, NULL, '2025-05-19 09:11:46', 5, 'agusn', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `otp` varchar(6) DEFAULT NULL,
  `status` enum('pending','active') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `address`, `password`, `role`, `otp`, `status`) VALUES
(11, 'awd', 'awd', 'awd', NULL, '$2y$10$0ouqQnzwTS9peB4yyX2Jf.Uev3ejpGvUfnU.M5QMVKYJs/iVsawPG', 'user', NULL, 'pending'),
(12, 'Kristan', 'flores', 'Tanly', NULL, '$2y$10$av9DfL0wKCWANgp.IhtEX..E.NkEFAUyII36TutMPA22GaQNXCUge', 'user', NULL, 'pending'),
(13, 'kristanrey', 'flores', 'rey', NULL, 'zenn', 'user', NULL, 'pending'),
(14, 'tan', 'o', 'jane', NULL, '$2y$10$5RXzWzeXKXQD7lVbAV85OOL5vvwV7wU8cg9lxIcuMFY/etvNg/vTq', 'user', NULL, 'pending'),
(15, 'sherwin', 'lumkang', 'win', NULL, '$2y$10$KtPqgWgZx3TwmLJkBClweu02sWYeB4rUSOc3mr92K1im7CcqpPgFm', 'user', NULL, 'pending'),
(16, 'kristanrey', 'flores', 'zennkawaz', NULL, '$2y$10$eBcnaKVFClplGWCCeIHn2.NXM6Sxy7LM4DCLS0UkSqSqPbfBtnVbW', 'user', NULL, 'pending'),
(17, 'aws', 'aws', 'love', NULL, '$2y$10$wkGoJN8ldwfwx.zr9KeFNuKncUtg5qsHG05uRgmYVPpO/LFTUWxNu', 'user', NULL, 'pending'),
(18, 'james', 'laag', 'jamlagz', NULL, '$2y$10$6hPYrKYD.H.kumdFGeuKs.A2PYRDnzTe3FISqp5nzUeZs8HyLjYty', 'user', NULL, 'pending'),
(19, 'zenn', 'flores', 'zenn', NULL, 'zenn', 'user', NULL, 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
