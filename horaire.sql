-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  mer. 06 mai 2020 à 10:21
-- Version du serveur :  8.0.18
-- Version de php :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `horaire`
--
CREATE DATABASE IF NOT EXISTS `horaire` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `horaire`;

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

DROP TABLE IF EXISTS `cours`;
CREATE TABLE IF NOT EXISTS `cours` (
  `idCours` int(11) NOT NULL AUTO_INCREMENT,
  `nomCours` int(11) NOT NULL,
  PRIMARY KEY (`idCours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `idEtudiant` int(11) NOT NULL AUTO_INCREMENT,
  `nomEtudiant` varchar(50) NOT NULL,
  `prenomEtudiant` varchar(50) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  PRIMARY KEY (`idEtudiant`),
  KEY `idGroupe` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `idGroupe` int(11) NOT NULL AUTO_INCREMENT,
  `nomGroupe` varchar(20) NOT NULL,
  PRIMARY KEY (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
CREATE TABLE IF NOT EXISTS `horaire` (
  `idHoraire` int(11) NOT NULL AUTO_INCREMENT,
  `idGroupe` int(11) NOT NULL,
  `idProfesseur` int(11) NOT NULL,
  `idSalle` int(11) NOT NULL,
  `idCours` int(11) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `jour` varchar(10) NOT NULL,
  `heure` varchar(3) NOT NULL,
  PRIMARY KEY (`idHoraire`),
  KEY `idGroupe` (`idGroupe`),
  KEY `idProfesseur` (`idProfesseur`),
  KEY `idSalle` (`idSalle`),
  KEY `idCours` (`idCours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
  `idProfesseur` int(11) NOT NULL AUTO_INCREMENT,
  `nomProfesseur` varchar(50) NOT NULL,
  `prenomProfesseur` varchar(50) NOT NULL,
  PRIMARY KEY (`idProfesseur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `idSalle` int(11) NOT NULL AUTO_INCREMENT,
  `nomSalle` varchar(10) NOT NULL,
  PRIMARY KEY (`idSalle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idGroupe`);

--
-- Contraintes pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD CONSTRAINT `horaire_ibfk_1` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idGroupe`),
  ADD CONSTRAINT `horaire_ibfk_2` FOREIGN KEY (`idCours`) REFERENCES `cours` (`idCours`),
  ADD CONSTRAINT `horaire_ibfk_3` FOREIGN KEY (`idSalle`) REFERENCES `salle` (`idSalle`),
  ADD CONSTRAINT `horaire_ibfk_4` FOREIGN KEY (`idProfesseur`) REFERENCES `professeur` (`idProfesseur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
