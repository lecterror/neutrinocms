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

/* @todo:
	- last_login u usera more bit null
	- dodat indexe na slug downloada i clanaka i svega
	- mozda merdat kategorije u jednu tablicu s polymorphic behejviorom..?
*/

class Migration0000006 extends BaseMigration /////////////////////////// TODO
{
	function id()
	{
		return '0000006';
	}

	function description()
	{
		return <<<END
Fixing a user table: last_login can be null.
END;
	}

	function check()
	{
//		$conn = $this->getDboSource();
//		$tables = $conn->listSources();
//
//		if (!in_array('users', $tables))
//		{
//			return true;
//		}
//
//		$result = $conn->query('describe users');
//
//		if (empty($result))
//		{
//			return true;
//		}
//
//		if (!Set::matches('/COLUMNS[Field=is_built_in]', $result))
//		{
//			return true;
//		}

		return true;
	}

	function up()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		// @todo

		return false;
	}

	function down()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		// @todo

		return false;
	}
}

?>
