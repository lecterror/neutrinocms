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

class GroupPermissionsController extends AppController
{
	var $name = 'GroupPermissions';
	var $uses = array('User');

	function beforeFilter()
	{
		parent::beforeFilter();

		if (empty($this->_user) || !$this->_user['is_root'])
		{
			$this->redirect('/');
		}
	}

	private function _getGroups()
	{
		return $this->Acl->Aro->find
			(
				'all',
				array
				(
					'contain' => array(),
					'conditions' => array
					(
						'or' => array
						(
							array('Aro.foreign_key' => null),
							array('Aro.foreign_key' => '')
						),
						'and' => array
						(
							'or' => array
							(
								array('Aro.model' => ''),
								array('Aro.model' => null)
							)
						)
					)
				)
			);
	}

	public function _getGroupPermissions($aroId, $acoId)
	{
		$returnValue = array
			(
				'create'		=> 0,
				'read'			=> 0,
				'read_own'		=> 0,
				'update'		=> 0,
				'update_own'	=> 0,
				'delete'		=> 0,
				'delete_own'	=> 0
			);

		$permissions = $this->Acl->Aro->find
			(
				'first',
				array
				(
					'contain' => array('Aco' => array('conditions' => array('Aco.id' => $acoId))),
					'conditions' => array('Aro.id' => $aroId)
				)
			);

		$permissions = Set::extract('/Aco/Permission', $permissions);

		if (!empty($permissions))
		{
			$permissions = $permissions[0]['Permission'];

			$returnValue['create']		= $permissions['_create'];
			$returnValue['read']		= $permissions['_read'];
			$returnValue['read_own']	= $permissions['_read_own'];
			$returnValue['update']		= $permissions['_update'];
			$returnValue['update_own']	= $permissions['_update_own'];
			$returnValue['delete']		= $permissions['_delete'];
			$returnValue['delete_own']	= $permissions['_delete_own'];
		}

		return $returnValue;
	}

	private function _getGroupsList()
	{
		return $this->Acl->Aro->find
			(
				'list',
				array
				(
					'contain' => array(),
					'fields' => array('id', 'alias'),
					'conditions' => array
					(
						'or' => array
						(
							array('Aro.foreign_key' => null),
							array('Aro.foreign_key' => '')
						),
						'and' => array
						(
							'or' => array
							(
								array('Aro.model' => ''),
								array('Aro.model' => null)
							)
						)
					)
				)
			);
	}

	public function index()
	{
		$this->set('aros', $this->_getGroups());
	}

	public function add()
	{
		$this->set('parents', $this->_getGroupsList());

		if (!$this->RequestHandler->isPost())
		{
			if (isset($this->passedArgs['parent']))
			{
				$this->set('defaultParent', Sanitize::clean($this->passedArgs['parent']));
			}

			return;
		}

		$groupParent = Sanitize::escape($this->data['Aro']['parent_id']);
		$groupAlias = Sanitize::escape($this->data['Aro']['alias']);

		$parent = $this->Acl->Aro->find
			(
				'first',
				array
				(
					'conditions' => array
					(
						'Aro.id' => $groupParent
					)
				)
			);

		if (empty($parent))
		{
			$this->blackhole();
		}

		if (!Validation::notEmpty($groupAlias))
		{
			$this->set('aliasError', __('Please enter a valid group name', true));
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		$existing = $this->Acl->Aro->findByAlias($groupAlias);

		if (!empty($existing))
		{
			$this->set('aliasError', __('A group with that name already exists', true));
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		$newAro = array
			(
				'Aro' => array
				(
					'parent_id' => $groupParent,
					'alias' => $groupAlias
				)
			);

		$this->Acl->Aro->create();

		if (!$this->Acl->Aro->save($newAro))
		{
			$this->Session->setFlash(__('There was an error while saving the new group', true));
			return;
		}

		$this->redirect(array('action' => 'index'));
	}

	public function view($id = null)
	{
		if (empty($id))
		{
			$this->Session->setFlash(__('No group selected', true));
			$this->redirect(array('action' => 'index'));
		}

		$groupId = Sanitize::escape($id);

		$aro = $this->Acl->Aro->find
			(
				'first',
				array
				(
					'conditions' => array('id' => $groupId)
				)
			);

		if (empty($aro))
		{
			$this->redirect(array('action' => 'index'));
		}

		$this->set('aro', $aro);
		$this->set('aroPath', $this->Acl->Aro->getpath($groupId));

		$aroChildren =  $this->Acl->Aro->children($groupId, true);
		$this->set('aroChildren', Set::extract('/Aro[alias=/.+/]', $aroChildren));

		$userIds = Set::extract('/Aro[model=User]/foreign_key', $aroChildren);
		$this->set
			(
				'users',
				$this->User->find
				(
					'all',
					array
					(
						'contain' => array(),
						'conditions' => array
						(
							'id' => $userIds
						)
					)
				)
			);
	}

	public function permissions()
	{
		// oh man..there's gotta be a better way..
		if ($this->RequestHandler->isGet())
		{
			if (!isset($this->passedArgs['group']) || !isset($this->passedArgs['featureAlias']))
			{
				$this->Session->setFlash(__('Invalid parameters passed', true));
				$this->redirect(array('action' => 'index'));
			}

			$featureAlias = Sanitize::escape($this->passedArgs['featureAlias']);
			$groupId = Sanitize::escape($this->passedArgs['group']);
		}
		else if ($this->RequestHandler->isPost())
		{
			if (!isset($this->data['Group']['id']) || !isset($this->data['Feature']['alias']))
			{
				$this->Session->setFlash(__('Invalid parameters passed', true));
				$this->redirect(array('action' => 'index'));
			}

			$featureAlias = Sanitize::escape($this->data['Feature']['alias']);
			$groupId = Sanitize::escape($this->data['Group']['id']);
		}

		if (!isset($featureAlias) || !isset($groupId) || empty($featureAlias) || empty($groupId))
		{
			$this->Session->setFlash(__('Invalid parameters passed', true));
			$this->redirect(array('action' => 'index'));
		}

		$feature = $this->Acl->Aco->find
			(
				'first',
				array
				(
					'contain' => array(),
					'conditions' => array
					(
						'alias' => $featureAlias
					)
				)
			);

		$group = $this->Acl->Aro->find
			(
				'first',
				array
				(
					'contain' => array(),
					'conditions' => array
					(
						'id' => $groupId
					)
				)
			);

		if (empty($feature) || empty($group))
		{
			$this->Session->setFlash(__('Invalid parameters passed', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->set(compact('feature', 'group'));

		if (!$this->RequestHandler->isPost())
		{
			$this->set('permissions', $this->_getGroupPermissions($group['Aro']['id'], $feature['Aco']['id']));
			return;
		}

		$this->set('permissions', $this->data['Permissions']);
		$functions = array('0' => 'inherit', '-1' => 'deny', '1' => 'allow');

		foreach ($this->data['Permissions'] as $action => $permission)
		{
			$this->Acl->{$functions[$permission]}($group['Aro']['alias'], $feature['Aco']['alias'], $action);
		}

		$this->Session->setFlash(__('Permissions saved successfully', true));
		$this->redirect(array('action' => $this->action, 'group' => $groupId, 'featureAlias' => $featureAlias));
	}

	// @todo: yeah ok... no delete method yet, but some day!
}
