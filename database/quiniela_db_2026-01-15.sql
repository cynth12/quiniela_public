# ************************************************************
# Antares - SQL Client
# Version 0.7.0
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: 127.0.0.1 (MySQL Community Server - GPL 8.0.41)
# Database: quiniela_db
# Generation time: 2026-01-15T14:57:23-05:00
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	("laravel-cache-gonzalez.cynth12@gmail.com|127.0.0.1", "i:1;", 1768503746),
	("laravel-cache-gonzalez.cynth12@gmail.com|127.0.0.1:timer", "i:1768503746;", 1768503746);

/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table cache_locks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





# Dump of table ganadores
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ganadores`;

CREATE TABLE `ganadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `quiniela_id` bigint unsigned NOT NULL,
  `jugador_id` bigint unsigned NOT NULL,
  `posicion` enum('primer','segundo','tercero') NOT NULL,
  `aciertos` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quiniela_id` (`quiniela_id`),
  KEY `jugador_id` (`jugador_id`),
  CONSTRAINT `ganadores_ibfk_1` FOREIGN KEY (`quiniela_id`) REFERENCES `quinielas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ganadores_ibfk_2` FOREIGN KEY (`jugador_id`) REFERENCES `jugadors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `ganadores` WRITE;
/*!40000 ALTER TABLE `ganadores` DISABLE KEYS */;

INSERT INTO `ganadores` (`id`, `numero`, `quiniela_id`, `jugador_id`, `posicion`, `aciertos`) VALUES
	(4, 6, 50, 41, "primer", 3),
	(5, 6, 51, 41, "tercero", 1),
	(6, 6, 53, 42, "tercero", 1),
	(7, 6, 54, 43, "primer", 3),
	(8, 6, 55, 44, "primer", 3),
	(9, 6, 56, 44, "primer", 3),
	(10, 6, 57, 45, "segundo", 2);

/*!40000 ALTER TABLE `ganadores` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table job_batches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





# Dump of table jornadas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jornadas`;

CREATE TABLE `jornadas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `fecha` date NOT NULL,
  `premio` decimal(10,2) NOT NULL,
  `cerrada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jornadas_numero_unique` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jornadas` WRITE;
/*!40000 ALTER TABLE `jornadas` DISABLE KEYS */;

INSERT INTO `jornadas` (`id`, `numero`, `fecha`, `premio`, `cerrada`, `created_at`, `updated_at`) VALUES
	(34, 6, "1925-08-30", 80000, 1, "2025-12-25 00:14:19", "2025-12-25 00:22:07"),
	(35, 3, "1925-08-30", 5000, 0, "2025-12-27 23:15:17", "2025-12-27 23:15:17"),
	(36, 7, "1984-12-13", 90000, 0, "2025-12-27 23:35:50", "2025-12-27 23:35:50");

/*!40000 ALTER TABLE `jornadas` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table jugadors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jugadors`;

CREATE TABLE `jugadors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pagada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jugadors` WRITE;
/*!40000 ALTER TABLE `jugadors` DISABLE KEYS */;

