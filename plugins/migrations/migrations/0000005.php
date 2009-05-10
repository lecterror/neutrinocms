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

class Migration0000005 extends BaseMigration
{
	function id()
	{
		return '0000005';
	}

	function description()
	{
		return <<<END
Modify users table to conform the new features: register, validate email,
change password, reset password, etc.
END;
	}

	function check()
	{
		$conn = $this->getDboSource();
		$tables = $conn->listSources();

		if (!in_array('users', $tables))
		{
			return true;
		}

		$result = $this->execute('describe users');

		if (empty($result))
		{
			return true;
		}

		if (!Set::matches('/COLUMNS[Field=is_built_in]', $result))
		{
			return true;
		}

		return false;
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
				alter table users
					add column homepage varchar(150) null
					after email
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column logins integer(11) not null default 0
					after last_login
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column is_built_in tinyint(1) not null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column is_root tinyint(1) not null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column is_verified tinyint(1) not null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column is_locked tinyint(1) not null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column is_banned tinyint(1) not null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column hash varchar(255) null
				'
			);

		$conn->execute
			(
				'
				alter table users
					add column hash_expires datetime null
				'
			);

		$result = $this->execute('select * from users limit 0,1');

		// not an update?
		if (empty($result))
		{
			return true;
		}

		$userId = $result[0]['users']['id'];
		$conn->execute
			(
				sprintf
				(
					'
					update users set
						is_built_in	= 1,
						is_root		= 1,
						is_verified	= 1,
						is_locked	= 0,
						is_banned	= 0
					where id = %s
					',
					$userId
				)
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

		$conn->execute('alter table users drop column homepage');
		$conn->execute('alter table users drop column logins');
		$conn->execute('alter table users drop column is_built_in');
		$conn->execute('alter table users drop column is_root');
		$conn->execute('alter table users drop column is_verified');
		$conn->execute('alter table users drop column is_locked');
		$conn->execute('alter table users drop column is_banned');
		$conn->execute('alter table users drop column hash');
		$conn->execute('alter table users drop column hash_expires');

		return true;
	}
}

?>