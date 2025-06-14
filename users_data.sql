-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `verification_code` varchar(255) DEFAULT NULL,
  `code_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Kashif','nimra3261@gmail.com','$2y$10$OxdspzyyAgB62ImBYdB8VOtiUs2cMDNbZ4IThqDDh1RYJMjrTFvMO',0,'hod','2025-06-10 03:13:11','2025-06-10 03:13:11',NULL,NULL,1,1,NULL,NULL),(2,'Amir','nimra3261@icloud.com','$2y$10$KyCPvfN8tpE1E3uTSE1rDOqHSmHjHxIOgzjM.N05w4PzDV2vg.9Ru',0,'dean','2025-06-10 03:13:11','2025-06-10 03:13:11',NULL,NULL,NULL,1,NULL,NULL),(3,'Mubasher','nimrak073@gmail.com','$2y$10$o6WoQX8dJhWjeZ7S89zviey1SO0qGdqPgIbnZqRmOJxa45OvnTPUK',0,'hr','2025-06-10 03:13:11','2025-06-10 03:13:11',NULL,NULL,NULL,1,NULL,NULL),(6,'Admin','admin@admin.com','$2y$10$4zz3gT3WtACICBazowoX2eAa8WHwot/E9WNP5xwHlG9df9eyP0dri',1,'admin','2025-06-10 03:13:12','2025-06-10 03:13:12',NULL,NULL,NULL,1,NULL,NULL),(7,'Admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1,'admin',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL),(8,'Muheeb','muheebahmed786786@gmail.com','$2y$10$/w.96tPy7l1BmlZ9z3c8f.BRz8LfKxn2/jT7Zk1xDs11zyy1GkNPe',0,'hod','2025-06-10 03:25:24','2025-06-11 04:10:56',NULL,NULL,4,1,NULL,NULL),(9,'muheeb','muheebahmed786@icloud.com','$2y$10$9wdADvmjMgwpqMlDpMVak.OtgZRzcEIhrfKbym5Mi05U4bG3LhyJu',0,'dean','2025-06-10 03:40:09','2025-06-11 04:06:28',NULL,NULL,NULL,1,NULL,NULL),(10,'Nimra','muheebahmed1833@gmail.com','$2y$10$M3VXHUIOEt8mePgu.nKf4ekGfz7Nn1e7uLJl3AwV8b91O8uGn8vWW',0,'hr','2025-06-10 03:40:48','2025-06-11 04:14:10',NULL,NULL,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-11 16:33:30