INSERT INTO `jugadors` (`id`, `nombre`, `telefono`, `created_at`, `updated_at`, `pagada`) VALUES
	(41, "cynthia", "9841312456", "2025-12-25 00:16:13", "2025-12-25 00:16:13", 0),
	(42, "sam", "766418393", "2025-12-25 00:17:20", "2025-12-25 00:17:20", 0),
	(43, "samantha", "766418393", "2025-12-25 00:17:20", "2025-12-25 00:17:20", 0),
	(44, "Sofia", "9871413267", "2025-12-25 00:17:42", "2025-12-25 00:17:42", 0),
	(45, "Sofia martinez", "9871413267", "2025-12-25 00:18:27", "2025-12-25 00:18:27", 0),
	(46, "Cynthia", "9841314567", "2025-12-25 00:38:42", "2025-12-25 00:38:42", 0),
	(47, "cynthia", "9841314389", "2025-12-25 00:48:37", "2025-12-25 00:48:37", 0),
	(48, "tyri", "9841312345", "2025-12-25 00:49:18", "2025-12-25 00:49:18", 0),
	(49, "cynthia", "9841314356", "2025-12-25 00:58:09", "2025-12-25 00:58:09", 0),
	(50, "Tryion Benjamin", "9841314389", "2025-12-25 20:57:28", "2025-12-25 20:57:28", 0),
	(51, "cynthia perez", "9841313457", "2025-12-25 22:59:35", "2025-12-25 22:59:35", 0),
	(52, "Yamima", "9841314389", "2025-12-27 23:16:41", "2025-12-27 23:16:41", 0),
	(53, "Ofe", "9841314389", "2025-12-27 23:34:23", "2025-12-27 23:34:23", 0),
	(54, "lyd", "9841314389", "2025-12-27 23:36:25", "2025-12-27 23:36:25", 0),
	(55, "topo", "9841314389", "2025-12-27 23:52:57", "2025-12-27 23:52:57", 0),
	(56, "DANIEL", "9841314567", "2025-12-27 23:53:49", "2025-12-27 23:53:49", 0),
	(57, "Tyri", "9841314389", "2025-12-27 23:56:50", "2025-12-27 23:56:50", 0),
	(58, "Gustavo", "9871432567", "2025-12-28 01:00:04", "2025-12-28 01:00:04", 0),
	(59, "Julio", "9871314389", "2025-12-28 02:08:22", "2025-12-28 02:08:22", 0),
	(60, "Ruben", "9841345678", "2025-12-28 02:19:29", "2025-12-28 02:19:29", 0);

/*!40000 ALTER TABLE `jugadors` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, "0001_01_01_000000_create_users_table", 1),
	(2, "0001_01_01_000001_create_cache_table", 1),
	(3, "0001_01_01_000002_create_jobs_table", 1),
	(4, "2025_11_13_044200_create_jugadors_table", 1),
	(5, "2025_11_13_062544_create_jugadores_table", 2),
	(6, "2025_11_13_062734_create_jornadas_table", 2),
	(7, "2025_11_13_062818_create_partidos_table", 2),
	(8, "2025_11_15_231215_create_resultados_table", 3),
	(9, "2025_11_17_015841_add_resultado_oficial_to_resultados_table", 4),
	(10, "2025_11_17_022744_update_partidos_table_structure", 5),
	(11, "2025_11_17_024655_make_numero_unique_in_jornadas_table", 6);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table pagos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pagos`;

CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jugador_id` bigint unsigned NOT NULL,
  `numero` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `comprobante_pdf` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jugador_id` (`jugador_id`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`jugador_id`) REFERENCES `jugadors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;





# Dump of table partidos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `partidos`;

CREATE TABLE `partidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` int unsigned DEFAULT NULL,
  `partido_numero` int unsigned DEFAULT NULL,
  `local` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visitante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `partidos` WRITE;
/*!40000 ALTER TABLE `partidos` DISABLE KEYS */;

INSERT INTO `partidos` (`id`, `numero`, `partido_numero`, `local`, `visitante`, `created_at`, `updated_at`) VALUES
	(77, 6, 1, "Monterrey", "Tigres", "2025-12-25 00:14:19", "2025-12-25 00:14:19"),
	(78, 6, 2, "Atlas", "América", "2025-12-25 00:14:19", "2025-12-25 00:14:19"),
	(79, 6, 3, "Cruz Azul", "Pumas", "2025-12-25 00:14:19", "2025-12-25 00:14:19"),
	(80, 3, 1, "Necaxa", "Juárez", "2025-12-27 23:15:17", "2025-12-27 23:15:17"),
	(81, 3, 2, "Atlético San Luis", "Cruz Azul", "2025-12-27 23:15:17", "2025-12-27 23:15:17"),
	(82, 3, 3, "Mazatlán", "Chivas", "2025-12-27 23:15:17", "2025-12-27 23:15:17"),
	(83, 7, 1, "Cruz Azul", "Pumas", "2025-12-27 23:35:50", "2025-12-27 23:35:50"),
	(84, 7, 2, "Tigres", "Toluca", "2025-12-27 23:35:50", "2025-12-27 23:35:50"),
	(85, 7, 3, "León", "Tijuana", "2025-12-27 23:35:50", "2025-12-27 23:35:50");

