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

	function index()
	{
		$this->set
			(
				'aros',
				$this->Acl->Aro->find
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
				)
			);
	}

	function add()
	{
		if (!$this->RequestHandler->isPost())
		{
			return;
		}
//		if (!isset($this->passedArgs['parent']))
//		{
//			$this->Session->setFlash(__('No parent group selected', true));
//			$this->redirect(array('action' => 'index'));
//		}
//
//		if (empty($this->data))
//		{
//			return;
//		}
//
//		if ($this->passedArgs['parent'] != $this->data['Aro']['parent_id'])
//		{
//			$this->blackhole();
//		}
//
//		$this->data['Aro']['parent_id'] = Sanitize::escape($this->data['Aro']['parent_id']);
//		$this->data['Aro']['alias'] = Sanitize::escape($this->data['Aro']['alias']);
//
//		if (!$this->Acl->Aro->save($this->data))
//		{
//			$this->Session->setFlash(__('There was an error while saving the new group', true));
//			$this->redirect(array('action' => 'add_group', 'parent' => $this->passedArgs['parent']));
//		}
//
//		$this->Session->setFlash(__('New group added successfully', true));
//		$this->redirect(array('action' => 'view', 'group' => $this->passedArgs['parent']));
	}

	function add_feature()
	{
		// @todo: continue..
	}

	function view()
	{
		if (!isset($this->passedArgs['group']))
		{
			$this->redirect(array('action' => 'index'));
		}

		$groupId = Sanitize::escape($this->passedArgs['group']);

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
}
