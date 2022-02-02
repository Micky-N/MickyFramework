-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           5.7.33 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour mickyframework
CREATE DATABASE IF NOT EXISTS `mickyframework` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `mickyframework`;

-- Listage de la structure de la table mickyframework. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `code_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`code_category`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.categories : ~5 rows (environ)
DELETE FROM `categories`;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`code_category`, `name`, `description`) VALUES
	(1, 'Electronics', 'Optio et qui maiores perferendis sit corrupti.'),
	(2, 'Health', 'Quo excepturi a et laboriosam.'),
	(3, 'Tools', 'Et et totam ipsam quo consequatur quam non.'),
	(4, 'Garden', 'Dignissimos at quaerat rerum et ut sed impedit.'),
	(5, 'Baby', 'Sit est totam consectetur magnam ipsum non ea.');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. notifiables
CREATE TABLE IF NOT EXISTS `notifiables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notifiable_id` int(11) DEFAULT NULL,
  `endpoint` text COLLATE utf8_unicode_ci,
  `auth` text COLLATE utf8_unicode_ci,
  `p256dh` text COLLATE utf8_unicode_ci,
  `expirationTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notifications_pk_2` (`notifiable_id`),
  CONSTRAINT `notifications_notifiables_id_fk` FOREIGN KEY (`notifiable_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Listage des données de la table mickyframework.notifiables : ~2 rows (environ)
DELETE FROM `notifiables`;
/*!40000 ALTER TABLE `notifiables` DISABLE KEYS */;
INSERT INTO `notifiables` (`id`, `notifiable_id`, `endpoint`, `auth`, `p256dh`, `expirationTime`) VALUES
	(35, 7, 'https://fcm.googleapis.com/fcm/send/f8UKonCc-qw:APA91bFt0DxTyQJ1pAtldm7fciiDWIwOc9OmhFbivlirkAvSciwX20z3MjON0muCthZqAHsMhtLR5YMfYg7Z23yLtK4EGeSILivjmbmmj2NVLPo-Lcg5j1Wnv8gyvmRdCJ4pGaduIxvR', 'xJ-TFfrWeib3LUD7JSNbSw', 'BNITqYEymvXHo8LwAyNKmjnnCacBGC3dXNh3YfjFrC-PlaKkTh4uVi6i8vych7Uu54cJUc_ICD95zLq2VBQjtWI', '2022-01-30 12:44:37'),
	(36, 1, 'https://wns2-par02p.notify.windows.com/w/?token=BQYAAACi40XuzMykvS03j9xob%2fv3rjZfsgkCy1f2NDN8EGtvGdCH0lkzm%2fulxK2hbKPCr8oJd7w6M0OtViWkDtln7mSy3%2bCyqlzRBhA11mffnK77KuKWZBoE2K6OgKPQdSgGvb7Qc66Yj7ZTOURVtilAT3c3hCtZkVlZj%2bAOBRt6bkqLbnKPATjdXMrlgOInGxutSE0DfYsclE13wzsh2wd0gxNYTN2TDBjr71WWh%2fAB0bVfnNeZSH3hlL8bP0VSW9aqRsD2SHLx%2batJf0Z3Gzc3otB%2bcmk%2bs1o8GMfbM%2fB%2boj22iKpbqpWG%2bBqXLyol2SoaSZY%3d', '-2JEpfHUo_bywPB2jg2SfA', 'BKXK_kTipbiwHhL28lWHy5UFkQmuFW-SUTntjRbI-0kWSRG0rAh0et3MjAXFWGkg9zkz8hFr2Iq3xC_XKOyif8s', '2021-12-29 19:11:41');
/*!40000 ALTER TABLE `notifiables` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. phinxlog
CREATE TABLE IF NOT EXISTS `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.phinxlog : ~5 rows (environ)
DELETE FROM `phinxlog`;
/*!40000 ALTER TABLE `phinxlog` DISABLE KEYS */;
INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
	(20211122184748, 'CreateCategoriesTable', '2021-12-05 16:43:21', '2021-12-05 16:43:21', 0),
	(20211122184801, 'CreateProductsTable', '2021-12-05 16:43:21', '2021-12-05 16:43:21', 0),
	(20211122184825, 'CreateSuppliersTable', '2021-12-05 16:43:21', '2021-12-05 16:43:21', 0),
	(20211122195838, 'CreateStocksTable', '2021-12-05 16:43:21', '2021-12-05 16:43:21', 0),
	(20211124200453, 'CreateProductSupplierTable', '2021-12-05 16:43:21', '2021-12-05 16:43:21', 0);
