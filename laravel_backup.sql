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
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hod_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`),
  KEY `departments_hod_id_foreign` (`hod_id`),
  CONSTRAINT `departments_hod_id_foreign` FOREIGN KEY (`hod_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Computer Science',NULL,NULL,NULL,NULL),(2,'Electrical Engineering',NULL,NULL,'2025-06-10 03:42:25',NULL),(3,'Business Administration',NULL,NULL,NULL,NULL),(4,'Robotics',NULL,NULL,'2025-06-10 03:25:24',8);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_applications`
--

DROP TABLE IF EXISTS `job_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_posting_id` bigint(20) unsigned DEFAULT NULL,
  `job_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `resume_path` varchar(255) NOT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Applied',
  `education` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `match_percentage` double(8,2) DEFAULT NULL,
  `missing_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`missing_keywords`)),
  `profile_summary` text DEFAULT NULL,
  `is_ranked` tinyint(1) NOT NULL DEFAULT 0,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `interview_location` varchar(255) DEFAULT NULL,
  `hod_feedback` text DEFAULT NULL,
  `hr_feedback` text DEFAULT NULL,
  `interview_instructions` text DEFAULT NULL,
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `interview_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_applications_job_id_foreign` (`job_id`),
  CONSTRAINT `job_applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_applications`
--

