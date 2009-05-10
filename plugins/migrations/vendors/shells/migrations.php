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

App::import('Vendor', 'Migrations.MigrationsConfig');

class MigrationsShell extends Shell
{
	var $uses = array('Migrations.Migration');

	var $heading = 'NeutrinoCMS Database Migrations Interactive Shell v1.0';

	var $_migrations = array();

	function main()
	{
		while (true)
		{
			$this->_migrations = $this->Migration->getMigrations();

			if (empty($this->_migrations))
			{
				$this->hr();
				$this->out(__('No migrations available!', true));
				return;
			}

			$this->_showMainMenu();

			$action = strtoupper
				(
					$this->in
					(
						__('What would you like to do?', true),
						array('1', '2', '3', '9', 'X'),
						'X'
					)
				);

			switch ($action)
			{
				case '1':
					$this->_checkDbMigration();
					break;
				case '2':
					$this->_listAvailableMigrations();
					break;
				case '3':
					$this->_migrateDatabase();
					break;
				case '9':
					$this->_emptyDatabase();
					break;
				case 'X':
					exit(0);
					break;
				default:
					$this->out(__('You have made an invalid selection. Please choose an action by entering 1, 2, 3, 9, or X.', true));
					break;
			}
		}
	}

	function _showMainMenu()
	{
		$this->out('');
		$this->hr();
		$this->out($this->heading);
		$this->hr();
		$this->out(__('[1] Check database migration', true));
		$this->out(__('[2] List available migrations', true));
		$this->out(__('[3] Migrate database', true));
		$this->out(__('[9] Empty database (removes all revisions)', true));
		$this->out(__('[X] Quit', true));
	}

	function _checkDbMigration()
	{
		$this->out('');
		$this->out(__('Checking database...', true));
		$currentMigration = $this->Migration->getDbMigration();

		if (is_null($currentMigration))
		{
			$this->out(__('No migration: the database has not been initialized!', true));
		}
		else
		{
			$this->out(sprintf(__('Current migration is: %s', true), $currentMigration->id()));
		}
	}

	function _listAvailableMigrations()
	{
		while (true)
		{
			$this->out('');
			$this->out(__('Available migrations:', true));
			$this->hr();
			$mCount = count($this->_migrations);

			for ($i = 0; $i <= $mCount; $i++)
			{
				$one	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$two	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$three	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$four	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$five	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$six	= (isset($this->_migrations[$i]) ? $this->_migrations[$i++]->id() : '');
				$seven	= (isset($this->_migrations[$i]) ? $this->_migrations[$i]->id() : '');

				$this->out(sprintf('%s %s %s %s %s %s %s', $one, $two, $three, $four, $five, $six, $seven));
			}

			$this->hr();
			$this->out('');
			$this->out(__('[1] View description for a migration', true));
			$this->out(__('[2] Back to previous menu', true));
			$this->out(__('[X] Quit', true));

			$action = strtoupper
					(
						$this->in
						(
							__('What would you like to do?', true),
							array('1', '2', 'X'),
							'X'
						)
					);

			switch ($action)
			{
				case '1':
					$migration = $this->_inputMigration();

					if (empty($migration))
					{
						return;
					}

					$this->hr();
					$this->out($migration->description());
					$this->hr();
					break;
				case '2':
					return;
					break;
				case 'X':
					exit(0);
					break;
				default:
					$this->out(__('You have made an invalid selection. Please choose an action by entering 1, 2, 3, or X.', true));
					break;
			}
		}
	}

	function _migrateDatabase()
	{
		$newMigration = $this->_inputMigration();

		if (empty($newMigration))
		{
			return;
		}

		$dbMigration = $this->Migration->getDbMigration();

		$this->out
			(
				sprintf
				(
					__('Database will be migrated from version %s to version %s', true),
					(is_null($dbMigration) ? 'null' : $dbMigration->id()),
					$newMigration->id()
				)
			);

		if (!is_null($dbMigration))
		{
			if ($dbMigration->id() > $newMigration->id())
			{
				$this->out(__('Warning: Database will be downgraded', true));
			}
			else if ($dbMigration->id() == $newMigration->id())
			{
				$this->out(__('Nothing to do, that is the current database migration', true));
				return;
			}
		}

		$lookOkay = strtoupper($this->in(__('Does this look okay?', true), array('Y', 'N')));

		if ($lookOkay != 'Y')
		{
			return;
		}

		if ($this->Migration->migrate($newMigration->id()))
		{
			$this->out(__('Database migrated successfully', true));
		}
		else
		{
			$this->out(__('Error: Failed to migrate!', true));
		}
	}

	function _inputMigration()
	{
		$this->out('');
		$migrationId = $this->in(__('Enter a migration id:', true));

		if (!preg_match('#^[0-9]{1,7}$#', $migrationId))
		{
			$this->out(__('Invalid migration id!', true));
			return null;
		}

		$migrationId = sprintf('%07s', $migrationId);
		$migration = $this->Migration->getMigrations($migrationId);

		if (empty($migration))
		{
			$this->out(sprintf(__('Migration %s does not exist!', true), $migrationId));
			return null;
		}

		return $migration[0];
	}

	function _emptyDatabase()
	{
		$this->out('');
		$this->hr();
		$this->out(__('WARNING: This will delete all the tables in your database!', true));
		$this->hr();
		$answer = strtoupper($this->in(__('Are you sure you want to continue?', true), array('Y', 'N'), 'N'));

		if ($answer != 'Y')
		{
			return;
		}

		/**
		 * A simple hack: make the DB go to the first migration,
		 * and then simply call the 1's down() to revert it.
		 */
		$this->Migration->migrate('1');
		$firstMigration = $this->Migration->getMigrations('1');
		$firstMigration[0]->down();
	}
}

?>