-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: test
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.25-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id_admin` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Super Admin','Layanan','Humas','Pengaduan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Layanan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `admin_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (2,'Super Administrator','superadmin','$2y$12$wXuAiAqNefyHbF9X1bZg3eKOO3.hy5YbQcreWSTyNqdXY9XB4EyKu','Super Admin','2026-01-14 03:56:16','2026-01-14 03:56:16'),(3,'Budi Santoso, S.Sos','petugas_layanan','$2y$12$Vf8nr0iIWHmsrGPalvnnGONWovVdi/QJmFa.Z8yKw55N2piytVkrS','Layanan','2026-01-14 06:53:03','2026-01-14 06:53:03'),(4,'Siti Nurhaliza, A.Md.IP','petugas_humas','$2y$12$750wTQceRp73TeIiGl.5TuVpEOP8sVNiNovRfM/Dpa8vu6MeDEy1a','Humas','2026-01-14 06:53:03','2026-01-14 06:53:03'),(5,'Andi Wijaya, S.H.','petugas_pengaduan','$2y$12$qlgIsfG84G476huaMnyXau/dXNyDky0gOzWMHxcd11ANtCDlDJr9.','Pengaduan','2026-01-14 06:53:03','2026-01-14 06:53:03');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;
