-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 24, 2025 at 11:52 PM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssl_chile`
--
CREATE DATABASE IF NOT EXISTS `ssl_chile` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci;
USE `ssl_chile`;

-- --------------------------------------------------------

--
-- Table structure for table `app_config`
--

DROP TABLE IF EXISTS `app_config`;
CREATE TABLE IF NOT EXISTS `app_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mark` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `compilation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `author` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `released_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `goals` int NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_config`
--

INSERT INTO `app_config` (`id`, `mark`, `name`, `version`, `compilation`, `author`, `released_date`, `update_date`, `goals`, `created`, `last_update`) VALUES
(1, ' Southland Shipping Line (SSL)', 'Sistema Integral SSL.', '2.13.pbx', 'SSLPPR10.220525.022.', 'Diego Alvarado López.', '2025-05-01 13:38:36', '2025-05-22 23:38:50', 10, '2025-05-07 21:32:06', '2025-06-20 21:26:03');

-- --------------------------------------------------------

--
-- Table structure for table `app_international_chargue`
--

DROP TABLE IF EXISTS `app_international_chargue`;
CREATE TABLE IF NOT EXISTS `app_international_chargue` (
  `row_id` int NOT NULL AUTO_INCREMENT,
  `counter_vessel` smallint NOT NULL,
  `vessel_id` int NOT NULL,
  `car_plate` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `container` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `seal_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `guide_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `exporter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `pallets_quantity` int NOT NULL,
  `name_driver` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `cellphone_driver` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `digited_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`row_id`),
  KEY `vessel_id` (`vessel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_international_chargue`
--

INSERT INTO `app_international_chargue` (`row_id`, `counter_vessel`, `vessel_id`, `car_plate`, `container`, `seal_number`, `guide_number`, `exporter`, `pallets_quantity`, `name_driver`, `cellphone_driver`, `digited_by`, `created`, `last_update`) VALUES
(1, 3, 2, 'RFKV90', 'MNBU9114517', 'MLCL028', '21321', 'UNIFRUTTI', 20, 'DIEGO ALVARADO', '923816700', '18.923.079-6', '2025-06-09 16:46:56', '2025-06-09 16:46:56'),
(2, 1, 4, 'LZYL62', 'UETU6168056', 'MLCL028517', '1233', 'EXPORTADORA NORFRUT CHILE LTDA', 20, 'DIEGO', '923816700', '18.923.079-6', '2025-06-10 18:23:44', '2025-06-10 18:23:44'),
(3, 3, 2, 'TRJG10', 'MNBU9114517', 'M,LQWWQW', '321', 'UNIFRUTTI', 20, 'ALEJANDRO', '923816700', '18.923.079-6', '2025-06-12 18:26:30', '2025-06-12 18:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `app_outer_port`
--

DROP TABLE IF EXISTS `app_outer_port`;
CREATE TABLE IF NOT EXISTS `app_outer_port` (
  `row_id` int NOT NULL AUTO_INCREMENT,
  `counter_vessel` smallint NOT NULL COMMENT 'contador de camion por nave',
  `vessel_id` int NOT NULL,
  `car_plate` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `guide_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `container` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `seal_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `exporter` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `agency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `pallets_quantity` int NOT NULL,
  `cellphone_driver` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `arrival_date` datetime NOT NULL,
  `departure_date` datetime NOT NULL,
  `comodity` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `booking` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `stay` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `observations` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `origin` tinyint(1) NOT NULL COMMENT '1 => Contenedores, 2 => Termos',
  `created` datetime NOT NULL,
  `created_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL COMMENT 'rut de quien realizo el ingreso de contenedor o termo',
  PRIMARY KEY (`row_id`),
  KEY `vessel_name` (`vessel_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_outer_port`
--

INSERT INTO `app_outer_port` (`row_id`, `counter_vessel`, `vessel_id`, `car_plate`, `guide_number`, `container`, `seal_number`, `exporter`, `agency`, `pallets_quantity`, `cellphone_driver`, `arrival_date`, `departure_date`, `comodity`, `booking`, `stay`, `observations`, `origin`, `created`, `created_by`) VALUES
(2, 1, 2, 'TRJG10', '2165', 'UETU6168056', '32156', 'UNIFRUTTI', 'FY', 20, '923816700', '2025-05-03 22:11:00', '2025-05-16 00:06:50', 'USDA', 'SD545', 'NA', 'NA', 1, '2025-05-01 02:11:39', '18.923.079-6'),
(17, 1, 6, 'RFKV90', '554', 'N/A', 'N/A', 'SADAS', 'N/A', 22, '000000000', '2025-05-01 04:39:00', '2025-05-05 21:41:00', 'No Fumigado', 'NA', 'N/A', 'NA', 2, '2025-05-01 02:39:17', '18.923.079-6'),
(18, 2, 2, 'TRJG10', '25699', 'TCNU6369938', 'FX2366', 'EL CALVARIO', 'FY', 20, '923816700', '2025-05-01 13:57:00', '2025-05-01 18:01:00', 'USDA', 'MLSK2333', 'NA', 'NA', 1, '2025-05-01 13:57:17', '18.923.079-6'),
(19, 3, 2, 'RFKV90', '25699', 'N/A', 'N/A', 'UNIFRUTTI', 'N/A', 20, '000000000', '2025-05-03 01:07:00', '2025-05-03 04:13:00', 'USDA', '256987', 'NA', 'NA', 2, '2025-05-04 01:08:01', '18.923.079-6'),
(26, 4, 2, 'LZYL62', '2369', 'N/A', 'N/A', 'EL CALVARIO', 'N/A', 22, '000000000', '2025-05-05 21:37:00', '0000-00-00 00:00:00', 'No Fumigado', '25', 'NA', 'NA', 2, '2025-05-05 21:40:11', '18.923.079-6'),
(25, 2, 6, 'QWWQ65', '2563', 'N/A', 'N/A', 'UNIFRUTTI', 'N/A', 20, '000000000', '2025-05-04 01:13:00', '2025-05-04 01:13:00', 'No Fumigado', '132', 'NA', 'NA', 2, '2025-05-04 01:13:45', '18.923.079-6'),
(27, 5, 2, 'LZYL62', '2365', 'N/A', 'N/A', 'UNIFRUTTI', 'N/A', 22, '000000000', '2025-05-05 21:40:00', '0000-00-00 00:00:00', 'No Fumigado', '254', 'NA', 'NA', 2, '2025-05-05 21:40:50', '18.923.079-6'),
(28, 1, 5, 'JFTT63', '13508', 'MNBU9114517', 'MLCL0284223', 'EXPORTADORA NORFRUT CHILE LTDA', 'STEMBEX', 20, '966923761', '2025-05-12 20:43:00', '2025-05-12 20:48:00', 'NO FUMIGADO', '253484640', 'NA', 'NA', 1, '2025-05-13 12:06:52', '18.923.079-6'),
(29, 1, 6, 'RFKV90', '32423', 'UETU6168056', '2323', 'UNIFRUTTI', 'FY', 20, '9238u16700', '2025-05-23 15:59:00', '0000-00-00 00:00:00', 'USDA', 'FX43544', 'NA', 'NA', 1, '2025-05-23 15:59:00', '18.923.079-6'),
(30, 2, 6, 'RFKV90', '345345', 'UETU6168056', 'XF033343', 'UNIFRUTTI', 'FY', 20, '923816700', '2025-05-28 22:01:00', '0000-00-00 00:00:00', 'USDA', 'DQ43', 'NA', 'NA', 1, '2025-05-28 22:01:00', '18.923.079-6'),
(31, 1, 2, 'JFTT63', '2165', 'UETU6168056', '32156', 'UNIFRUTTI', 'FY', 20, '923816700', '2025-05-03 22:11:00', '2025-06-02 15:28:00', 'USDA', 'SD545', 'NA', 'NA', 1, '2025-05-03 22:11:00', '18.923.079-6'),
(32, 2, 2, 'TRJG10', '5435', 'UETU6168056', '543T5', 'UNIFRUTTI', 'FY', 20, '999999999', '2025-06-02 12:34:00', '0000-00-00 00:00:00', 'USDA', '435', 'NA', 'NA', 1, '2025-06-02 12:34:00', '18.923.079-6');

-- --------------------------------------------------------

--
-- Table structure for table `app_ports`
--

DROP TABLE IF EXISTS `app_ports`;
CREATE TABLE IF NOT EXISTS `app_ports` (
  `port_id` int NOT NULL AUTO_INCREMENT,
  `city` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `country` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`port_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_ports`
--

INSERT INTO `app_ports` (`port_id`, `city`, `country`, `created`, `last_update`) VALUES
(3, 'Los Angeles', 'Estados Unidos', '2025-05-03 00:54:39', '2025-05-03 22:35:38'),
(4, 'Colon', 'Panama', '2025-05-03 03:19:08', '2025-06-12 20:33:24'),
(6, 'Flussing', 'Países Bajos', '2025-05-03 23:34:09', '2025-06-20 22:16:41'),
(11, 'Long Beach', 'Estados Unidos', '2025-05-04 01:39:35', '2025-05-04 01:39:35'),
(12, 'Filadelfia', 'Estados Unidos', '2025-05-13 11:56:26', '2025-05-13 11:56:26'),
(16, 'Coquimbo', 'Chile', '2025-06-11 00:16:59', '2025-06-11 00:16:59'),
(19, 'Rodman', 'Panama', '2025-06-12 20:23:43', '2025-06-12 20:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `app_ships`
--

DROP TABLE IF EXISTS `app_ships`;
CREATE TABLE IF NOT EXISTS `app_ships` (
  `ship_id` int NOT NULL AUTO_INCREMENT,
  `vessel_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `ship_line` int NOT NULL,
  `voyage` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `port_discharge` int NOT NULL,
  `finished` tinyint NOT NULL COMMENT 'indica si el emabrque de la motonave finalizo',
  `finished_date` datetime NOT NULL COMMENT 'fecha de finalización de embarque',
  `eta` datetime NOT NULL,
  `etd` datetime NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ship_id`),
  KEY `port_discharge` (`port_discharge`),
  KEY `vessel_line` (`ship_line`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_ships`
--

INSERT INTO `app_ships` (`ship_id`, `vessel_name`, `ship_line`, `voyage`, `port_discharge`, `finished`, `finished_date`, `eta`, `etd`, `created`, `last_update`) VALUES
(1, 'CS STRATOS', 2, 'WC-151', 4, 1, '2025-06-16 17:19:42', '2025-04-28 22:33:00', '2025-05-02 03:33:00', '2025-05-01 00:07:15', '2025-06-16 17:19:42'),
(2, 'POLAR CHILE', 2, 'EC-256', 3, 0, '0000-00-00 00:00:00', '2025-05-03 22:52:00', '2025-05-03 00:52:00', '2025-05-01 00:07:15', '2025-05-03 23:33:37'),
(4, 'JORGEN REEFER', 2, 'WC-520', 6, 0, '0000-00-00 00:00:00', '2025-05-03 00:47:00', '2025-05-06 05:53:00', '2025-05-03 00:47:43', '2025-05-04 01:40:04'),
(5, 'POLAR PERU', 6, '518N', 12, 0, '0000-00-00 00:00:00', '2025-05-12 11:57:00', '2025-05-13 11:57:00', '2025-05-13 11:58:06', '2025-05-13 11:58:06'),
(6, 'POLAR CHILE', 6, '519S', 4, 0, '0000-00-00 00:00:00', '2025-05-27 06:00:00', '2025-05-30 23:00:00', '2025-05-23 11:49:11', '2025-06-16 17:27:33');

-- --------------------------------------------------------

--
-- Table structure for table `app_ship_lines`
--

DROP TABLE IF EXISTS `app_ship_lines`;
CREATE TABLE IF NOT EXISTS `app_ship_lines` (
  `line_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`line_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_ship_lines`
--

INSERT INTO `app_ship_lines` (`line_id`, `name`, `created`, `last_update`) VALUES
(6, 'MAERSK', '2025-05-13 11:56:52', '2025-05-13 11:56:52'),
(2, 'COOL CARRIERS', '2025-05-03 02:33:31', '2025-05-03 22:36:16'),
(5, 'MEDITERREAN SHIPPING COMPANY (MSC)', '2025-05-03 23:33:11', '2025-05-03 23:33:11'),
(9, 'GLOBAL REEFERS', '2025-05-28 22:11:54', '2025-05-28 22:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `app_tracking`
--

DROP TABLE IF EXISTS `app_tracking`;
CREATE TABLE IF NOT EXISTS `app_tracking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_chargue` int NOT NULL,
  `status` int NOT NULL,
  `status_date` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_charge` (`id_chargue`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `app_tracking`
--

INSERT INTO `app_tracking` (`id`, `id_chargue`, `status`, `status_date`, `created`) VALUES
(5, 1, 2, '2025-06-10 22:53:40', '2025-06-10 22:53:40'),
(3, 2, 0, '2025-06-10 18:23:44', '2025-06-10 18:23:44'),
(4, 1, 1, '2025-06-10 22:53:13', '2025-06-10 22:53:13'),
(6, 3, 0, '2025-06-12 18:26:30', '2025-06-12 18:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

DROP TABLE IF EXISTS `app_users`;
CREATE TABLE IF NOT EXISTS `app_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `run` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `last_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `division` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL COMMENT 'Indica si pertenece a SSL o Portal',
  `last_session` datetime NOT NULL,
  `reset_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `token_expiration` datetime NOT NULL,
  `created` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci COMMENT='Tabla de usuarios';

--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`user_id`, `run`, `name`, `last_name`, `email`, `password`, `division`, `last_session`, `reset_token`, `token_expiration`, `created`, `last_update`) VALUES
(15, '18.923.079-6', 'Diego', 'Alvarado', 'diego.alvaraado@gmail.com', '$2y$10$pvjqJoc/n9fcIh4vxKQcyeuOHMzabq1L9KIRaYhPLpIKdbHDmU23W', 'ssl', '2025-06-23 12:00:54', '64f7a28d4ce895eb30f97c7630cb2b54', '2025-05-12 13:33:16', '2025-05-06 23:20:44', '2025-05-07 21:05:00'),
(46, '9.573.778-1', 'fdgfdg', 'dfgdfg', 'dfgdfgdfg@cl.cl', '$2y$10$Uxi9AqbGm9hGJb4c6e5aU.9m58tNGrJDdA61TGeJalUZ7V9Yq.6RO', 'ssl', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2025-05-06 23:20:51', '2025-05-06 23:20:53'),
(53, '10.764.100-9', 'Alejandro', 'Alvarado', 'dalvarado@gotruck.cl', '$2y$10$1ADhGhSHkAZKf4izX1KOQeGei2dxI2GmeghyaB4AbshZEET10WsV2', 'terminal', '2025-06-20 21:11:11', 'e83b80b36d6fbbf4667ea3b63be562d5', '2025-05-09 01:00:11', '2025-05-07 23:56:51', '2025-05-07 23:56:51');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
