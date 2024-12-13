-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 déc. 2024 à 11:39
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `panier1`
--

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `creationDate` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'En attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`id`, `userId`, `creationDate`, `total`, `status`) VALUES
(10, NULL, '2024-11-26 20:50:49', 51.98, 'En attente'),
(11, NULL, '2024-11-26 20:51:27', 67.48, 'En attente'),
(12, NULL, '2024-11-26 20:54:49', 25.99, 'En attente'),
(13, NULL, '2024-11-26 20:59:47', 0.00, 'En attente'),
(14, NULL, '2024-11-26 21:04:32', 0.00, 'En attente'),
(15, NULL, '2024-11-26 21:04:34', 0.00, 'En attente'),
(16, NULL, '2024-11-26 21:04:55', 0.00, 'En attente'),
(17, NULL, '2024-11-26 21:05:49', 0.00, 'En attente'),
(18, NULL, '2024-11-26 21:05:51', 0.00, 'En attente'),
(19, NULL, '2024-11-26 21:07:39', 0.00, 'En attente'),
(20, NULL, '2024-11-26 21:07:40', 0.00, 'En attente'),
(21, NULL, '2024-11-26 21:09:51', 25.99, 'En attente'),
(22, NULL, '2024-11-26 21:11:40', 0.00, 'En attente'),
(23, NULL, '2024-11-26 21:12:56', 0.00, 'En attente'),
(24, NULL, '2024-11-26 21:12:57', 0.00, 'En attente'),
(25, NULL, '2024-11-26 21:17:49', 0.00, 'En attente'),
(26, NULL, '2024-11-26 21:20:03', 0.00, 'Ready for Delivery'),
(28, NULL, '2024-11-26 21:22:56', 0.00, 'En attente'),
(29, NULL, '2024-11-26 21:25:52', 0.00, 'En attente'),
(30, NULL, '2024-11-26 21:26:32', 56.99, 'En attente'),
(31, NULL, '2024-11-26 21:28:53', 0.00, 'En attente'),
(32, NULL, '2024-11-26 21:29:05', 0.00, 'En attente'),
(33, NULL, '2024-11-26 21:30:53', 0.00, 'En attente'),
(34, NULL, '2024-11-26 21:31:26', 41.49, 'En attente'),
(35, NULL, '2024-11-26 21:32:34', 25.99, 'En attente'),
(36, NULL, '2024-11-26 21:34:38', 0.00, 'En attente'),
(37, NULL, '2024-11-26 21:38:12', 0.00, 'En attente'),
(38, NULL, '2024-11-26 21:40:58', 0.00, 'En attente'),
(39, NULL, '2024-11-26 21:41:00', 0.00, 'En attente'),
(40, NULL, '2024-11-26 21:42:06', 0.00, 'En attente'),
(41, NULL, '2024-11-26 21:44:00', 25.99, 'En attente'),
(42, NULL, '2024-11-26 21:45:01', 0.00, 'En attente'),
(43, NULL, '2024-11-26 21:45:47', 56.99, 'En attente'),
(44, NULL, '2024-11-26 21:47:27', 0.00, 'En attente'),
(45, NULL, '2024-11-26 22:05:42', 25.99, 'En attente'),
(46, NULL, '2024-11-26 22:38:18', 0.00, 'En attente'),
(47, NULL, '2024-11-26 22:40:05', 0.00, 'En attente'),
(48, NULL, '2024-11-26 22:40:10', 0.00, 'En attente'),
(49, NULL, '2024-11-26 22:41:35', 67.48, 'En attente'),
(50, NULL, '2024-11-26 23:01:36', 0.00, 'En attente'),
(51, NULL, '2024-11-26 23:02:01', 25.99, 'En attente'),
(52, NULL, '2024-11-26 23:14:12', 0.00, 'En attente'),
(53, NULL, '2024-11-26 23:14:36', 0.00, 'En attente'),
(54, NULL, '2024-11-26 23:21:34', 25.99, 'En attente'),
(55, NULL, '2024-11-27 09:41:18', 25.99, 'Delivered'),
(56, NULL, '2024-11-28 21:02:39', 67.48, 'En attente'),
(57, NULL, '2024-11-28 21:02:44', 0.00, 'En attente'),
(58, NULL, '2024-11-28 21:02:55', 0.00, 'En attente'),
(59, NULL, '2024-11-28 21:04:23', 0.00, 'En attente'),
(60, NULL, '2024-11-28 21:04:28', 0.00, 'En attente'),
(61, NULL, '2024-11-28 21:04:50', 0.00, 'En attente'),
(62, NULL, '2024-11-28 21:04:54', 0.00, 'En attente'),
(63, NULL, '2024-11-28 21:06:10', 0.00, 'En attente'),
(64, NULL, '2024-11-28 21:06:14', 0.00, 'En attente'),
(65, NULL, '2024-11-28 21:09:01', 0.00, 'En attente'),
(66, NULL, '2024-11-28 21:09:06', 0.00, 'En attente'),
(67, NULL, '2024-11-28 21:09:10', 0.00, 'En attente'),
(68, NULL, '2024-11-28 21:11:53', 0.00, 'En attente'),
(69, NULL, '2024-11-28 21:12:02', 0.00, 'En attente'),
(70, NULL, '2024-11-28 21:14:10', 0.00, 'En attente'),
(71, NULL, '2024-11-28 21:14:23', 0.00, 'En attente'),
(72, NULL, '2024-11-28 21:14:24', 0.00, 'En attente'),
(73, NULL, '2024-11-28 21:14:35', 0.00, 'En attente'),
(74, NULL, '2024-11-28 21:14:57', 0.00, 'En attente'),
(75, NULL, '2024-11-28 21:15:04', 0.00, 'En attente'),
(76, NULL, '2024-11-28 21:18:42', 0.00, 'En attente'),
(77, NULL, '2024-11-28 21:18:44', 0.00, 'En attente'),
(78, NULL, '2024-11-28 21:18:48', 0.00, 'En attente'),
(79, NULL, '2024-11-28 21:22:29', 0.00, 'En attente'),
(80, NULL, '2024-11-28 21:22:33', 0.00, 'En attente'),
(81, NULL, '2024-11-28 21:32:32', 0.00, 'En attente'),
(82, NULL, '2024-11-28 21:49:07', 0.00, 'En attente'),
(83, NULL, '2024-11-28 21:50:17', 0.00, 'En attente'),
(84, NULL, '2024-11-28 21:55:32', 41.49, 'En attente'),
(85, NULL, '2024-11-28 21:57:54', 0.00, 'En attente'),
(86, NULL, '2024-11-28 22:01:15', 0.00, 'En attente'),
(87, NULL, '2024-11-28 22:03:28', 0.00, 'En attente'),
(88, NULL, '2024-11-28 22:04:25', 0.00, 'En attente'),
(89, NULL, '2024-11-28 22:05:35', 0.00, 'En attente'),
(90, NULL, '2024-11-28 22:08:09', 0.00, 'En attente'),
(91, NULL, '2024-11-28 22:08:34', 0.00, 'En attente'),
(92, NULL, '2024-11-28 22:13:37', 0.00, 'En attente'),
(93, NULL, '2024-11-28 22:17:54', 0.00, 'En attente'),
(94, NULL, '2024-11-28 22:22:25', 0.00, 'En attente'),
(95, NULL, '2024-11-28 22:23:49', 0.00, 'En attente'),
(96, NULL, '2024-11-28 22:23:56', 0.00, 'En attente'),
(97, NULL, '2024-11-28 22:39:45', 77.97, 'En attente'),
(98, NULL, '2024-11-28 22:39:59', 51.98, 'En attente'),
(110, NULL, '2024-11-28 23:21:18', 171.44, 'En attente'),
(111, NULL, '2024-11-28 23:21:21', 0.00, 'En attente'),
(112, NULL, '2024-11-28 23:21:49', 25.99, 'En attente'),
(113, NULL, '2024-11-28 23:22:33', 0.00, 'En attente'),
(114, NULL, '2024-11-28 23:23:18', 15.50, 'En attente'),
(115, NULL, '2024-11-28 23:41:01', 51.98, 'En attente'),
(116, NULL, '2024-11-28 23:42:35', 0.00, 'En attente'),
(117, NULL, '2024-11-29 00:19:37', 134.96, 'En attente'),
(118, NULL, '2024-11-29 00:22:41', 0.00, 'En attente'),
(119, NULL, '2024-11-29 00:23:29', 25.99, 'En attente'),
(120, NULL, '2024-11-29 00:27:16', 0.00, 'En attente'),
(121, NULL, '2024-11-29 00:27:44', 25.99, 'En attente'),
(122, NULL, '2024-11-29 00:28:56', 25.99, 'En attente'),
(123, NULL, '2024-11-29 00:31:21', 25.99, 'En attente'),
(124, NULL, '2024-11-29 00:41:52', 93.47, 'En attente'),
(125, NULL, '2024-11-29 00:45:40', 0.00, 'En attente'),
(126, NULL, '2024-11-29 00:46:06', 25.99, 'En attente'),
(127, NULL, '2024-11-29 01:14:54', 0.00, 'En attente'),
(128, NULL, '2024-11-29 01:15:06', 51.98, 'En attente'),
(129, NULL, '2024-11-29 01:15:59', 51.98, 'En attente'),
(130, NULL, '2024-11-29 01:16:25', 0.00, 'En attente'),
(131, NULL, '2024-11-29 09:26:56', 25.99, 'En attente'),
(132, NULL, '2024-11-29 09:31:58', 77.97, 'En attente'),
(133, NULL, '2024-11-29 09:42:29', 25.99, 'En attente'),
(134, NULL, '2024-11-29 10:06:38', 25.99, 'En attente'),
(135, NULL, '2024-11-29 10:10:02', 77.97, 'En attente'),
(136, NULL, '2024-11-29 10:11:40', 0.00, 'En attente'),
(137, NULL, '2024-12-03 16:38:25', 25.99, 'En attente'),
(138, NULL, '2024-12-03 16:40:49', 25.99, 'En attente'),
(139, NULL, '2024-12-03 20:05:04', 103.96, 'En attente'),
(140, NULL, '2024-12-03 20:12:00', 25.99, 'En attente'),
(141, NULL, '2024-12-03 20:17:36', 0.00, 'En attente'),
(142, NULL, '2024-12-03 20:17:52', 25.99, 'En attente'),
(146, NULL, '2024-12-03 22:40:25', 197.43, 'En attente'),
(147, NULL, '2024-12-03 22:40:28', 0.00, 'En attente'),
(148, NULL, '2024-12-03 22:53:26', 0.00, 'En attente'),
(149, NULL, '2024-12-03 23:02:00', 0.00, 'En attente'),
(150, NULL, '2024-12-03 23:05:14', 0.00, 'En attente'),
(151, NULL, '2024-12-03 23:08:22', 0.00, 'En attente'),
(152, NULL, '2024-12-03 23:12:30', 0.00, 'En attente'),
(153, NULL, '2024-12-03 23:17:40', 222.48, 'En attente'),
(154, NULL, '2024-12-03 23:54:24', 51.98, 'En attente'),
(155, NULL, '2024-12-03 23:55:05', 41.49, 'En attente'),
(156, NULL, '2024-12-03 23:57:29', 15.50, 'En attente'),
(157, NULL, '2024-12-03 23:59:47', 41.49, 'En attente'),
(158, NULL, '2024-12-04 09:19:45', 145.45, 'En attente'),
(159, NULL, '2024-12-04 09:48:33', 51.98, 'En attente'),
(160, NULL, '2024-12-04 09:53:26', 41.49, 'En attente'),
(161, NULL, '2024-12-04 09:56:02', 15.50, 'En attente'),
(162, NULL, '2024-12-04 10:00:46', 31.00, 'Prête pour la livraison'),
(163, NULL, '2024-12-04 13:54:14', 25.99, 'En attente'),
(164, NULL, '2024-12-04 14:03:15', 67.48, 'En attente'),
(165, NULL, '2024-12-04 14:15:02', 25.99, 'En attente'),
(166, NULL, '2024-12-04 14:39:25', 25.99, 'En attente'),
(167, NULL, '2024-12-04 14:49:05', 25.99, 'En attente'),
(168, NULL, '2024-12-05 20:46:56', 25.99, 'En attente'),
(169, NULL, '2024-12-05 21:13:37', 25.99, 'En attente'),
(170, NULL, '2024-12-05 21:19:36', 41.49, 'En attente'),
(171, NULL, '2024-12-05 21:40:03', 41.49, 'En attente'),
(172, NULL, '2024-12-05 21:41:11', 41.49, 'En attente'),
(173, NULL, '2024-12-05 21:42:50', 25.99, 'En attente'),
(174, NULL, '2024-12-05 21:45:12', 15.50, 'En attente'),
(175, NULL, '2024-12-05 21:47:50', 25.99, 'En attente'),
(176, NULL, '2024-12-05 21:49:43', 51.98, 'En attente'),
(177, NULL, '2024-12-05 21:52:19', 51.98, 'En attente'),
(178, NULL, '2024-12-05 21:54:18', 41.49, 'En attente'),
(179, NULL, '2024-12-05 21:54:59', 31.00, 'En attente'),
(180, NULL, '2024-12-05 21:59:05', 15.50, 'En attente'),
(181, NULL, '2024-12-05 22:18:26', 41.49, 'En attente'),
(182, NULL, '2024-12-05 22:23:37', 25.99, 'En attente'),
(183, NULL, '2024-12-05 22:25:12', 51.98, 'En attente'),
(184, NULL, '2024-12-05 22:40:20', 25.99, 'En attente'),
(185, NULL, '2024-12-05 22:47:44', 25.99, 'En attente'),
(186, NULL, '2024-12-05 22:52:19', 51.98, 'En attente'),
(187, NULL, '2024-12-05 22:58:59', 15.50, 'En attente'),
(188, NULL, '2024-12-05 23:02:46', 51.98, 'En attente'),
(189, NULL, '2024-12-05 23:06:30', 25.99, 'En attente'),
(190, NULL, '2024-12-05 23:07:32', 41.49, 'En attente'),
(191, NULL, '2024-12-05 23:10:36', 31.00, 'En attente'),
(192, NULL, '2024-12-05 23:11:28', 15.50, 'En attente'),
(193, NULL, '2024-12-05 23:39:59', 25.99, 'En attente'),
(194, NULL, '2024-12-06 00:13:28', 41.49, 'En attente'),
(195, NULL, '2024-12-06 00:15:54', 41.49, 'En attente'),
(196, NULL, '2024-12-06 00:44:00', 25.99, 'En attente'),
(197, NULL, '2024-12-06 00:47:16', 25.99, 'En attente'),
(198, NULL, '2024-12-06 00:49:05', 41.49, 'En attente'),
(199, NULL, '2024-12-06 00:51:12', 25.99, 'En attente'),
(200, NULL, '2024-12-06 00:52:10', 25.99, 'En attente'),
(201, NULL, '2024-12-06 00:55:34', 25.99, 'Prête pour la livraison'),
(202, NULL, '2024-12-06 00:56:41', 119.46, 'En attente'),
(203, NULL, '2024-12-06 01:07:22', 77.97, 'Livrée'),
(204, NULL, '2024-12-06 01:09:20', 15.50, 'Prête pour la livraison'),
(205, NULL, '2024-12-06 10:54:09', 145.45, 'En cours'),
(206, NULL, '2024-12-06 10:56:05', 31.00, 'En cours'),
(207, NULL, '2024-12-10 23:54:52', 25.99, 'En attente'),
(208, NULL, '2024-12-12 12:45:09', 25.99, 'Pending'),
(209, NULL, '2024-12-13 10:17:40', 25.99, 'En attente'),
(210, NULL, '2024-12-13 11:02:18', 15.50, 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `cartitem`
--

CREATE TABLE `cartitem` (
  `id` int(11) NOT NULL,
  `cartId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cartitem`
--

INSERT INTO `cartitem` (`id`, `cartId`, `productId`, `quantity`) VALUES
(320, 196, 1, 1),
(322, 198, 1, 2),
(323, 198, 2, 2),
(325, 199, 1, 2),
(327, 201, 1, 1),
(328, 202, 1, 4),
(329, 202, 2, 1),
(330, 203, 1, 3),
(334, 206, 1, 1),
(335, 206, 2, 2),
(336, 207, 1, 1),
(337, 208, 1, 1),
(338, 209, 1, 1),
(339, 210, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `governorate` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `card_number` varchar(19) NOT NULL,
  `expiration_date` varchar(5) NOT NULL,
  `cvv` varchar(4) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `first_name`, `last_name`, `country`, `address`, `city`, `governorate`, `phone`, `email`, `card_number`, `expiration_date`, `cvv`, `cart_id`, `created_at`) VALUES
(1, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '1245369874523698', '10/24', '123', 146, '2024-12-03 21:40:25'),
(2, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '1245369874523698', '10/24', '123', 147, '2024-12-03 21:40:28'),
(3, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '1111111111111111', '12/10', '123', 148, '2024-12-03 22:01:00'),
(4, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '1111111111111111', '12/10', '123', 149, '2024-12-03 22:03:52'),
(5, 'toukebri', 'ben jemaa', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'nabeul', '28062422', 'meryam.aski@gmail.com', '457892225558', '26/07', '123', 150, '2024-12-03 22:07:25'),
(6, 'toukebri', 'ben jemaa', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'nabeul', '28062422', 'meryam.aski@gmail.com', '457892225558', '26/07', '123', 151, '2024-12-03 22:12:28'),
(7, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '1245369874523698', '10/24', '123', 153, '2024-12-03 22:53:52'),
(8, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '1245369874523698', '10/24', '123', 154, '2024-12-03 22:54:39'),
(9, 'yomna', 'dahmeni', 'tunisie', 'jardin menzah', 'ariana', 'ariana', '99900771', 'yomnadahmani04@gmail.com', '145879632544', '07/24', '123', 155, '2024-12-03 22:56:33'),
(10, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 156, '2024-12-03 22:57:57'),
(11, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 158, '2024-12-04 08:45:53'),
(12, 'toukebri', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 159, '2024-12-04 08:49:11'),
(13, 'smaarrrr', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 160, '2024-12-04 08:54:47'),
(14, 'inga', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 161, '2024-12-04 08:56:12'),
(15, 'toukebri', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 163, '2024-12-04 12:54:28'),
(16, 'miryam', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 164, '2024-12-04 13:08:33'),
(17, 'omar', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 165, '2024-12-04 13:15:22'),
(18, 'toukebri', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 166, '2024-12-04 13:39:34'),
(19, 'miryam', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 167, '2024-12-04 13:53:29'),
(20, 'mehrzia', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 168, '2024-12-05 19:47:21'),
(21, 'mehrzia', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'hhhhhhhhhh', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 169, '2024-12-05 20:13:43'),
(22, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 170, '2024-12-05 20:19:50'),
(23, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 171, '2024-12-05 20:40:15'),
(24, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 172, '2024-12-05 20:41:25'),
(25, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 173, '2024-12-05 20:42:54'),
(26, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 174, '2024-12-05 20:45:17'),
(27, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 175, '2024-12-05 20:47:55'),
(28, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 176, '2024-12-05 20:49:56'),
(29, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 177, '2024-12-05 20:52:30'),
(30, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 178, '2024-12-05 20:54:23'),
(31, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 179, '2024-12-05 20:55:11'),
(32, 'touhemi', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 180, '2024-12-05 20:59:15'),
(33, 'touhemi', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 181, '2024-12-05 21:18:42'),
(34, 'touhemi', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 182, '2024-12-05 21:23:42'),
(35, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 183, '2024-12-05 21:25:33'),
(36, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 184, '2024-12-05 21:40:25'),
(37, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 185, '2024-12-05 21:47:48'),
(38, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 186, '2024-12-05 21:52:23'),
(39, 'meryoumaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 187, '2024-12-05 21:59:04'),
(40, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 188, '2024-12-05 22:03:04'),
(41, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 189, '2024-12-05 22:06:36'),
(42, 'toukebri ', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 190, '2024-12-05 22:08:11'),
(43, 'toukebri ', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 191, '2024-12-05 22:10:48'),
(44, 'toukebri ', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 192, '2024-12-05 22:12:40'),
(45, 'toukebri ', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 193, '2024-12-05 22:40:09'),
(46, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 194, '2024-12-05 23:13:44'),
(47, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 195, '2024-12-05 23:16:53'),
(48, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 196, '2024-12-05 23:46:55'),
(49, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 197, '2024-12-05 23:47:21'),
(50, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 198, '2024-12-05 23:51:06'),
(51, 'okitou', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '1234567891', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 199, '2024-12-05 23:51:26'),
(52, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 200, '2024-12-05 23:52:16'),
(53, 'meriem', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 201, '2024-12-05 23:55:41'),
(54, 'anais', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 202, '2024-12-05 23:56:51'),
(55, 'anais', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '2806242201', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 203, '2024-12-06 00:07:30'),
(56, 'anais', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 204, '2024-12-06 00:10:39'),
(57, 'anais', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 205, '2024-12-06 09:54:16'),
(58, 'anais', 'lllll', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 206, '2024-12-06 10:58:56'),
(59, 'anais', 'lllll', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 207, '2024-12-10 22:55:10'),
(60, 'meriemaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 209, '2024-12-13 09:18:09'),
(61, 'meriemaaa', 'askri', 'Tunisie', 'route korba beni khalled sté al maçafi', 'tunis', 'jdkdj', '28062422', 'meryam.aski@gmail.com', '4456666666666999', '07/25', '123', 210, '2024-12-13 10:02:23');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `description`, `image_url`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Pure Honey', 25.99, 100, 'Natural and organic honey from our hives.', 'images/honey.png', 'Honey', '2024-11-22 09:34:12', '2024-11-22 09:34:12'),
(2, 'Beeswax Candle', 15.50, 50, 'Eco-friendly candle made from natural beeswax.', 'images/candle.png', 'Candles', '2024-11-22 09:34:12', '2024-11-22 09:34:12');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Index pour la table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cartId` (`cartId`,`productId`),
  ADD KEY `productId` (`productId`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT pour la table `cartitem`
--
ALTER TABLE `cartitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
