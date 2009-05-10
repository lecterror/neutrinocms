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

App::import('vendor', 'Migrations.BaseMigration');
App::import('vendor', 'Migrations.MigrationsConfig');

class Migration extends MigrationsAppModel
{
	var $name = 'Migration';
	var $useTable = false;
	var $cacheQueries = false;
	var $cacheSources = false;

	function beforeFind($queryData)
	{
		$dbs = $this->getDataSource();
		$dbs->_queryCache = array();

		return parent::beforeFind($queryData);
	}

	function __getMigration($filename)
	{
		$id = str_replace('.php', '', $filename);
		$className = sprintf('Migration%s', $id);

		require_once MIGRATIONS.$filename;

		if (!class_exists($className))
		{
			$this->log
				(
					sprintf
					(
						__('Invalid migration file %s, file present with no %s class defined', true),
						$filename,
						$className
					)
				);

			return null;
		}

		return new $className;
	}

	function getMigrations($migration = null)
	{
		if (!is_null($migration))
		{
			$filename = sprintf('%07s.php', $migration);

			if (!file_exists(MIGRATIONS.$filename))
			{
				$this->log
					(
						sprintf
						(
							__('Migration file %s does not exist (path used: %s)', true),
							$filename,
							MIGRATIONS
						)
					);

				return array();
			}

			return array($this->__getMigration($filename));
		}

		$folder = new Folder(MIGRATIONS);

		$filenames = $folder->read(true, true);
		$migrations = array();

		foreach ($filenames[1] as $filename)
		{
			if (preg_match('#^[0-9]{7}\.php$#', $filename))
			{
				$migrations[] = $this->__getMigration($filename);
			}
		}

		return $migrations;
	}

	function migrate($to = null)
	{
		$low = $high = null;
		$direction = 'up';

		$from = $this->getDbMigration();
		$to = $this->getMigrations($to);

		if (is_null($from))
		{
			$low = 0;
		}
		else
		{
			$low = intval($from->id());
		}

		if (is_null($to))
		{
			$this->log
				(
					sprintf
					(
						__('Invalid id %s in migration process', true),
						$filename,
						$className
					)
				);

			return false;
		}
		else
		{
			$high = intval($to[0]->id());
		}

		if ($low > $high)
		{
			$tmp = $from;
			$from = $to;
			$to = $tmp;
			$direction = 'down';
		}

		$allMigrations = $this->getMigrations();

		if ($direction == 'down')
		{
			$allMigrations = array_reverse($allMigrations);
		}

		foreach ($allMigrations as $migration)
		{
			$id = intval($migration->id());

			if ($direction == 'up' && ($id <= $low || $id > $high) ||
				($direction == 'down' && ($id <= $high || $id > $low)))
			{
				continue;
			}

			if (!$migration->$direction())
			{
				trigger_error
					(
						sprintf
						(
							__('Error in migration %s, %s() failed!', true),
							$migration->id(),
							$direction
						)
					);

				return false;
			}
		}

		return true;
	}

	/**
	 * Return the current database migration.
	 *
	 * Start from the last migration and go back,
	 * stop on the first to come out OK.
	 *
	 * @return BaseMigration
	 */
	function getDbMigration()
	{
		clearCache(null, 'models');

		$migrations = $this->getMigrations();
		$mCount = count($migrations) - 1;
		$current = null;

		for ($i = $mCount; $i >= 0; $i--)
		{
			if (!$migrations[$i]->check())
			{
				$current = $migrations[$i];
				break;
			}
		}

		return $current;
	}

	function needsMigration()
	{
	//	$migrations = $this->getMigrations();
		$neutrinoConfig = new NEUTRINO_CONFIG();
		$dbMigration = $this->getDbMigration();

		// no migrations, no need
		// no woman, no cry
		if (empty($migrations))
		{
			return false;
		}

	//	$migration = array_pop($migrations);
	//	$migration->id()
		if ($dbMigration->id() < $neutrinoConfig->requiredMigration())
		{
			return true;
		}

		return false;
	}
}

?>