/*!40000 ALTER TABLE `partidos` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table password_reset_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





# Dump of table quinielas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `quinielas`;

CREATE TABLE `quinielas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jugador_id` bigint unsigned NOT NULL,
  `numero` int unsigned NOT NULL,
  `numero_quiniela` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_quiniela` (`numero_quiniela`),
  UNIQUE KEY `numero_quiniela_2` (`numero_quiniela`),
  UNIQUE KEY `numero_quiniela_3` (`numero_quiniela`),
  KEY `jugador_id` (`jugador_id`),
  CONSTRAINT `quinielas_ibfk_1` FOREIGN KEY (`jugador_id`) REFERENCES `jugadors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `quinielas` WRITE;
/*!40000 ALTER TABLE `quinielas` DISABLE KEYS */;

INSERT INTO `quinielas` (`id`, `jugador_id`, `numero`, `numero_quiniela`, `created_at`, `updated_at`) VALUES
	(50, 41, 6, "694c824d412f0", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(51, 41, 6, "694c824d4358e", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(52, 41, 6, "694c824d44e43", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(53, 42, 6, "694c8290efccd", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(54, 43, 6, "694c8290f1fe6", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(55, 44, 6, "694c82a6c819d", "2025-12-25 00:17:42", "2025-12-25 00:17:42"),
	(56, 44, 6, "694c82d33f36c", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(57, 45, 6, "694c82d34189e", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(58, 44, 6, "694c84b5b28f4", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(59, 45, 6, "694c84b5b4c3e", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(60, 44, 6, "694c84c244d58", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(61, 45, 6, "694c84c246a05", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(62, 44, 6, "694c84f8a21d7", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(63, 45, 6, "694c84f8a8a51", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(64, 46, 6, "694c87926945c", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(65, 46, 6, "694c87926ba2a", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(66, 47, 6, "694c89e54490e", "2025-12-25 00:48:37", "2025-12-25 00:48:37"),
	(67, 47, 6, "694c89eaceab6", "2025-12-25 00:48:42", "2025-12-25 00:48:42"),
	(68, 48, 6, "694c8a0e51508", "2025-12-25 00:49:18", "2025-12-25 00:49:18"),
	(69, 48, 6, "694c8a2a1c978", "2025-12-25 00:49:46", "2025-12-25 00:49:46"),
	(70, 48, 6, "694c8a2c610a3", "2025-12-25 00:49:48", "2025-12-25 00:49:48"),
	(71, 49, 6, "694c8c21dbdce", "2025-12-25 00:58:09", "2025-12-25 00:58:09"),
	(72, 50, 6, "694da5383d099", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(73, 50, 6, "694da53842436", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(74, 51, 6, "694dc1d7a61a5", "2025-12-25 22:59:35", "2025-12-25 22:59:35"),
	(75, 52, 3, "695068d915740", "2025-12-27 23:16:41", "2025-12-27 23:16:41"),
	(76, 53, 3, "69506cff1567d", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(77, 53, 3, "69506cff18d72", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(78, 54, 7, "69506d796e02b", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(79, 54, 7, "69506d7970b00", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(80, 55, 7, "69507159b0751", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(81, 55, 7, "69507159b389a", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(82, 56, 7, "6950718dcd1bc", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(83, 56, 7, "6950718dceddc", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(84, 56, 7, "695071afdd0c9", "2025-12-27 23:54:23", "2025-12-27 23:54:23"),
	(85, 57, 7, "6950724219b57", "2025-12-27 23:56:50", "2025-12-27 23:56:50"),
	(86, 58, 7, "69508114b1f22", "2025-12-28 01:00:04", "2025-12-28 01:00:04"),
	(87, 59, 7, "695091165f589", "2025-12-28 02:08:22", "2025-12-28 02:08:22"),
	(88, 59, 7, "695092b892955", "2025-12-28 02:15:20", "2025-12-28 02:15:20"),
	(89, 60, 7, "695093b1668ea", "2025-12-28 02:19:29", "2025-12-28 02:19:29");

/*!40000 ALTER TABLE `quinielas` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table respuestas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `respuestas`;

CREATE TABLE `respuestas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quiniela_id` bigint unsigned NOT NULL,
  `partido_numero` int unsigned NOT NULL,
  `respuesta` varchar(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiniela_id` (`quiniela_id`),
  CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`quiniela_id`) REFERENCES `quinielas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `respuestas` WRITE;
/*!40000 ALTER TABLE `respuestas` DISABLE KEYS */;

INSERT INTO `respuestas` (`id`, `quiniela_id`, `partido_numero`, `respuesta`, `created_at`, `updated_at`) VALUES
	(130, 50, 1, "L", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(131, 50, 2, "E", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(132, 50, 3, "L", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(133, 51, 1, "E", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(134, 51, 2, "E", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(135, 51, 3, "E", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(136, 52, 1, "V", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(137, 52, 2, "V", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(138, 52, 3, "E", "2025-12-25 00:16:13", "2025-12-25 00:16:13"),
	(139, 53, 1, "E", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(140, 53, 2, "E", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(141, 53, 3, "E", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(142, 54, 1, "L", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(143, 54, 2, "E", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(144, 54, 3, "L", "2025-12-25 00:17:20", "2025-12-25 00:17:20"),
	(145, 55, 1, "L", "2025-12-25 00:17:42", "2025-12-25 00:17:42"),
	(146, 55, 2, "E", "2025-12-25 00:17:42", "2025-12-25 00:17:42"),
	(147, 55, 3, "L", "2025-12-25 00:17:42", "2025-12-25 00:17:42"),
	(148, 56, 1, "L", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(149, 56, 2, "E", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(150, 56, 3, "L", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(151, 57, 1, "L", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(152, 57, 2, "L", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(153, 57, 3, "L", "2025-12-25 00:18:27", "2025-12-25 00:18:27"),
	(154, 58, 1, "L", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(155, 58, 2, "E", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(156, 58, 3, "L", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(157, 59, 1, "L", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(158, 59, 2, "L", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(159, 59, 3, "L", "2025-12-25 00:26:29", "2025-12-25 00:26:29"),
	(160, 60, 1, "L", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(161, 60, 2, "E", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(162, 60, 3, "L", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(163, 61, 1, "L", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(164, 61, 2, "L", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(165, 61, 3, "L", "2025-12-25 00:26:42", "2025-12-25 00:26:42"),
	(166, 62, 1, "L", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(167, 62, 2, "E", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(168, 62, 3, "L", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(169, 63, 1, "L", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(170, 63, 2, "L", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(171, 63, 3, "L", "2025-12-25 00:27:36", "2025-12-25 00:27:36"),
	(172, 64, 1, "L", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(173, 64, 2, "L", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(174, 64, 3, "L", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(175, 65, 1, "E", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(176, 65, 2, "E", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(177, 65, 3, "E", "2025-12-25 00:38:42", "2025-12-25 00:38:42"),
	(178, 66, 1, "L", "2025-12-25 00:48:37", "2025-12-25 00:48:37"),
	(179, 66, 2, "L", "2025-12-25 00:48:37", "2025-12-25 00:48:37"),
	(180, 66, 3, "L", "2025-12-25 00:48:37", "2025-12-25 00:48:37"),
	(181, 67, 1, "L", "2025-12-25 00:48:42", "2025-12-25 00:48:42"),
	(182, 67, 2, "L", "2025-12-25 00:48:42", "2025-12-25 00:48:42"),
	(183, 67, 3, "L", "2025-12-25 00:48:42", "2025-12-25 00:48:42"),
	(184, 68, 1, "L", "2025-12-25 00:49:18", "2025-12-25 00:49:18"),
	(185, 68, 2, "L", "2025-12-25 00:49:18", "2025-12-25 00:49:18"),
	(186, 68, 3, "L", "2025-12-25 00:49:18", "2025-12-25 00:49:18"),
	(187, 69, 1, "L", "2025-12-25 00:49:46", "2025-12-25 00:49:46"),
	(188, 69, 2, "L", "2025-12-25 00:49:46", "2025-12-25 00:49:46"),
	(189, 69, 3, "L", "2025-12-25 00:49:46", "2025-12-25 00:49:46"),
	(190, 70, 1, "L", "2025-12-25 00:49:48", "2025-12-25 00:49:48"),
	(191, 70, 2, "L", "2025-12-25 00:49:48", "2025-12-25 00:49:48"),
	(192, 70, 3, "L", "2025-12-25 00:49:48", "2025-12-25 00:49:48"),
	(193, 71, 1, "L", "2025-12-25 00:58:09", "2025-12-25 00:58:09"),
	(194, 71, 2, "L", "2025-12-25 00:58:09", "2025-12-25 00:58:09"),
	(195, 71, 3, "L", "2025-12-25 00:58:09", "2025-12-25 00:58:09"),
	(196, 72, 1, "L", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(197, 72, 2, "E", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(198, 72, 3, "L", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(199, 73, 1, "E", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(200, 73, 2, "E", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(201, 73, 3, "E", "2025-12-25 20:57:28", "2025-12-25 20:57:28"),
	(202, 74, 1, "E", "2025-12-25 22:59:35", "2025-12-25 22:59:35"),
	(203, 74, 2, "E", "2025-12-25 22:59:35", "2025-12-25 22:59:35"),
	(204, 74, 3, "E", "2025-12-25 22:59:35", "2025-12-25 22:59:35"),
	(205, 75, 1, "E", "2025-12-27 23:16:41", "2025-12-27 23:16:41"),
	(206, 75, 2, "E", "2025-12-27 23:16:41", "2025-12-27 23:16:41"),
	(207, 75, 3, "E", "2025-12-27 23:16:41", "2025-12-27 23:16:41"),
	(208, 76, 1, "L", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(209, 76, 2, "E", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(210, 76, 3, "L", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(211, 77, 1, "E", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(212, 77, 2, "E", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(213, 77, 3, "E", "2025-12-27 23:34:23", "2025-12-27 23:34:23"),
	(214, 78, 1, "L", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(215, 78, 2, "L", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(216, 78, 3, "L", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(217, 79, 1, "E", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(218, 79, 2, "E", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(219, 79, 3, "V", "2025-12-27 23:36:25", "2025-12-27 23:36:25"),
	(220, 80, 1, "L", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(221, 80, 2, "L", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(222, 80, 3, "L", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(223, 81, 1, "E", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(224, 81, 2, "E", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(225, 81, 3, "E", "2025-12-27 23:52:57", "2025-12-27 23:52:57"),
	(226, 82, 1, "L", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(227, 82, 2, "E", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(228, 82, 3, "L", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(229, 83, 1, "E", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(230, 83, 2, "E", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(231, 83, 3, "E", "2025-12-27 23:53:49", "2025-12-27 23:53:49"),
	(232, 84, 1, "E", "2025-12-27 23:54:23", "2025-12-27 23:54:23"),
	(233, 84, 2, "E", "2025-12-27 23:54:23", "2025-12-27 23:54:23"),
	(234, 84, 3, "E", "2025-12-27 23:54:23", "2025-12-27 23:54:23"),
	(235, 85, 1, "E", "2025-12-27 23:56:50", "2025-12-27 23:56:50"),
	(236, 85, 2, "E", "2025-12-27 23:56:50", "2025-12-27 23:56:50"),
	(237, 85, 3, "E", "2025-12-27 23:56:50", "2025-12-27 23:56:50"),
	(238, 86, 1, "E", "2025-12-28 01:00:04", "2025-12-28 01:00:04"),
	(239, 86, 2, "E", "2025-12-28 01:00:04", "2025-12-28 01:00:04"),
	(240, 86, 3, "E", "2025-12-28 01:00:04", "2025-12-28 01:00:04"),
	(241, 87, 1, "L", "2025-12-28 02:08:22", "2025-12-28 02:08:22"),
	(242, 87, 2, "L", "2025-12-28 02:08:22", "2025-12-28 02:08:22"),
	(243, 87, 3, "L", "2025-12-28 02:08:22", "2025-12-28 02:08:22"),
	(244, 88, 1, "E", "2025-12-28 02:15:20", "2025-12-28 02:15:20"),
	(245, 88, 2, "E", "2025-12-28 02:15:20", "2025-12-28 02:15:20"),
	(246, 88, 3, "E", "2025-12-28 02:15:20", "2025-12-28 02:15:20"),
	(247, 89, 1, "E", "2025-12-28 02:19:29", "2025-12-28 02:19:29"),
	(248, 89, 2, "E", "2025-12-28 02:19:29", "2025-12-28 02:19:29"),
	(249, 89, 3, "E", "2025-12-28 02:19:29", "2025-12-28 02:19:29");

/*!40000 ALTER TABLE `respuestas` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table resultados
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resultados`;

CREATE TABLE `resultados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` int unsigned NOT NULL,
  `partido_numero` int unsigned NOT NULL,
  `resultado_oficial` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `resultados` WRITE;
/*!40000 ALTER TABLE `resultados` DISABLE KEYS */;

INSERT INTO `resultados` (`id`, `numero`, `partido_numero`, `resultado_oficial`, `created_at`, `updated_at`) VALUES
	(37, 6, 1, "l", "2025-12-25 00:22:07", "2025-12-25 00:22:07"),
	(38, 6, 2, "e", "2025-12-25 00:22:07", "2025-12-25 00:22:07"),
	(39, 6, 3, "l", "2025-12-25 00:22:07", "2025-12-25 00:22:07");

/*!40000 ALTER TABLE `resultados` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	("tfzaPis12wkjOEA6vbvnRNgJt2GlEwKDIImtQoX6", 3, "127.0.0.1", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36", "YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTEdwaTJUc01mNW1OZlhQTHk5V2g0ZlR4dDk1aVdNV0xDM01yM1p3dCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9yZXN1bHRhZG9zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9", 1768503957);

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, "Cynthia Eileen", "ofechina@gmail.com", NULL, "$2y$12$pqkH9m9wau/puYkhF0c1FOQ6R8C/W3KwqatcRFgHvLaCvbJaCe04e", NULL, "2025-11-13 22:49:54", "2025-11-13 22:49:54"),
	(2, "Eileen", "tyrion@gmail.com", NULL, "$2y$12$lvq8CEWV65gU12Yx4Kqw2eVipu4hytxI7Duhr5fRDhxoF6on3A0oO", NULL, "2025-11-16 10:15:39", "2025-11-16 10:15:39"),
	(3, "Denis", "denis@gmail.com", NULL, "$2y$12$/6E8FxaX47FVmgUqWMsDJOi9rJJk7SmFSHPFHfQAk2yfEXfia6V/W", NULL, "2026-01-15 19:02:09", "2026-01-15 19:02:09");

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of views
# ------------------------------------------------------------

# Creating temporary tables to overcome VIEW dependency errors


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Dump completed on 2026-01-15T14:57:24-05:00
