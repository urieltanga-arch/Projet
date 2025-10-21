-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mar. 21 oct. 2025 à 09:43
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
-- Base de données : `zeduc_db`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCommande` (IN `user_id` INT)   BEGIN
    DECLARE new_numero VARCHAR(255);
    DECLARE sequence_num INT;
    
    -- Trouver le dernier numéro de séquence pour aujourd'hui
    SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(numero_commande, '-', -1) AS UNSIGNED)), 0) + 1 
    INTO sequence_num 
    FROM commandes 
    WHERE numero_commande LIKE CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-%');
    
    -- Générer le nouveau numéro
    SET new_numero = CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(sequence_num, 4, '0'));
    
    -- Insérer la commande
    INSERT INTO commandes (user_id, numero_commande, created_at, updated_at) 
    VALUES (user_id, new_numero, NOW(), NOW());
    
    -- Retourner le numéro généré
    SELECT new_numero as commande_number;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCommandeItem` (IN `p_plat_id` INT, IN `p_commande_id` INT)   BEGIN
    DECLARE plat_price DECIMAL(10,2);
    
    -- Récupérer le prix actuel du plat
    SELECT price INTO plat_price FROM plats WHERE id = p_plat_id;
    
    -- Insérer avec le prix
    INSERT INTO commande_items (plat_id, commande_id, price, created_at, updated_at) 
    VALUES (p_plat_id, p_commande_id, plat_price, NOW(), NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-jemina@gmail.com|127.0.0.1', 'i:1;', 1761007990),
('laravel-cache-jemina@gmail.com|127.0.0.1:timer', 'i:1761007990;', 1761007990),
('laravel-cache-roy.spot27@gmail.com|127.0.0.1', 'i:1;', 1761004802),
('laravel-cache-roy.spot27@gmail.com|127.0.0.1:timer', 'i:1761004802;', 1761004802);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `numero_commande` varchar(255) DEFAULT NULL,
  `status` enum('en_attente','en_preparation','prete','en_livraison','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `mode_paiement` enum('especes','mobile_money','carte') DEFAULT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `adresse_livraison` text DEFAULT NULL,
  `telephone_contact` varchar(20) DEFAULT NULL,
  `instructions_speciales` text DEFAULT NULL,
  `preparation_debut` timestamp NULL DEFAULT NULL,
  `prete_a` timestamp NULL DEFAULT NULL,
  `livraison_debut` timestamp NULL DEFAULT NULL,
  `livree_a` timestamp NULL DEFAULT NULL,
  `annulee_a` timestamp NULL DEFAULT NULL,
  `annulee_par` bigint(20) UNSIGNED DEFAULT NULL,
  `raison_annulation` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `points_gagnes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `numero_commande`, `status`, `mode_paiement`, `montant_total`, `adresse_livraison`, `telephone_contact`, `instructions_speciales`, `preparation_debut`, `prete_a`, `livraison_debut`, `livree_a`, `annulee_a`, `annulee_par`, `raison_annulation`, `created_at`, `updated_at`, `points_gagnes`) VALUES
(1, 3, '1246', 'prete', NULL, 0.00, 'Madagascar, 1, impasse de Sanchez', '01 65 39 33 24', 'Praesentium sit nostrum quae.', '2025-10-12 21:31:57', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 21:26:57', '2025-10-20 22:13:23', 0),
(4, 3, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:36:50', '2025-10-20 20:36:50', 0),
(5, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:49:39', '2025-10-20 20:49:39', 0),
(6, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:51:06', '2025-10-20 20:51:06', 0),
(7, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:55:02', '2025-10-20 20:55:02', 0),
(8, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:58:08', '2025-10-20 20:58:08', 0),
(9, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 20:58:31', '2025-10-20 20:58:31', 0),
(10, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 21:01:17', '2025-10-20 21:01:17', 0),
(11, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 21:05:08', '2025-10-20 21:05:08', 0),
(12, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 21:12:17', '2025-10-20 21:12:17', 0),
(13, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 21:12:45', '2025-10-20 21:12:45', 0),
(14, 3, NULL, 'prete', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 21:13:17', '2025-10-20 22:13:29', 0),
(15, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 23:05:29', '2025-10-20 23:05:29', 0),
(16, 3, NULL, 'en_attente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-21 06:03:00', '2025-10-21 06:03:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `commande_items`
--

CREATE TABLE `commande_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commande_id` bigint(20) UNSIGNED NOT NULL,
  `plat_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande_items`
--

INSERT INTO `commande_items` (`id`, `commande_id`, `plat_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 7, 3, 1, NULL, '2025-10-20 20:55:02', '2025-10-20 20:55:02'),
(2, 7, 6, 1, NULL, '2025-10-20 20:55:02', '2025-10-20 20:55:02'),
(3, 8, 7, 1, NULL, '2025-10-20 20:58:08', '2025-10-20 20:58:08'),
(4, 9, 3, 1, NULL, '2025-10-20 20:58:31', '2025-10-20 20:58:31'),
(5, 10, 6, 1, NULL, '2025-10-20 21:01:17', '2025-10-20 21:01:17'),
(6, 11, 3, 1, NULL, '2025-10-20 21:05:08', '2025-10-20 21:05:08'),
(7, 12, 3, 1, NULL, '2025-10-20 21:12:17', '2025-10-20 21:12:17'),
(8, 13, 5, 1, NULL, '2025-10-20 21:12:45', '2025-10-20 21:12:45'),
(9, 14, 7, 1, NULL, '2025-10-20 21:13:17', '2025-10-20 21:13:17'),
(10, 15, 1, 1, NULL, '2025-10-20 23:05:29', '2025-10-20 23:05:29'),
(11, 16, 9, 1, NULL, '2025-10-21 06:03:00', '2025-10-21 06:03:00');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('karaoke','football','other') NOT NULL DEFAULT 'other',
  `event_date` datetime NOT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `current_participants` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `type`, `event_date`, `max_participants`, `current_participants`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Soirée Karaoké', 'Venez chanter et vous amuser avec nous !', 'karaoke', '2025-11-02 20:00:00', 50, 12, 1, '2025-10-19 10:01:26', '2025-10-19 10:01:26'),
(2, 'Match de Foot', 'Regardons ensemble le grand match sur écran géant', 'football', '2025-10-26 18:00:00', 100, 35, 1, '2025-10-19 10:01:26', '2025-10-19 10:01:26'),
(3, 'Soirée Dégustation', 'Découvrez nos nouveaux plats en avant-première', 'other', '2025-11-09 19:00:00', 30, 8, 1, '2025-10-19 10:01:26', '2025-10-19 10:01:26');

-- --------------------------------------------------------

--
-- Structure de la table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('registered','attended','cancelled') NOT NULL DEFAULT 'registered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `type` enum('earned','spent') NOT NULL DEFAULT 'earned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `loyalty_points`
--

INSERT INTO `loyalty_points` (`id`, `user_id`, `description`, `points`, `type`, `created_at`, `updated_at`) VALUES
(1, 3, 'Parrainage de Jemina', 100, 'earned', '2025-10-19 13:09:25', '2025-10-19 13:09:25'),
(2, 6, 'Parrainage de roy senghor', 100, 'earned', '2025-10-19 13:12:57', '2025-10-19 13:12:57'),
(3, 3, 'Bonus de bienvenue - parrainage', 50, 'earned', '2025-10-19 13:12:57', '2025-10-19 13:12:57'),
(4, 3, 'Parrainage de Uriel', 100, 'earned', '2025-10-20 22:45:26', '2025-10-20 22:45:26'),
(5, 8, 'Bonus de bienvenue - parrainage', 50, 'earned', '2025-10-20 22:45:26', '2025-10-20 22:45:26');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_18_153158_plats', 1),
(5, '2025_10_18_190121_loyalty_points', 2),
(6, '2025_10_18_190216_referrals', 2),
(7, '2025_10_18_190255_rewards', 2),
(8, '2025_10_18_190410_events', 2),
(9, '2025_10_18_190443_event_participants', 2),
(10, '2025_10_19_112229_add_referral_code_to_users_table', 3),
(11, '2025_10_19_113845_drop_loyalty_points', 4),
(12, '2025_10_19_132810_commandes', 5),
(13, '2025_10_19_134522_create_reclamations_table', 5),
(14, '2025_10_19_134533_create_notifications_table', 5),
(16, '2025_10_19_165931_commandes', 6),
(17, '2025_10_19_170401_commandes_items', 7),
(18, '2025_10_19_165305_commandes', 8),
(19, '2025_10_20_120915_create_carts_table', 8),
(20, '2025_10_20_120929_create_cart_items_table', 8);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plats`
--

CREATE TABLE `plats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `category` enum('plat','boisson','dessert') NOT NULL DEFAULT 'plat',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plats`
--

INSERT INTO `plats` (`id`, `name`, `description`, `price`, `category`, `image_url`, `created_at`, `updated_at`, `is_available`) VALUES
(1, 'Ndole', 'Plat traditionnel camerounais à base de feuilles de ndole, de viande et de crevettes.', 1000.00, 'plat', 'https://i0.wp.com/afrovitalityeats.com/wp-content/uploads/2018/06/Cameroon-Ndole-dish.jpg?resize=1200%2C1200&ssl=1', NULL, NULL, 1),
(2, 'Poulet Rôti', 'Poulet rôti aux herbes avec pommes de terre et légumes de saison', 1200.00, 'plat', 'https://img-3.journaldesfemmes.fr/bXkNDfcjEiK0tBVXS9k6K-2Y1vU=/750x500/3c4947934604405a830dab37ab6c172a/ccmcms-jdf/40007820.jpg', NULL, NULL, 1),
(3, 'Eru', 'plat traditionnel', 1000.00, 'plat', 'https://afrocuisine.co/wp-content/uploads/2022/04/eru.jpg', NULL, NULL, 1),
(4, 'Jus planet', 'boisson gazeuse gout orange', 500.00, 'boisson', 'https://www.easy-market.net/wp-content/uploads/2021/09/jus_planet_cocktail.jpg', NULL, NULL, 1),
(5, 'Eau Minérale', 'Eau minérale naturelle 1.5l', 500.00, 'boisson', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkmFwupQ7NdInJxPXuMlkvJrRxGkyr6snNAg&s', NULL, NULL, 1),
(6, 'Okok salé', 'Specialité camerounaise', 1000.00, 'plat', 'https://glance-magazine.com/wp-content/uploads/2024/10/Okok-800x445.jpg', '2025-10-20 15:11:12', '2025-10-20 15:11:12', 1),
(7, 'Poulet DG', 'Pour les amateurs de poulet et de plantain frits', 2500.00, 'plat', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS7tSqugAq0lcQ2cdYbOEgjJBD78wuRbTUuJg&s', '2025-10-20 15:17:14', '2025-10-20 15:17:14', 1),
(8, 'Foléré ', 'Boisson naturelle à base d osseille', 500.00, 'boisson', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7-43opHc3oo0JFE-25IFSRdyc5yM1y5Khvw&s', '2025-10-20 15:18:06', '2025-10-20 15:18:06', 0),
(9, 'Roti de porc', 'Délicieux plat à base de porc', 1000.00, 'plat', 'https://www.hervecuisine.com/wp-content/uploads/porc-caramel.jpg.webp', '2025-10-21 01:31:56', '2025-10-21 01:31:56', 1),
(10, 'Tiramissu', 'Dessert', 1500.00, 'dessert', 'https://img.cuisineaz.com/1024x768/2023/11/20/i196570-tiramisu-simple.jpg', '2025-10-21 06:16:39', '2025-10-21 06:16:39', 1);

-- --------------------------------------------------------

--
-- Structure de la table `reclamations`
--

CREATE TABLE `reclamations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `statut` enum('non_traitee','en_cours','resolue','fermee') NOT NULL DEFAULT 'non_traitee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referrer_id` bigint(20) UNSIGNED NOT NULL,
  `referred_id` bigint(20) UNSIGNED DEFAULT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `referrals`
--

INSERT INTO `referrals` (`id`, `referrer_id`, `referred_id`, `points_earned`, `created_at`, `updated_at`) VALUES
(1, 3, 6, 100, '2025-10-19 13:09:25', '2025-10-19 13:09:25'),
(2, 6, 3, 100, '2025-10-19 13:12:56', '2025-10-19 13:12:56'),
(3, 3, 8, 100, '2025-10-20 22:45:26', '2025-10-20 22:45:26');

-- --------------------------------------------------------

--
-- Structure de la table `rewards`
--

CREATE TABLE `rewards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `points_required` int(11) NOT NULL,
  `type` enum('free_drink','main_dish','discount') NOT NULL DEFAULT 'free_drink',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rewards`
--

INSERT INTO `rewards` (`id`, `name`, `description`, `points_required`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Boisson Gratuite', 'Obtenez une boisson offerte au restaurant', 500, 'free_drink', 1, NULL, NULL),
(2, 'Plat Principal', 'Un plat principal de votre choix offert', 1500, 'main_dish', 1, NULL, NULL),
(3, 'Réduction 10%', 'Réduction de 10% sur votre prochaine commande', 1000, 'discount', 1, NULL, NULL),
(4, 'Réduction 20%', 'Réduction de 20% sur votre prochaine commande', 2000, 'discount', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3lrdjKNzluytWfn9eIPolybGxQXwKIfMKWgaU20h', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUo3aHNab2dGMlJkcTY4ZFU0WlRadmthUThQc3ZsalFNbW1pdzR1cyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZW51Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1761031167);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('student','employee','manager','admin') NOT NULL DEFAULT 'student',
  `referral_code` varchar(255) DEFAULT NULL,
  `referred_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `referral_code`, `referred_by`, `total_points`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'roy senghor', 'roy.senghor@gmail.com', 'student', 'ROY.SENGHOR2025', 6, 150, NULL, '$2y$12$RDZGULJ2v3HmX19/Drw40eUqDlopNl5Nun0/rIQsxBpGtNmYu0CCa', 'WR71oxaCayv4AS5KQHw7Qab1Ifj5VEFQ0G6JSDhwJRvGpaBNniYuamJVVYTQ', NULL, '2025-10-20 22:45:26'),
(5, 'Test User', 'test@example.com', 'student', NULL, NULL, 0, '2025-10-19 12:56:38', '$2y$12$z2QvsCeuN.pptpk5BsnSwe6r/Cqn9A/baST9ei2dq8m27YxPGpz3K', 'tYWPtakeXS', '2025-10-19 12:56:39', '2025-10-19 12:56:39'),
(6, 'Jemina', 'jemina@gmail.cm', 'employee', '3E21C501', 3, 100, NULL, '$2y$12$Zc021SMPj0W.sVpOcAFU2uSEOKR.phi.gdKFuEcEw/wgDdMSsM13i', NULL, '2025-10-19 12:59:17', '2025-10-19 13:12:57'),
(8, 'Uriel', 'uriel@gmail.com', 'employee', NULL, 3, 50, NULL, '$2y$12$E/uHbF2oagqPamgmso239uWkHZf4YvVCfQMqb1l1h43WsPOa3q6I.', NULL, '2025-10-19 14:32:12', '2025-10-20 22:45:26'),
(9, 'employee', 'employee@order.cm', 'employee', NULL, NULL, 0, NULL, '$2y$12$E/uHbF2oagqPamgmso239uWkHZf4YvVCfQMqb1l1h43WsPOa3q6I.', NULL, '2025-10-19 14:44:27', '2025-10-19 14:44:27'),
(10, 'Ami', 'ami@gmail.com', 'student', '18BEEF95', NULL, 0, NULL, '$2y$12$V.B8kFWEWR5aPpUObDQQVOHDsqneY1j/BWg5wcheNVtcza2oG0EB6', NULL, '2025-10-20 08:03:02', '2025-10-20 08:03:02');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commandes_numero_unique` (`numero_commande`),
  ADD KEY `commandes_user_id_foreign` (`user_id`),
  ADD KEY `commandes_annulee_par_foreign` (`annulee_par`),
  ADD KEY `commandes_statut_index` (`status`),
  ADD KEY `commandes_created_at_index` (`created_at`),
  ADD KEY `commandes_numero_index` (`numero_commande`);

--
-- Index pour la table `commande_items`
--
ALTER TABLE `commande_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_items_commande_id_foreign` (`commande_id`),
  ADD KEY `commande_items_plat_id_foreign` (`plat_id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_participants_event_id_user_id_unique` (`event_id`,`user_id`),
  ADD KEY `event_participants_user_id_foreign` (`user_id`);

--
-- Index pour la table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_points_user_id_type_index` (`user_id`,`type`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `plats`
--
ALTER TABLE `plats`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reclamations`
--
ALTER TABLE `reclamations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `referrals_referrer_id_foreign` (`referrer_id`),
  ADD KEY `referrals_referred_id_foreign` (`referred_id`);

--
-- Index pour la table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_referral_code_index` (`referral_code`),
  ADD KEY `users_referred_by_index` (`referred_by`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `commande_items`
--
ALTER TABLE `commande_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plats`
--
ALTER TABLE `plats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reclamations`
--
ALTER TABLE `reclamations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_annulee_par_foreign` FOREIGN KEY (`annulee_par`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `commandes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande_items`
--
ALTER TABLE `commande_items`
  ADD CONSTRAINT `commande_items_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_items_plat_id_foreign` FOREIGN KEY (`plat_id`) REFERENCES `plats` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
