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

	public function permissions()
	{
		if ($this->_isAjaxRequest())
		{
			// nastavit.. napravit handlere za getanje juzera i featura, i tako editirat samo jedan set permissiona
			if (!isset($this->passedArgs['get']) || !in_array($this->passedArgs['get'], array('user', 'feature')))
			{
				$this->blackhole();
			}

			if (empty($this->passedArgs['user']))
			{
				$this->set('userPermissions', array());
			}
			else
			{
				$this->set
					(
						'userPermissions',
						$this->Acl->Aro->find
						(
							'threaded',
							array
							(
								'conditions' => array('Aro.foreign_key' => Sanitize::escape($this->passedArgs['user']))
							)
						)
					);
			}

			$this->_renderUserPermissions();
		}

		$this->set
			(
				'users',
				$this->User->find('list', array('fields' => array('id', 'username')))
			);

		if (empty($this->data))
		{
			return;
		}
	}

	private function _renderUserPermissions()
	{
		$this->autoRender = false;
		$this->viewPath = 'elements'.DS.'users';
		$this->render('permissions', 'ajax');
	}

	function save_permission()
	{
		if (!$this->_isAjaxRequest()
			|| !isset($this->passedArgs['feature'])
			|| !isset($this->passedArgs['aclaction'])
			|| !isset($this->passedArgs['value']))
		{
			$this->redirect('/');
		}

		$this->RequestHandler->respondAs('ajax');
		$this->layout = 'ajax';

		if (!empty($this->passedArgs['feature'])
			|| empty($this->passedArgs['acl_action'])
			|| !in_array($this->passedArgs['value'], array('0', '1', '-1')))
		{
			$this->set('message', __('Invalid parameters supplied', true));
		}

		$this->set('message', __('tralala', true));
	}
}

