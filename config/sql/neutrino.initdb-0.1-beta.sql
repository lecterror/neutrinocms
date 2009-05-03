CREATE TABLE `articles` (
  `id` int(11) NOT NULL auto_increment,
  `article_category_id` int(11) NOT NULL,
  `title` varchar(100) collate utf8_unicode_ci NOT NULL,
  `intro` varchar(255) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `content_description` varchar(500) collate utf8_unicode_ci NOT NULL,
  `content_keywords` varchar(1000) collate utf8_unicode_ci NOT NULL,
  `slug` varchar(50) collate utf8_unicode_ci NOT NULL,
  `created` datetime default NULL,
  `updated` datetime default NULL,
  `isdraft` tinyint(1) NOT NULL,
  `hitcount` int(11) NOT NULL default '0',
  `hitcount_rss` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `article_category_id` (`article_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `article_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` varchar(500) collate utf8_unicode_ci NOT NULL,
  `slug` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL auto_increment,
  `article_id` int(11) NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `website` varchar(300) collate utf8_unicode_ci default NULL,
  `email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `comment` text collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `article_author` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `value` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL auto_increment,
  `download_category_id` int(11) NOT NULL,
  `name` varchar(200) collate utf8_unicode_ci NOT NULL,
  `slug` varchar(50) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `content_description` varchar(500) collate utf8_unicode_ci NOT NULL,
  `content_keywords` varchar(1000) collate utf8_unicode_ci NOT NULL,
  `display_file_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `real_file_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `mime_type` varchar(50) collate utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `downloaded` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `downloads_ibfk_1` (`download_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `download_categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` varchar(500) collate utf8_unicode_ci NOT NULL,
  `slug` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) collate utf8_unicode_ci NOT NULL,
  `password` varchar(50) collate utf8_unicode_ci NOT NULL,
  `email` varchar(70) collate utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `class_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`article_category_id`) REFERENCES `article_categories` (`id`);

ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`download_category_id`) REFERENCES `download_categories` (`id`);

ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