/*!40000 ALTER TABLE `phinxlog` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. products
CREATE TABLE IF NOT EXISTS `products` (
  `code_product` int(11) NOT NULL AUTO_INCREMENT,
  `code_category` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selling_price` double NOT NULL,
  `photo` text NOT NULL,
  PRIMARY KEY (`code_product`),
  KEY `code_category` (`code_category`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`code_category`) REFERENCES `categories` (`code_category`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_3` FOREIGN KEY (`code_category`) REFERENCES `categories` (`code_category`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.products : ~100 rows (environ)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`code_product`, `code_category`, `name`, `user_id`, `selling_price`, `photo`) VALUES
	(1, 3, 'Ergonomic Iron Table', 7, 235.39, 'https://via.placeholder.com/640x480.png/005544?text=product+Ergonomic+Iron+Table+est'),
	(2, 5, 'Synergistic Granite Gloves', 1, 589.57, 'https://via.placeholder.com/640x480.png/00eecc?text=product+Synergistic+Granite+Gloves+praesentium'),
	(3, 2, 'Heavy Duty Copper Coat', 1, 423.71, 'https://via.placeholder.com/640x480.png/0011ee?text=product+Heavy+Duty+Copper+Coat+error'),
	(4, 2, 'Mediocre Aluminum Pants', 1, 724.49, 'https://via.placeholder.com/640x480.png/00ee00?text=product+Mediocre+Aluminum+Pants+veniam'),
	(5, 1, 'Durable Wooden Hat', 6, 539.42, 'https://via.placeholder.com/640x480.png/00ee22?text=product+Durable+Wooden+Hat+fugiat'),
	(6, 2, 'Heavy Duty Concrete Car', 7, 300.29, 'https://via.placeholder.com/640x480.png/00bbcc?text=product+Heavy+Duty+Concrete+Car+neque'),
	(7, 2, 'Awesome Leather Watch', 3, 463.54, 'https://via.placeholder.com/640x480.png/00dd66?text=product+Awesome+Leather+Watch+tempora'),
	(8, 4, 'Synergistic Silk Car', 2, 375.36, 'https://via.placeholder.com/640x480.png/00bbee?text=product+Synergistic+Silk+Car+et'),
	(9, 4, 'Mediocre Linen Bottle', 6, 359.04, 'https://via.placeholder.com/640x480.png/00aacc?text=product+Mediocre+Linen+Bottle+magnam'),
	(10, 4, 'Fantastic Wool Plate', 6, 360.4, 'https://via.placeholder.com/640x480.png/004466?text=product+Fantastic+Wool+Plate+officia'),
	(11, 1, 'Sleek Bronze Keyboard', 2, 669.41, 'https://via.placeholder.com/640x480.png/005555?text=product+Sleek+Bronze+Keyboard+repudiandae'),
	(12, 4, 'Synergistic Leather Knife', 2, 353.35, 'https://via.placeholder.com/640x480.png/0088cc?text=product+Synergistic+Leather+Knife+eligendi'),
	(13, 5, 'Gorgeous Marble Bottle', 7, 482.74, 'https://via.placeholder.com/640x480.png/007711?text=product+Gorgeous+Marble+Bottle+nam'),
	(14, 3, 'Intelligent Copper Hat', 3, 691.43, 'https://via.placeholder.com/640x480.png/00dd66?text=product+Intelligent+Copper+Hat+consequuntur'),
	(15, 2, 'Aerodynamic Iron Bench', 2, 260.77, 'https://via.placeholder.com/640x480.png/007799?text=product+Aerodynamic+Iron+Bench+odio'),
	(16, 5, 'Fantastic Rubber Knife', 1, 202.22, 'https://via.placeholder.com/640x480.png/0033dd?text=product+Fantastic+Rubber+Knife+ad'),
	(17, 2, 'Ergonomic Leather Chair', 2, 377.19, 'https://via.placeholder.com/640x480.png/0022bb?text=product+Ergonomic+Leather+Chair+ducimus'),
	(18, 2, 'Intelligent Iron Clock', 1, 366.44, 'https://via.placeholder.com/640x480.png/004400?text=product+Intelligent+Iron+Clock+qui'),
	(19, 1, 'Small Rubber Plate', 2, 469.96, 'https://via.placeholder.com/640x480.png/006677?text=product+Small+Rubber+Plate+ducimus'),
	(20, 5, 'Mediocre Cotton Hat', 1, 701.72, 'https://via.placeholder.com/640x480.png/001122?text=product+Mediocre+Cotton+Hat+maxime'),
	(21, 2, 'Awesome Steel Gloves', 7, 282.77, 'https://via.placeholder.com/640x480.png/009933?text=product+Awesome+Steel+Gloves+tempore'),
	(22, 1, 'Lightweight Paper Plate', 6, 268.62, 'https://via.placeholder.com/640x480.png/0099aa?text=product+Lightweight+Paper+Plate+eos'),
	(23, 4, 'Rustic Paper Bench', 2, 572.75, 'https://via.placeholder.com/640x480.png/00ff33?text=product+Rustic+Paper+Bench+ipsum'),
	(24, 5, 'Synergistic Bronze Computer', 7, 365.65, 'https://via.placeholder.com/640x480.png/003399?text=product+Synergistic+Bronze+Computer+qui'),
	(25, 4, 'Sleek Rubber Bag', 6, 581.76, 'https://via.placeholder.com/640x480.png/00dd11?text=product+Sleek+Rubber+Bag+qui'),
	(26, 3, 'Intelligent Marble Clock', 1, 344.65, 'https://via.placeholder.com/640x480.png/002200?text=product+Intelligent+Marble+Clock+sit'),
	(27, 4, 'Rustic Wooden Table', 6, 496.28, 'https://via.placeholder.com/640x480.png/00eeee?text=product+Rustic+Wooden+Table+est'),
	(28, 1, 'Sleek Bronze Computer', 1, 390.31, 'https://via.placeholder.com/640x480.png/00aa22?text=product+Sleek+Bronze+Computer+quia'),
	(29, 4, 'Awesome Iron Gloves', 6, 523.4, 'https://via.placeholder.com/640x480.png/00ffff?text=product+Awesome+Iron+Gloves+explicabo'),
	(30, 5, 'Heavy Duty Copper Lamp', 7, 537.86, 'https://via.placeholder.com/640x480.png/0022dd?text=product+Heavy+Duty+Copper+Lamp+assumenda'),
	(31, 3, 'Aerodynamic Wool Shoes', 7, 799.38, 'https://via.placeholder.com/640x480.png/00ddee?text=product+Aerodynamic+Wool+Shoes+saepe'),
	(32, 5, 'Practical Wooden Gloves', 2, 462.54, 'https://via.placeholder.com/640x480.png/007711?text=product+Practical+Wooden+Gloves+ex'),
	(33, 2, 'Gorgeous Silk Keyboard', 1, 587.61, 'https://via.placeholder.com/640x480.png/00ee22?text=product+Gorgeous+Silk+Keyboard+et'),
	(34, 1, 'Aerodynamic Linen Chair', 7, 233.73, 'https://via.placeholder.com/640x480.png/0055ee?text=product+Aerodynamic+Linen+Chair+officiis'),
	(35, 3, 'Awesome Paper Shirt', 7, 382.9, 'https://via.placeholder.com/640x480.png/00aa11?text=product+Awesome+Paper+Shirt+quam'),
	(36, 4, 'Awesome Wool Pants', 2, 442.14, 'https://via.placeholder.com/640x480.png/00ccdd?text=product+Awesome+Wool+Pants+eaque'),
	(37, 3, 'Practical Rubber Clock', 7, 797.53, 'https://via.placeholder.com/640x480.png/001188?text=product+Practical+Rubber+Clock+quo'),
	(38, 5, 'Rustic Bronze Computer', 6, 488.71, 'https://via.placeholder.com/640x480.png/000011?text=product+Rustic+Bronze+Computer+voluptate'),
	(39, 2, 'Practical Copper Lamp', 3, 432.37, 'https://via.placeholder.com/640x480.png/006666?text=product+Practical+Copper+Lamp+qui'),
	(40, 5, 'Small Copper Wallet', 2, 256.59, 'https://via.placeholder.com/640x480.png/00ccff?text=product+Small+Copper+Wallet+sapiente'),
	(41, 5, 'Incredible Wooden Clock', 3, 668.2, 'https://via.placeholder.com/640x480.png/00ee66?text=product+Incredible+Wooden+Clock+cumque'),
	(42, 4, 'Gorgeous Concrete Watch', 3, 662.32, 'https://via.placeholder.com/640x480.png/00dddd?text=product+Gorgeous+Concrete+Watch+sunt'),
	(43, 4, 'Synergistic Silk Watch', 2, 399.16, 'https://via.placeholder.com/640x480.png/00bbee?text=product+Synergistic+Silk+Watch+perferendis'),
	(44, 5, 'Durable Granite Bottle', 1, 230.54, 'https://via.placeholder.com/640x480.png/0000ee?text=product+Durable+Granite+Bottle+ipsum'),
	(45, 2, 'Sleek Rubber Shirt', 7, 460.48, 'https://via.placeholder.com/640x480.png/003311?text=product+Sleek+Rubber+Shirt+possimus'),
	(46, 4, 'Awesome Rubber Gloves', 7, 610.94, 'https://via.placeholder.com/640x480.png/0022aa?text=product+Awesome+Rubber+Gloves+adipisci'),
	(47, 2, 'Durable Aluminum Shirt', 6, 601, 'https://via.placeholder.com/640x480.png/0055ee?text=product+Durable+Aluminum+Shirt+harum'),
	(48, 2, 'Fantastic Wooden Shirt', 7, 250.98, 'https://via.placeholder.com/640x480.png/00ddcc?text=product+Fantastic+Wooden+Shirt+animi'),
	(49, 2, 'Heavy Duty Granite Knife', 3, 235.28, 'https://via.placeholder.com/640x480.png/0000ff?text=product+Heavy+Duty+Granite+Knife+dolores'),
	(50, 3, 'Intelligent Plastic Pants', 7, 472.29, 'https://via.placeholder.com/640x480.png/001133?text=product+Intelligent+Plastic+Pants+molestiae'),
	(51, 2, 'Incredible Silk Pants', 6, 534.78, 'https://via.placeholder.com/640x480.png/0044cc?text=product+Incredible+Silk+Pants+est'),
	(52, 4, 'Practical Silk Keyboard', 6, 559.74, 'https://via.placeholder.com/640x480.png/00dd00?text=product+Practical+Silk+Keyboard+maiores'),
	(53, 1, 'Small Leather Bench', 7, 438.99, 'https://via.placeholder.com/640x480.png/0055ee?text=product+Small+Leather+Bench+non'),
	(54, 2, 'Sleek Marble Bag', 2, 424.59, 'https://via.placeholder.com/640x480.png/006622?text=product+Sleek+Marble+Bag+sint'),
	(55, 2, 'Mediocre Leather Hat', 3, 394.3, 'https://via.placeholder.com/640x480.png/0022ff?text=product+Mediocre+Leather+Hat+omnis'),
	(56, 3, 'Enormous Aluminum Keyboard', 2, 790.12, 'https://via.placeholder.com/640x480.png/00ccaa?text=product+Enormous+Aluminum+Keyboard+quibusdam'),
	(57, 5, 'Aerodynamic Wooden Bench', 2, 201.42, 'https://via.placeholder.com/640x480.png/00aa55?text=product+Aerodynamic+Wooden+Bench+accusamus'),
	(58, 3, 'Heavy Duty Wool Shoes', 7, 575.04, 'https://via.placeholder.com/640x480.png/002233?text=product+Heavy+Duty+Wool+Shoes+qui'),
	(59, 3, 'Ergonomic Silk Hat', 2, 584.98, 'https://via.placeholder.com/640x480.png/000022?text=product+Ergonomic+Silk+Hat+accusamus'),
	(60, 2, 'Intelligent Wool Knife', 1, 241.21, 'https://via.placeholder.com/640x480.png/003300?text=product+Intelligent+Wool+Knife+doloremque'),
	(61, 5, 'Enormous Wooden Gloves', 3, 593.95, 'https://via.placeholder.com/640x480.png/00dd99?text=product+Enormous+Wooden+Gloves+tempora'),
	(62, 1, 'Aerodynamic Marble Knife', 1, 795.2, 'https://via.placeholder.com/640x480.png/005522?text=product+Aerodynamic+Marble+Knife+labore'),
	(63, 2, 'Incredible Concrete Shoes', 1, 672.02, 'https://via.placeholder.com/640x480.png/007722?text=product+Incredible+Concrete+Shoes+nisi'),
	(64, 3, 'Fantastic Rubber Lamp', 1, 739.18, 'https://via.placeholder.com/640x480.png/00cc99?text=product+Fantastic+Rubber+Lamp+in'),
	(65, 4, 'Practical Iron Gloves', 1, 670.74, 'https://via.placeholder.com/640x480.png/006600?text=product+Practical+Iron+Gloves+officia'),
	(66, 5, 'Durable Copper Lamp', 2, 591.72, 'https://via.placeholder.com/640x480.png/005599?text=product+Durable+Copper+Lamp+quae'),
	(67, 4, 'Small Leather Bottle', 2, 676.9, 'https://via.placeholder.com/640x480.png/00bbdd?text=product+Small+Leather+Bottle+rem'),
	(68, 4, 'Gorgeous Rubber Shoes', 6, 695.15, 'https://via.placeholder.com/640x480.png/00ffdd?text=product+Gorgeous+Rubber+Shoes+earum'),
	(69, 5, 'Intelligent Linen Pants', 1, 249.39, 'https://via.placeholder.com/640x480.png/0099ff?text=product+Intelligent+Linen+Pants+eum'),
	(70, 3, 'Sleek Iron Gloves', 3, 214.22, 'https://via.placeholder.com/640x480.png/002244?text=product+Sleek+Iron+Gloves+facere'),
	(71, 3, 'Practical Plastic Wallet', 2, 428.31, 'https://via.placeholder.com/640x480.png/00aa88?text=product+Practical+Plastic+Wallet+cupiditate'),
	(72, 4, 'Enormous Marble Shoes', 3, 420.38, 'https://via.placeholder.com/640x480.png/00dd22?text=product+Enormous+Marble+Shoes+omnis'),
	(73, 4, 'Small Leather Computer', 7, 363.86, 'https://via.placeholder.com/640x480.png/00ccaa?text=product+Small+Leather+Computer+ut'),
	(74, 5, 'Gorgeous Plastic Lamp', 6, 542.11, 'https://via.placeholder.com/640x480.png/00ddbb?text=product+Gorgeous+Plastic+Lamp+cum'),
	(75, 5, 'Intelligent Bronze Bench', 1, 340.12, 'https://via.placeholder.com/640x480.png/0055ff?text=product+Intelligent+Bronze+Bench+explicabo'),
	(76, 2, 'Practical Linen Bag', 3, 434.53, 'https://via.placeholder.com/640x480.png/00bbdd?text=product+Practical+Linen+Bag+facere'),
	(77, 5, 'Practical Linen Bottle', 1, 613.16, 'https://via.placeholder.com/640x480.png/000088?text=product+Practical+Linen+Bottle+voluptates'),
	(78, 3, 'Enormous Concrete Wallet', 2, 413.48, 'https://via.placeholder.com/640x480.png/004444?text=product+Enormous+Concrete+Wallet+fugiat'),
	(79, 3, 'Synergistic Steel Bottle', 1, 371.96, 'https://via.placeholder.com/640x480.png/00ffee?text=product+Synergistic+Steel+Bottle+qui'),
	(80, 1, 'Intelligent Linen Shirt', 2, 249.06, 'https://via.placeholder.com/640x480.png/00ccaa?text=product+Intelligent+Linen+Shirt+illum'),
	(81, 1, 'Enormous Marble Plate', 1, 325.19, 'https://via.placeholder.com/640x480.png/003344?text=product+Enormous+Marble+Plate+totam'),
	(82, 2, 'Intelligent Granite Gloves', 6, 758.06, 'https://via.placeholder.com/640x480.png/006699?text=product+Intelligent+Granite+Gloves+soluta'),
	(83, 2, 'Durable Rubber Hat', 7, 334.87, 'https://via.placeholder.com/640x480.png/006688?text=product+Durable+Rubber+Hat+necessitatibus'),
	(84, 1, 'Durable Paper Pants', 3, 693.48, 'https://via.placeholder.com/640x480.png/00cc22?text=product+Durable+Paper+Pants+praesentium'),
	(85, 5, 'Incredible Plastic Table', 7, 722.89, 'https://via.placeholder.com/640x480.png/00bbdd?text=product+Incredible+Plastic+Table+porro'),
	(86, 5, 'Heavy Duty Steel Bag', 6, 643.84, 'https://via.placeholder.com/640x480.png/005511?text=product+Heavy+Duty+Steel+Bag+eum'),
	(87, 1, 'Ergonomic Steel Computer', 3, 355.79, 'https://via.placeholder.com/640x480.png/0055aa?text=product+Ergonomic+Steel+Computer+ipsum'),
	(88, 5, 'Sleek Iron Clock', 3, 733.82, 'https://via.placeholder.com/640x480.png/001177?text=product+Sleek+Iron+Clock+qui'),
	(89, 3, 'Enormous Marble Lamp', 7, 407.4, 'https://via.placeholder.com/640x480.png/0066bb?text=product+Enormous+Marble+Lamp+similique'),
	(90, 5, 'Sleek Copper Chair', 1, 595.95, 'https://via.placeholder.com/640x480.png/009999?text=product+Sleek+Copper+Chair+magnam'),
	(91, 4, 'Synergistic Silk Knife', 2, 334.04, 'https://via.placeholder.com/640x480.png/00dd44?text=product+Synergistic+Silk+Knife+recusandae'),
	(92, 1, 'Gorgeous Rubber Clock', 7, 733.17, 'https://via.placeholder.com/640x480.png/00dd77?text=product+Gorgeous+Rubber+Clock+aut'),
	(93, 3, 'Lightweight Plastic Keyboard', 1, 693.06, 'https://via.placeholder.com/640x480.png/0022ee?text=product+Lightweight+Plastic+Keyboard+sunt'),
	(94, 4, 'Ergonomic Copper Watch', 2, 470.31, 'https://via.placeholder.com/640x480.png/008822?text=product+Ergonomic+Copper+Watch+voluptate'),
	(95, 4, 'Incredible Marble Coat', 2, 611.69, 'https://via.placeholder.com/640x480.png/006666?text=product+Incredible+Marble+Coat+ad'),
	(96, 3, 'Heavy Duty Silk Knife', 3, 676.09, 'https://via.placeholder.com/640x480.png/0099bb?text=product+Heavy+Duty+Silk+Knife+quam'),
	(97, 4, 'Rustic Silk Shoes', 7, 742.58, 'https://via.placeholder.com/640x480.png/0077ee?text=product+Rustic+Silk+Shoes+excepturi'),
	(98, 2, 'Intelligent Granite Bag', 1, 420.42, 'https://via.placeholder.com/640x480.png/00bb66?text=product+Intelligent+Granite+Bag+rerum'),
	(99, 2, 'Synergistic Granite Computer', 6, 450.19, 'https://via.placeholder.com/640x480.png/00bbaa?text=product+Synergistic+Granite+Computer+sequi'),
	(100, 3, 'Awesome Leather Lamp', 6, 583.88, 'https://via.placeholder.com/640x480.png/007722?text=product+Awesome+Leather+Lamp+ipsam');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. product_supplier
CREATE TABLE IF NOT EXISTS `product_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_product` int(11) NOT NULL,
  `code_supplier` int(11) NOT NULL,
  `purchase_price` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_product` (`code_product`,`code_supplier`),
  KEY `code_supplier` (`code_supplier`),
  CONSTRAINT `product_supplier_ibfk_1` FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_supplier_ibfk_2` FOREIGN KEY (`code_supplier`) REFERENCES `suppliers` (`code_supplier`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_supplier_ibfk_3` FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `product_supplier_ibfk_4` FOREIGN KEY (`code_supplier`) REFERENCES `suppliers` (`code_supplier`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.product_supplier : ~200 rows (environ)
DELETE FROM `product_supplier`;
/*!40000 ALTER TABLE `product_supplier` DISABLE KEYS */;
INSERT INTO `product_supplier` (`id`, `code_product`, `code_supplier`, `purchase_price`) VALUES
	(1, 85, 12, 416.95),
	(2, 24, 2, 213.9),
	(3, 36, 5, 423.52),
	(4, 23, 12, 431.99),
	(5, 62, 18, 365.87),
	(6, 10, 9, 526.03),
	(7, 75, 17, 548.23),
	(8, 51, 4, 370.69),
	(9, 58, 15, 338.78),
	(10, 46, 10, 384.82),
	(11, 15, 2, 379.3),
	(12, 13, 11, 477.13),
	(13, 41, 15, 514.08),
	(14, 82, 4, 384.63),
	(15, 97, 4, 323.94),
	(16, 99, 19, 593.3),
	(17, 3, 13, 372.44),
	(18, 16, 15, 437.89),
	(19, 50, 18, 514.88),
	(20, 54, 6, 570.98),
	(21, 80, 10, 255.26),
	(22, 85, 6, 261.2),
	(23, 93, 13, 256.77),
	(24, 61, 19, 354.85),
	(25, 86, 17, 504.14),
	(26, 29, 7, 472.18),
	(27, 4, 17, 568.26),
	(28, 26, 1, 223.86),
	(29, 98, 12, 207.55),
	(30, 39, 11, 414.32),
	(31, 10, 4, 581.13),
	(32, 40, 4, 519.31),
	(33, 1, 18, 443.19),
	(34, 56, 14, 216.21),
	(35, 12, 15, 288.49),
	(36, 55, 19, 282.18),
	(37, 96, 3, 462.04),
	(38, 8, 5, 387.99),
	(39, 71, 19, 425.83),
	(40, 73, 3, 523.83),
	(41, 53, 1, 244.58),
	(42, 69, 20, 399.83),
	(43, 51, 16, 237.35),
	(44, 77, 3, 436.18),
	(45, 33, 8, 351.33),
	(46, 99, 17, 410.85),
	(47, 5, 16, 552.76),
	(48, 64, 12, 271.18),
	(49, 13, 19, 595.82),
	(50, 84, 3, 435.63),
	(51, 61, 8, 491.9),
	(52, 32, 8, 388.18),
	(53, 25, 2, 300.3),
	(54, 3, 11, 423.12),
	(55, 7, 2, 330.8),
	(56, 91, 16, 234.44),
	(57, 12, 5, 243.57),
	(58, 77, 11, 362.79),
	(59, 47, 4, 411.51),
	(60, 51, 3, 457.28),
	(61, 82, 9, 308.68),
	(62, 48, 13, 259.24),
	(63, 13, 18, 394.28),
	(64, 8, 9, 536.08),
	(65, 37, 10, 560.07),
	(66, 95, 10, 371.04),
	(67, 6, 3, 488.68),
	(68, 35, 16, 290.14),
	(69, 82, 7, 292.55),
	(70, 7, 9, 224.75),
	(71, 9, 12, 347.22),
	(72, 98, 19, 526.63),
	(73, 56, 1, 531.89),
	(74, 43, 6, 237.36),
	(75, 1, 20, 578.81),
	(76, 92, 10, 334.94),
	(77, 41, 9, 362.38),
	(78, 68, 20, 533.19),
	(79, 29, 18, 341.94),
	(80, 61, 16, 549),
	(81, 71, 20, 230.99),
	(82, 91, 8, 252.38),
	(83, 87, 20, 575.27),
	(84, 14, 13, 463.53),
	(85, 64, 20, 506.72),
	(86, 14, 4, 499.97),
	(87, 45, 5, 442.89),
	(88, 38, 3, 260.7),
	(89, 37, 2, 576.18),
	(90, 51, 6, 236.99),
	(91, 11, 15, 233.42),
	(92, 97, 14, 386.21),
	(93, 16, 9, 532.13),
	(94, 94, 3, 491.73),
	(95, 39, 16, 408.12),
	(96, 35, 18, 440.55),
	(97, 79, 17, 522.52),
	(98, 96, 9, 357.76),
	(99, 98, 15, 490.12),
	(100, 17, 6, 436.74),
	(101, 77, 19, 328.22),
	(102, 91, 15, 472.21),
	(103, 25, 5, 329.66),
	(104, 78, 12, 529.37),
	(105, 13, 8, 256.41),
	(106, 64, 10, 278.37),
	(107, 59, 18, 484.5),
	(108, 25, 20, 404.67),
	(109, 43, 18, 593.45),
	(110, 73, 10, 435.3),
	(111, 45, 14, 226.33),
	(112, 59, 16, 352.38),
	(113, 72, 20, 425.44),
	(114, 4, 18, 252.22),
	(115, 74, 9, 355.74),
	(116, 100, 16, 356.38),
	(117, 90, 10, 425.75),
	(118, 52, 1, 433.55),
	(119, 95, 2, 551.01),
	(120, 79, 16, 245.43),
	(121, 56, 12, 334.54),
	(122, 43, 11, 302.48),
	(123, 5, 14, 254.4),
	(124, 89, 7, 420.69),
	(125, 16, 12, 588.2),
	(126, 44, 18, 362.46),
	(127, 97, 15, 264.08),
	(128, 32, 3, 546.71),
	(129, 46, 20, 554.16),
	(130, 36, 9, 315.32),
	(131, 22, 13, 312.58),
	(132, 66, 1, 364.35),
	(133, 27, 2, 297.99),
	(134, 90, 13, 533.89),
	(135, 83, 14, 529.45),
	(136, 60, 17, 472.47),
	(137, 72, 14, 245.44),
	(138, 42, 11, 567.67),
	(139, 93, 9, 575.38),
	(140, 13, 4, 485.51),
	(141, 4, 1, 316.84),
	(142, 7, 12, 285.8),
	(143, 96, 4, 311.64),
	(144, 40, 5, 544.98),
	(145, 18, 10, 394.73),
	(146, 74, 6, 226.4),
	(147, 74, 15, 486.65),
	(148, 65, 6, 468.57),
	(149, 34, 3, 554.09),
	(150, 69, 1, 301.62),
	(151, 29, 13, 459.26),
	(152, 98, 4, 402.79),
	(153, 47, 13, 368.32),
	(154, 19, 19, 204.26),
	(155, 31, 2, 474.56),
	(156, 71, 17, 293.46),
	(157, 28, 7, 412.32),
	(158, 43, 14, 397.37),
	(159, 81, 6, 320.29),
	(160, 41, 1, 474.23),
	(161, 37, 8, 551.63),
	(162, 39, 2, 357.15),
	(163, 55, 2, 565.95),
	(164, 71, 9, 518.13),
	(165, 53, 14, 402.36),
	(166, 59, 6, 506.23),
	(167, 41, 14, 412.47),
	(168, 76, 14, 208.12),
	(169, 60, 3, 228.36),
	(170, 48, 4, 355.36),
	(171, 36, 8, 337.38),
	(172, 83, 17, 426.55),
	(173, 15, 7, 345.23),
	(174, 45, 6, 296.11),
	(175, 79, 20, 542.15),
	(176, 90, 20, 282.81),
	(177, 39, 6, 561.32),
	(178, 61, 14, 227.08),
	(179, 92, 20, 540.63),
	(180, 23, 2, 330.92),
	(181, 77, 5, 561.93),
	(182, 48, 20, 215.76),
	(183, 52, 2, 248.7),
	(184, 35, 11, 503.22),
	(185, 94, 11, 469.85),
	(186, 9, 20, 391.09),
	(187, 96, 1, 215.5),
	(188, 49, 18, 563.27),
	(189, 50, 16, 545.3),
	(190, 42, 16, 335.1),
	(191, 24, 13, 494.24),
	(192, 75, 1, 417.07),
	(193, 97, 6, 376.62),
	(194, 93, 12, 338.06),
	(195, 61, 6, 401.03),
	(196, 98, 17, 490.61),
	(197, 58, 18, 546.56),
	(198, 70, 13, 488.03),
	(199, 14, 10, 335.92),
	(200, 94, 5, 252.2);
