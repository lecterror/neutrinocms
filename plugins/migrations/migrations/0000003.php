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

class Migration0000003 extends BaseMigration
{
	function id()
	{
		return '0000003';
	}

	function description()
	{
		return <<<END
Adds user_id column to nearly all tables and updates all
rows to set the owner to the only user currently present
in the database.

Comments and ratings can have a null user because they
are made public.
END;
	}

	function check()
	{
		$conn = $this->getDboSource();
		$tables = $conn->listSources();

		if (!in_array('articles', $tables))
		{
			return true;
		}

		$result = $this->execute('describe articles');

		if (empty($result))
		{
			return true;
		}

		if (!Set::matches('/COLUMNS[Field=user_id]', $result))
		{
			return true;
		}

		return false;
	}

	function _addUserId(&$connection, $table, $null = false)
	{
		$connection->execute
			(
				sprintf
				(
					'
					alter table %s
						add column user_id integer(11) %s null
						after id
					',
					$table,
					($null ? '' : 'not')
				)
			);
	}

	function _removeUserId(&$connection, $table)
	{
		$connection->execute
			(
				sprintf
				(
					'alter table %s drop column user_id',
					$table
				)
			);
	}

	function _initializeUserId(&$connection, $table, $userId, $conditions = '1 = 1')
	{
		$connection->execute
			(
				sprintf
				(
					'update %s set user_id = %s where %s',
					$table,
					$userId,
					$conditions
				)
			);
	}

	function _addUserIdRelation(&$connection, $table)
	{
		$connection->execute
			(
				sprintf
				(
					'
					alter table %1$s add
						constraint fk_%1$s_users
	  					foreign key (user_id)
	  					references users (id)
	  				',
					$table
				)
			);
	}

	function _removeUserIdRelation(&$connection, $table)
	{
		$connection->execute
			(
				sprintf
				(
					'
					alter table %1$s
						drop index fk_%1$s_users
	  				',
					$table
				)
			);
	}

	function up()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		$this->_addUserId($conn, 'articles');
		$this->_addUserId($conn, 'article_categories');
		$this->_addUserId($conn, 'comments', true);
		$this->_addUserId($conn, 'downloads');
		$this->_addUserId($conn, 'download_categories');
		$this->_addUserId($conn, 'ratings', true);

		$result = $conn->query('select * from users limit 0,1');
		$userId = Set::extract('/users/id', $result);

		if (!empty($userId))
		{
			$userId = $userId[0];

			$this->_initializeUserId($conn, 'articles', $userId);
			$this->_initializeUserId($conn, 'article_categories', $userId);
			$this->_initializeUserId($conn, 'downloads', $userId);
			$this->_initializeUserId($conn, 'download_categories', $userId);
			$this->_initializeUserId($conn, 'comments', $userId, 'article_author = 1');
		}

		$this->_addUserIdRelation($conn, 'articles');
		$this->_addUserIdRelation($conn, 'article_categories');
		$this->_addUserIdRelation($conn, 'downloads');
		$this->_addUserIdRelation($conn, 'download_categories');
		$this->_addUserIdRelation($conn, 'comments');
		$this->_addUserIdRelation($conn, 'ratings');

		return true;
	}

	function down()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}
// ??
//		$this->_removeUserIdRelation($conn, 'articles');
//		$this->_removeUserIdRelation($conn, 'article_categories');
//		$this->_removeUserIdRelation($conn, 'downloads');
//		$this->_removeUserIdRelation($conn, 'download_categories');
//		$this->_removeUserIdRelation($conn, 'comments');
//		$this->_removeUserIdRelation($conn, 'ratings');

		$this->_removeUserId($conn, 'articles');
		$this->_removeUserId($conn, 'article_categories');
		$this->_removeUserId($conn, 'comments');
		$this->_removeUserId($conn, 'downloads');
		$this->_removeUserId($conn, 'download_categories');
		$this->_removeUserId($conn, 'ratings');

		return true;
	}
}

?>