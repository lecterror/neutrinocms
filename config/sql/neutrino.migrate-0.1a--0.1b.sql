ALTER TABLE `articles`
	CHANGE `content_keywords` `content_keywords` varchar(1000)
		CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `downloads`
	CHANGE `content_keywords` `content_keywords` varchar(1000)
		CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `articles`
	CHANGE `content_description` `content_description` varchar(500)
		CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `downloads`
	CHANGE `content_description` `content_description` varchar(500)
		CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `class_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `configuration` (`name`, `value`)
VALUES ('SiteCopyrightNotice', 'Your copyright notice here');

INSERT INTO `configuration` (`name`, `value`)
VALUES ('GoogleWebmasterToolsVerificationCode', '');

INSERT INTO `configuration` (`name`, `value`)
VALUES ('GoogleAnalyticsAccountCode', '');