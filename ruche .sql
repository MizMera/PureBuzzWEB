-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 déc. 2024 à 11:39
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
-- Base de données : `ruche`
--

-- --------------------------------------------------------

--
-- Structure de la table `apiaries`
--

CREATE TABLE `apiaries` (
  `idApiary` int(11) NOT NULL,
  `apiaryName` varchar(255) NOT NULL,
  `beekeeper` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `coordinates` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `weather` varchar(255) DEFAULT NULL,
  `hiveCount` int(11) NOT NULL,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `apiaries`
--

INSERT INTO `apiaries` (`idApiary`, `apiaryName`, `beekeeper`, `location`, `coordinates`, `date`, `weather`, `hiveCount`, `observation`) VALUES
(1556584, 'apiar48', 'sarrabenothmene@gmail.com', 'sousse', '35.8256, 10.6360', '2024-12-16', 'sunny', 21, 'all good'),
(1556585, 'apiary 5', 'Sarra.benothmane@gmail.com', 'Tunis', '36.8065, 10.1815', '2024-12-11', 'cloudy', 4, 'all good'),
(1556586, 'apiary 7', 'sarrabenothmen@gmail.com', 'djerba', '33.8088, 10.8365', '2024-12-02', 'sunny', 70, 'all hives are good'),
(1556587, 'apiary 6', 'sarrabenothmen@gmail.com', 'monastir', '35.7643, 10.8113', '2024-12-17', 'sunny', 5, 'all hives are good'),
(1556588, 'apiary 7', 'sbenothmen@gmail.com', 'kairouan', '35.7643, 10.8113', '2024-12-27', 'cloudy', 5, 'all good'),
(1556589, 'apiary 1', 'sarrabenothmen@gmail.com', 'sfax', '34.7459, 10.7603', '2024-12-17', 'sunny', 20, 'all good');

-- --------------------------------------------------------

--
-- Structure de la table `harvests`
--

CREATE TABLE `harvests` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `quality` varchar(50) NOT NULL,
  `apiary` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `harvests`
--

INSERT INTO `harvests` (`id`, `date`, `location`, `quantity`, `quality`, `apiary`) VALUES
(1556577, '2024-12-18', 'djerba', 40, 'good', 1556586),
(1556578, '2024-12-18', 'monastir', 70, 'good', 1556585),
(1556579, '2024-12-20', 'tunis', 80, 'good', 1556585),
(1556580, '2024-12-14', 'sousse', 50, 'good', 1556584),
(1556581, '2024-12-14', 'monastir', 90, 'good', 1556587),
(1556582, '2024-12-19', 'djerba', 41, 'good', 1556588),
(1556583, '2024-12-13', 'djerba', 205, 'good', 1556586),
(1556584, '2024-12-20', 'sfax', 40, 'good', 1556589);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `apiaries`
--
ALTER TABLE `apiaries`
  ADD PRIMARY KEY (`idApiary`);

--
-- Index pour la table `harvests`
--
ALTER TABLE `harvests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idApiary` (`apiary`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `apiaries`
--
ALTER TABLE `apiaries`
  MODIFY `idApiary` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1556592;

--
-- AUTO_INCREMENT pour la table `harvests`
--
ALTER TABLE `harvests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1556585;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `harvests`
--
ALTER TABLE `harvests`
  ADD CONSTRAINT `idApiary` FOREIGN KEY (`apiary`) REFERENCES `apiaries` (`idApiary`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
