-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2023 at 09:57 AM
-- Server version: 8.0.34-0ubuntu0.20.04.1
-- PHP Version: 8.1.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Symfony_Ecommerce_Demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', '1', '2023-08-24 11:26:19', '2023-08-24 11:26:19'),
(2, 'Clothing', '1', '2023-08-24 11:26:32', '2023-08-24 11:26:32'),
(3, 'Home And Garden', '1', '2023-08-24 11:27:25', '2023-08-24 11:27:25'),
(4, 'Books', '1', '2023-08-24 11:27:38', '2023-08-24 11:27:38'),
(5, 'Footware', '1', '2023-08-24 11:27:53', '2023-08-24 11:27:53'),
(6, 'Sports And Outdoors', '1', '2023-08-24 11:28:28', '2023-08-24 11:28:28'),
(7, 'Furniture', '1', '2023-08-24 11:28:47', '2023-08-24 11:28:47'),
(8, 'Watch', '1', '2023-08-24 11:54:52', '2023-08-24 11:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230818125802', '2023-08-18 18:28:11', 156),
('DoctrineMigrations\\Version20230821130216', '2023-08-21 18:32:27', 73),
('DoctrineMigrations\\Version20230821131529', '2023-08-21 18:45:40', 21),
('DoctrineMigrations\\Version20230822052659', '2023-08-22 11:04:30', 108);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `subcategory_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `price`, `status`, `created_at`, `updated_at`, `subcategory_id`) VALUES
(1, 1, 'Lenovo', '25000', '1', '2023-08-24 11:42:01', '2023-09-18 15:24:06', 1),
(2, 1, 'Iphone', '130000', '1', '2023-08-24 11:42:46', '2023-08-24 11:43:47', 2),
(3, 1, 'Daikin', '40000', '1', '2023-08-24 11:45:32', '2023-08-24 11:45:32', 3),
(4, 2, 'Shirt', '1200', '1', '2023-08-24 11:46:17', '2023-08-24 11:46:33', 4),
(5, 2, 'Tshirt', '500', '1', '2023-08-24 11:47:24', '2023-08-24 11:47:24', 4),
(6, 2, 'Shirt', '1000', '1', '2023-08-24 11:47:50', '2023-08-24 11:47:50', 5),
(7, 2, 'Tshirt', '2000', '1', '2023-08-24 11:48:12', '2023-08-24 11:48:12', 5),
(8, 2, 'Shirt', '600', '1', '2023-08-24 11:48:43', '2023-08-24 11:48:43', 6),
(9, 3, 'Plant', '650', '1', '2023-08-24 11:50:45', '2023-08-24 11:50:45', NULL),
(10, 5, 'Boot', '650', '1', '2023-08-24 11:51:40', '2023-08-24 11:51:40', 7),
(11, 5, 'Women Boot', '1000', '1', '2023-08-24 11:52:30', '2023-08-24 11:52:30', 8),
(12, 7, 'Chair', '5000', '1', '2023-08-24 11:53:24', '2023-08-24 11:53:24', 9),
(13, 7, 'Table', '1200', '1', '2023-08-24 11:54:32', '2023-08-24 11:54:32', 9),
(14, 8, 'Titan', '2500', '1', '2023-08-24 11:55:10', '2023-08-24 11:55:10', NULL),
(15, 8, 'Fastrack', '3000', '1', '2023-08-24 11:55:42', '2023-08-24 11:55:42', NULL),
(16, 2, 'ddz', '111', '1', '2023-09-18 10:41:04', '2023-09-18 10:41:04', 4),
(17, 1, 'sds', '110', '1', '2023-09-18 15:23:59', '2023-09-18 15:23:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`id`, `product_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, '64e6f4b1730f8.jpg', '2023-08-24 11:42:01', '2023-08-24 11:42:01'),
(2, 2, '64e6f4de0ba38.png', '2023-08-24 11:42:46', '2023-08-24 11:42:46'),
(4, 3, '64e6f58434c5d.jpg', '2023-08-24 11:45:32', '2023-08-24 11:45:32'),
(5, 3, '64e6f58434cf8.jpg', '2023-08-24 11:45:32', '2023-08-24 11:45:32'),
(6, 3, '64e6f58434d3b.jpg', '2023-08-24 11:45:32', '2023-08-24 11:45:32'),
(7, 3, '64e6f58434d78.jpg', '2023-08-24 11:45:32', '2023-08-24 11:45:32'),
(8, 3, '64e6f58434dc9.jpg', '2023-08-24 11:45:32', '2023-08-24 11:45:32'),
(9, 4, '64e6f5b14e5d2.jpg', '2023-08-24 11:46:17', '2023-08-24 11:46:17'),
(10, 4, '64e6f5c12d026.jpg', '2023-08-24 11:46:33', '2023-08-24 11:46:33'),
(11, 5, '64e6f5f47820b.jpg', '2023-08-24 11:47:24', '2023-08-24 11:47:24'),
(12, 6, '64e6f60e4e830.jpg', '2023-08-24 11:47:50', '2023-08-24 11:47:50'),
(13, 7, '64e6f62470f7b.jpg', '2023-08-24 11:48:12', '2023-08-24 11:48:12'),
(14, 8, '64e6f64358ac5.jpg', '2023-08-24 11:48:43', '2023-08-24 11:48:43'),
(15, 9, '64e6f6bdc66b6.jpg', '2023-08-24 11:50:45', '2023-08-24 11:50:45'),
(16, 10, '64e6f6f4c2e0a.jpg', '2023-08-24 11:51:40', '2023-08-24 11:51:40'),
(17, 11, '64e6f726948f5.jpg', '2023-08-24 11:52:30', '2023-08-24 11:52:30'),
(18, 12, '64e6f75c6f067.jpg', '2023-08-24 11:53:24', '2023-08-24 11:53:24'),
(19, 13, '64e6f7a00e4f6.jpg', '2023-08-24 11:54:32', '2023-08-24 11:54:32'),
(20, 14, '64e6f7c68aa96.jpg', '2023-08-24 11:55:10', '2023-08-24 11:55:10'),
(21, 15, '64e6f7e6438a8.jpg', '2023-08-24 11:55:42', '2023-08-24 11:55:42'),
(22, 17, '65081e3799f59.jpg', '2023-09-18 15:23:59', '2023-09-18 15:23:59'),
(23, 17, '65081e379a053.jpg', '2023-09-18 15:23:59', '2023-09-18 15:23:59'),
(24, 17, '65081e379a0b5.jpg', '2023-09-18 15:23:59', '2023-09-18 15:23:59');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`id`, `category_id`, `name`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Compter', '2023-08-24 11:29:40', '2023-08-24 11:29:40', '1'),
(2, 1, 'Mobile', '2023-08-24 11:29:51', '2023-08-24 11:29:51', '1'),
(3, 1, 'AC', '2023-08-24 11:30:09', '2023-08-24 11:30:09', '1'),
(4, 2, 'Mens Clothing', '2023-08-24 11:30:47', '2023-08-24 11:30:47', '1'),
(5, 2, 'Womens Clothing', '2023-08-24 11:31:07', '2023-08-24 11:31:07', '1'),
(6, 2, 'Kids Clothing', '2023-08-24 11:31:28', '2023-08-24 11:31:28', '1'),
(7, 5, 'Mens Footware', '2023-08-24 11:32:27', '2023-08-24 11:32:27', '1'),
(8, 5, 'Woomens Footware', '2023-08-24 11:32:49', '2023-08-24 11:32:49', '1'),
(9, 7, 'Godhrej', '2023-08-24 11:33:24', '2023-08-24 11:33:24', '1'),
(10, 7, 'Chair', '2023-08-24 11:33:37', '2023-08-24 11:33:37', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`),
  ADD KEY `IDX_D34A04AD5DC6FE57` (`subcategory_id`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64617F034584665A` (`product_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BCE3F79812469DE2` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_D34A04AD5DC6FE57` FOREIGN KEY (`subcategory_id`) REFERENCES `sub_category` (`id`);

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `FK_64617F034584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `FK_BCE3F79812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
