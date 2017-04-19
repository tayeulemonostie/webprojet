-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 16 Avril 2017 à 20:45
-- Version du serveur :  5.7.17-0ubuntu0.16.04.2
-- Version de PHP :  7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `webprojet`
--

-- --------------------------------------------------------

--
-- Structure de la table `Comptes`
--

CREATE TABLE `Comptes` (
  `compte_ID` int(11) NOT NULL,
  `usager_ID` int(11) NOT NULL,
  `nom_utilisateur` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration_password` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `Comptes`
--

INSERT INTO `Comptes` (`compte_ID`, `usager_ID`, `nom_utilisateur`, `user_password`, `expiration_password`) VALUES
(1, 1, 'CorSeb', 'root', '2017-07-15');

-- --------------------------------------------------------

--
-- Structure de la table `Departements`
--

CREATE TABLE `Departements` (
  `departements_ID` int(11) NOT NULL,
  `nom_departement` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `Departements`
--

INSERT INTO `Departements` (`departements_ID`, `nom_departement`) VALUES
(1, 'Direction'),
(2, 'Comptabilité'),
(3, 'Ressources Humaines'),
(4, 'TI'),
(5, 'Stagiaires');

-- --------------------------------------------------------

--
-- Structure de la table `Historique_password`
--

CREATE TABLE `Historique_password` (
  `historique_ID` int(11) NOT NULL,
  `usager_ID` int(11) NOT NULL,
  `date_modif` date NOT NULL,
  `ancien_password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Usagers_description`
--

CREATE TABLE `Usagers_description` (
  `usager_ID` int(11) NOT NULL,
  `nom` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_tel_poste` int(11) NOT NULL,
  `no_tel_dom` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_machine` int(11) NOT NULL,
  `departements_ID` int(11) NOT NULL,
  `quota` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `Usagers_description`
--

INSERT INTO `Usagers_description` (`usager_ID`, `nom`, `prenom`, `no_tel_poste`, `no_tel_dom`, `no_machine`, `departements_ID`, `quota`) VALUES
(1, 'Corbeil', 'Sebastien', 1111, '819-578-3276', 5555, 1, 10);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Comptes`
--
ALTER TABLE `Comptes`
  ADD PRIMARY KEY (`compte_ID`),
  ADD KEY `fkusager_ID2` (`usager_ID`);

--
-- Index pour la table `Departements`
--
ALTER TABLE `Departements`
  ADD PRIMARY KEY (`departements_ID`);

--
-- Index pour la table `Historique_password`
--
ALTER TABLE `Historique_password`
  ADD PRIMARY KEY (`historique_ID`),
  ADD KEY `fkusager_ID1` (`usager_ID`);

--
-- Index pour la table `Usagers_description`
--
ALTER TABLE `Usagers_description`
  ADD PRIMARY KEY (`usager_ID`),
  ADD KEY `fkDepartement_ID` (`departements_ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Comptes`
--
ALTER TABLE `Comptes`
  MODIFY `compte_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `Departements`
--
ALTER TABLE `Departements`
  MODIFY `departements_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `Historique_password`
--
ALTER TABLE `Historique_password`
  MODIFY `historique_ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `Usagers_description`
--
ALTER TABLE `Usagers_description`
  MODIFY `usager_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Comptes`
--
ALTER TABLE `Comptes`
  ADD CONSTRAINT `Comptes_ibfk_1` FOREIGN KEY (`usager_ID`) REFERENCES `Usagers_description` (`usager_ID`);

--
-- Contraintes pour la table `Historique_password`
--
ALTER TABLE `Historique_password`
  ADD CONSTRAINT `Historique_password_ibfk_1` FOREIGN KEY (`usager_ID`) REFERENCES `Usagers_description` (`usager_ID`);

--
-- Contraintes pour la table `Usagers_description`
--
ALTER TABLE `Usagers_description`
  ADD CONSTRAINT `Usagers_description_ibfk_1` FOREIGN KEY (`departements_ID`) REFERENCES `Departements` (`departements_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