LOCK TABLES `job_applications` WRITE;
/*!40000 ALTER TABLE `job_applications` DISABLE KEYS */;
INSERT INTO `job_applications` VALUES (1,NULL,1,'Test Applicant','test@example.com','1234567890','resumes/test-resume.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 03:13:12','2025-06-10 03:13:12',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,3,3,'muheeb','muheebahmed1833@gmail.com','03313365533','resumes/Y2KnN4NI9nuKnYPXRnlomRtV6YQdhL5diQgLC2yk.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 03:45:49','2025-06-10 03:45:49',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,3,3,'Umer ali','muheebahmed786@icloud.com','03354964112','resumes/vpTCi0eZnmXGfUpWuufeyr7ale2kWOLlFwK2mPfu.pdf',NULL,NULL,'Rejected',NULL,NULL,NULL,'2025-06-10 04:17:23','2025-06-10 04:30:50',20.00,'[\"Masters\",\"Lab Attendant\",\"CS\"]','Muheeb Ahmed is a professional with experience in e-commerce management, customer service, and data transcribing. He is currently pursuing a Bachelors in Artificial Intelligence and has demonstrated technical skills in Python and C++. However, his education and experience do not align well with the job description of a DSA Lab Attendant requiring a Masters in AI or CS.',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,4,4,'Ahmed','muheebahmed786@icloud.com','03313365533','resumes/xY2JJ6q9bm6iFXijaWWQP7rZSr6tUaG7qtl3BW7I.pdf',NULL,NULL,'Interview Scheduled',NULL,NULL,NULL,'2025-06-10 04:59:29','2025-06-10 12:56:45',20.00,'[\"Masters in CS,AI,SE\",\"2 years of teaching experience\"]','Muheeb Ahmed is a professional with experience in e-commerce management and customer service. Has demonstrated skills in Python and C++, and is currently pursuing a Bachelors in Artificial Intelligence. However, the candidate lacks the required Masters degree in Computer Science, Artificial Intelligence, or Software Engineering, and does not have the necessary 2 years of teaching experience for the DSA Teacher position.',1,'2025-06-14','10:30:00','ORIC Hall',NULL,NULL,'None','2025-06-10 12:56:45',NULL,NULL,NULL,NULL,NULL),(5,5,5,'muheeb','muheebahmed1833@gmail.com','03354964112','resumes/SDaCJIfmQlX0NJxWLgqeYl2i7BbARIncfW09Iszn.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 13:02:44','2025-06-10 13:12:32',0.00,'[\"N\\/A\"]','Muheeb Ahmed is an individual with a background in e-commerce management and customer service, with experience in transcription. They have demonstrated skills in Python and C++, as well as debugging. They are currently pursuing a Bachelor\'s in Artificial Intelligence at Szabist University. They have strong interpersonal skills and a proven track record of dependability. They are also a team player who learns from failures. However, without a specific job description and requirements, it\'s impossible to determine how well their skills and experience match.',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(6,5,5,'muheeb','muheebahmed1833@gmail.com','03354964112','resumes/3vla7aHQjpzXyj0NYktrDiMebw1FSK1EhE4Tbyds.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 13:04:26','2025-06-10 13:04:45',0.00,'\"Not Applicable\"','Muheeb Ahmed is a professional with experience in e-commerce management and transcription. He has handled eBay, Amazon, and Onbuy accounts and has a strong background in customer service. He has skills in Python and C++, and is also experienced in debugging. He is currently pursuing a Bachelor\'s degree in Artificial Intelligence. He is a team player, dependable, and has strong interpersonal skills. He has full professional proficiency in English and Urdu.',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,5,5,'muheeb','hod@example.com','03354964112','resumes/QIga1vPp0ZWNTli1CcsuIbzWrkag86SS6MfAw4QU.pdf',NULL,NULL,'Interview Scheduled',NULL,NULL,NULL,'2025-06-10 13:06:55','2025-06-10 13:18:01',0.00,'[\"any\"]','Muheeb Ahmed is an individual with a diverse set of skills and experiences. He has worked as an E-Commerce Manager and a Customer Service Representative / Transcriber, demonstrating skills in order processing, account health maintenance, data transcribing, and transcription review and editing. He is also pursuing a Bachelors in Artificial Intelligence and showcases skills in Python and C++. His interpersonal skills, dependability, and team-player attitude are also notable. However, without specific job requirements, it\'s hard to determine how well his skills and experiences match a potential job.',1,'2025-06-12','10:30:00','ORIC Hall','Accepted by HOD',NULL,'Bring cv','2025-06-10 13:18:01',NULL,NULL,NULL,NULL,NULL),(8,NULL,7,'Umer ali','admin@admin.com','03105495554','resumes/3NJegQvAIZyMp2FqFqgycI6NE13ZOF6SPielDNtt.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 14:17:52','2025-06-10 14:17:59',70.00,'[\"Mysql\"]','Muheeb Ahmed is a highly energetic individual with a Bachelor\'s degree in Artificial Intelligence and experience in e-commerce management and customer service representation. He has a proven track record of handling accounts on e-commerce platforms such as eBay, Amazon, and Onbuy, and experience in data transcribing. He has also demonstrated skills in Python and C++, but lacks experience with MySQL as required by the job description. He is a team player and is known for his dependability and interpersonal skills. He is fluent in both English and Urdu.',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,NULL,7,'Muheeb','ahmed1@gmail.com','03105495554','resumes/c1sxU00VOgRD8fkPjQ1p17MqMINKXNQZv0SYgHUK.pdf',NULL,NULL,'Applied',NULL,NULL,NULL,'2025-06-10 14:19:48','2025-06-10 14:19:54',70.00,'[\"Mysql\"]','Muheeb Ahmed is a strong candidate for the Lab Attendant position. He possesses a Bachelors degree in Artificial Intelligence from Szabist University and has skills in C++ and Python, which are required for the role. His experience as an E-Commerce Manager and Customer Service Representative/Transcriber demonstrate his ability to manage multiple tasks, work collaboratively, and handle data effectively. However, he does not mention any experience or skills in Mysql, which is a requirement for the role. His language proficiency in English and Urdu could be an additional asset in diverse working environments.',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,7,7,'Haseeb','has123@gmail.com','03344556674','resumes/rL3a02pSgYbghH1KQeaXsuDPzvfonbRuWYBHr9rX.pdf',NULL,NULL,'Interview Scheduled',NULL,NULL,NULL,'2025-06-10 14:26:28','2025-06-10 14:30:16',80.00,'[\"Mysql\"]','Muheeb Ahmed is a highly energetic professional with a Bachelor\'s degree in Artificial Intelligence. He has experience in e-commerce management, customer service, and data transcribing, demonstrating his strong interpersonal skills. Muheeb is also skilled in Python and C++, which are required for the Lab Attendant position. However, his resume lacks evidence of experience or skills in MySQL, which is a requirement for this role. Further, his job experience does not align perfectly with the technical demands of the Lab Attendant position as it is more customer service and e-commerce oriented.',1,'2025-06-12','10:00:00','ORIC Hall','Accepted by HOD',NULL,'Bring cv','2025-06-10 14:30:16',NULL,NULL,NULL,NULL,NULL),(11,8,8,'Haseeb Ahmed','haseeb123@gmail.com','03105495554','resumes/2oWPGNLmo5s4Vmow6hDIb9kcuber4FwR1wYsruFZ.pdf',NULL,NULL,'Interview Scheduled',NULL,NULL,NULL,'2025-06-11 04:08:45','2025-06-11 04:12:48',40.00,'[\"Machine Learning\",\"Teaching Experience\"]','Muheeb Ahmed holds a Bachelor\'s degree in Artificial Intelligence and possesses skills in both Python and C++. However, he lacks experience in teaching and machine learning, which are key requirements for the job description. His professional experience mainly revolves around e-commerce management and transcription services. Additionally, he demonstrates skills in event management and fluency in English and Urdu.',1,'2025-06-12','10:30:00','ORIC Hall','Accepted by HOD',NULL,'Bring two copies of your cv','2025-06-11 04:12:48',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `job_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_listings`
--

DROP TABLE IF EXISTS `job_listings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_listings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `department_id` bigint(20) unsigned NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'full-time',
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `posted_at` date DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_listings_department_id_foreign` (`department_id`),
  CONSTRAINT `job_listings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_listings`
--

LOCK TABLES `job_listings` WRITE;
/*!40000 ALTER TABLE `job_listings` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_listings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_postings`
--

DROP TABLE IF EXISTS `job_postings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_postings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `department` varchar(255) DEFAULT NULL,
  `job_request_id` bigint(20) unsigned DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_postings_job_request_id_foreign` (`job_request_id`),
  CONSTRAINT `job_postings_job_request_id_foreign` FOREIGN KEY (`job_request_id`) REFERENCES `job_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_postings`
--

LOCK TABLES `job_postings` WRITE;
/*!40000 ALTER TABLE `job_postings` DISABLE KEYS */;
INSERT INTO `job_postings` VALUES (1,'Assistant Professor - Computer Science',NULL,'Teach and guide students in the Computer Science department.','PhD in Computer Science or related field, teaching experience preferred.','pending','Computer Science',1,'2025-06-10 03:13:12','2025-06-10 03:13:12','2025-06-10 03:13:12',NULL),(2,'Research Assistant in AI',NULL,'Support AI research projects in the CS department.','Master\'s degree in CS, experience in machine learning research.','pending','Computer Science',2,'2025-06-10 03:13:12','2025-06-10 03:13:12','2025-06-10 03:13:12',NULL),(3,'Junior Lecturer',NULL,'DSA LAB attendant required','Masters in BS AI, CS','Closed',NULL,3,'2025-06-10 03:44:40','2025-06-10 03:44:40','2025-06-10 04:37:59',NULL),(4,'Professor',NULL,'DSA Teacher required','Masters in CS,AI,SE with 2 years of teaching experience','Active',NULL,4,'2025-06-10 04:58:22','2025-06-10 04:58:22','2025-06-10 04:58:22',NULL),(5,'Junior Lecturer',NULL,'any','any','Active',NULL,5,'2025-06-10 13:01:11','2025-06-10 13:01:11','2025-06-10 13:01:11',NULL),(7,'Junior Lecturer',NULL,'Lab Attendant Required with skills in C++ and Python','Bachelors in AI,CS,SE\r\nSkills in C++, Python, Mysql','Active',NULL,6,'2025-06-10 14:09:29','2025-06-10 14:09:29','2025-06-10 14:09:29',NULL),(8,'Assistant Professor',NULL,'Professor required for teaching machine learning course.','Bachelors in AI, Datascience, CS , SE\r\nStrong skill set in C++ And python','Active',NULL,7,'2025-06-11 04:07:37','2025-06-11 04:07:37','2025-06-11 04:07:37',NULL);
/*!40000 ALTER TABLE `job_postings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_requests`
--

DROP TABLE IF EXISTS `job_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint(20) unsigned NOT NULL,
  `hod_id` bigint(20) unsigned NOT NULL,
  `dean_id` bigint(20) unsigned DEFAULT NULL,
  `hr_id` bigint(20) unsigned DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `justification` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `rejection_comment` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `approved_by_dean_at` timestamp NULL DEFAULT NULL,
  `posted_by_hr_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rejected_by_dean_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_requests_department_id_foreign` (`department_id`),
  KEY `job_requests_hod_id_foreign` (`hod_id`),
  KEY `job_requests_hr_id_foreign` (`hr_id`),
  KEY `job_requests_dean_id_foreign` (`dean_id`),
  CONSTRAINT `job_requests_dean_id_foreign` FOREIGN KEY (`dean_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `job_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_requests_hod_id_foreign` FOREIGN KEY (`hod_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_requests_hr_id_foreign` FOREIGN KEY (`hr_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_requests`
--

LOCK TABLES `job_requests` WRITE;
/*!40000 ALTER TABLE `job_requests` DISABLE KEYS */;
INSERT INTO `job_requests` VALUES (1,1,1,NULL,10,'Assistant Professor','Seeking a individual teach in Computer Science.',NULL,NULL,NULL,'Posted by HR','2025-06-10 03:13:12','2025-06-10 13:01:31','2025-06-10 03:13:12','2025-06-10 13:01:31',NULL),(2,1,1,NULL,NULL,'Research Assistant','Looking for a Research ',NULL,NULL,'Not required','Rejected by Dean','2025-06-10 03:13:12','2025-06-10 03:13:12','2025-06-10 03:13:12','2025-06-10 04:57:32','2025-06-10 04:57:32'),(3,4,8,NULL,10,'Junior Lecturer','DSA LAB attendant required','Vacancy Opening','Masters in BS AI, CS',NULL,'Posted by HR','2025-06-10 03:43:23','2025-06-10 03:44:40','2025-06-10 03:31:10','2025-06-10 03:44:40',NULL),(4,4,8,NULL,10,'Professor','DSA Teacher required','Vacancy','Masters in CS,AI,SE with 2 years of teaching experience',NULL,'Posted by HR','2025-06-10 04:57:34','2025-06-10 04:58:22','2025-06-10 04:56:02','2025-06-10 04:58:22',NULL),(5,4,8,NULL,10,'Junior Lecturer','any','any','any',NULL,'Posted by HR','2025-06-10 12:54:50','2025-06-10 13:01:11','2025-06-10 12:54:09','2025-06-10 13:01:11',NULL),(6,4,8,NULL,10,'Junior Lecturer','Lab Attendant Required with skills in C++ and Python','Vacancy opening','Bachelors in AI,CS,SE\r\nSkills in C++, Python, Mysql',NULL,'Posted by HR','2025-06-10 14:08:27','2025-06-10 14:09:29','2025-06-10 14:06:30','2025-06-10 14:09:29',NULL),(7,4,8,NULL,10,'Assistant Professor','Professor required for teaching machine learning course.','Vacancy Opening','Bachelors in AI, Datascience, CS , SE\r\nStrong skill set in C++ And python',NULL,'Posted by HR','2025-06-11 04:06:38','2025-06-11 04:07:37','2025-06-11 04:05:11','2025-06-11 04:07:37',NULL);
/*!40000 ALTER TABLE `job_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_titles`
--

DROP TABLE IF EXISTS `job_titles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_titles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_titles_title_unique` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_titles`
--

LOCK TABLES `job_titles` WRITE;
/*!40000 ALTER TABLE `job_titles` DISABLE KEYS */;
INSERT INTO `job_titles` VALUES (1,'Junior Lecturer',1,NULL,NULL),(2,'Support Office',1,NULL,NULL),(3,'Senior Lecturer',1,NULL,NULL),(4,'Assistant Professor',1,NULL,NULL),(5,'Professor',1,NULL,NULL);
/*!40000 ALTER TABLE `job_titles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"ae8fe3bf-6bf7-4edb-aea8-457e92819401\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"541da9e3-3ca7-47b4-8010-4a3a9930ec53\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749578207,1749578207),(2,'default','{\"uuid\":\"c68c010e-2245-46cc-a0c5-b8ab55cf320e\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"b7f3ea20-3efc-4ad9-a2f8-c12cd6d44242\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749579051,1749579051),(3,'default','{\"uuid\":\"7efb6f89-bb47-4547-b65e-7d4a5ae454ae\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:3;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"3342ace6-2a97-4774-ba0b-5b069c1c09f0\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749579051,1749579051),(4,'default','{\"uuid\":\"fa16d40b-41eb-46e2-bad2-6b5b3c55dfcb\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"c71cabf9-c753-4cca-bb41-18dc5701cfa3\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749579051,1749579051),(5,'default','{\"uuid\":\"e37c485b-52e6-4536-96ce-e7c419c9eb29\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"3badfce2-9c69-4caa-9f99-3dffbc2737dc\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749579481,1749579481),(6,'default','{\"uuid\":\"31cb4822-d2fe-41b7-a75c-accb92fe2d6e\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"64d87d77-eca1-4c69-a5b3-9e2f8bba391a\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749583718,1749583718),(7,'default','{\"uuid\":\"64b005be-b304-41de-8524-2e5620bb549e\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:3;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"dfb9b6a4-aa0d-49de-b3de-9e133c7ef95c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749583718,1749583718),(8,'default','{\"uuid\":\"a3ea5a74-5942-4dfb-b4b7-fb40f345a8fb\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"cbd52b5b-c7ff-49fa-97b4-d48dfbc10ab9\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749583718,1749583718),(9,'default','{\"uuid\":\"a8c00996-0c46-443e-b767-88be81440095\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"3975b2d8-6112-4d18-8ef9-10b887074990\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749583816,1749583816),(10,'default','{\"uuid\":\"44502f81-e911-459e-8aee-7ead958e9087\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:11;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"02e71bc1-bccf-488e-b4b3-3a394b1feb5d\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749633094,1749633094),(11,'default','{\"uuid\":\"910283c4-e77d-49b4-a6f1-4b88be21edd0\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:3;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"c51113a7-4fbd-4ce4-be1d-a59c45abb095\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749633094,1749633094),(12,'default','{\"uuid\":\"302722c1-ff14-4fb9-a9a4-a4dd27911568\",\"displayName\":\"App\\\\Notifications\\\\HrCandidateAccepted\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\HrCandidateAccepted\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"e8f8ce3e-9129-4468-8410-56794701d139\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749633094,1749633094),(13,'default','{\"uuid\":\"b2b6e01c-27f7-4e18-994e-440b0d595ebc\",\"displayName\":\"App\\\\Notifications\\\\ApplicationStatusUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";a:1:{i:0;i:11;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:42:\\\"App\\\\Notifications\\\\ApplicationStatusUpdated\\\":2:{s:14:\\\"\\u0000*\\u0000application\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:25:\\\"App\\\\Models\\\\JobApplication\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"3b605082-9e0b-4848-ae89-8f281f0ee2be\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}',0,NULL,1749633168,1749633168);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_03_08_000000_create_departments_table',1),(6,'2024_03_09_000000_create_job_requests_table',1),(7,'2024_03_10_000000_create_job_postings_table',1),(8,'2024_03_11_000000_create_job_applications_table',1),(9,'2024_03_12_000000_add_cv_ranking_to_applications',1),(10,'2024_03_14_165902_add_interview_and_feedback_to_job_applications_table',1),(11,'2024_10_23_142709_add_auth_columns_to_users_table',1),(12,'2024_11_07_082127_add_department_id_to_users_table',1),(13,'2024_11_27_191320_add_is_admin_to_users_table',1),(14,'2024_12_01_173721_create_jobs_table',1),(15,'2024_12_04_052604_create_job_titles_table',1),(16,'2024_12_04_074844_add_status_to_job_postings_table',1),(17,'2024_12_06_102209_add_is_active_to_users_table',1),(18,'2024_12_06_102438_add_hod_id_to_departments_table',1),(19,'2025_01_16_153406_add_position_to_job_postings',1),(20,'2025_01_16_203105_add_soft_deletes_to_job_postings_table',1),(21,'2025_01_16_212431_add_department_to_job_postings_table',1),(22,'2025_01_18_151353_create_subscribers_table',1),(23,'2025_01_18_212923_add_two_factor_auth_to_users',1),(24,'2025_03_14_000000_add_rejected_by_dean_at_to_job_requests',1),(25,'2025_03_14_165902_update_job_applications_table',1),(26,'2025_03_17_194856_add_interview_and_feedback_to_job_applications_table',1),(27,'2025_03_17_204839_add_interview_details_to_job_applications_table',1),(28,'2025_03_18_104000_create_queue_jobs_table',1),(29,'2025_03_24_154722_add_requirements_to_job_requests_table',1),(30,'2025_03_24_162405_add_hr_id_to_job_requests_table',1),(31,'2025_03_24_162532_add_dean_id_to_job_requests_table',1),(32,'2025_04_13_115952_create_personality_questions_table',1),(33,'2025_04_13_120041_create_personality_tests_table',1),(34,'2025_04_13_120126_create_personality_answers_table',1),(35,'2025_04_13_141749_add_job_id_to_job_applications_table',1),(36,'2025_04_13_142201_add_education_fields_to_job_applications',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personality_answers`
--

DROP TABLE IF EXISTS `personality_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personality_answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `personality_test_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `answer` text NOT NULL,
  `score` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personality_answers_personality_test_id_foreign` (`personality_test_id`),
  KEY `personality_answers_question_id_foreign` (`question_id`),
  CONSTRAINT `personality_answers_personality_test_id_foreign` FOREIGN KEY (`personality_test_id`) REFERENCES `personality_tests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `personality_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `personality_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personality_answers`
--

LOCK TABLES `personality_answers` WRITE;
/*!40000 ALTER TABLE `personality_answers` DISABLE KEYS */;
INSERT INTO `personality_answers` VALUES (1,1,1,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(2,1,2,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(3,1,3,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(4,1,4,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(5,1,5,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(6,1,6,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(7,1,7,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(8,1,8,'4',4,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(9,1,9,'3',3,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(10,1,10,'5',5,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(11,1,11,'Based on logic and facts',NULL,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(12,1,12,'Calm and structured',NULL,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(13,2,1,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(14,2,2,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(15,2,3,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(16,2,4,'5',5,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(17,2,5,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(18,2,6,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(19,2,7,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(20,2,8,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(21,2,9,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(22,2,10,'4',4,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(23,2,11,'It depends on the situation',NULL,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(24,2,12,'Calm and structured',NULL,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(25,3,1,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(26,3,2,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(27,3,3,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(28,3,4,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(29,3,5,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(30,3,6,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(31,3,7,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(32,3,8,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(33,3,9,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(34,3,10,'4',4,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(35,3,11,'A combination of both',NULL,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(36,3,12,'Collaborative and team-oriented',NULL,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(37,4,1,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(38,4,2,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(39,4,3,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(40,4,4,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(41,4,5,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(42,4,6,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(43,4,7,'4',4,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(44,4,8,'4',4,'2025-06-10 13:07:36','2025-06-10 13:07:36'),(45,4,9,'4',4,'2025-06-10 13:07:36','2025-06-10 13:07:36'),(46,4,10,'4',4,'2025-06-10 13:07:36','2025-06-10 13:07:36'),(47,4,11,'A combination of both',NULL,'2025-06-10 13:07:36','2025-06-10 13:07:36'),(48,4,12,'Calm and structured',NULL,'2025-06-10 13:07:36','2025-06-10 13:07:36'),(49,5,1,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(50,5,2,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(51,5,3,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(52,5,4,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(53,5,5,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(54,5,6,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(55,5,7,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(56,5,8,'5',5,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(57,5,9,'4',4,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(58,5,10,'5',5,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(59,5,11,'A combination of both',NULL,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(60,5,12,'Calm and structured',NULL,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(61,6,1,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(62,6,2,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(63,6,3,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(64,6,4,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(65,6,5,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(66,6,6,'4',4,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(67,6,7,'5',5,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(68,6,8,'4',4,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(69,6,9,'5',5,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(70,6,10,'5',5,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(71,6,11,'A combination of both',NULL,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(72,6,12,'Calm and structured',NULL,'2025-06-10 14:20:23','2025-06-10 14:20:23'),(73,7,1,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(74,7,2,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(75,7,3,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(76,7,4,'3',3,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(77,7,5,'3',3,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(78,7,6,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(79,7,7,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(80,7,8,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(81,7,9,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(82,7,10,'4',4,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(83,7,11,'A combination of both',NULL,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(84,7,12,'Collaborative and team-oriented',NULL,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(85,8,1,'4',4,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(86,8,2,'4',4,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(87,8,3,'4',4,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(88,8,4,'3',3,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(89,8,5,'3',3,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(90,8,6,'2',2,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(91,8,7,'3',3,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(92,8,8,'4',4,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(93,8,9,'4',4,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(94,8,10,'3',3,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(95,8,11,'Based on feelings and values',NULL,'2025-06-11 04:09:17','2025-06-11 04:09:17'),(96,8,12,'Calm and structured',NULL,'2025-06-11 04:09:17','2025-06-11 04:09:17');
/*!40000 ALTER TABLE `personality_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personality_questions`
--

DROP TABLE IF EXISTS `personality_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personality_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `type` enum('multiple_choice','likert_scale') NOT NULL DEFAULT 'likert_scale',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personality_questions`
--

LOCK TABLES `personality_questions` WRITE;
/*!40000 ALTER TABLE `personality_questions` DISABLE KEYS */;
INSERT INTO `personality_questions` VALUES (1,'I prefer working in a team rather than independently.','likert_scale',NULL,1,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(2,'I enjoy solving complex problems.','likert_scale',NULL,2,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(3,'I am comfortable taking risks.','likert_scale',NULL,3,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(4,'I adapt well to changes in the workplace.','likert_scale',NULL,4,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(5,'I prefer structured tasks with clear instructions.','likert_scale',NULL,5,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(6,'I enjoy taking on leadership roles.','likert_scale',NULL,6,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(7,'I handle stress well in high-pressure situations.','likert_scale',NULL,7,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(8,'I prefer innovation over following established methods.','likert_scale',NULL,8,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(9,'I pay close attention to details.','likert_scale',NULL,9,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(10,'I am able to effectively communicate my ideas to others.','likert_scale',NULL,10,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(11,'How do you prefer to make decisions?','multiple_choice','\"[\\\"Based on logic and facts\\\",\\\"Based on feelings and values\\\",\\\"A combination of both\\\",\\\"It depends on the situation\\\"]\"',11,1,'2025-06-10 03:52:11','2025-06-10 03:52:11'),(12,'What type of work environment do you prefer?','multiple_choice','\"[\\\"Fast-paced and dynamic\\\",\\\"Calm and structured\\\",\\\"Collaborative and team-oriented\\\",\\\"Independent with minimal supervision\\\"]\"',12,1,'2025-06-10 03:52:11','2025-06-10 03:52:11');
/*!40000 ALTER TABLE `personality_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personality_tests`
--

DROP TABLE IF EXISTS `personality_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personality_tests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_application_id` bigint(20) unsigned NOT NULL,
  `results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`results`)),
  `summary` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personality_tests_job_application_id_foreign` (`job_application_id`),
  CONSTRAINT `personality_tests_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personality_tests`
--

LOCK TABLES `personality_tests` WRITE;
/*!40000 ALTER TABLE `personality_tests` DISABLE KEYS */;
INSERT INTO `personality_tests` VALUES (1,2,NULL,NULL,'2025-06-10 03:55:31','2025-06-10 03:55:31'),(2,3,NULL,NULL,'2025-06-10 04:18:30','2025-06-10 04:18:30'),(3,4,NULL,NULL,'2025-06-10 05:00:10','2025-06-10 05:00:10'),(4,7,NULL,NULL,'2025-06-10 13:07:35','2025-06-10 13:07:35'),(5,8,NULL,NULL,'2025-06-10 14:18:30','2025-06-10 14:18:30'),(6,9,NULL,NULL,'2025-06-10 14:20:22','2025-06-10 14:20:22'),(7,10,NULL,NULL,'2025-06-10 14:26:58','2025-06-10 14:26:58'),(8,11,NULL,NULL,'2025-06-11 04:09:17','2025-06-11 04:09:17');
/*!40000 ALTER TABLE `personality_tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
INSERT INTO `subscribers` VALUES (1,'muheebahmed1833@gmail.com',1,'2025-06-10 04:52:21','2025-06-10 04:52:21');
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

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

-- Dump completed on 2025-06-11 14:49:36
