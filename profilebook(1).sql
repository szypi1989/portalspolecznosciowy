-- phpMyAdmin SQL Dump
-- version 3.4.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 18 Sty 2015, 22:40
-- Wersja serwera: 5.5.15
-- Wersja PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `profilebook`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `black_list_ip`
--

CREATE TABLE IF NOT EXISTS `black_list_ip` (
  `login_ip` varchar(15) NOT NULL DEFAULT '',
  `logins` varchar(40) NOT NULL,
  `time_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_photo` int(255) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `contents` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_comments_photo` (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id`, `id_photo`, `sender_id`, `contents`, `time`, `status`) VALUES
(1, 1, 2, 'fajna fotka', '2014-12-25 14:56:23', 0),
(2, 2, 2, 'niezła fotka', '2014-12-25 14:57:07', 0),
(7, 5, 3, 'asdf', '2014-10-01 16:40:16', 0),
(8, 5, 3, 'asdfasd', '2014-10-01 16:40:51', 0),
(9, 7, 1, 'hahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahahah', '2014-12-27 12:49:25', 0),
(10, 8, 1, 'królkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkrólkró', '2014-12-27 14:29:29', 0),
(11, 7, 1, 'tylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylkotylk', '2014-12-27 14:29:53', 0),
(12, 7, 1, 'niezła fota', '2014-12-27 14:47:01', 0),
(13, 6, 1, 'asfdsadfsdf', '2014-12-28 18:33:17', 0),
(14, 6, 1, 'asfdsadfsdfasdfsadfsadfasasfsadfsdafasdddddddddddddddddddddddddddddd', '2014-12-28 18:33:24', 0),
(15, 9, 1, 'sfsad', '2015-01-16 21:30:31', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `cookies`
--

CREATE TABLE IF NOT EXISTS `cookies` (
  `cookie_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_expired` int(11) NOT NULL,
  PRIMARY KEY (`cookie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `user_id` int(255) NOT NULL,
  `id_friends` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_foreign` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_foreign`),
  KEY `FK_users_friends` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `friends`
--

INSERT INTO `friends` (`user_id`, `id_friends`, `id_foreign`) VALUES
(1, '2,3', 1),
(2, '1', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_users_gallery` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `gallery`
--

INSERT INTO `gallery` (`id`, `name`, `user_id`, `description`, `time`) VALUES
(1, '1.jpg', 2, '', '2014-07-28 21:14:55'),
(2, '2.jpg', 2, '', '2014-07-28 21:20:39'),
(5, '1.jpg', 3, '', '2014-07-28 21:40:25'),
(6, '1.jpg', 1, '', '2014-12-25 12:15:30'),
(7, '2.jpg', 1, '', '2014-12-25 12:15:33'),
(8, '3.jpg', 1, '', '2014-12-25 19:37:56'),
(9, '4.jpg', 1, '', '2014-12-25 19:37:59');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `hobby`
--

CREATE TABLE IF NOT EXISTS `hobby` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `hobby`
--

INSERT INTO `hobby` (`user_id`, `name`) VALUES
(1, 'tenis stolowy,hokeysdf'),
(3, '1');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `invitation`
--

CREATE TABLE IF NOT EXISTS `invitation` (
  `user_id` int(255) NOT NULL,
  `id_friends` varchar(180) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `id_freq` (`id`),
  KEY `FK_users_invitation` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `invitation`
--

INSERT INTO `invitation` (`user_id`, `id_friends`, `id`) VALUES
(1, '2,3', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `ip_login`
--

CREATE TABLE IF NOT EXISTS `ip_login` (
  `login_ip` varchar(15) NOT NULL DEFAULT '',
  `login` varchar(40) NOT NULL,
  `cookie` varchar(40) NOT NULL DEFAULT '',
  `first_time` int(11) NOT NULL DEFAULT '0',
  `last_time` int(11) NOT NULL DEFAULT '0',
  `amount` int(10) NOT NULL DEFAULT '0',
  `browser` varchar(140) CHARACTER SET utf8 NOT NULL,
  UNIQUE KEY `controlbot` (`login_ip`,`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `ip_login`
--

INSERT INTO `ip_login` (`login_ip`, `login`, `cookie`, `first_time`, `last_time`, `amount`, `browser`) VALUES
('83.23.255.221', 'test', '', 1406618267, 1406618267, 1, ''),
('::1', 'kazio1989', '', 1411196704, 1411196704, 1, ''),
('::1', 'kaziu', '', 1411222716, 1411223974, 4, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `contents` varchar(500) NOT NULL,
  `recipient_id` int(180) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Zrzut danych tabeli `message`
--

INSERT INTO `message` (`id`, `sender_id`, `contents`, `recipient_id`, `time`, `status`) VALUES
(1, 3, 'hej jak tam?', 1, '2014-09-20 13:18:36', 1),
(2, 3, 'hej jak tam?', 1, '2014-09-20 13:20:35', 1),
(3, 3, 'co tam?', 1, '2014-09-20 13:20:35', 1),
(4, 1, 'asdffassdf', 3, '2014-09-20 14:39:55', 1),
(5, 1, 'asdffassdf', 3, '2014-09-20 14:39:55', 1),
(6, 3, 'asfaasfasdf', 1, '2014-09-20 13:32:43', 1),
(7, 3, 'fasdfsad', 1, '2014-09-20 13:43:04', 1),
(8, 3, 'fasdfsad', 1, '2014-09-20 14:11:25', 1),
(9, 3, 'fasdfsad', 1, '2014-09-20 14:11:25', 1),
(10, 3, 'jhjhll', 1, '2014-09-20 14:44:49', 1),
(11, 3, 'jhjhllvbhty', 1, '2014-09-20 15:07:37', 1),
(12, 3, 'jhjhllvbhty', 1, '2014-09-20 15:11:03', 1),
(13, 3, 'jhjhllvbhsdfty', 1, '2014-09-20 15:13:08', 1),
(14, 3, 'sadfasd', 1, '2014-09-20 15:13:48', 1),
(15, 3, 'sadfasdsdfasdffs', 1, '2014-09-20 15:14:35', 1),
(16, 3, 'sadfasdsdfasdffsasdf', 1, '2014-09-20 15:14:51', 1),
(17, 3, 'sadfasdsdfasdffsasdfasdfasd', 1, '2014-09-20 15:21:03', 1),
(18, 3, 'sadfasdsdfasdffsasdfasdfasd', 1, '2014-09-20 15:49:52', 1),
(19, 3, 'fasd', 1, '2014-09-20 15:49:52', 1),
(20, 3, 'sdfafasd', 1, '2014-09-20 16:18:51', 1),
(21, 3, 'sdfafasdasdf', 1, '2014-09-20 16:18:51', 1),
(22, 3, 'sdfafasdasdf', 1, '2014-09-20 16:20:57', 1),
(23, 3, 'sdfafasdasdffgg', 1, '2014-09-20 16:20:57', 1),
(24, 3, 'sdfafasdasdffgg', 1, '2014-09-20 16:22:39', 1),
(25, 3, 'sdfafasdasdffgg', 1, '2014-09-20 16:23:43', 1),
(26, 3, 'sdfafasdasdffgg', 1, '2014-09-20 16:23:43', 1),
(27, 3, 'asdfasd', 1, '2014-09-20 17:25:38', 1),
(28, 3, 'hej co tam?', 1, '2014-09-21 13:25:15', 1),
(29, 3, 'hej co tam?', 1, '2014-09-21 13:31:10', 1),
(30, 3, 'hej co tam?', 1, '2014-09-21 13:33:02', 1),
(31, 3, 'hej co tam?', 1, '2014-09-21 13:35:40', 1),
(32, 3, 'hej co tam?', 1, '2014-09-21 13:35:40', 1),
(33, 3, 'hej co tam?asdf', 1, '2014-09-21 13:35:40', 1),
(34, 3, 'hej co tam?asdfasdfdsa', 1, '2014-09-21 13:38:03', 1),
(35, 3, 'no tah', 1, '2014-09-21 13:39:52', 1),
(36, 3, 'no tah', 1, '2014-09-21 13:39:52', 1),
(37, 2, 'halo', 1, '2014-09-21 13:39:43', 1),
(38, 2, 'halo', 1, '2014-09-21 14:06:49', 1),
(39, 2, 'halo', 1, '2014-09-21 14:09:20', 1),
(40, 2, 'halo', 1, '2014-09-21 14:09:55', 1),
(41, 2, 'halosda', 1, '2014-09-21 14:10:37', 1),
(42, 2, 'halosdasdfa', 1, '2014-09-21 14:12:46', 1),
(43, 2, 'halosdasdfa', 1, '2014-09-21 14:14:03', 1),
(44, 1, 'sfdsd', 2, '2015-01-03 12:39:13', 1),
(45, 1, 'sdfasd', 2, '2015-01-03 12:39:13', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `message_control`
--

CREATE TABLE IF NOT EXISTS `message_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipients_id` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sender_id_2` (`sender_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Zrzut danych tabeli `message_control`
--

INSERT INTO `message_control` (`id`, `sender_id`, `recipients_id`) VALUES
(1, 1, ''),
(12, 2, ''),
(19, 3, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `message_type`
--

CREATE TABLE IF NOT EXISTS `message_type` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `spam` tinyint(1) NOT NULL,
  `id_foreign` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_foreign`),
  KEY `FK_message_rule_cntrl` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Zrzut danych tabeli `message_type`
--

INSERT INTO `message_type` (`id`, `user_id`, `hidden`, `spam`, `id_foreign`) VALUES
(1, 3, 0, 0, 1),
(1, 1, 0, 0, 2),
(2, 3, 0, 0, 3),
(2, 1, 0, 0, 4),
(3, 3, 0, 0, 5),
(3, 1, 0, 0, 6),
(4, 1, 0, 0, 7),
(4, 3, 0, 0, 8),
(5, 1, 0, 0, 9),
(5, 3, 0, 0, 10),
(6, 3, 0, 0, 11),
(6, 1, 0, 0, 12),
(7, 3, 0, 0, 13),
(7, 1, 0, 0, 14),
(8, 3, 0, 0, 15),
(8, 1, 0, 0, 16),
(9, 3, 0, 0, 17),
(9, 1, 0, 0, 18),
(10, 3, 0, 0, 19),
(10, 1, 0, 0, 20),
(11, 3, 0, 0, 21),
(11, 1, 0, 0, 22),
(12, 3, 0, 0, 23),
(12, 1, 0, 0, 24),
(13, 3, 0, 0, 25),
(13, 1, 0, 0, 26),
(14, 3, 0, 0, 27),
(14, 1, 0, 0, 28),
(15, 3, 0, 0, 29),
(15, 1, 0, 0, 30),
(16, 3, 0, 0, 31),
(16, 1, 0, 0, 32),
(17, 3, 0, 0, 33),
(17, 1, 0, 0, 34),
(18, 3, 0, 0, 35),
(18, 1, 0, 0, 36),
(19, 3, 0, 0, 37),
(19, 1, 0, 0, 38),
(20, 3, 0, 0, 39),
(20, 1, 0, 0, 40),
(21, 3, 0, 0, 41),
(21, 1, 0, 0, 42),
(22, 3, 0, 0, 43),
(22, 1, 0, 0, 44),
(23, 3, 0, 0, 45),
(23, 1, 0, 0, 46),
(24, 3, 0, 0, 47),
(24, 1, 0, 0, 48),
(25, 3, 0, 0, 49),
(25, 1, 0, 0, 50),
(26, 3, 0, 0, 51),
(26, 1, 0, 0, 52),
(27, 3, 0, 0, 53),
(27, 1, 0, 0, 54),
(28, 3, 0, 0, 55),
(28, 1, 0, 0, 56),
(29, 3, 0, 0, 57),
(29, 1, 0, 0, 58),
(30, 3, 0, 0, 59),
(30, 1, 0, 0, 60),
(31, 3, 0, 0, 61),
(31, 1, 0, 0, 62),
(32, 3, 0, 0, 63),
(32, 1, 0, 0, 64),
(33, 3, 0, 0, 65),
(33, 1, 0, 0, 66),
(34, 3, 0, 0, 67),
(34, 1, 0, 0, 68),
(35, 3, 0, 0, 69),
(35, 1, 0, 0, 70),
(36, 3, 0, 0, 71),
(36, 1, 0, 0, 72),
(37, 2, 0, 0, 73),
(37, 1, 0, 0, 74),
(38, 2, 0, 0, 75),
(38, 1, 0, 0, 76),
(39, 2, 0, 0, 77),
(39, 1, 0, 0, 78),
(40, 2, 0, 0, 79),
(40, 1, 0, 0, 80),
(41, 2, 0, 0, 81),
(41, 1, 0, 0, 82),
(42, 2, 0, 0, 83),
(42, 1, 0, 0, 84),
(43, 2, 0, 0, 85),
(43, 1, 0, 0, 86),
(44, 1, 1, 0, 87),
(44, 2, 0, 0, 88),
(45, 1, 0, 0, 89),
(45, 2, 0, 0, 90);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `schools`
--

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degree_school` int(11) NOT NULL DEFAULT '0',
  `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `place` varchar(40) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `street_nr` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `schools`
--

INSERT INTO `schools` (`id`, `degree_school`, `name`, `place`, `street`, `street_nr`) VALUES
(1, 0, 'wsinf', 'bydgoszcz', NULL, NULL),
(2, 0, 'utp', 'bydgoszcz', NULL, NULL),
(3, 0, 'akw', 'bydgoszcz', NULL, NULL),
(4, 0, 'uniwersystet technologiczno przyrodniczy', 'bydgoszcz', NULL, NULL),
(5, 0, 'uniwersystet technologiczno przyrodniczy imienia jana', 'bydgoszcz', NULL, NULL),
(6, 0, 'wsdd', 'bydgoszcz', NULL, NULL),
(7, 0, 'ukw', 'bydgoszcz', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `school_info`
--

CREATE TABLE IF NOT EXISTS `school_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(1) NOT NULL,
  `specialization` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(100) DEFAULT NULL,
  `time_start` year(4) DEFAULT NULL,
  `time_end` year(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users_school_inf` (`user_id`),
  KEY `FK_schoolid_school_inf` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=46 ;

--
-- Zrzut danych tabeli `school_info`
--

INSERT INTO `school_info` (`id`, `user_id`, `specialization`, `school_id`, `time_start`, `time_end`) VALUES
(1, 3, 'programowanie', 1, 1947, 1950),
(2, 2, 'architektura', 2, 1952, 1952),
(3, 2, 'politologia', 1, 1952, 1952),
(39, 1, 'programowanie', 4, 1945, 1945),
(44, 1, 'architektura', 7, 1954, 1957),
(45, 1, 'grafika', 1, 1958, 1962);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(120) CHARACTER SET utf8 NOT NULL,
  `session_user` int(8) NOT NULL DEFAULT '0',
  `session_ip` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `session_browser` varchar(200) CHARACTER SET utf8 NOT NULL,
  `session_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_ozn` (`session_ip`,`session_browser`,`session_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

--
-- Zrzut danych tabeli `sessions`
--

INSERT INTO `sessions` (`id`, `session_id`, `session_user`, `session_ip`, `session_browser`, `session_time`) VALUES
(10, '83.238.156.2411406624554', 1, '83.238.156.241', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36', 1406624554),
(20, '83.238.156.2411406716258', 1, '83.238.156.241', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0', 1406716258),
(21, '77.254.177.211406879466', 1, '77.254.177.21', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0', 1406879466),
(22, '81.219.91.2251406961621', 1, '81.219.91.225', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0', 1406961621),
(40, '::11415483366;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0', 1415483366),
(48, '::11411308866;2', 2, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36', 1411308866),
(53, '::11412760970;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; rv:11.0) like Gecko', 1412760970),
(61, '::11412192888;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36', 1412192888),
(62, '::11415615240;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0', 1415615240),
(65, '127.0.0.11418396269;1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1418396269),
(70, '::11419535391;2', 2, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.65 Safari/537.36 OPR/26.0.1656.24', 1419535391),
(74, '::11419791573;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419791573),
(77, '::11419791573;1', 2, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36', 1419791573),
(89, '::11421443823;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0', 1421443823),
(92, '::11421611900;1', 1, '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0', 1421611900);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `session_action_data`
--

CREATE TABLE IF NOT EXISTS `session_action_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `name_function` varchar(120) CHARACTER SET utf8 NOT NULL,
  `worth` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_ozn` (`worth`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Zrzut danych tabeli `session_action_data`
--

INSERT INTO `session_action_data` (`id`, `session_id`, `name_function`, `worth`) VALUES
(98, '::11411202686;1', 'reset_message_control', '1'),
(101, '::11411219071;3', 'reset_message_control', '3'),
(116, '::11411306750;2', 'reset_message_control', '2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(32) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `user_password` varchar(40) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `user_lastvisit` int(8) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_lastvisit`) VALUES
(1, 'szypi1989', '7ec942cee2635e4bed3c18973f7456db', 1406581969),
(2, 'szypi1989s', '7ec942cee2635e4bed3c18973f7456db', 1406582019),
(3, 'kazio', '7ec942cee2635e4bed3c18973f7456db', 1406582672);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `user_id` int(1) NOT NULL AUTO_INCREMENT,
  `sex` int(1) NOT NULL,
  `birth` date DEFAULT NULL,
  `mail` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `code_authorization` varchar(128) CHARACTER SET latin1 NOT NULL,
  `village` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  `Opis` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'brak',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  `surname` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `users_info`
--

INSERT INTO `users_info` (`user_id`, `sex`, `birth`, `mail`, `active`, `code_authorization`, `village`, `Opis`, `name`, `surname`) VALUES
(1, 2, '1989-10-02', 'szypula89@onet.pl', 0, '4cda32165b792a141365796cbc7b9ff4$hkr7ae77', 'bydgoszcz', 'brak', 'mariusz', 'szypula'),
(2, 2, '1945-01-01', 'kowalski@onet.pl', 0, '0562e1e7b53247ce6be976342a934039$hkr7ae77', 'warszawa', 'brak', 'janusz', 'kowalski'),
(3, 2, '1959-04-01', 'nowes@onet.pl', 0, '5e35992b4ae459468f83b3ae25e71ea5$hkr7ae77', 'bydgoszcz', 'brak', 'albert', 'dombek');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `vilage`
--

CREATE TABLE IF NOT EXISTS `vilage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `vilage`
--

INSERT INTO `vilage` (`id`, `name`) VALUES
(1, 'bydgoszcz'),
(2, 'mabap');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `works`
--

CREATE TABLE IF NOT EXISTS `works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `place` varchar(40) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `street_nr` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `works`
--

INSERT INTO `works` (`id`, `name`, `place`, `street`, `street_nr`) VALUES
(1, 'ar plast', 'wielka brytania', NULL, NULL),
(2, 'nazwa firmy', 'bydgoszcz', NULL, NULL),
(3, 'metalbark', 'bydgoszcz', NULL, NULL),
(4, 'mekx', 'warszawa', NULL, NULL),
(5, 'alba', 'gdansk', NULL, NULL),
(6, 'akor', 'bydgoszcz', NULL, NULL),
(7, 'pesa', 'bydgoszcz', NULL, NULL),
(8, 'jobil', 'bydgoszcz', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `work_info`
--

CREATE TABLE IF NOT EXISTS `work_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(1) NOT NULL,
  `profession` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `work_id` int(100) DEFAULT NULL,
  `time_start` year(4) DEFAULT NULL,
  `time_end` year(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users_work_inf` (`user_id`),
  KEY `FK_workid_work_inf` (`work_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `work_info`
--

INSERT INTO `work_info` (`id`, `user_id`, `profession`, `work_id`, `time_start`, `time_end`) VALUES
(4, 2, 'weterynarz', 4, 1945, 1945),
(5, 2, 'murarz', 5, 1945, 1945),
(6, 1, 'operator', 6, 1948, 1959),
(7, 1, 'spawacz', 7, 1961, 1969),
(8, 1, 'elektronik', 8, 1968, 1978);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_comments_photo` FOREIGN KEY (`id_photo`) REFERENCES `gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `FK_users_friends` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `FK_users_gallery` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `hobby`
--
ALTER TABLE `hobby`
  ADD CONSTRAINT `FK_users_hobby` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `FK_users_invitation` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `message_type`
--
ALTER TABLE `message_type`
  ADD CONSTRAINT `FK_message_rule_cntrl` FOREIGN KEY (`id`) REFERENCES `message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `school_info`
--
ALTER TABLE `school_info`
  ADD CONSTRAINT `FK_schoolid_school_inf` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_users_school_inf` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `users_info`
--
ALTER TABLE `users_info`
  ADD CONSTRAINT `FK_users_info` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `work_info`
--
ALTER TABLE `work_info`
  ADD CONSTRAINT `FK_users_work_inf` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_workid_work_inf` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
