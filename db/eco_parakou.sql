-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 24 août 2025 à 01:43
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eco_parakou`
--

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_contact` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_general_ci,
  `quartier` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `localisation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `secteur_id` int DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` enum('en_attente','valide','rejete','suspendu') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `motif_rejet` text COLLATE utf8mb4_general_ci,
  `motif_suspension` text COLLATE utf8mb4_general_ci,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_validation` datetime DEFAULT NULL,
  `modifie_par` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `slug`, `description`, `telephone`, `email_contact`, `adresse`, `quartier`, `localisation`, `secteur_id`, `logo`, `statut`, `motif_rejet`, `motif_suspension`, `date_inscription`, `date_validation`, `modifie_par`) VALUES
(1, 'AgroParakou', 'agroparakou', 'AgroParakou accompagne les agriculteurs de la région avec des outils modernes de gestion des cultures, de distribution et de transformation agroalimentaire.', '0151967000', 'agroparakou@test.bj', 'Parakou, Bénin', 'Depot', '', 1, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:30:05', 1),
(2, 'TechNova', 'technova', 'TechNova accompagne les agriculteurs de la région avec des outils modernes de gestion des cultures, de distribution et de transformation agroalimentaire.', '0151967001', 'technova@test.bj', 'Parakou, Bénin', 'Zongo', '', 1, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:30:39', 1),
(3, 'École Horizon', 'cole-horizon', 'École Horizon accompagne les agriculteurs de la région avec des outils modernes de gestion des cultures, de distribution et de transformation agroalimentaire.', '0151967002', 'cole-horizon@test.bj', 'Parakou, Bénin', 'Albarika', '', 1, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:30:42', 1),
(5, 'TransBenin', 'transbenin', 'TransBenin accompagne les agriculteurs de la région avec des outils modernes de gestion des cultures, de distribution et de transformation agroalimentaire.', '0151967004', 'transbenin@test.bj', 'Parakou, Bénin', 'Kpébié', '', 1, '', 'rejete', 'sdfghjk', NULL, '2025-08-17 01:05:31', NULL, 1),
(7, 'WebXpert', 'webxpert', 'WebXpert propose des formations innovantes en ligne et en présentiel, adaptées aux besoins des jeunes et des professionnels de Parakou.', '0151967006', 'webxpert@test.bj', 'Parakou, Bénin', 'Zongo', '', 2, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:30:56', 1),
(10, 'Tourisme Nord', 'tourisme-nord', 'Tourisme Nord offre des services de santé de proximité, avec des consultations spécialisées, un laboratoire moderne et une pharmacie intégrée.', '0151967009', 'tourisme-nord@test.bj', 'Parakou, Bénin', 'Kpébié', '', 3, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(11, 'Artisanat Soleil', 'artisanat-soleil', 'Artisanat Soleil offre des services de santé de proximité, avec des consultations spécialisées, un laboratoire moderne et une pharmacie intégrée.', '0151967010', 'artisanat-soleil@test.bj', 'Parakou, Bénin', 'Depot', '', 3, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(13, 'GreenLife', 'greenlife', 'GreenLife offre des services de santé de proximité, avec des consultations spécialisées, un laboratoire moderne et une pharmacie intégrée.', '0151967012', 'greenlife@test.bj', 'Parakou, Bénin', 'Albarika', '', 3, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(14, 'AgroPlus', 'agroplus', 'AgroPlus est spécialisée dans le transport de marchandises et de passagers, avec une flotte moderne et des itinéraires optimisés.', '0151967013', 'agroplus@test.bj', 'Parakou, Bénin', 'Wansirou', '', 4, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:31:12', 1),
(16, 'Santé Express', 'sante-express', 'Santé Express est spécialisée dans le transport de marchandises et de passagers, avec une flotte moderne et des itinéraires optimisés.', '0151967015', 'sante-express@test.bj', 'Parakou, Bénin', 'Depot', '', 4, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:31:07', 1),
(17, 'LogiTrack', 'logitrack', 'LogiTrack développe des solutions énergétiques durables, notamment dans le solaire, le biogaz et l’optimisation des réseaux locaux.', '0151967016', 'logitrack@test.bj', 'Parakou, Bénin', 'Zongo', '', 5, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(18, 'Énergie Verte', 'nergie-verte', 'Énergie Verte développe des solutions énergétiques durables, notamment dans le solaire, le biogaz et l’optimisation des réseaux locaux.', '0151967017', 'nergie-verte@test.bj', 'Parakou, Bénin', 'Albarika', '', 5, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(19, 'CodeLab', 'codelab', 'CodeLab développe des solutions énergétiques durables, notamment dans le solaire, le biogaz et l’optimisation des réseaux locaux.', '0151967018', 'codelab@test.bj', 'Parakou, Bénin', 'Wansirou', '', 5, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(20, 'Boutique Élite', 'boutique-lite', 'Boutique Élite conçoit des outils numériques sur mesure : sites web, applications mobiles, automatisation et cybersécurité.', '0151967019', 'boutique-lite@test.bj', 'Parakou, Bénin', 'Kpébié', '', 6, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(21, 'AssurBenin', 'assurbenin', 'AssurBenin conçoit des outils numériques sur mesure : sites web, applications mobiles, automatisation et cybersécurité.', '0151967020', 'assurbenin@test.bj', 'Parakou, Bénin', 'Depot', '', 6, '', 'valide', NULL, NULL, '2025-08-17 01:05:31', '2025-08-17 01:31:18', 1),
(22, 'Voyage Évasion', 'voyage-vasion', 'Voyage Évasion conçoit des outils numériques sur mesure : sites web, applications mobiles, automatisation et cybersécurité.', '0151967021', 'voyage-vasion@test.bj', 'Parakou, Bénin', 'Zongo', '', 6, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(23, 'CréaMain', 'creamain', 'CréaMain est un acteur du commerce local, avec des points de vente physiques et une plateforme e-commerce pour les produits béninois.', '0151967022', 'creamain@test.bj', 'Parakou, Bénin', 'Albarika', '', 7, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(24, 'LogisPro', 'logispro', 'LogisPro est un acteur du commerce local, avec des points de vente physiques et une plateforme e-commerce pour les produits béninois.', '0151967023', 'logispro@test.bj', 'Parakou, Bénin', 'Wansirou', '', 7, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(25, 'EcoNature', 'econature', 'EcoNature propose des services financiers accessibles : microcrédit, assurance, gestion de portefeuille et accompagnement comptable.', '0151967024', 'econature@test.bj', 'Parakou, Bénin', 'Kpébié', '', 8, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(26, 'AgriTech', 'agritech', 'AgriTech propose des services financiers accessibles : microcrédit, assurance, gestion de portefeuille et accompagnement comptable.', '0151967025', 'agritech@test.bj', 'Parakou, Bénin', 'Depot', '', 8, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(27, 'Campus Connect', 'campus-connect', 'Campus Connect valorise le patrimoine touristique de Parakou à travers des circuits guidés, des hébergements et des expériences culturelles.', '0151967026', 'campus-connect@test.bj', 'Parakou, Bénin', 'Zongo', '', 9, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(28, 'PharmaCare', 'pharmacare', 'PharmaCare valorise le patrimoine touristique de Parakou à travers des circuits guidés, des hébergements et des expériences culturelles.', '0151967027', 'pharmacare@test.bj', 'Parakou, Bénin', 'Albarika', '', 9, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(29, 'MobilityX', 'mobilityx', 'MobilityX fabrique et commercialise des objets artisanaux uniques : textiles, poteries, bijoux et décorations traditionnelles.', '0151967028', 'mobilityx@test.bj', 'Parakou, Bénin', 'Wansirou', '', 10, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(30, 'PowerGrid', 'powergrid', 'PowerGrid intervient dans l’immobilier résidentiel et commercial, avec des offres de location, vente et gestion locative.', '0151967029', 'powergrid@test.bj', 'Parakou, Bénin', 'Kpébié', '', 11, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(31, 'DevStudio', 'devstudio', 'DevStudio intervient dans l’immobilier résidentiel et commercial, avec des offres de location, vente et gestion locative.', '0151967030', 'devstudio@test.bj', 'Parakou, Bénin', 'Depot', '', 11, '', 'rejete', 'dfgh', NULL, '2025-08-17 01:05:31', NULL, 1),
(32, 'CommerceLink', 'commercelink', 'CommerceLink agit pour la préservation de l’environnement : recyclage, reboisement, sensibilisation et gestion des déchets.', '0151967031', 'commercelink@test.bj', 'Parakou, Bénin', 'Zongo', '', 12, '', 'en_attente', NULL, NULL, '2025-08-17 01:05:31', NULL, NULL),
(33, 'FATOMBI DEV', 'fatombi', 'ertyuhgfghkjkl,nb', '0151967913', 'akomedi533@gmail.com', 'depot parakou', 'Depot', '', 6, 'logo_68a8423587e7c.jpg', 'suspendu', NULL, NULL, '2025-08-19 03:35:44', '2025-08-19 03:40:11', 1),
(37, 'Light Innovation', 'light-innovation', 'Light Innovation est une agence de communication corporate existante depuis 2016 et basée au Bénin. Nous vous proposons nos services en création graphique, en développement web et mobile et en communication digitale.', '0167406640', 'talk@light.bj', 'Parakou, 1ère rue à droite après le stade', 'Ladjifarani', 'https://www.google.com/maps?q=Light+Innovation+Parakou+Ladjifarani', 6, 'logo_68aa0981cf442.jpg', 'valide', NULL, NULL, '2025-08-23 19:25:07', '2025-08-23 19:25:22', 1);

-- --------------------------------------------------------

--
-- Structure de la table `logs_actions`
--

CREATE TABLE `logs_actions` (
  `id` int NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `table_cible` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cible_id` int DEFAULT NULL,
  `date_action` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logs_actions`
--

INSERT INTO `logs_actions` (`id`, `utilisateur_id`, `action`, `table_cible`, `cible_id`, `date_action`) VALUES
(1, 1, 'Validation entreprise #1', 'entreprises', 1, '2025-08-17 01:30:06'),
(2, 1, 'Validation entreprise #2', 'entreprises', 2, '2025-08-17 01:30:39'),
(3, 1, 'Validation entreprise #3', 'entreprises', 3, '2025-08-17 01:30:42'),
(4, 1, 'Validation entreprise #4', 'entreprises', 4, '2025-08-17 01:30:45'),
(5, 1, 'Validation entreprise #7', 'entreprises', 7, '2025-08-17 01:30:56'),
(6, 1, 'Validation entreprise #9', 'entreprises', 9, '2025-08-17 01:31:03'),
(7, 1, 'Validation entreprise #16', 'entreprises', 16, '2025-08-17 01:31:07'),
(8, 1, 'Validation entreprise #14', 'entreprises', 14, '2025-08-17 01:31:12'),
(9, 1, 'Validation entreprise #21', 'entreprises', 21, '2025-08-17 01:31:18'),
(10, 1, 'Ajout du secteur : logiciel', 'secteurs', 13, '2025-08-17 13:49:46'),
(11, 1, 'Modification du secteur : logiciel', 'secteurs', 13, '2025-08-17 14:46:26'),
(13, 1, 'Suppression du secteur : logiciel', 'secteurs', 13, '2025-08-17 14:48:31'),
(14, 1, 'Rejet de l&#039;entreprise', 'entreprises', 5, '2025-08-17 17:04:12'),
(15, 1, 'Rejet de l&#039;entreprise', 'entreprises', 31, '2025-08-18 06:30:40'),
(16, 1, 'Rejet de l&#039;entreprise #6', 'entreprises', 6, '2025-08-18 06:41:22'),
(23, NULL, 'Suppression du log #22', 'logs_actions', 22, '2025-08-18 09:42:47'),
(24, NULL, 'Suppression du log #21', 'logs_actions', 21, '2025-08-18 10:07:26'),
(27, NULL, 'Suppression du log #26', 'logs_actions', 26, '2025-08-18 10:21:20'),
(28, NULL, 'Suppression du log #18', 'logs_actions', 18, '2025-08-18 10:28:59'),
(29, NULL, 'Suppression du log #17', 'logs_actions', 17, '2025-08-18 10:29:19'),
(30, 1, 'Validation entreprise #33', 'entreprises', 33, '2025-08-19 03:40:11'),
(31, 1, 'Suspension de l&#039;entreprise : fghjk', 'entreprises', 33, '2025-08-19 03:47:16'),
(32, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 03:48:38'),
(33, 1, 'Suspension de l&#039;entreprise : ghjkl', 'entreprises', 33, '2025-08-19 03:50:04'),
(34, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 20:53:07'),
(35, 1, 'Suspension de l&#039;entreprise : dfghjk', 'entreprises', 33, '2025-08-19 20:57:24'),
(36, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 21:05:58'),
(37, 1, 'Suspension de l&#039;entreprise : sdfghjk', 'entreprises', 33, '2025-08-19 21:06:56'),
(38, 1, 'Modification entreprise', 'entreprises', 33, '2025-08-19 21:50:18'),
(39, 1, 'Modification entreprise', 'entreprises', 33, '2025-08-19 21:52:31'),
(40, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 21:56:42'),
(41, 1, 'Modification entreprise', 'entreprises', 33, '2025-08-19 22:01:36'),
(42, 1, 'Suspension de l&#039;entreprise : ghrf', 'entreprises', 33, '2025-08-19 22:08:44'),
(43, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 22:09:11'),
(44, 1, 'Suspension de l&#039;entreprise : sdfghqertyt', 'entreprises', 33, '2025-08-19 22:30:32'),
(45, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 22:33:18'),
(46, 1, 'Suspension de l&#039;entreprise : qsdfgh', 'entreprises', 33, '2025-08-19 22:36:21'),
(47, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 33, '2025-08-19 22:38:19'),
(48, 1, 'Rejet d entreprise #34', 'entreprises', 34, '2025-08-19 22:46:51'),
(49, 1, 'Validation entreprise #34', 'entreprises', 34, '2025-08-19 22:53:53'),
(50, 1, 'Suppression entreprise #34 : qsdfghjk', 'entreprises', 34, '2025-08-19 23:02:42'),
(51, 1, 'Suppression de l&#039;utilisateur', 'utilisateurs', 2, '2025-08-19 23:16:09'),
(52, 1, 'Suppression de l&#039;utilisateur', 'utilisateurs', 3, '2025-08-19 23:22:10'),
(53, 1, 'Suspension de l&#039;entreprise : dfgh', 'entreprises', 1, '2025-08-22 10:42:39'),
(54, 1, 'Réactivation de l&#039;entreprise : ', 'entreprises', 1, '2025-08-22 10:42:58'),
(55, 1, 'Modification entreprise', 'entreprises', 33, '2025-08-22 11:11:01'),
(56, 1, 'Modification du secteur : Agriculture', 'secteurs', 1, '2025-08-22 13:48:47'),
(57, 1, 'Modification de l&#039;utilisateur', 'utilisateurs', 4, '2025-08-22 14:09:36'),
(58, 1, 'Validation entreprise #35', 'entreprises', 35, '2025-08-23 18:56:37'),
(59, 1, 'Suppression entreprise #35 : SALUT', 'entreprises', 35, '2025-08-23 19:03:07'),
(60, 1, 'Validation entreprise #36', 'entreprises', 36, '2025-08-23 19:12:15'),
(61, 1, 'Suppression entreprise #36 : lolo', 'entreprises', 36, '2025-08-23 19:24:57'),
(62, 1, 'Validation entreprise #37', 'entreprises', 37, '2025-08-23 19:25:28'),
(63, 1, 'Suspension de l&#039;entreprise : lolo', 'entreprises', 33, '2025-08-23 19:25:47'),
(64, 1, 'Modification entreprise', 'entreprises', 37, '2025-08-23 19:33:37');

-- --------------------------------------------------------

--
-- Structure de la table `messages_contact`
--

CREATE TABLE `messages_contact` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sujet` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  `traite` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages_contact`
--

INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `traite`) VALUES
(1, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'dfghgfdfghgf', '2025-08-17 04:26:25', 1),
(2, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'dfghjkcvghjkdrftyui', '2025-08-22 09:05:33', 0),
(3, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'sdfghj', '2025-08-22 09:13:04', 0),
(4, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'sdfghjkdfghftyurty', '2025-08-22 09:17:46', 0),
(5, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'reiytytrtuiyidusytqdfsgjhkqsdfghjsdfgu', '2025-08-22 09:21:08', 0),
(6, 'FATOMBI', 'akomedi25yahoo@gmail.com', 'Demande', 'qsdfghjklmlkjhg', '2025-08-22 09:22:14', 0);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `entreprise_id` int DEFAULT NULL,
  `type` enum('validation','rejet','suspension','reactivation','suppression','modification') COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `entreprise_id`, `type`, `message`, `date_envoi`) VALUES
(1, 1, 'validation', 'Votre entreprise « AgroParakou » a été validée avec succès.', '2025-08-17 01:30:05'),
(2, 2, 'validation', 'Votre entreprise « TechNova » a été validée avec succès.', '2025-08-17 01:30:39'),
(3, 3, 'validation', 'Votre entreprise « École Horizon » a été validée avec succès.', '2025-08-17 01:30:42'),
(5, 7, 'validation', 'Votre entreprise « WebXpert » a été validée avec succès.', '2025-08-17 01:30:56'),
(7, 16, 'validation', 'Votre entreprise « Santé Express » a été validée avec succès.', '2025-08-17 01:31:07'),
(8, 14, 'validation', 'Votre entreprise « AgroPlus » a été validée avec succès.', '2025-08-17 01:31:12'),
(9, 21, 'validation', 'Votre entreprise « AssurBenin » a été validée avec succès.', '2025-08-17 01:31:18'),
(10, 5, 'rejet', 'Votre entreprise a été rejetée. Motif : sdfghjk', '2025-08-17 17:04:11'),
(11, 31, 'rejet', 'Votre entreprise a été rejetée. Motif : dfgh', '2025-08-18 06:30:40'),
(20, 33, 'validation', 'Votre entreprise « FATOMBI » a été validée avec succès.', '2025-08-19 03:40:11'),
(21, 33, 'suspension', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été suspendue temporairement.\n\nMotif : fghjk\n\nVous pouvez contacter l’équipe Eco Parakou pour plus d’informations.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 03:47:16'),
(22, 33, 'reactivation', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été réactivée avec succès.\n\nElle est désormais visible sur la plateforme Eco Parakou.\n\nMerci de votre confiance.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 03:48:38'),
(23, 33, 'suspension', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été suspendue temporairement.\n\nMotif : ghjkl\n\nVous pouvez contacter l’équipe Eco Parakou pour plus d’informations.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 03:50:03'),
(24, 33, 'reactivation', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été réactivée avec succès.\n\nElle est désormais visible sur la plateforme Eco Parakou.\n\nMerci de votre confiance.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 20:53:06'),
(25, 33, 'suspension', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été suspendue temporairement.\n\nMotif : dfghjk\n\nVous pouvez contacter l’équipe Eco Parakou pour plus d’informations.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 20:57:23'),
(26, 33, 'reactivation', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été réactivée avec succès.\n\nElle est désormais visible sur la plateforme Eco Parakou.\n\nMerci de votre confiance.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 21:05:57'),
(27, 33, 'suspension', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été suspendue temporairement.\n\nMotif : sdfghjk\n\nVous pouvez contacter l’équipe Eco Parakou pour plus d’informations.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 21:06:56'),
(28, 33, 'reactivation', 'Bonjour,\n\nVotre entreprise « FATOMBI » a été réactivée avec succès.\n\nElle est désormais visible sur la plateforme Eco Parakou.\n\nMerci de votre confiance.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 21:56:41'),
(29, 33, 'suspension', 'Bonjour,\n\nVotre entreprise « FATOMBI DEV » a été suspendue temporairement.\n\nMotif : ghrf\n\nVous pouvez contacter l’équipe Eco Parakou pour plus d’informations.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 22:08:43'),
(30, 33, 'reactivation', 'Bonjour,\n\nVotre entreprise « FATOMBI DEV » a été réactivée avec succès.\n\nElle est désormais visible sur la plateforme Eco Parakou.\n\nMerci de votre confiance.\n\nCordialement,\nL’équipe Eco Parakou', '2025-08-19 22:09:11'),
(31, 33, 'suspension', '\n      <h3>Bonjour FATOMBI DEV,</h3>\n      <p>Votre fiche entreprise a été suspendue temporairement.</p>\n      <p><strong>Motif :</strong> sdfghqertyt</p>\n      <p><strong>Date :</strong> 19/08/2025 21:30</p>\n      <p>Vous pouvez nous contacter pour plus d’informations.</p>\n      <br>\n      <p>— Equipe EcoParakou</p>\n      ', '2025-08-19 22:30:32'),
(32, 33, 'reactivation', '\n        <h3>Bonjour FATOMBI DEV,</h3>\n        <p>Votre entreprise a été réactivée avec succès.</p>\n        <p><strong>Date :</strong> 19/08/2025 - 21:33</p>\n        <p>Elle est désormais visible sur la plateforme Eco Parakou.</p>\n        <br>\n        <p>— Equipe EcoParakou</p>\n        ', '2025-08-19 22:33:18'),
(33, 33, 'suspension', '\n      <h3>Bonjour FATOMBI DEV,</h3>\n      <p>Votre entreprise a été suspendue temporairement sur EcoParakou.</p>\n      <p><strong>Motif :</strong> qsdfgh</p>\n      <p><strong>Date :</strong> 19/08/2025 - 21:36</p>\n      <p>Vous pouvez nous contacter pour plus d’informations.</p>\n      <br>\n      <p>— Equipe EcoParakou</p>\n      ', '2025-08-19 22:36:21'),
(34, 33, 'reactivation', '\n        <h3>Bonjour FATOMBI DEV,</h3>\n        <p>Votre entreprise a été réactivée avec succès.</p>\n        <p><strong>Date :</strong> 19/08/2025 - 21:38</p>\n        <p>Elle est désormais visible sur la plateforme Eco Parakou.</p>\n        <br>\n        <p>— Equipe EcoParakou</p>\n        ', '2025-08-19 22:38:19'),
(38, 1, 'suspension', '\n      <h3>Bonjour AgroParakou,</h3>\n      <p>Votre entreprise a été suspendue temporairement sur EcoParakou.</p>\n      <p><strong>Motif :</strong> dfgh</p>\n      <p><strong>Date :</strong> 22/08/2025 - 09:42</p>\n      <p>Vous pouvez nous contacter pour plus d’informations.</p>\n      <br>\n      <p>— Equipe EcoParakou</p>\n      ', '2025-08-22 10:42:39'),
(39, 1, 'reactivation', '\n        <h3>Bonjour AgroParakou,</h3>\n        <p>Votre entreprise a été réactivée avec succès.</p>\n        <p><strong>Date :</strong> 22/08/2025 - 09:42</p>\n        <p>Elle est désormais visible sur la plateforme Eco Parakou.</p>\n        <br>\n        <p>— Equipe EcoParakou</p>\n        ', '2025-08-22 10:42:58'),
(44, 37, 'validation', 'Votre entreprise « Light Innovation » a été validée avec succès.', '2025-08-23 19:25:22'),
(45, 33, 'suspension', '\n      <h3>Bonjour FATOMBI DEV,</h3>\n      <p>Votre entreprise a été suspendue temporairement sur EcoParakou.</p>\n      <p><strong>Motif :</strong> lolo</p>\n      <p><strong>Date :</strong> 23/08/2025 - 18:25</p>\n      <p>Vous pouvez nous contacter pour plus d’informations.</p>\n      <br>\n      <p>— Equipe EcoParakou</p>\n      ', '2025-08-23 19:25:46');

-- --------------------------------------------------------

--
-- Structure de la table `secteurs`
--

CREATE TABLE `secteurs` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `ordre` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `secteurs`
--

INSERT INTO `secteurs` (`id`, `nom`, `slug`, `description`, `ordre`) VALUES
(1, 'Agriculture', 'agriculture', 'Ensemble des activités liées à la culture des sols, à l’élevage, à la transformation des produits agricoles et à la sécurité alimentaire.', 1),
(2, 'Éducation', 'ducation', 'Système d’enseignement formel et informel, incluant les écoles, universités, formations professionnelles et initiatives d’alphabétisation.', 2),
(3, 'Santé', 'sante', 'Services médicaux, hôpitaux, cliniques, pharmacies, prévention sanitaire et initiatives de santé publique.', 3),
(4, 'Transport', 'transport', 'Infrastructure et services liés au déplacement de personnes et de marchandises : routes, véhicules, logistique, mobilité urbaine.', 4),
(5, 'Énergie', 'nergie', 'Production, distribution et gestion des ressources énergétiques : électricité, gaz, solaire, biomasse, etc.', 5),
(6, 'Technologie', 'technologie', 'Secteur dédié à l’innovation numérique : développement logiciel, télécommunications, IA, cybersécurité, électronique.', 6),
(7, 'Commerce', 'commerce', 'Activités de vente, distribution, import-export, marchés locaux, e-commerce et gestion des flux commerciaux.', 7),
(8, 'Finance', 'finance', 'Services bancaires, microfinance, assurances, comptabilité, investissements et gestion des risques économiques.', 8),
(9, 'Tourisme', 'tourisme', 'Promotion et gestion des activités touristiques : hébergement, restauration, patrimoine culturel, écotourisme.', 9),
(10, 'Artisanat', 'artisanat', 'Production manuelle et locale d’objets utilitaires ou décoratifs : textile, poterie, sculpture, bijouterie, etc.', 10),
(11, 'Immobilier', 'immobilier', 'Construction, vente, location et gestion de biens immobiliers résidentiels, commerciaux ou industriels.', 11),
(12, 'Environnement', 'environnement', 'Protection des ressources naturelles, gestion des déchets, reforestation, écologie urbaine et développement durable.', 12);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','moderateur') COLLATE utf8mb4_general_ci DEFAULT 'moderateur',
  `actif` tinyint(1) DEFAULT '1',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `dernier_acces` datetime DEFAULT NULL,
  `token_invitation` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `actif`, `date_creation`, `dernier_acces`, `token_invitation`) VALUES
(1, 'Akomedi', 'akomedi533@gmail.com', '$2y$10$vS2IkaKFbwXPiA7ijYRsQOmSrinsa1jMOAyk92K582yxscr9i1qGO', 'admin', 1, '2025-08-17 01:29:32', NULL, NULL),
(4, 'FATOMBI', 'akomedi25yahoo@gmail.com', '$2y$10$2nnxbGYYKZCvZedm9MfSxOARQOQSc3F2ZVgdTeKC4ftinEBY9jFia', 'admin', 1, '2025-08-19 23:22:20', NULL, NULL),
(5, 'Aicha', 'aichatoubiosanna46@gmail.com', '$2y$10$8Wc8OBtd6dGgvUAmZuk3o.YhL4zkk1qV04T25XGUy5EEJVSkQBb0.', 'admin', 1, '2025-08-22 16:00:12', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `secteur_id` (`secteur_id`),
  ADD KEY `modifie_par` (`modifie_par`);

--
-- Index pour la table `logs_actions`
--
ALTER TABLE `logs_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_entreprise` (`entreprise_id`);

--
-- Index pour la table `secteurs`
--
ALTER TABLE `secteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `token_invitation` (`token_invitation`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `logs_actions`
--
ALTER TABLE `logs_actions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `secteurs`
--
ALTER TABLE `secteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_ibfk_1` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entreprises_ibfk_2` FOREIGN KEY (`modifie_par`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `logs_actions`
--
ALTER TABLE `logs_actions`
  ADD CONSTRAINT `logs_actions_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_entreprise` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
