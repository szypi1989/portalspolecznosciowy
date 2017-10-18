-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 18 Cze 2014, 12:14
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `photoblog`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `black_list_ip`
--

CREATE TABLE IF NOT EXISTS `black_list_ip` (
  `login_ip` varchar(15) NOT NULL DEFAULT '',
  `logins` varchar(40) NOT NULL,
  `time_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_photo` int(255) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `contents` varchar(500) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id`, `id_photo`, `sender_id`, `contents`, `time`, `status`) VALUES
(28, 93, 12, 'heja', '2014-06-07 10:42:48', 0),
(29, 93, 12, 'heujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheujaheuj', '2014-06-12 15:39:05', 0),
(30, 96, 12, 'fdsafdasf', '2014-06-17 07:30:02', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cookies`
--

CREATE TABLE IF NOT EXISTS `cookies` (
  `cookie_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_expired` int(11) NOT NULL,
  PRIMARY KEY (`cookie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `cookies`
--

INSERT INTO `cookies` (`cookie_id`, `name`, `time_start`, `time_expired`) VALUES
(1, 'saw', 23124, 12342134);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(255) NOT NULL,
  `id_friends` varchar(180) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `friends`
--

INSERT INTO `friends` (`id`, `id_friends`) VALUES
(12, '38,14');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=97 ;

--
-- Zrzut danych tabeli `gallery`
--

INSERT INTO `gallery` (`id`, `name`, `id_user`, `description`, `time`) VALUES
(1, '1.jpg', 26, '', '2014-05-30 06:26:09'),
(2, '2.jpg', 26, '', '2014-05-30 06:26:09'),
(3, '3.jpg', 26, '', '2014-05-30 06:26:09'),
(4, '4.jpg', 26, '', '2014-05-30 06:26:09'),
(5, '5.jpg', 26, '', '2014-05-30 06:26:09'),
(6, '6.jpg', 26, '', '2014-05-30 06:26:09'),
(8, '8.jpg', 26, '', '2014-05-30 06:26:09'),
(28, '9.jpg', 26, '', '2014-05-30 06:26:09'),
(33, '1.jpg', 32, '', '2014-05-30 06:26:09'),
(79, '2.jpg', 35, '', '2014-05-30 06:26:09'),
(80, '1.jpg', 34, '', '2014-05-30 06:26:09'),
(81, '2.jpg', 34, '', '2014-05-30 06:26:09'),
(82, '3.jpg', 34, '', '2014-05-30 06:26:09'),
(83, '1.jpg', 37, '', '2014-05-30 06:26:09'),
(84, '2.jpg', 37, '', '2014-05-30 06:26:09'),
(87, '1.jpg', 38, '', '2014-05-30 06:26:09'),
(88, '2.jpg', 38, '', '2014-05-30 06:26:09'),
(89, '3.jpg', 38, '', '2014-05-30 06:26:09'),
(90, '4.jpg', 38, '', '2014-05-30 06:26:09'),
(93, '3.jpg', 12, 'na wakacjach', '2014-05-30 20:10:33'),
(96, '5.jpg', 12, '', '2014-05-31 05:33:41');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `hobby`
--

CREATE TABLE IF NOT EXISTS `hobby` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Zrzut danych tabeli `hobby`
--

INSERT INTO `hobby` (`id`, `name`) VALUES
(12, 'tenis ziemny'),
(13, 'piłka nożna'),
(31, 'golf,tenis stołowy'),
(32, 'siatkówka'),
(35, 'tenis stołowy,piłka nożna'),
(37, 'narty, pływanie'),
(38, 'tenis stołowy,golf');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `invitation`
--

CREATE TABLE IF NOT EXISTS `invitation` (
  `id` int(255) NOT NULL,
  `id_friends` varchar(180) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_freq` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_freq`),
  KEY `id_freq` (`id_freq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `invitation`
--

INSERT INTO `invitation` (`id`, `id_friends`, `id_freq`) VALUES
(12, '25', 1),
(23, '14,12', 2),
(25, '14,12', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ip_login`
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
('123.23.21', 'cros', '123.23.211358772556', 1358772099, 1358772556, 3, ''),
('123.23.21', 'crosadfdsa', '123.23.211358772092', 1358772092, 1358772092, 1, ''),
('123.23.26', 'szypi', '123.23.261358772435', 1358772436, 1358772435, 1, ''),
('178.181.144.148', 'test', '', 1389452023, 1389452047, 7, ''),
('77.254.177.129', 'szypi1989', '', 1385029331, 1385029348, 3, ''),
('77.254.179.228', 'sdaf', '', 1396992298, 1396992298, 1, ''),
('79.186.138.34', 'admin\\'')--', '', 1384178876, 1384178876, 1, ''),
('79.186.138.34', 'admin\\'');--', '', 1384178882, 1384178882, 1, ''),
('79.186.138.34', 'admin\\''--', '', 1384178859, 1384178859, 1, ''),
('79.186.138.34', 'szypi\\''--', '', 1384178822, 1384178822, 1, ''),
('79.186.141.133', 'czetus', '', 1388969097, 1388969128, 5, ''),
('79.186.147.20', 'szypi1989', '', 1389563037, 1389563074, 5, ''),
('79.186.154.161', 'admin', '79.186.154.1611384016173', 1384016170, 1384016173, 2, ''),
('79.186.154.161', 'admin\\'') --', '79.186.154.1611384016130', 1384016130, 1384016130, 1, ''),
('79.186.155.10', 'asdfasdf', '', 1389784548, 1389784548, 1, ''),
('79.186.155.10', 'szypi1989', '', 1389784577, 1389784584, 2, ''),
('81.219.90.218', 'adsfsda', '81.219.90.2181384110636', 1384110165, 1384110636, 3, ''),
('83.238.158.113', 'kazio1988', '', 1387116235, 1387116235, 1, ''),
('89.191.156.162', 'mabroz', '', 1397305081, 1397305081, 1, ''),
('89.191.156.162', 'szypi89', '', 1390672559, 1390672559, 1, ''),
('::1', 'szypi89', '::11382466650', 1382466650, 1382466650, 1, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `contents` varchar(500) NOT NULL,
  `recipient_id` int(180) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=137 ;

--
-- Zrzut danych tabeli `message`
--

INSERT INTO `message` (`id`, `sender_id`, `contents`, `recipient_id`, `time`, `status`) VALUES
(134, 12, 'hej jak tam?', 37, '2014-06-18 10:03:37', 0),
(135, 12, 'sadf', 37, '2014-06-18 10:05:20', 0),
(136, 12, 'asdfsd', 37, '2014-06-18 10:12:04', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message_control`
--

CREATE TABLE IF NOT EXISTS `message_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipients_id` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sender_id_2` (`sender_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=371 ;

--
-- Zrzut danych tabeli `message_control`
--

INSERT INTO `message_control` (`id`, `sender_id`, `recipients_id`) VALUES
(13, 36, ''),
(282, 12, ''),
(339, 38, ''),
(351, 37, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message_type`
--

CREATE TABLE IF NOT EXISTS `message_type` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `spam` tinyint(1) NOT NULL,
  `id_foreign` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_foreign`),
  KEY `FK_message_rule_cntrl` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=335 ;

--
-- Zrzut danych tabeli `message_type`
--

INSERT INTO `message_type` (`id`, `id_user`, `hidden`, `spam`, `id_foreign`) VALUES
(134, 12, 0, 0, 329),
(134, 37, 0, 0, 330),
(135, 12, 0, 0, 331),
(135, 37, 0, 0, 332),
(136, 12, 0, 0, 333),
(136, 37, 0, 0, 334);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `schools`
--

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degree_school` int(11) NOT NULL DEFAULT '0',
  `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `place` varchar(40) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `street_nr` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Zrzut danych tabeli `schools`
--

INSERT INTO `schools` (`id`, `degree_school`, `name`, `place`, `street`, `street_nr`) VALUES
(1, 5, 'wyższa szkoła informatyki', 'Bydgoszcz', 'Fordońska', '246'),
(2, 3, 'Neks', 'Toruń', 'Toruńska', '246'),
(3, 4, 'Technikum mechaniczne', 'Bydgoszcz', 'Toruńska', '101'),
(4, 0, 'ukw', 'bydgoszcz', NULL, NULL),
(5, 0, 'ukw c', 'bydgoszcz', NULL, NULL),
(6, 0, 'wyzsza szkola bankowa', 'bydgoszcz', NULL, NULL),
(7, 0, 'wyzsza szkola gospodarki', 'bydgoszcz', NULL, NULL),
(8, 0, 'wyzsza szkola poloznictwa', 'bydgoszcz', NULL, NULL),
(9, 0, 'wyzsza szkola polożnictwa', 'bydgoszcz', NULL, NULL),
(10, 0, 'wyzsza szkola żnictwa', 'bydgoszcz', NULL, NULL),
(11, 0, 'wyzsza szkola położnictwa', 'bydgoszcz', NULL, NULL),
(12, 0, 'wyzsza szkola noznictwa', 'bydgoszcz', NULL, NULL),
(13, 0, 'wyzsza szkola położnictwa', 'łódz', NULL, NULL),
(14, 0, 'wsg', 'bydgoszcz', NULL, NULL),
(15, 0, 'wsinuiu', 'bydgoszcz', NULL, NULL),
(16, 0, 'wyższa szkoła melanżu', 'poznań', NULL, NULL),
(17, 0, 'politechnika wrocławska', 'wrocław', NULL, NULL),
(18, 0, 'neks', 'bydgoszcz', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `school_info`
--

CREATE TABLE IF NOT EXISTS `school_info` (
  `id_key` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(1) NOT NULL,
  `specialization` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `school_id` int(100) DEFAULT NULL,
  `time_start` year(4) DEFAULT NULL,
  `time_end` year(4) DEFAULT NULL,
  PRIMARY KEY (`id_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `school_info`
--

INSERT INTO `school_info` (`id_key`, `id`, `specialization`, `school_id`, `time_start`, `time_end`) VALUES
(1, 13, 'pedagogika', 2, 2016, 2007),
(2, 38, 'inżynieria i programowanie aplikacji mobilnych', 1, 2011, 2016),
(3, 32, 'programowanie i bazy danych', 14, 1948, 1955),
(4, 38, '', 2, 1945, 1949),
(5, 35, 'mechanika', 3, 1945, 1945),
(6, 34, 'informatyka i technologia sieci', 16, 1945, 1945),
(7, 37, 'bulilding management system', 17, 2004, 2006),
(8, 12, 'programowanie i bazy danych', 14, 2012, 2014),
(9, 12, 'pedagogika', 18, 1945, 1954);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(120) CHARACTER SET utf8 NOT NULL,
  `session_user` int(8) NOT NULL DEFAULT '0',
  `session_ip` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `session_browser` varchar(200) CHARACTER SET utf8 NOT NULL,
  `session_time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `sessions`
--

INSERT INTO `sessions` (`session_id`, `session_user`, `session_ip`, `session_browser`, `session_time`) VALUES
('89.191.156.1621390728023', 34, '89.191.156.162', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36', 1390728023),
('89.68.188.471392339613', 38, '89.68.188.47', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36', 1392339613),
('89.191.156.1621397305112', 12, '89.191.156.162', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36', 1397305220),
('89.191.156.1621397305335', 37, '89.191.156.162', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0', 1397305502),
('81.219.90.1741398362096', 12, '81.219.90.174', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1398432957),
('81.219.90.1741398415277', 12, '81.219.90.174', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1398432957),
('81.219.90.1741398428323', 12, '81.219.90.174', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1398432957),
('81.219.90.1741398432868', 12, '81.219.90.174', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1398432957),
('77.254.179.271398616337', 12, '77.254.179.27', 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1398616578),
('81.219.91.2521400687658', 12, '81.219.91.252', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.137 Safari/537.36', 1400687729),
('81.219.91.2521400688306', 12, '81.219.91.252', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0', 1400688310),
('::11401412997', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36', 1401413041),
('::11402322226', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402328143', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402337082', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402400876', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402404457', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402422341', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402503390', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402509188', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402509189', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:29.0) Gecko/20100101 Firefox/29.0', 1402511299),
('::11402579712', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402584148', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402595442', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402765268', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402765270', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402770121', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402849308', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402915221', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402989566', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402994232', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11402999144', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11403071381', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11403082532', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394),
('::11403085661', 12, '::1', 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0', 1403086394);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(32) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `user_lastvisit` int(8) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_lastvisit`) VALUES
(12, 'szypi1989', '98ce3010caf21876324addaf1a0f4aa2', 33343),
(13, 'mario1989', 'de2f15d014d40b93578d255e6221fd60', 33343),
(14, 'ela1988', 'de2f15d014d40b93578d255e6221fd60', 33343),
(15, 'nowy1988', '3b64714ef99d64fece28f6d40bb418db', 1357828298),
(16, 'starter', '7ec942cee2635e4bed3c18973f7456db', 1383390730),
(17, 'dupek', '2ee936ae0a90a242877e89d83f46162e', 1384016375),
(19, 'haker1889', '7ec942cee2635e4bed3c18973f7456db', 1384068110),
(20, 'mario', '7ec942cee2635e4bed3c18973f7456db', 1384178924),
(21, 'adam', 'ec9d144aa9e820189850c698f0e3bf39', 1384196715),
(22, 'nowy1900', '7ec942cee2635e4bed3c18973f7456db', 1384692807),
(23, 'mariusz1989', '1528b899df17af95752ab8e2244be9a9', 1384694285),
(25, 'nowy11', '1528b899df17af95752ab8e2244be9a9', 1384694619),
(26, 'costam', '7ec942cee2635e4bed3c18973f7456db', 1384694679),
(27, 'haker1989', '7ec942cee2635e4bed3c18973f7456db', 1384697062),
(28, 'mokster1988', '7ec942cee2635e4bed3c18973f7456db', 1384698878),
(29, 'czetus', 'bf0f72c625d83b30662fc2dc18787bb3', 1384811568),
(30, 'alex1988', '7ec942cee2635e4bed3c18973f7456db', 1385029121),
(31, 'kazik1989', '7ec942cee2635e4bed3c18973f7456db', 1387116221),
(32, 'karol1989', '7ec942cee2635e4bed3c18973f7456db', 1388596857),
(33, 'merlin', '65d5e6eaf7488707aa8c6e2a1084a10e', 1389389450),
(34, 'szymon', 'a8f5f167f44f4964e6c998dee827110c', 1389434243),
(35, 'test', '83560a75c016ee68f0dd71bf1bb35b84', 1389451651),
(36, 'fearheart', 'd033f020cdac803e766cbfc904c08b98', 1390139780),
(37, 'grad', '98ce3010caf21876324addaf1a0f4aa2', 1390672144),
(38, 'example', '9df3b01c60df20d13843841ff0d4482c', 1392339601);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `sex` int(1) NOT NULL,
  `birth` date DEFAULT NULL,
  `mail` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `code_authorization` varchar(128) CHARACTER SET latin1 NOT NULL,
  `village` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  `Opis` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'brak',
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  `surname` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'brak',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=39 ;

--
-- Zrzut danych tabeli `users_info`
--

INSERT INTO `users_info` (`id`, `sex`, `birth`, `mail`, `active`, `code_authorization`, `village`, `Opis`, `name`, `surname`) VALUES
(12, 2, '1943-01-03', 'tymczasowy777@onet.pl', 1, 'fac0f78694f1a28e69ed173db805eefd$tmzsw18ycaoy99', 'warszawa', 'Sportowiec', 'mariusz', 'szypuła'),
(13, 2, '1990-08-04', 'tymczasowy777@onet.pl', 1, 'fac0f78694f1a28e69ed173db805eefd$tmzsw18ycaoy99', '', 'Sportowiec', 'mariusz', 'szypuła'),
(14, 1, '1990-08-02', 'tymczasowy777@onet.pl', 1, 'fac0f78694f1a28e69ed173db805eefd$tmzsw18ycaoy99', 'Bydgoszcz', 'Sportowiec', 'mariusz', 'szypuła'),
(15, 2, '1988-07-04', 'witar385398452@onet.pl', 0, '233fec4e83b4dfa8ef0d6be1e36446c3$tkt99es18', 'brak', 'brak', 'brak', 'brak'),
(16, 2, '1989-10-02', 'sszypula89@onet.pl', 0, 'c952733625882a3b4a3a19bba544ce7e$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(17, 1, '1960-03-26', 'dupek@wp.pl', 0, '49a2459260357487f5138a4b115c458c$dupekdupek', 'brak', 'brak', 'brak', 'brak'),
(19, 2, '1945-01-01', 'cos@onet.pl', 0, '57a024fdfe91cfd01205345efee80fb9$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(20, 2, '1945-01-01', 'szypulaa89@onet.pl', 0, 'd21d1d09582562f6ef5044b37d5e21d0$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(21, 2, '1989-06-01', 'nowys19@onet.cd', 0, '1722c642d2d91485dc931876c320cd17$hkr8ae88', 'brak', 'brak', 'brak', 'brak'),
(22, 2, '1960-09-01', 'nowys19@onet.fd', 0, '6f44a9a708e8ee6e8e8f7a28a52870ae$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(23, 2, '1958-08-12', 'sdszypula89@onet.pl', 0, '1729c93cc40ebc4459b5d8e5401c924a$mga7ai77', 'brak', 'brak', 'brak', 'brak'),
(25, 2, '1979-08-01', 'sdfszypula89@onet.pl', 0, 'e1d91dd5efc7e85e019aa864daf73333$mga7ai77', 'brak', '', 'Kaziu', 'Pies'),
(26, 2, '1961-01-01', 'trendisk@onet.de', 0, '4d5462d2f650bcf8a52bf9af44399dac$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(27, 2, '1945-01-01', 'asdfaszypula89@onet.pl', 0, '6b4324cc73d04c16de67c5c665be0823$hkr7ae77', 'brak', 'brak', 'andrzej', 'flot'),
(28, 2, '1945-01-01', 'szypula89@onet.pl', 0, 'f0da84d7b5ea1213694ede5f41338aca$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(29, 2, '1956-03-10', 'czetus@wp.pl', 0, 'b045bd4baf45235055b02a19897ad69e$jnkaek', 'brak', 'brak', 'brak', 'brak'),
(30, 2, '1959-11-01', 'alex@onet.pl', 0, '0cdfe178aa85fb959a04719edaaab22b$hkr7ae77', 'brak', 'brak', 'brak', 'brak'),
(31, 2, '1959-08-01', 'kazikowski@onet.pl', 0, '935d75e38ff25a0d88749c4a1e037a34$hkr7ae77', 'osowa góra', 'brak', 'marcin', 'Kazikowski'),
(32, 2, '1953-09-01', 'urbanski@onet.pl', 0, 'aef1205b22741aed334a68370bd9ec28$hkr7ae77', 'warszawa', 'brak', 'Karol', 'urbański'),
(33, 2, '1945-01-01', 'wojek@wp.pl', 0, '1b685cfa3a4e039c2335314550770da4$badN0lnya0', 'brak', 'brak', 'Peter', 'Janek'),
(34, 1, '1945-01-01', 'szymon@onet.pl', 0, 'f304ff4cce48f2f065bd1c86ae835e21$adssad', 'bydgoszcz', 'brak', 'szymon', 'mariusz'),
(35, 2, '1960-08-01', 'pilskuski@onet.pl', 0, '086c42d7cd4822bf9fbd6133aee36746$ts77et7', 'bydgoszcz', 'brak', 'jurek', 'piłsuski'),
(36, 2, '1945-01-01', 'karson92@wp.pld', 0, '08ab2e5b6cd45cf4785075094e5d967d$krl2ao1', 'brak', 'brak', 'Zbychu', 'Zdzichu'),
(37, 2, '1945-01-01', 'grad@wp.pl', 0, 'magia', 'bydgoszcz', 'brak', 'piotr', 'grad'),
(38, 1, '1945-07-01', 'asas@wp.pl', 0, 'c5037f14cc26975433af22d1acd88baa$ssnaaak', 'bydgoszcz', 'brak', 'mariusz', 'example');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `vilage`
--

CREATE TABLE IF NOT EXISTS `vilage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Zrzut danych tabeli `vilage`
--

INSERT INTO `vilage` (`id`, `name`) VALUES
(1, 'Bydgoszcz'),
(14, 'Warszawa'),
(15, 'Wrocław'),
(16, 'Nowa Wieś Wielka'),
(17, 'osowa góra'),
(18, 'naklo'),
(19, 'nakola'),
(20, 'wars'),
(21, 'waró'),
(22, 'nakło na notecią'),
(23, 'nakło'),
(24, 'nakło nad notecią'),
(25, 'nakło nad'),
(26, 'nakło nad noteciąsdfsd'),
(27, 'nak'),
(28, 'łódz'),
(29, 'brak');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `works`
--

CREATE TABLE IF NOT EXISTS `works` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  `place` varchar(40) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `street_nr` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `works`
--

INSERT INTO `works` (`id`, `name`, `place`, `street`, `street_nr`) VALUES
(1, 'Erplast', 'Bydgoszcz', 'Fordońska', '246'),
(2, 'Pesa', 'Toruń', 'jagiellońska', '25'),
(3, 'erplast', 'waszawa', NULL, NULL),
(4, 'pesa', 'bydgoszcz', NULL, NULL),
(5, 'utp', 'bydgoszcz', NULL, NULL),
(6, 'wsiu', 'bydgoszcz', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `work_info`
--

CREATE TABLE IF NOT EXISTS `work_info` (
  `id_key` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(1) NOT NULL,
  `profession` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `work_id` int(100) DEFAULT NULL,
  `time_start` year(4) DEFAULT NULL,
  `time_end` year(4) DEFAULT NULL,
  PRIMARY KEY (`id_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `work_info`
--

INSERT INTO `work_info` (`id_key`, `id`, `profession`, `work_id`, `time_start`, `time_end`) VALUES
(1, 38, 'spawacz', 1, 1950, 2014),
(2, 38, 'operator maszyn CNC i programista', 2, 1993, 2013),
(3, 37, 'belfer', 6, 2000, 2014);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `message_type`
--
ALTER TABLE `message_type`
  ADD CONSTRAINT `FK_message_rule_cntrl` FOREIGN KEY (`id`) REFERENCES `message` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
