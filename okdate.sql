-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 30 Juillet 2015 à 18:25
-- Version du serveur: 5.5.41
-- Version de PHP: 5.5.26-1~dotdeb+7.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `site_name` varchar(255) NOT NULL,
  `site_tagline` varchar(255) NOT NULL,
  `site_description` text NOT NULL,
  `site_tags` text NOT NULL,
  `site_analytics` text NOT NULL,
  `site_age_limit` int(11) NOT NULL DEFAULT '0',
  `bgcolor_navbar` varchar(10) NOT NULL,
  `textcolor_navbar` varchar(10) NOT NULL,
  `bgcolor_main` varchar(10) NOT NULL,
  `web_captcha` int(11) NOT NULL DEFAULT '1',
  `enable_payments` int(11) NOT NULL DEFAULT '1',
  `fb_url` varchar(255) NOT NULL,
  `twitter_url` varchar(255) NOT NULL,
  `instagram_url` varchar(255) NOT NULL,
  `googleplus_url` varchar(255) NOT NULL,
  `ads_code` text NOT NULL,
  `inapp_price` double NOT NULL,
  `paypal_api_username` varchar(255) NOT NULL,
  `paypal_api_pw` varchar(255) NOT NULL,
  `paypal_api_sign` varchar(255) NOT NULL,
  `stripe_secret_key` varchar(255) NOT NULL,
  `stripe_pub_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin` (`site_name`, `site_tagline`, `site_description`, `site_tags`, `site_analytics`, `site_age_limit`, `bgcolor_navbar`, `textcolor_navbar`, `bgcolor_main`, `web_captcha`, `enable_payments`, `fb_url`, `twitter_url`, `instagram_url`, `googleplus_url`, `ads_code`, `inapp_price`, `paypal_api_username`, `paypal_api_pw`, `paypal_api_sign`, `stripe_secret_key`, `stripe_pub_key`) VALUES
('OKDate', 'Website Tagline', 'Website Description', 'Website Keywords', '', 0, '', '', '', 1, 1, 'https://www.facebook.com/hardy.axel?ref=bookmarks', 'http://twitter.com/fraxool', '', 'https://plus.google.com/103841012435165031166/posts', '', 1.5, '', '', '', '', '');


-- --------------------------------------------------------

--
-- Structure de la table `captcha`
--

