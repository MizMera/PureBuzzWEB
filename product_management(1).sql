-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 12:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `product_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(2, 'me', 'reincarnated'),
(6, 'honey', 'you are honey category'),
(7, 'Medicines', 'Your medicines category'),
(8, 'cosmetics', 'your cosmetics category'),
(9, 'dhjsdihnjsiodhg', 'dsigndsigjs');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `description`, `image_url`, `created_at`, `updated_at`, `category_id`) VALUES
(43, 'Honey Jar', 5.00, 10, 'Pure, golden honey straight from the hive to your table. Perfect for sweetening teas, drizzling on desserts, or enjoying as a natural energy booster.', '/uploads/675b708fdd323_honey.png', '2024-12-05 22:31:51', '2024-12-12 23:23:59', 6),
(49, 'Beeswax Candle', 10.00, 150, 'Handcrafted beeswax candles with a natural honey aroma. Eco-friendly and long-lasting for a warm, inviting ambiance.', '/uploads/product_675b735a4affe9.69647485.jpg', '2024-12-12 23:35:54', '2024-12-12 23:35:54', 8),
(50, 'Pollen Granules', 9.00, 20, 'Nutrient-packed bee pollen granules, ideal for smoothies, yogurts, or snacks. A natural superfood to boost your day.', '/uploads/product_675b739e1644e9.45843765.png', '2024-12-12 23:37:02', '2024-12-12 23:37:02', 6),
(51, 'Royal Jelly', 20.00, 560, 'Premium royal jelly, known for its rich nutrients and skin-enhancing properties. A treasured health supplement.', '/uploads/product_675b740c2da254.87381001.jpg', '2024-12-12 23:38:52', '2024-12-12 23:38:52', 6),
(52, 'ME ', 20.00, 100, 'DGKSOSGK', '/uploads/product_675c0fdb2bdbe4.29493557.jpg', '2024-12-13 10:43:39', '2024-12-13 10:43:39', 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
