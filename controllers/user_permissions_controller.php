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
 * @property User $User
 */
class UserPermissionsController extends AppController
{
	var $name = 'UserPermissions';
	var $uses = array('User');

	function beforeFilter()
	{
		parent::beforeFilter();

		if (empty($this->_user) || !$this->_user['is_root'])
		{
			$this->redirect('/');
		}
	}

	function isAuthorized()
	{
		return parent::isAuthorized();
	}

	private function _getUserPermissions($aroId, $acoId)
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

	function index()
	{
		$this->set('users', $this->User->find('all', array('contain' => array())));
	}

	function view($id = null)
	{
		if (empty($id))
		{
			$this->Session->setFlash(__('No user selected', true));
			$this->redirect(array('action' => 'index'));
		}

		$userId = Sanitize::escape($id);

		$user = $this->User->find
			(
				'first',
				array
				(
					'conditions' => array('id' => $userId)
				)
			);

		if (empty($user))
		{
			$this->redirect(array('action' => 'index'));
		}

		$this->set('user', $user);
//		$this->User->id = $userId;
//		$this->set('aro', $this->User->node());
		$this->set('aroPath', $this->Acl->Aro->getpath($userId));
	}

	public function permissions()
	{
		// @todo: there's gotta be a better way..
		if ($this->RequestHandler->isGet())
		{
			if (!isset($this->passedArgs['user']) || !isset($this->passedArgs['featureAlias']))
			{
				$this->Session->setFlash(__('Invalid parameters passed', true));
				$this->redirect(array('action' => 'index'));
			}

			$featureAlias = Sanitize::escape($this->passedArgs['featureAlias']);
			$userId = Sanitize::escape($this->passedArgs['user']);
		}
		else if ($this->RequestHandler->isPost())
		{
			if (!isset($this->data['User']['id']) || !isset($this->data['Feature']['alias']))
			{
				$this->Session->setFlash(__('Invalid parameters passed', true));
				$this->redirect(array('action' => 'index'));
			}

			$featureAlias = Sanitize::escape($this->data['Feature']['alias']);
			$userId = Sanitize::escape($this->data['User']['id']);
		}

		if (!isset($featureAlias) || !isset($userId) || empty($featureAlias) || empty($userId))
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

		$user = $this->User->find
			(
				'first',
				array
				(
					'conditions' => array('User.id' => $userId),
					'contain' => array()
				)
			);

		$userAro = $this->Acl->Aro->find
			(
				'first',
				array
				(
					'contain' => array(),
					'conditions' => array
					(
						'model' => 'User',
						'foreign_key' => $userId
					)
				)
			);

		if (empty($feature) || empty($user) || empty($userAro))
		{
			$this->Session->setFlash(__('Invalid parameters passed', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->set(compact('feature', 'userAro', 'user'));

		if (!$this->RequestHandler->isPost())
		{
			$this->set('permissions', $this->_getUserPermissions($userAro['Aro']['id'], $feature['Aco']['id']));
			return;
		}

		$this->set('permissions', $this->data['Permissions']);
		$functions = array('0' => 'inherit', '-1' => 'deny', '1' => 'allow');

		foreach ($this->data['Permissions'] as $action => $permission)
		{
			$this->Acl->{$functions[$permission]}(array('model' => 'user', 'foreign_key' => $user['User']['id']), $feature['Aco']['alias'], $action);
		}

		$this->Session->setFlash(__('Permissions saved successfully', true));
		$this->redirect(array('action' => $this->action, 'user' => $userId, 'featureAlias' => $featureAlias));
	}
}

