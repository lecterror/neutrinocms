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

class BaseMigration extends Object
{
	/**
	 * To be overridden in subclasses.
	 *
	 * Check if the migration needs to be run.
	 *
	 * @return bool true if migration needs to be run.
	 *
	 */
	function check() {}

	/**
	 * To be overridden in subclasses.
	 *
	 * Run migration (upgrade).
	 *
	 */
	function up() {}

	/**
	 * To be overridden in subclasses.
	 *
	 * Run migration (downgrade).
	 *
	 */
	function down() {}

	/**
	 * To be overridden in subclasses.
	 *
	 * Migration id, a seven digit number.
	 *
	 */
	function id() {}

	/**
	 * To be overridden in subclasses.
	 *
	 * Migration description.
	 *
	 */
	function description() {}

	/**
	 * Get the default connection.
	 *
	 * @return DboSource
	 */
	function &getDboSource()
	{
		static $conn = null;

		if (is_null($conn))
		{
			$db =& ConnectionManager::getInstance();
			$conn =& $db->getDataSource('default');
			$conn->cacheSources = false;
		}

		$conn->_queryCache = array(); // very much required
		return $conn;
	}

	/**
	 * Execute a query, but clear the query cache before that.
	 * Essential for detection of current database migration.
	 *
	 * @param string $query
	 * @return mixed Query result
	 */
	function execute($query)
	{
		$conn =& $this->getDboSource();
		$conn->_queryCache = array();

		return $conn->fetchAll($query, false);
	}
}
?>