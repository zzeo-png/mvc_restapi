-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for klop.com
CREATE DATABASE IF NOT EXISTS `klop.com` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci */;
USE `klop.com`;

-- Dumping structure for table klop.com.ads
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_slovenian_ci NOT NULL,
  `description` text COLLATE utf8_slovenian_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.ads: ~4 rows (approximately)
INSERT INTO `ads` (`id`, `title`, `description`, `user_id`, `timestamp`) VALUES
	(88, 'Audi A4', 'Audi A4 B8', 7, '2023-03-11 19:12:01'),
	(91, 'Album AJA', 'Steely Dan - Aja', 7, '2023-03-11 19:55:14'),
	(95, 'Prodajam hišo', 'grajena leta 1899, obnovljena leta 1980', 9, '2023-03-16 13:21:49');

-- Dumping structure for table klop.com.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_slovenian_ci NOT NULL,
  `value` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.categories: ~21 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `value`) VALUES
	(1, 'nepremicnine', 'Nepremičnine'),
	(2, 'avto-moto', 'Avto-moto'),
	(3, 'rekreacija,sport', 'Rekreacija, šport'),
	(4, 'dom', 'Dom'),
	(5, 'telefonija', 'Telefonija'),
	(6, 'vsezazimo', 'Vse za zimo'),
	(7, 'avdio,video', 'Avdio, video'),
	(8, 'fotografija,optika', 'Fotografija, optika'),
	(9, 'gradnja', 'Gradnja'),
	(10, 'hobi,zbirateljstvo', 'Hobi, zbirateljstvo'),
	(11, 'kmetijstvo', 'Kmetijstvo'),
	(12, 'knjige,revije,stripi', 'Knjige, revije, stripi'),
	(13, 'oblacila,obutev', 'Oblačila, obutev'),
	(14, 'turizeminkupino', 'Turizem in kuponi'),
	(15, 'poslovnaoprema', 'Poslovna oprema'),
	(16, 'racunalnistvo', 'Računalništvo'),
	(17, 'stroji,orodja', 'Stroji, orodja'),
	(18, 'umetnine,starine', 'Umetnine, starine'),
	(19, 'vsezaotroka', 'Vse za otroka'),
	(20, 'zivali', 'Živali'),
	(21, 'ostalo', 'Ostalo');

-- Dumping structure for table klop.com.category_in_ad
CREATE TABLE IF NOT EXISTS `category_in_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(11) unsigned NOT NULL,
  `id_category` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_category_in_ad_ad` (`id_ad`),
  KEY `FK_category_in_ad_category` (`id_category`),
  CONSTRAINT `FK_category_in_ad_ad` FOREIGN KEY (`id_ad`) REFERENCES `ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_category_in_ad_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=728 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.category_in_ad: ~6 rows (approximately)
INSERT INTO `category_in_ad` (`id`, `id_ad`, `id_category`) VALUES
	(691, 88, 2),
	(692, 88, 18),
	(715, 91, 7),
	(716, 91, 10),
	(717, 95, 1),
	(718, 95, 18);

-- Dumping structure for table klop.com.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_slovenian_ci NOT NULL,
  `size` int(11) unsigned NOT NULL,
  `type` text COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.images: ~21 rows (approximately)
INSERT INTO `images` (`id`, `name`, `size`, `type`) VALUES
	(133, 'images/20221011_173248_11032023201201', 0, ''),
	(134, 'images/20221231_220640_11032023201215', 3622172, 'image/jpeg'),
	(135, 'images/monster_img3_11032023205219', 0, ''),
	(136, 'images/monster_img3_11032023205410', 0, ''),
	(137, 'images/CAPSA0139___57452_11032023205514', 0, ''),
	(138, 'images/Trello_logo.svg_12032023123703', 0, ''),
	(139, 'images/atlassian_jira_logo_icon_170511_12032023123703', 0, ''),
	(140, 'images/JavaScript-logo_12032023123703', 0, ''),
	(141, 'images/market-share-of-the-leading-project-management-software-610c31d531a56_12032023123703', 0, ''),
	(142, 'images/Screenshot 2023-03-04 152129_12032023123703', 0, ''),
	(143, 'images/Microsoft_Project_(2019–present).svg_12032023123703', 0, ''),
	(144, 'images/20221011_173233_12032023151449', 0, ''),
	(145, 'images/20221011_173248_12032023151449', 0, ''),
	(146, 'images/20221231_220743_12032023151600', 0, ''),
	(150, 'images/old_house_16032023142149', 0, ''),
	(151, 'images/rectangle-xmark_16032023142950', 0, ''),
	(152, 'images/klop.com_icon_16032023143449', 0, ''),
	(153, 'images/rectangle-xmark_16032023144315', 0, ''),
	(155, 'images/close_16032023144614', 0, ''),
	(158, 'images/rectangle-xmark_16032023145342', 8479, 'image/png');

-- Dumping structure for table klop.com.image_in_ad
CREATE TABLE IF NOT EXISTS `image_in_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(11) unsigned NOT NULL,
  `id_image` int(11) unsigned NOT NULL,
  `is_primary` tinyint(3) unsigned DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_image_in_ad_image` (`id_image`),
  KEY `FK_image_in_ad_ad` (`id_ad`),
  CONSTRAINT `FK_image_in_ad_ad` FOREIGN KEY (`id_ad`) REFERENCES `ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_image_in_ad_image` FOREIGN KEY (`id_image`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.image_in_ad: ~5 rows (approximately)
INSERT INTO `image_in_ad` (`id`, `id_ad`, `id_image`, `is_primary`) VALUES
	(124, 88, 133, 1),
	(125, 88, 134, 0),
	(128, 91, 137, 1),
	(141, 95, 150, 1);

-- Dumping structure for table klop.com.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` text COLLATE utf8_slovenian_ci NOT NULL,
  `password` text COLLATE utf8_slovenian_ci NOT NULL,
  `email` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  `name` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  `surname` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  `address` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  `post` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  `phone` text COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `surname`, `address`, `post`, `phone`) VALUES
	(7, 'leo', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test@gmail.com', 'Leo', 'Test', 'Address', 'Pošta', '07023132312'),
	(8, 'user2', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'dsa@sad.com', 'User', 'Testerović', '', '', ''),
	(9, 'user3', '3ebfa301dc59196f18593c45e519287a23297589', 'user3@siol.net', 'Uporabnik', 'Borko', 'ljubljanska 25', '2000 Ljubljana', '');

-- Dumping structure for table klop.com.views
CREATE TABLE IF NOT EXISTS `views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned DEFAULT NULL,
  `id_ad` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `views_ad` (`id_ad`),
  KEY `views_user` (`id_user`),
  CONSTRAINT `views_ad` FOREIGN KEY (`id_ad`) REFERENCES `ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `views_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.views: ~7 rows (approximately)
INSERT INTO `views` (`id`, `id_user`, `id_ad`) VALUES
	(8, 7, 88),
	(11, 7, 91),
	(13, NULL, 91),
	(16, 8, 91),
	(17, NULL, 88),
	(18, NULL, 91),
	(20, NULL, 95);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
