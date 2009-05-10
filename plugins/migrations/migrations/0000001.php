<?php
/*
	This file is part of NeutrinoCMS.

	NeutrinoCMS is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	NeutrinoCMS is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with NeutrinoCMS.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * NeutrinoCMS initial DB migration (at the moment of creation of the "migration system")
 */

class Migration0000001 extends BaseMigration
{
	function id()
	{
		return '0000001';
	}

	function description()
	{
		return <<<END
Initial database migration.
Pretty much identical to 0.1-beta release of NeutrinoCMS.
END;
	}

	function check()
	{
		$conn = $this->getDboSource();
		$tables = $conn->listSources();

		if (!empty($tables))
		{
			return false;
		}

		return true;
	}

	function up()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		$conn->execute
			(
				'
				create table articles
				(
  					id					int(11) not null auto_increment,
					article_category_id	int(11) not null,
					title				varchar(100) collate utf8_unicode_ci not null,
					intro				varchar(255) collate utf8_unicode_ci not null,
					content				text collate utf8_unicode_ci not null,
					content_description	varchar(500) collate utf8_unicode_ci not null,
					content_keywords	varchar(1000) collate utf8_unicode_ci not null,
					slug				varchar(50) collate utf8_unicode_ci not null,
					created				datetime default null,
					updated				datetime default null,
					isdraft				tinyint(1) not null,
					hitcount			int(11) not null default \'0\',
					hitcount_rss		int(11) not null default \'0\',
					primary key			(id),
					unique key			articles_title (title),
					index				article_category_id (article_category_id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table article_categories
				(
					id			int(11) NOT NULL auto_increment,
					name		varchar(100) collate utf8_unicode_ci not null,
					description	varchar(500) collate utf8_unicode_ci not null,
					slug		varchar(50) collate utf8_unicode_ci not null,
					primary key	(id),
					unique key	article_categories_name (name)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table comments
				(
					id				int(11) not null auto_increment,
					article_id		int(11) not null,
					name			varchar(50) collate utf8_unicode_ci not null,
					website			varchar(300) collate utf8_unicode_ci default null,
					email			varchar(100) collate utf8_unicode_ci not null,
					comment			text collate utf8_unicode_ci not null,
					created			datetime not null,
					article_author	tinyint(1) not null default \'0\',
					primary key		(id),
					index			comments_article_id (article_id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table configuration
				(
					id			int(11) not null auto_increment,
					name		varchar(50) collate utf8_unicode_ci not null,
					value		text collate utf8_unicode_ci not null,
					primary key	(id),
					unique key	configuration_name (name)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table downloads
				(
					id						int(11) not null auto_increment,
					download_category_id	int(11) not null,
					name					varchar(200) collate utf8_unicode_ci not null,
					slug					varchar(50) collate utf8_unicode_ci not null,
					description				text collate utf8_unicode_ci NOT NULL,
					content_description		varchar(500) collate utf8_unicode_ci not null,
					content_keywords		varchar(1000) collate utf8_unicode_ci not null,
					display_file_name		varchar(255) collate utf8_unicode_ci not null,
					real_file_name			varchar(255) collate utf8_unicode_ci not null,
					mime_type				varchar(50) collate utf8_unicode_ci not null,
					size					int(11) not null,
					published				tinyint(1) not null,
					created					datetime not null,
					downloaded				int(11) not null,
					primary key				(id),
					unique key				downloads_slug (slug),
					index					downloads_download_category_id (download_category_id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table download_categories
				(
					id			int(11) not null auto_increment,
					name		varchar(100) collate utf8_unicode_ci not null,
					description	varchar(500) collate utf8_unicode_ci not null,
					slug		varchar(50) collate utf8_unicode_ci not null,
					primary key	(id),
					unique key	download_categories_name (name)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table users
				(
					id			int(11) not null auto_increment,
					username	varchar(50) collate utf8_unicode_ci not null,
					password	varchar(50) collate utf8_unicode_ci not null,
					email		varchar(70) collate utf8_unicode_ci not null,
					first_name	varchar(50) collate utf8_unicode_ci not null,
					last_name	varchar(50) collate utf8_unicode_ci not null,
					last_login	datetime not null,
					primary key	(id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table ratings
				(
					id			int(11) not null auto_increment,
					class_name	varchar(50) collate utf8_unicode_ci not null,
					foreign_id	int(11) not null,
					rating		int(11) not null,
					created		datetime not null,
					primary key	(id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				alter table articles add
					constraint fk_articles_articlecategories
	  					foreign key (article_category_id)
	  					references article_categories (id)
				'
			);

		$conn->execute
			(
				'
				alter table downloads add
					constraint fk_downloads_downloadcategories
	  					foreign key (download_category_id)
	  					references download_categories (id)
				'
			);

		$conn->execute
			(
				'
				alter table comments add
					constraint fk_comments_articles
	  					foreign key (article_id)
	  					references articles (id)
				'
			);

		return true;
	}

	function down()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		$conn->execute
			(
				'
				alter table articles
					drop foreign key fk_articles_articlecategories
				'
			);

		$conn->execute
			(
				'
				alter table downloads
					drop foreign key fk_downloads_downloadcategories
				'
			);

		$conn->execute
			(
				'
				alter table comments
					drop foreign key fk_comments_articles
				'
			);

		$conn->execute('drop table ratings');
		$conn->execute('drop table comments');
		$conn->execute('drop table articles');
		$conn->execute('drop table article_categories');
		$conn->execute('drop table downloads');
		$conn->execute('drop table download_categories');
		$conn->execute('drop table configuration');
		$conn->execute('drop table users');

		return true;
	}

}

?>