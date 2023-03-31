-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.27-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
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
  `title` text NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.ads: ~4 rows (approximately)
INSERT INTO `ads` (`id`, `title`, `description`, `user_id`, `timestamp`) VALUES
	(88, 'Audi A4', 'Audi A4 B8', 7, '2023-03-11 19:12:01'),
	(91, 'Album AJA', 'Steely Dan - Aja', 7, '2023-03-11 19:55:14'),
	(95, 'Prodajam hišo', 'grajena leta 1899, obnovljena leta 1980', 9, '2023-03-16 13:21:49'),
	(124, 'Prodajam KUŽKE', 'Lepi kužki', 10, '2023-03-30 20:23:02'),
	(151, 'oddajam otok', 'zelo ne-sumljiv otok', 7, '2023-03-30 22:34:29');

-- Dumping structure for table klop.com.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `value` text NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=913 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.category_in_ad: ~7 rows (approximately)
INSERT INTO `category_in_ad` (`id`, `id_ad`, `id_category`) VALUES
	(854, 124, 20),
	(855, 91, 11),
	(856, 91, 21),
	(904, 151, 1),
	(905, 151, 9),
	(906, 151, 11),
	(907, 151, 14),
	(908, 151, 21),
	(909, 88, 2),
	(910, 88, 18),
	(911, 95, 1),
	(912, 95, 18);

-- Dumping structure for table klop.com.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `id_ad` int(11) unsigned NOT NULL,
  `content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip` tinytext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comments_users` (`id_user`),
  KEY `FK_comments_ad` (`id_ad`),
  CONSTRAINT `FK_comments_ad` FOREIGN KEY (`id_ad`) REFERENCES `ads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_comments_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.comments: ~7 rows (approximately)
INSERT INTO `comments` (`id`, `id_user`, `id_ad`, `content`, `timestamp`, `ip`) VALUES
	(2, 8, 91, 'wow super', '2023-03-30 21:42:27', '91.121.62.146'),
	(17, 7, 95, 'zelo lepa hiša', '2023-03-30 21:41:41', '176.57.95.80'),
	(18, 8, 95, 'hvala', '2023-03-30 21:42:26', '91.121.62.146'),
	(19, 10, 124, 'PRODAJAM', '2023-03-30 21:41:40', '176.57.95.80'),
	(21, 8, 124, 'pasma?', '2023-03-30 21:42:25', '91.121.62.146'),
	(22, 10, 91, 'Všeč mi je artwork', '2023-03-30 21:41:39', '176.57.95.80'),
	(23, 7, 91, 'hvala, kupiš?', '2023-03-30 21:41:39', '176.57.95.80'),
	(24, 8, 91, 'ne', '2023-03-30 21:42:26', '91.121.62.146'),
	(26, 7, 91, 'ja ok te', '2023-03-30 21:33:54', '176.57.95.80'),
	(29, 7, 151, 'pišite za več info', '2023-03-30 22:44:54', '176.57.95.80'),
	(30, 10, 151, 'zelo lepo', '2023-03-30 23:46:53', '176.57.95.80');

