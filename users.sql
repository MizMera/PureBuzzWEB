-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 déc. 2024 à 11:40
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `purebuzz`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `role` varchar(50) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `face_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `date_of_birth`, `email`, `password`, `location`, `mobile`, `gender`, `role`, `profile_picture`, `verification_token`, `status`, `reset_token`, `reset_token_expiry`, `face_image_path`) VALUES
(6, 'Chahd', 'nejmaoui', '2004-06-09', 'chahd.najmaoui@esprit.tn', '$2y$10$EPpD5sDwv6E6/houAnyXKevpq3bdjVr7jaW/euReE3Dlzq9wRhg6S', 'gafsa', '12345678', 'female', 'client', '', NULL, NULL, NULL, NULL, NULL),
(7, 'karam', 'hmidi', '2003-07-22', 'karam.hmidi@esprit.tn', '$2y$10$I9TeLx.qCbDp4JBkY98iYeK0NpSRvepXrng5xeigUSvb9Z8cRptJO', 'gafsa', '12345678', 'male', 'beekeeper', '', NULL, NULL, NULL, NULL, NULL),
(8, 'gtg', 'nej', '2003-06-22', 'cha.nej@gmail.com', '$2y$10$FvzY1iX/q5k7UJEQZBULQuZ/Aa.jDG.YcxfxfTyIHgXS4/0J8yZ6u', 'sss', '66985555', 'female', 'client', '', NULL, NULL, NULL, NULL, NULL),
(9, 'chahd', 'nejmaoui', '1993-07-22', 'chahd.najmaoui@espeit.tn', '$2y$10$gEm62fSdavxJ8LAyBMI2lOhRujgieJpH0rrAOL137dzX9abUxoJC.', 'gafsa', '55458456', 'female', 'client', '', NULL, NULL, NULL, NULL, NULL),
(10, 'nermine', 'aachour', '2003-06-27', 'achour.nermine@esprit.tn', '$2y$10$qcEw7RxcsPcl7F/AdGtVHOxI/AmTfqfrzurcn5uvgMrrvxiaE6yXu', 'ggg', '5655899555', 'female', 'client', '', NULL, NULL, NULL, NULL, NULL),
(11, 'chahdoud', 'najmaoui', '2000-02-18', 'chahd.najmaoui@esprit.tn', '$2y$10$sSKqLA0dRJDLQKCKfW6hwep7wGHic.GFAP.9YyTsad5jvjTFIMvoa', 'chahd', '88865555555', 'female', 'beekeeper', '', NULL, NULL, NULL, NULL, NULL),
(12, 'chahd', 'najmaoui', '2002-06-07', 'chahd.najmaoui@esprit.tn', '$2y$10$AEmsXHhiGHlbo9xfyn/iKO0YvdL28Fe4x3WFAf0VpKTHflXMrkhla', 'gfdshjk', '888575588', 'female', 'beekeeper', '', NULL, NULL, NULL, NULL, NULL),
(13, 'elhem', 'missaoui', '2002-06-05', 'elhem.missaoui@gmail.com', '$2y$10$zD5elus7OC55ewsIImsHpO1INe0ZpGW4DqIJY.K4i5IHubVQzdpbu', 'hgdcskl', '5428658542865', 'female', 'client', '', NULL, NULL, NULL, NULL, NULL),
(14, 'chahd', 'nejmaoui', '2003-02-07', 'chahd.najmaoui@gmail.com', '$2y$10$pZrOVfs6txbzyYkX4LbCI.XxocVdZMWj/62CcdNHzZmPLO8DNhLcS', 'agaggga', '459555555', 'female', 'beekeeper', '', NULL, NULL, NULL, NULL, NULL),
(19, 'narimen', 'najmaoui', '1999-06-03', 'narimen.najmaoui@gmail.com', '$2y$10$WzHz/eZ2v/a0lGhVV4xWRualz0IJ0SA4LFg6L4u1wYS5kU0hm4N9S', 'fff', '5555555555', 'female', 'client', 'chahd.jpg', NULL, 1, NULL, NULL, NULL),
(20, 'lilia', 'missaoui', '1985-02-06', 'lilia.missaoui@gmail.com', '$2y$10$wxherDhDN7jWIqrDdHhCsOqhgM/IkMw7fZmO9jeOe0zOzPi9RcILi', 'ggg', '444444444444', 'female', 'admin', 'b.png', NULL, 1, NULL, NULL, '../../../assets/images/uploads/faces/user_20.jpg'),
(21, 'lilia', 'missaoui', '1985-02-06', 'lilia.missaoui@gmail.com', '$2y$10$XnL6Rz4.DDn2iLcyzBDQge7RMf9trQWo5ZNBos4rHFYwKF5Hn.ogy', 'ggg', '444444444444', 'female', 'client', 'b.png', NULL, 1, NULL, NULL, NULL),
(22, 'lilia', 'missaoui', '1985-02-06', 'lilia.missaoui@gmail.com', '$2y$10$QtXduPpJ7XCit69y3rpyFO9zN3hTcL14jMjp0h1scpd27t5nyJ1WG', 'ggg', '444444444444', 'female', 'client', 'b.png', 'f187ee2c8580dc603e1a804b5dd47d8c4d3fc9adb911f52fa1f3662b75e918a8', NULL, NULL, NULL, NULL),
(23, 'eya', 'najmaoui', '2004-07-16', 'eya.najmaoui@gmail.com', '$2y$10$6VNrtxVFThsFjMc4z7f0N.Unm.85hffkmPUQzf/EjHVOQSFpifAIy', 'fff', '555555555', 'female', 'client', 'chahd.jpg', NULL, 1, NULL, NULL, NULL),
(24, 'ggg', 'ggg', '1990-03-22', 'chahd.hmidi@gmail.com', '$2y$10$Bg6yh8QLKy9s/lhJGFl7z.zJjLTdFMyt19R8WpvIx8eMbfx0KgknG', 'fff', '8555555555555', 'female', 'seller', 'chahd.jpg', '99cd81d32e94da811db593c611b3ff391ab0414747230595236c49dfafd8a911', NULL, NULL, NULL, NULL),
(25, 'ggg', 'ggg', '1990-03-22', 'chahd.hmidi@gmail.com', '$2y$10$HFbEh/ikDfJzHSijpInBoO63s2bIbb.GsRlSZOLcpc6sc9wTd/Xfe', 'fff', '8555555555555', 'female', 'seller', 'chahd.jpg', 'ae0a4863def9616b9f5bbcbe13aa1b6d4a1b4cfc79849453f15691bbef6d8980', NULL, NULL, NULL, NULL),
(26, 'ggg', 'ggg', '1990-03-22', 'chahd.hmidi@gmail.com', '$2y$10$K1f90EDH.AOb46cDEp/4DOZlhxBYKdzrwQiko.F7e4dS27.7ugBru', 'fff', '8555555555555', 'female', 'seller', 'chahd.jpg', '969e09c58b2aa8b57b3d868c5c0403e00b002357b13b617327c5d295a5735c8b', NULL, NULL, NULL, NULL),
(27, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$5m6O.Wi8vKycHvP9Sxh1.ewnUXirrukIjnQQZxk62LDcAUFVv/.HG', 'fff', '12345645', 'female', 'client', 'chahd.jpg', '4f6f2bee41152222f846fdb9aa7533efbff903e35bc4f897532d217b02adce02', NULL, NULL, NULL, NULL),
(28, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$2y33paOBLJIZCKNZ2ftpTu93kIlj05XS.W8NSHx6s3reloeLaTEBq', 'fff', '12345645', 'female', 'client', 'chahd.jpg', 'fcee4e3bd2499b724014a1bd10fb861e77d1f90d48e1da3052e7d437fc81e3e2', NULL, NULL, NULL, NULL),
(29, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$GldJJRbc6qNxVsuCszfqjOaLu1Yx5ol/Nroqv9qbhVtPTe0iZpWYW', 'fff', '12345645', 'female', 'client', 'chahd.jpg', '5a0e93fb57f631f50817becd63bcffbc6b8d3be696e1db4cb04a52db6efcdcbf', NULL, NULL, NULL, NULL),
(30, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$IJ47d4wiJrNB.xJcgk4Mx.Tt9Edt3OrLDFnPhETd5Edxpw7/AmTYy', 'fff', '12345645', 'female', 'client', 'chahd.jpg', '73089ccfeb6afbd4bb3a57a56295e48bd40407b395c14450aa673ce6d18cd7d2', NULL, NULL, NULL, NULL),
(31, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$iHZnDZytUvqaKksONICWbe2qRTLAgiNjRy1GMZjZvmL8VyI0itPP.', 'fff', '12345645', 'female', 'client', 'chahd.jpg', '12c2f713a922c7167498d629a11c4beea0670b04b06546c68d69c5a45a2b9d2c', NULL, NULL, NULL, NULL),
(32, 'chahd', 'slimen', '2000-03-03', 'chahd.slimen@esprit.tn', '$2y$10$p.hmsxQDRJFH.LOpYeXOcuJbGu/uHaADNFJfETyP.0qHPIJxfaNYS', 'fff', '12345645', 'female', 'client', 'chahd.jpg', '3d5e40489ba0dd0be6b7202a28e5a0b7236b6b6d301d8cc1659cb684439f2238', NULL, NULL, NULL, NULL),
(33, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$8cKT/DHpRhicL/BzWcKnSe9ZJP1BiI7NX.yUYxIeylco9VW.jb1Sa', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', '93347df7e92dd3758f9f2e2cd13504367968d279b5e76b3b2efc0854f4f4261c', NULL, '4c4882f2fcbec53401ba5c291a9472e871c98a34759391ebedbfbbf5cf8c638c', '2024-12-13 00:32:45', NULL),
(34, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$MEe/6C..1PvMsdyigbMhsO6J.im/o.b9u4oIBsjsYLh5jVjA40jky', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', '4b573185625cb4dc3103f05cf0f06f70376faa7064dfe8ea338986ce59424b43', NULL, NULL, NULL, NULL),
(35, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$PyoBWLwDH0237S.5cDxeJ.0zaMXgyd8CAHzDPIBFQZ7IMozLwhb6i', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', '59d1815171947d61c43a808c8e613b10e6e7fc5890f409c24a91bc281a9ca74a', NULL, NULL, NULL, NULL),
(36, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$H6vtjGXGFhVJQSpU9u3EQu.ned8/Vi5yvoihdpmsi/IForS13KPp6', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', 'e52aad626c01c12a2e1e140e894cb84aa7bc12085b087d997c355f6841848e0e', NULL, NULL, NULL, NULL),
(37, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$xKT5sqetJWBZZX75T6hSSeLiRet58WWyd6CDCHMe8g66evcJ4756C', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', '4411bcf7739755408affce48f6a79291fd152b1a47f3d5bf8afd490e77b28b80', NULL, NULL, NULL, NULL),
(38, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$lMd2pqcQRLrSQKLewB062OXugO.zvaKnfaBK7X/XuwWCXNrtyNnma', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', '79beb425b85d3ef2b7dc09d0d77e0cc8af7571718b9724b660b40c34b6f258a8', NULL, NULL, NULL, NULL),
(39, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$54y/kpkgqwQj0gVIgtRKmu9XrKcGkpt936uOyTG1KCzkzOfTt0BLW', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', 'a3842db34ede97e5555957e995da885e9771d72132e25468ca259075109f5117', NULL, NULL, NULL, NULL),
(40, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$HFWATH5By3wYx5TVqgHq6.zMwsclcPIQM4e4PEbXaH9xNgJi.eIya', 'hhh', '1111111111111111', 'male', 'beekeeper', 'image carro.png', 'b9528ada2642b768faf6d266c984d9fb98e12465681fba9e7bd757f96ee6a04c', NULL, NULL, NULL, NULL),
(41, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$8FnlDsg/4RRi6DHV3yC6N.Q2AuT4zXG/.xymtMCkGKMtgeNUpLUWC', 'hhh', '1111111111111111', 'female', 'beekeeper', 'image carro.png', '05ccff7a5903e52d20c0881a48849f8d9b46facaf90971941f52c60bbfe7ca62', NULL, NULL, NULL, NULL),
(42, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$gvzrbgnOPtLFKsrWFBoUtempKnwuFq/1tUAV0TYL8l1/UTEEQfCPi', 'hhh', '1111111111111111', 'female', 'beekeeper', 'image carro.png', 'e689cb5f78871a891a3bf77a3859542090591f8a6410f9388f93df932fa4900c', NULL, NULL, NULL, NULL),
(43, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$Y/LRf93iquogATUH0Q9raOPxEF.BYuh58K7nKwC03Bw1O0rof7bw.', 'hhh', '1111111111111111', 'female', 'beekeeper', 'image carro.png', 'b399563ebe36fd5101a5689bac2693f400d5d53c8332be7002ab96fe6be3c0f6', NULL, NULL, NULL, NULL),
(44, 'chahd', 'najmaoui', '2000-03-17', 'chahd.najmaoui16@gmail.com', '$2y$10$UcotM.icsVbOwIuggRf/.epgYw0Lo0TYoolP3b/txEEAB.k4xIjFK', 'hhh', '1111111111111111', 'female', 'beekeeper', 'image carro.png', '755e9254aaa6a6e0cb2efb9ac7c73675cbea795e0f98b887e4704faa2887301d', NULL, NULL, NULL, NULL),
(45, 'chahd', 'nejmaoui', '2000-03-16', 'chahd.najmaoui16@gmail.com', '$2y$10$V207nGa341mrbtz9nets6eOza0OIFz1jkAVkFsaS2xM8uHXIDOnFa', 'jjjjjj', '1111111111111111', 'female', 'seller', 'hhh.png', '71ad574068f70277220fe7eb55b21afb4d22ed9d9c182f21c1b415200c7ec718', NULL, NULL, NULL, NULL),
(46, 'chahd', 'nejmaoui', '2000-03-16', 'chahd.najmaoui16@gmail.com', '$2y$10$cqONu/Rdx4.Cf2EYcyKXjOqvVx3B05oPsaep0MrPWtj4zsZ3E9yTy', 'jjjjjj', '1111111111111111', 'female', 'seller', 'hhh.png', '3659270a527012b994bb9ead6b83f31c66350dbb8865abc529d5b71969b5429e', NULL, NULL, NULL, NULL),
(49, 'malika', 'najmaoui', '2000-03-16', 'malika.najmaoui@gmail.com', '$2y$10$6rcLUsVhcH3SGdwVJf4n1uo63G2Cy8wVO7k34Gz8SQb6BKxyI0kV6', 'fff', '1111111111111111', 'female', 'farmer', 'chahd.jpg', '41dd944bedad7fb99623103506ce821b6506a7578c7d5637ae447b8d00547d3c', NULL, NULL, NULL, NULL),
(50, 'malika', 'najmaoui', '2000-03-16', 'malika.najmaoui@gmail.com', '$2y$10$.iVxK3fRXQ78wUMKdg14W.hf0q4LcGt/y2auy8uoPxPBqZAc/BpuK', 'fff', '1111111111111111', 'female', '', 'chahd.jpg', '7faa34da98e902c906565978d242d413639f6affaa6afb5495462f1a19f255a8', NULL, NULL, NULL, NULL),
(51, 'malika', 'najmaoui', '2000-03-16', 'malika.najmaoui@gmail.com', '$2y$10$xjUAUXjkO0n1heVizbT0euC7k9rfX/3j2JDy11nr8gfFLbsa9LUem', 'fff', '1111111111111111', 'female', 'farmer', 'chahd.jpg', 'c4d88d7e838d83d2ddae917cc3d8bd5e00248da28e2949b9667367433dceab60', NULL, NULL, NULL, NULL),
(52, 'manel', 'zaabi', '2000-04-14', 'manel.zaabi@gmail.com', '$2y$10$uhXe.P7R0KruKQQUf62IaOrEmO1yiWQ4gOUx6/Jb5.2HfS3rwbfqe', 'jjjjj', '555555555555555', 'female', 'farmer', 'chahd.jpg', '3c7a355e7b060f3b285d09ba2d1c4d89542165b35c8cb830cf8465cded8befd4', NULL, NULL, NULL, NULL),
(53, 'zaabouti', 'chahd', '2000-04-15', 'chahd.zaabouti@gmail.com', '$2y$10$m1.yjNALQ8h6C5bEFG6jv.UkhEOtu0F84NwbAZWZRUcN15yQ0sgpW', 'fff', '1111111111111111', 'female', 'beekeeper', 'chahd.jpg', 'ba75549e882a8f1a91a6dcc0ec2f9e6b96460c2aafc55201f87c0932a4dd143d', NULL, NULL, NULL, NULL),
(54, 'chachou', 'najmaoui', '2000-03-16', 'chahd.hmidi@gmail.com', '$2y$10$K6WO8sP0w/YanJ86QCyXWO9FSYz5Jo7rDFvA33XzZPe6uZovskJbG', 'fff', '1111111111111111', 'female', 'client', 'chahd.jpg', 'f9185174aaa48adc2ea9a0454ec82438effb0fbd3445fe9e3b59542c547ad3ea', NULL, NULL, NULL, NULL),
(55, 'manel', 'nejmaoui', '2000-05-17', 'cha.nej@gmail.com', '$2y$10$.wJkfOwxUGD5j5S5iJ0dyux.dcwxNKYMNQmOs5dK0faOE7EvSNGGq', 'fff', '11111111111111', 'female', 'client', 'image carro.png', '26651894ffb8fca6e49ad8f0558f3684e7eec0b17066a03fef878c1a69719089', NULL, NULL, NULL, NULL),
(56, 'chahd', 'hmidi', '2000-03-15', 'chahd.hmidi@esprit.tn', '$2y$10$uSj6/eC/yWYVcjM2HszYSedU6J9Y2EB.rym388Daaq4e2TDFBm8Zi', 'hhhhh', '55555555', 'female', 'beekeeper', 'chahd.jpg', '99a2e1151610666a37b5c8b5db5332d6d6c4d1391144522394d68baff95bebf8', NULL, NULL, NULL, NULL),
(57, 'chahd', 'hmidi', '2000-03-15', 'chahd.hmidi@esprit.tn', '$2y$10$Es0AaIrMC5xjcvi3JlHpA.uNy6sTKuzYhyqz1c7nV/eSng85agHKy', 'hhhhh', '55555555', 'female', 'beekeeper', 'chahd.jpg', 'a9658ef419f7836570633702990dbe75a85ef66616ba4349e69dbb2b08d2bff7', NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