/*!40000 ALTER TABLE `product_supplier` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.roles : ~3 rows (environ)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'ADMIN'),
	(2, 'SELLER'),
	(3, 'BUYER');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. stocks
CREATE TABLE IF NOT EXISTS `stocks` (
  `code_stock` int(11) NOT NULL AUTO_INCREMENT,
  `code_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`code_stock`),
  UNIQUE KEY `code_product` (`code_product`),
  CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`code_product`) REFERENCES `products` (`code_product`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.stocks : ~100 rows (environ)
DELETE FROM `stocks`;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
INSERT INTO `stocks` (`code_stock`, `code_product`, `quantity`) VALUES
	(1, 13, 81),
	(2, 34, 167),
	(3, 12, 151),
	(4, 80, 191),
	(5, 20, 50),
	(6, 94, 53),
	(7, 15, 79),
	(8, 77, 170),
	(9, 58, 100),
	(10, 78, 207),
	(11, 81, 71),
	(12, 44, 133),
	(13, 93, 119),
	(14, 19, 75),
	(15, 50, 73),
	(16, 8, 58),
	(17, 49, 78),
	(18, 82, 185),
	(19, 41, 176),
	(20, 86, 68),
	(21, 24, 237),
	(22, 10, 97),
	(23, 79, 194),
	(24, 30, 242),
	(25, 55, 136),
	(26, 7, 83),
	(27, 73, 158),
	(28, 38, 217),
	(29, 98, 108),
	(30, 35, 168),
	(31, 56, 161),
	(32, 32, 239),
	(33, 95, 86),
	(34, 69, 196),
	(35, 11, 222),
	(36, 66, 93),
	(37, 68, 88),
	(38, 45, 171),
	(39, 40, 69),
	(40, 61, 168),
	(41, 47, 73),
	(42, 28, 136),
	(43, 60, 144),
	(44, 67, 168),
	(45, 53, 247),
	(46, 85, 166),
	(47, 62, 230),
	(48, 71, 152),
	(49, 92, 78),
	(50, 65, 60),
	(51, 64, 167),
	(52, 99, 78),
	(53, 88, 202),
	(54, 76, 225),
	(55, 52, 243),
	(56, 16, 199),
	(57, 21, 96),
	(58, 31, 242),
	(59, 17, 131),
	(60, 91, 215),
	(61, 39, 153),
	(62, 70, 243),
	(63, 33, 115),
	(64, 18, 156),
	(65, 9, 139),
	(66, 2, 101),
	(67, 90, 57),
	(68, 72, 134),
	(69, 23, 163),
	(70, 84, 198),
	(71, 59, 96),
	(72, 36, 94),
	(73, 100, 243),
	(74, 37, 71),
	(75, 51, 116),
	(76, 42, 109),
	(77, 46, 53),
	(78, 22, 216),
	(79, 83, 149),
	(80, 6, 145),
	(81, 87, 180),
	(82, 29, 206),
	(83, 89, 84),
	(84, 74, 54),
	(85, 4, 150),
	(86, 57, 110),
	(87, 27, 164),
	(88, 48, 179),
	(89, 63, 71),
	(90, 96, 167),
	(91, 26, 214),
	(92, 5, 65),
	(93, 1, 108),
	(94, 97, 167),
	(95, 3, 189),
	(96, 75, 226),
	(97, 43, 116),
	(98, 25, 89),
	(99, 14, 54),
	(100, 54, 64);
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `code_supplier` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `informations` varchar(100) NOT NULL,
  `num_street` varchar(10) NOT NULL,
  `name_street` varchar(50) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`code_supplier`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.suppliers : ~20 rows (environ)
DELETE FROM `suppliers`;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` (`code_supplier`, `name`, `informations`, `num_street`, `name_street`, `postcode`, `city`) VALUES
	(1, 'Auger S.A.S.', 'Dolor et blanditiis porro accusantium voluptas aperiam ducimus accusamus.                           ', '6', 'rue de la république', '69700', 'Givors'),
	(2, 'Hebert S.A.R.L.', 'Deleniti et velit facilis eius veniam autem.                        ', '17', 'avenue de Paris', '58 362', 'Dias'),
	(3, 'Mary', 'Delectus odio consequuntur debitis repellat pariatur.', '77', 'place Victoire Duhamel', '36122', 'Parentnec'),
	(4, 'Lopez S.A.', 'Expedita temporibus ipsa maxime illo quasi.', '11', 'boulevard Audrey Pages', '57553', 'Vallee'),
	(5, 'Bertrand', 'Temporibus veritatis temporibus qui id similique.', '86', 'rue Lucie De Sousa', '86 017', 'Robert-sur-Collin'),
	(6, 'Fontaine S.A.R.L.', 'Facilis quia rerum perspiciatis quia facilis ex.', '6', 'place de Adam', '23 303', 'BlotVille'),
	(7, 'Lamy SAS', 'Ut totam perspiciatis modi et excepturi repudiandae.', '89', 'rue de Lelievre', '92 731', 'Morin'),
	(8, 'Moreno Camus SA', 'Velit et quae consectetur nulla occaecati.', '10', 'rue Gabriel Andre', '12696', 'Leconte'),
	(9, 'Masse', 'Id ea incidunt tempore ratione impedit delectus.', '3', 'rue Aimé Boulay', '84 369', 'Simon'),
	(10, 'Potier Blondel SAS', 'Et libero cumque et ad.', '22', 'impasse de Rodrigues', '54643', 'Picard'),
	(11, 'Ribeiro', 'Deleniti voluptatem consequuntur iusto.', '58', 'rue de Bouvier', '37304', 'Pichonboeuf'),
	(12, 'Hoarau', 'Perspiciatis at quos sunt eveniet accusamus.', '9', 'place Gerard', '79 717', 'Pascal-sur-Bruneau'),
	(13, 'Morel', 'Temporibus quis molestiae magni ipsa.', '46', 'impasse de Cohen', '03 880', 'Da Costa-sur-Gaillard'),
	(14, 'Payet', 'Quia ut delectus dolore.', '3', 'rue Poirier', '35791', 'Lopes'),
	(15, 'Levy', 'Ea ipsum id labore quam.', '389', 'rue de Marechal', '58 687', 'Louis'),
	(16, 'Laine Marques S.A.S.', 'Tempore sunt labore tempore eaque fugiat nihil nihil.', '672', 'impasse de Le Roux', '67 355', 'Bouchet'),
	(17, 'Bourgeois', 'Consequatur officiis quidem quo.', '62', 'avenue Lejeune', '46949', 'Ledoux-sur-Sanchez'),
	(18, 'Maillot Thibault SAS', 'Est nam impedit velit ut.', '6', 'rue de Roche', '14 294', 'Delmasboeuf'),
	(19, 'Royer Barbe S.A.', 'Tempora placeat sed magni quas praesentium et non.', '47', 'boulevard Victor Allain', '61434', 'NormandVille'),
	(20, 'Gomes SARL', 'Quibusdam est molestiae architecto voluptatem iste vitae.', '10', 'rue de Sanchez', '70786', 'Lefevre');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;

-- Listage de la structure de la table mickyframework. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table mickyframework.users : ~7 rows (environ)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `role_id`, `created`) VALUES
	(1, 'qcorkery', '$2y$10$ZSiMCq2qZnrCkUc/gD6Sb.WkjVmD8i/LlP6kpaXVI/UAbnWvm3u/O', 'edwin.ward@labadie.com', 'Walton', 'Jacobson', 2, '2021-07-29 22:39:33'),
	(2, 'jromaguera', '$2y$10$yYdnLydDlKm/d.UAqHR8tep1GGcQ1uN6tBE/uLfoJmMsarkpkPKAm', 'kris.roob@senger.com', 'Cayla', 'Abernathy', 1, '2021-05-07 13:47:07'),
	(3, 'loraine.ferry', '$2y$10$eriv18n2SChAWf6WRv.Z8.evAM5MkvpOncmtjh1UMMd8rufpnQI.G', 'frank.stroman@yahoo.com', 'Arianna', 'Kshlerin', 2, '2021-10-30 20:44:17'),
	(4, 'karson54', '$2y$10$xaqEDGYTN4noxeyhbQJhouH.zm2dclP0jkAfDBIhTpkoogUJODn3e', 'dcollier@oreilly.com', 'Jensen', 'Christiansen', 3, '2021-09-27 19:33:01'),
	(5, 'kuhlman.gabriel', '$2y$10$j26Uksl7naX12Th4vJTzKu7sCTg1ziBulG10tT9ONHkKTsneVPV1m', 'tbrakus@hotmail.com', 'Mavis', 'Sipes', 3, '2021-08-07 01:51:25'),
	(6, 'lenna92', '$2y$10$pQIpGSHCjtCrgFw7keRiKe3ZeFLi7Cpji55/fpBVbtDgc/8IsngYy', 'marcel36@yahoo.com', 'Chauncey', 'Kuphal', 2, '2021-06-13 22:27:04'),
	(7, 'vidal47', '$2y$10$Kg6HskEw.I0zqu1n3.XN0uNVGdHieIWhU1um6bQZLn4LKqnvtMUmq', 'ferry.katlyn@hotmail.com', 'Lucie', 'Witting', 2, '2021-08-09 14:12:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