-- Dumping structure for table klop.com.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `size` int(11) unsigned NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.images: ~33 rows (approximately)
INSERT INTO `images` (`id`, `name`, `size`, `type`) VALUES
	(135, 'images/monster_img3_11032023205219', 0, ''),
	(136, 'images/monster_img3_11032023205410', 0, ''),
	(138, 'images/Trello_logo.svg_12032023123703', 0, ''),
	(139, 'images/atlassian_jira_logo_icon_170511_12032023123703', 0, ''),
	(140, 'images/JavaScript-logo_12032023123703', 0, ''),
	(141, 'images/market-share-of-the-leading-project-management-software-610c31d531a56_12032023123703', 0, ''),
	(142, 'images/Screenshot 2023-03-04 152129_12032023123703', 0, ''),
	(143, 'images/Microsoft_Project_(2019–present).svg_12032023123703', 0, ''),
	(144, 'images/20221011_173233_12032023151449', 0, ''),
	(145, 'images/20221011_173248_12032023151449', 0, ''),
	(146, 'images/20221231_220743_12032023151600', 0, ''),
	(151, 'images/rectangle-xmark_16032023142950', 0, ''),
	(152, 'images/klop.com_icon_16032023143449', 0, ''),
	(153, 'images/rectangle-xmark_16032023144315', 0, ''),
	(155, 'images/close_16032023144614', 0, ''),
	(158, 'images/rectangle-xmark_16032023145342', 8479, 'image/png'),
	(161, 'images/nora_krava2_30032023172016', 3034480, 'image/png'),
	(163, 'images/colors_30032023195842', 0, ''),
	(164, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023201052', 0, ''),
	(165, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023201130', 0, ''),
	(166, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023201339', 0, ''),
	(167, 'images/colors_30032023201624', 0, ''),
	(168, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023202012', 0, ''),
	(169, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023202128', 0, ''),
	(170, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_30032023202602', 0, ''),
	(171, 'images/DALL·E 2023-01-05 00.10.55_30032023202715', 0, ''),
	(172, 'images/inspo_30032023202935', 0, ''),
	(173, 'images/colors_30032023203142', 0, ''),
	(174, 'images/colors_30032023203317', 0, ''),
	(175, 'images/0992_01_4x3L_30032023222302', 0, ''),
	(176, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023000635', 0, ''),
	(177, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023001226', 0, ''),
	(178, 'images/DALL·E 2023-01-05 00.10.55_31032023001244', 0, ''),
	(179, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023001922', 0, ''),
	(180, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023001926', 0, ''),
	(181, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023001927', 0, ''),
	(182, 'images/colors_31032023001945', 0, ''),
	(183, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023002050', 0, ''),
	(184, 'images/colors_31032023002201', 0, ''),
	(185, 'images/DALL·E 2023-01-03 21.22.51 - retro program icon with 80s synth vibes_31032023002230', 0, ''),
	(186, 'images/colors_31032023002412', 0, ''),
	(187, 'images/colors_31032023002507', 0, ''),
	(188, 'images/colors_31032023002526', 0, ''),
	(189, 'images/colors_31032023002620', 0, ''),
	(190, 'images/colors_31032023002639', 0, ''),
	(191, 'images/RE4O2Nz_1920x1080_31032023002909', 0, ''),
	(192, 'images/RE4O87P_1920x1080_31032023003025', 0, ''),
	(193, 'images/RE4O5fz_1920x1080_31032023003054', 0, ''),
	(194, '/images/RE4O5fz_1920x1080_31032023003123', 0, ''),
	(195, './images/RE4O87P_1920x1080_31032023003159', 0, ''),
	(196, '../images/RE4OaMY_1920x1080_31032023003228', 0, ''),
	(197, '../images/RE4O2Nz_1920x1080_31032023003250', 0, ''),
	(198, '../images/RE4O5fz_1920x1080_31032023003250', 0, ''),
	(199, '../images/RE4O87P_1920x1080_31032023003250', 0, ''),
	(203, '../images/RE4OaMY_1920x1080_31032023003659', 546149, 'image/jpeg'),
	(204, '../images/RE4O87P_1920x1080_31032023003659', 592134, 'image/jpeg'),
	(205, '../images/RE4NUV0_1920x1080_31032023004403', 648672, 'image/jpeg'),
	(206, '../images/DALL·E 2023-01-06 01.27.49_31032023021414', 1550660, 'image/png'),
	(207, '../images/DALL·E 2023-01-06 01.28.05_31032023021414', 828142, 'image/png'),
	(208, '../images/RE5c2hX_1920x1080_31032023021435', 1700504, 'image/jpeg');

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
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.image_in_ad: ~5 rows (approximately)
INSERT INTO `image_in_ad` (`id`, `id_ad`, `id_image`, `is_primary`) VALUES
	(152, 91, 161, 1),
	(166, 124, 175, 1),
	(194, 151, 203, 0),
	(195, 151, 204, 1),
	(196, 151, 205, 0),
	(197, 88, 206, 0),
	(198, 88, 207, 1),
	(199, 95, 208, 1);

-- Dumping structure for table klop.com.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `surname` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `post` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `name`, `surname`, `address`, `post`, `phone`, `is_admin`) VALUES
	(7, 'leo', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test@gmail.com', 'Leo', 'Test', 'Address', 'Pošta', '07023132312', 1),
	(8, 'user2', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'dsa@sad.com', 'User', 'Testerović', '', '', '', 0),
	(9, 'user3', '3ebfa301dc59196f18593c45e519287a23297589', 'user3@siol.net', 'Uporabnik', 'Borko', 'ljubljanska 25', '2000 Ljubljana', '', 0),
	(10, 'user4', '', 'blablabla@bla.com', 'User4', 'Testović', '', '', '', 0),
	(11, 'novi_user', 'test', 'novi@gmail.com', 'nov', 'user', '', '', '', 0),
	(12, 'novi2', '', 'novi2@gmail.com', 'novi', '21', '', '', '', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- Dumping data for table klop.com.views: ~17 rows (approximately)
INSERT INTO `views` (`id`, `id_user`, `id_ad`) VALUES
	(8, 7, 88),
	(11, 7, 91),
	(13, NULL, 91),
	(16, 8, 91),
	(17, NULL, 88),
	(18, NULL, 91),
	(20, NULL, 95),
	(21, 7, 95),
	(22, 8, 95),
	(23, 10, 95),
	(24, 10, 124),
	(25, 7, 124),
	(26, 8, 124),
	(27, NULL, 124),
	(28, NULL, 95),
	(29, 10, 91),
	(30, NULL, 91),
	(32, 7, 151),
	(33, 10, 151);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
