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
 * @property EmailComponent $Email
 * @property User $User
 */
class NotifyComponent extends Object
{
	var $name = 'Notify';
	var $components = array('Email');
	var $Controller = null;
	var $active = false;

	function initialize(&$controller)
	{
		$this->Controller =& $controller;
	}

	function startup(&$controller)
	{
		$this->active = $this->Controller->_installed && !$this->Controller->_needsMigration;

		/**
		 * bugfix: A bug in EmailComponent kicked my bottom for two days..
		 * 
		 * @see https://trac.cakephp.org/ticket/5904
		 */
	//	$this->Email->startup($controller);
	}

	function accountActivation($data)
	{
		$to = sprintf('"%s %s" <%s>', $data['User']['first_name'], $data['User']['last_name'], $data['User']['email']);
		$from = sprintf('"%s" <noreply@%s>', Configure::read('Neutrino.SiteTitle'), env('HTTP_HOST'));

		$this->Email->reset();
		$this->Email->sendAs = 'both';
		$this->Email->to = $to;
		$this->Email->from = $from;
		$this->Email->replyTo = $from;
		$this->Email->return = $from;
		$this->Email->subject = sprintf(__('%s - account activation', true), Configure::read('Neutrino.SiteTitle'));
		$this->Email->template = 'account_activation';

		$this->Controller->set('user', $data);
		$this->Email->send();
	}

	function passwordReset($data)
	{
		$to = sprintf('"%s %s" <%s>', $data['User']['first_name'], $data['User']['last_name'], $data['User']['email']);
		$from = sprintf('"%s" <noreply@%s>', Configure::read('Neutrino.SiteTitle'), env('HTTP_HOST'));

		$this->Email->reset();
		$this->Email->sendAs = 'both';
		$this->Email->to = $to;
		$this->Email->from = $from;
		$this->Email->replyTo = $from;
		$this->Email->return = $from;
		$this->Email->subject = sprintf(__('%s - password reset', true), Configure::read('Neutrino.SiteTitle'));
		$this->Email->template = 'password_reset';

		$this->Controller->set('user', $data);
		$this->Email->send();
	}
}
