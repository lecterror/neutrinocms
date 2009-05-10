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

class Migration0000002 extends BaseMigration
{
	function id()
	{
		return '0000002';
	}

	function description()
	{
		return <<<END
Creates ACL tables.
END;
	}

	function check()
	{
		$conn = $this->getDboSource();
		$tables = $conn->listSources();

		if (!empty($tables) && in_array('acos', $tables))
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
				create table aros
				(
					id			integer(10) unsigned not null auto_increment,
					parent_id	integer(10) default null,
					model		varchar(255) default \'\',
					foreign_key	integer(10) unsigned default null,
					alias		varchar(255) default \'\',
					lft			integer(10) default null,
					rght		integer(10) default null,
					primary key	(id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table acos
				(
					id			integer(10) unsigned not null auto_increment,
					parent_id	integer(10) default null,
					model		varchar(255) default \'\',
					foreign_key	integer(10) unsigned default null,
					alias		varchar(255) default \'\',
					lft			integer(10) default null,
					rght		integer(10) default null,
					primary key	(id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);

		$conn->execute
			(
				'
				create table aros_acos
				(
					id			integer(10) unsigned not null auto_increment,
					aro_id		integer(10) unsigned not null,
					aco_id		integer(10) unsigned not null,
					_create		char(2) not null default 0,
					_read		char(2) not null default 0,
					_read_own	char(2) not null default 0,
					_update		char(2) not null default 0,
					_update_own	char(2) not null default 0,
					_delete		char(2) not null default 0,
					_delete_own	char(2) not null default 0,
					primary key	(id)
				)
				default charset=utf8
				collate=utf8_unicode_ci
				'
			);
	}

	function down()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		$conn->execute('drop table aros_acos');
		$conn->execute('drop table aros');
		$conn->execute('drop table acos');
	}
}

?>