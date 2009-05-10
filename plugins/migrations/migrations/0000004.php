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
 * @todo: this migration is "a bit" hacky and unoptimized, find a better way.
 *
 * @property Aro $this->Aro
 * @property Aco $this->Aco
 * @property Permission $Permission
 */
class Migration0000004 extends BaseMigration
{
	function id()
	{
		return '0000004';
	}

	function description()
	{
		return <<<END
Create ARO and ACO nodes, AROs for the only user in database,
and ACOs for all the controllers in NeutrinoCMS.

Additionally, create a default set of user groups and their
default permissions.
END;
	}

	function check()
	{
		$conn = $this->getDboSource();
		$tables = $conn->listSources();

		if (!in_array('aros_acos', $tables))
		{
			return true;
		}

		$result = $this->execute('select * from aros_acos limit 0,1');

		if (empty($result))
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

		$result = $this->execute('select * from users limit 0,1');

		$existingSetup = !empty($result);

		if (!class_exists('AclNode'))
		{
			uses('model' . DS . 'db_acl');
		}

		$this->Aro =& ClassRegistry::init('Aro');
		$this->Aco =& ClassRegistry::init('Aco');
		$this->Permission =& ClassRegistry::init('Permission');

		$this->Aro->create();
		$this->Aro->save(array('alias' => __('Everyone', true)));
		$rootId = $this->Aro->id;

		$this->Aro->create();
		$this->Aro->save(array('alias' => __('Administrators', true), 'parent_id' => $rootId));
		$this->Aro->create();
		$this->Aro->save(array('alias' => __('Registered users', true), 'parent_id' => $rootId));

		$features = NEUTRINO_CONFIG::features();

		$this->Aco->create();
		$this->Aco->save(array('alias' => __('Everything', true)));

		$this->AcoRoot = $this->Aco->id;
		$everythingL10n = __('Everything', true);

		foreach ($features as $feature)
		{
			if ($feature == $everythingL10n)
			{
				continue;
			}

			$this->Aco->create();
			$this->Aco->save(array('alias' => $feature, 'parent_id' => $this->AcoRoot));
		}

		// deny everything to everone
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Everyone', true)),
				$this->Aco->findByAlias($everythingL10n),
				array
				(
					'_create'		=> -1,
					'_read'			=> -1,
					'_read_own'		=> -1,
					'_update'		=> -1,
					'_update_own'	=> -1,
					'_delete'		=> -1,
					'_delete_own'	=> -1
				)
			);

		// allow everything to administrators
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Administrators', true)),
				$this->Aco->findByAlias($everythingL10n),
				array
				(
					'_create'		=> 1,
					'_read'			=> 1,
					'_read_own'		=> 1,
					'_update'		=> 1,
					'_update_own'	=> 1,
					'_delete'		=> 1,
					'_delete_own'	=> 1
				)
			);

		// allow read/update_own/delete_own of ArticleCategories to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('ArticleCategories', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> 1,
					'_update_own'	=> 1,
					'_delete_own'	=> 1
				)
			);
		
		// allow read/update_own/delete_own of Articles to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Articles', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> 1,
					'_update_own'	=> 1,
					'_delete_own'	=> 1
				)
			);

		// allow read/update_own/delete_own of DownloadCategories to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('DownloadCategories', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> 1,
					'_update_own'	=> 1,
					'_delete_own'	=> 1
				)
			);

		// allow read/update_own/delete_own of Downloads to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Downloads', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> 1,
					'_update_own'	=> 1,
					'_delete_own'	=> 1
				)
			);

		// allow read/update_own/delete_own of Attachments to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Attachments', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> 1,
					'_update_own'	=> 1,
					'_delete_own'	=> 1
				)
			);

		// allow read/update_own/delete_own of Comments to registered users
		// deny create
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Comments', true)),
				array
				(
					'_create'		=> 1,
					'_read'			=> 1,
					'_update_own'	=> 1
				)
			);

		// deny everything of Neutrino to registered users
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Neutrino', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> -1,
					'_read_own'		=> -1,
					'_update'		=> -1,
					'_update_own'	=> -1,
					'_delete'		=> -1,
					'_delete_own'	=> -1
				)
			);

		// deny everything of Stats to registered users
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Stats', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> -1,
					'_read_own'		=> -1,
					'_update'		=> -1,
					'_update_own'	=> -1,
					'_delete'		=> -1,
					'_delete_own'	=> -1
				)
			);

		// deny everything of Stats to registered users
		$this->_createPermission
			(
				$this->Aro->findByAlias(__('Registered users', true)),
				$this->Aco->findByAlias(__('Users', true)),
				array
				(
					'_create'		=> -1,
					'_read'			=> -1,
					'_read_own'		=> 1,
					'_update'		=> -1,
					'_update_own'	=> 1,
					'_delete'		=> -1,
					'_delete_own'	=> 1
				)
			);

		if ($existingSetup)
		{
			$userId = $result[0]['users']['id'];
			$userAlias = $result[0]['users']['username'];

			$this->Aro->create();
			$this->Aro->save
				(
					array
					(
						'model'			=> 'User',
						'parent_id'		=> $rootId,
						'foreign_key'	=> $userId,
						'alias'			=> $userAlias
					)
				);

			// allow everything for our newly created admin
			$this->_createPermission
				(
					$this->Aro->findByAlias($userAlias),
					$this->Aco->findByAlias(__('Everything', true)),
					array
					(
						'_create'		=> 1,
						'_read'			=> 1,
						'_read_own'		=> 1,
						'_update'		=> 1,
						'_update_own'	=> 1,
						'_delete'		=> 1,
						'_delete_own'	=> 1
					)
				);
		}

		return true;
	}

	function down()
	{
		$conn = $this->getDboSource();

		if (!$conn->isConnected())
		{
			return false;
		}

		$conn->execute('truncate aros_acos');
		$conn->execute('truncate aros');
		$conn->execute('truncate acos');

		return true;
	}

	function _createPermission($aro, $aco, $permissions)
	{
		$this->Permission->create();

		$this->Permission->save
			(
				array
				(
					'Permission' => array_merge
					(
						$permissions,
						array
						(
							'aro_id' => $aro['Aro']['id'],
							'aco_id' => $aco['Aco']['id'],
						)
					)
				)
			);
	}
}

?>