CREATE TABLE IF NOT EXISTS `captcha` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(60) NOT NULL DEFAULT '',
  `answer` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `captcha_question` (`question`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `captcha`
--

INSERT INTO `captcha` (`id`, `question`, `answer`) VALUES
(4, 'How much is fourteen minus five ?', '9'),
(3, 'How much is ten minus four ?', '6'),
(2, 'How much is eight more four ?', '12'),
(1, 'How much is one more six ?', '7'),
(5, 'How much is six more five ?', '11'),
(6, 'How much is four minus three ?', '1'),
(7, 'How much is three minus one ?', '2');

-- --------------------------------------------------------

--
-- Structure de la table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- --------------------------------------------------------

--
-- Structure de la table `friend_notif`
--

CREATE TABLE IF NOT EXISTS `friend_notif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `seen` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `thumb_url` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `votes` int(11) DEFAULT '0',
  `comments` int(11) DEFAULT '0',
  `views` int(11) DEFAULT '0',
  `text` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Structure de la table `pm_conv`
--

CREATE TABLE IF NOT EXISTS `pm_conv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `last_answer_date` datetime NOT NULL,
  `nb_messages` int(11) NOT NULL,
  `is_read_sender` int(11) NOT NULL,
  `is_read_recipient` int(11) NOT NULL DEFAULT '0',
  `last_answer_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `sender_id` (`sender_id`),
  KEY `relationship_conv_from_to` (`sender_id`,`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `pm_email_notif`
--

CREATE TABLE IF NOT EXISTS `pm_email_notif` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `pm_message`
--

CREATE TABLE IF NOT EXISTS `pm_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `conv_id` int(11) NOT NULL,
  `read` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `conv_id` (`conv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `encrypt_id` varchar(255) DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `banned` int(11) DEFAULT NULL,
  `banned_reason` text,
  `first_step_form` int(11) DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  `valid_snapchat` int(11) DEFAULT '0',
  `introduced_forum` int(11) DEFAULT '0',
  `shared_snapals` int(11) DEFAULT '0',
  `referer` varchar(255) DEFAULT NULL,
  `allow_social_featuring` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `fb_id` bigint(20) DEFAULT NULL,
  `rate_app_status` int(11) DEFAULT NULL,
  `last_activity_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_action`
--

CREATE TABLE IF NOT EXISTS `user_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text,
  `date` datetime DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_block`
--

CREATE TABLE IF NOT EXISTS `user_block` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_friend`
--

CREATE TABLE IF NOT EXISTS `user_friend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `seen` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


-- --------------------------------------------------------

--
-- Structure de la table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gender` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `about` text,
  `main_photo` int(11) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `relation_type` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `app_pref_pm` int(11) DEFAULT NULL,
  `browse_invisibly` int(11) DEFAULT '0',
  `interested_in` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `main_photo` (`main_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_love`
--

CREATE TABLE IF NOT EXISTS `user_love` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `viewed` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to_user_id` (`to_user_id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `relationship_to_from` (`to_user_id`,`from_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_password_recovery`
--

CREATE TABLE IF NOT EXISTS `user_password_recovery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `encrypt_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_profile_visit`
--

CREATE TABLE IF NOT EXISTS `user_profile_visit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `viewed` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `profile_id` (`profile_id`),
  KEY `relationship_to_from_visits` (`user_id`,`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `user_purchase`
--

CREATE TABLE IF NOT EXISTS `user_purchase` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `purchase_name` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `welcome_message`
--

CREATE TABLE IF NOT EXISTS `welcome_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `custom_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `icon` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Structure de la table `forum_answers`
--

CREATE TABLE IF NOT EXISTS `forum_answers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text,
  `topic_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `forum_categories`
--

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `forum_topics`
--

CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` text,
  `date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `last_answer_id` int(11) DEFAULT NULL,
  `last_answer_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `sticky` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `admin` ADD `enable_forum` INT NOT NULL DEFAULT '0' AFTER `enable_payments`;
ALTER TABLE `admin` ADD `paygol_service_id` INT NOT NULL, ADD `paygol_service_name` VARCHAR( 255 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `user_coin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nb_coins` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `coin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb` int(11) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `coin` (`id`, `nb`, `price`) VALUES
(1, 100, 2),
(2, 500, 6),
(3, 1000, 10);


CREATE TABLE IF NOT EXISTS `user_featured` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `admin` ADD `see_who_loves_you_price` INT NOT NULL DEFAULT '100',
ADD `browse_invisibly_price` INT NOT NULL DEFAULT '150',
ADD `featured_one_week_price` INT NOT NULL DEFAULT '100',
ADD `featured_one_month_price` INT NOT NULL DEFAULT '300';

ALTER TABLE `user_featured` ADD `purchase_name` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE  `admin` ADD  `default_language` VARCHAR( 255 ) NOT NULL DEFAULT  'english';
INSERT INTO `admin` (`default_language`) VALUES ('english');


CREATE TABLE IF NOT EXISTS `language_redirection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `admin` ADD `upload_limit` INT NOT NULL DEFAULT 0 ;

CREATE TABLE IF NOT EXISTS `photo_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `content` text,
  `user_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `photo_vote` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_custom_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `attr_name` varchar(255) NOT NULL,
  `attr_val` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `report` ADD `type` INT NOT NULL AFTER `date`;
ALTER TABLE `custom_page` ADD `welcome_enable` INT NOT NULL AFTER `date` ;
ALTER TABLE `admin` ADD `currency` VARCHAR( 10 ) NOT NULL ;
ALTER TABLE `admin` ADD `hide_country` INT NOT NULL ;
ALTER TABLE `admin` ADD `hide_timeline` INT NOT NULL ;
ALTER TABLE `admin` ADD `user_extra_fields` TEXT NOT NULL ;
ALTER TABLE `admin` ADD `online_delay` INT NOT NULL ;

SET collation_connection = 'utf8_general_ci' ;

ALTER TABLE user CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_info CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE admin CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE ci_sessions CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE pm_conv CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE pm_message CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE captcha CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE forum_answers CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE forum_topics CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_custom_field CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE coin CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE custom_page CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE forum_answers CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE forum_categories CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE forum_topics CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE friend_notif CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE language_redirection CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE photo CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE photo_comment CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE photo_vote CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE pm_email_notif CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE report CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_action CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_block CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_coin CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_custom_field CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_featured CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_friend CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_info CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_love CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_password_recovery CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_profile_visit CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE user_purchase CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE welcome_message CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `user_info` ADD `language_id` INT NOT NULL;
ALTER TABLE `user` ADD `is_fake` INT NOT NULL DEFAULT '0';
ALTER TABLE `admin` ADD `f_bg_color` VARCHAR( 100 ) NOT NULL AFTER `bgcolor_main` ;
ALTER TABLE `admin` ADD `s_bg_color` VARCHAR( 100 ) NOT NULL AFTER `f_bg_color` ;
ALTER TABLE `admin` ADD `f_txt_color` VARCHAR( 100 ) NOT NULL AFTER `bgcolor_main` ;
ALTER TABLE `admin` ADD `s_txt_color` VARCHAR( 100 ) NOT NULL AFTER `f_txt_color` ;
ALTER TABLE `admin` ADD `s_third_color` VARCHAR( 100 ) NOT NULL AFTER `s_txt_color` ;
ALTER TABLE `admin` ADD `main_block_color` VARCHAR( 100 ) NOT NULL AFTER `bgcolor_main` ;
ALTER TABLE `admin` ADD `main_txt_color` VARCHAR( 100 ) NOT NULL AFTER `main_block_color` ;
ALTER TABLE `admin` ADD `logo_color` VARCHAR( 100 ) NOT NULL AFTER `s_bg_color` ;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
