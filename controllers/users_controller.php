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

class UsersController extends AppController
{
	var $name = 'Users';
	var $uses = array('User');

	function login()
	{
		if (!$this->Auth->user())
		{
			return;
		}

		if (empty($this->data))
		{
			$this->redirect($this->Auth->redirect());
		}

		if (empty($this->data['User']['remember_me']))
		{
			$this->RememberMe->delete();
		}
		else
		{
			$this->RememberMe->remember
				(
					$this->data['User']['username'],
					$this->data['User']['password']
				);
		}

		unset($this->data['User']['remember_me']);
		$this->redirect($this->Auth->redirect());
	}

	function logout()
	{
		$this->RememberMe->delete();
		$this->redirect($this->Auth->logout());
	}
}

?>