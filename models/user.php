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

class User extends AppModel
{
	var $name = 'User';
	var $validate = array();
	var $actsAs = array
		(
			'Hitcount' => array('hitField' => 'logins'),
			'Acl' => array('type' => 'requester')
		);

	/**
	 * These are the fields which will be validated on user registration.
	 *
	 * @var array
	 */
	var $_registrationFields = array
		(
			'username',
			'passwd',
			'passwd_confirm',
			'email',
			'first_name',
			'last_name'
		);

	/**
	 * Fields which will be validated on password reset action.
	 *
	 * @var array
	 */
	var $_passwordResetFields = array
		(
			'passwd',
			'passwd_confirm'
		);

	/**
	 * @var AclBehavior
	 */
	var $Acl = null;

	function __construct()
	{
		parent::__construct();

		$this->validate = array
			(
				'username' =>
					array
					(
						'notEmpty' =>
							array
							(
								'rule'		=> VALID_NOT_EMPTY,
								'message'	=> __('Enter a valid username', true)
							),
						'isUnique' =>
							array
							(
								'rule'		=> 'isUnique',
								'message'	=> __('A user with that name is already registered', true)
							)
					),
				'password' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'required'	=> true,
						'message'	=> __('Enter a valid password', true)
					),
				'passwd' =>
					array
					(
						'rule'			=> 'validatePasswd',
						'required'		=> true,
						'allowEmpty'	=> false,
						'message'		=> __('Enter a valid password', true)
					),
				'passwd_confirm' =>
					array
					(
						'rule'			=> 'validatePasswdConfirm',
						'required'		=> true,
						'allowEmpty'	=> false,
						'message'		=> __('Passwords do not match', true)
					),
				'email' =>
					array
					(
						'validEmail' => array
						(
							'rule'			=> VALID_EMAIL,
							'required'		=> true,
							'allowEmpty'	=> false,
							'message'		=> __('Please enter a valid email address', true)
						),
						'isUnique' =>
						array
						(
							'rule'		=> 'isUnique',
							'message'	=> __('A user with that email address is already registered', true)
						)
					),
				'first_name' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter your first name', true)
					),
				'last_name' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('Enter your last name', true)
					),
				'homepage' =>
					array
					(
						'rule'			=> array('url', true),
						'allowEmpty'	=> true,
						'on'			=> 'update',
						'message'		=> __('Enter your website URL (or leave blank if you don\'t have one)', true)
					)
			);
	}

	/**
	 * To avoid Security/Auth hashing our password twice (wtf?)
	 * we use these two rules (validatePasswd and validatePasswdConfirm).
	 *
	 * @param array $data
	 * @return bool
	 */
	function validatePasswd($data)
	{
		if (empty($data['passwd']))
		{
			return false;
		}

		return true;
	}

	/**
	 * To avoid Security/Auth hashing our password twice (wtf?)
	 * we use these two rules (validatePasswd and validatePasswdConfirm).
	 *
	 * @param array $data
	 * @return bool
	 */
	function validatePasswdConfirm($data)
	{
		if ($this->data[$this->alias]['passwd'] !== $data['passwd_confirm'])
		{
			return false;
		}

		return true;
	}

	/**
	 * Before save callback.
	 *
	 * Stuff done here: we check for presence of "passwd" and "passwd_confirm" fields,
	 * which are used on user registration. We remove them after setting the correct
	 * "password" value (hashed "passwd" value).
	 *
	 * @return bool
	 */
	function beforeSave()
	{
		if (isset($this->data[$this->alias]['passwd']))
		{
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['passwd'], null, true);
			unset($this->data[$this->alias]['passwd']);
		}

		if (isset($this->data[$this->alias]['passwd_confirm']))
		{
			unset($this->data[$this->alias]['passwd_confirm']);
		}

		return true;
	}

	/**
	 *
	 * @todo: add a configuration var for this.. somehow handle the change.
	 */
	function parentNode()
	{
		return __('Registered users', true);
	}

	function existingUser($username)
	{
		$_username = Sanitize::escape($username);

		return $this->hasAny(array('username' => $_username));
	}

	function isVerified($username)
	{
		$_username = Sanitize::escape($username);

		return $this->hasAny(array('username' => $_username, 'is_verified' => 1));
	}

	function isLocked($username)
	{
		$_username = Sanitize::escape($username);

		return $this->hasAny(array('username' => $_username, 'is_locked' => 1));
	}

	function isBanned($username)
	{
		$_username = Sanitize::escape($username);

		return $this->hasAny(array('username' => $_username, 'is_banned' => 1));
	}

	function updateLoginInfo($user)
	{
		$_username = Sanitize::escape($user['User']['username']);

		$user = $this->find
			(
				'first',
				array
				(
					'fields' => array('id'),
					'recursive' => -1,
					'conditions' => array('username' => $_username)
				)
			);

		if (empty($user))
		{
			return;
		}

		$this->id = $user['User']['id'];
		$this->hit();
		$this->save(array('last_login' => date('Y-m-d H:i:s')));
	}

	function getTicket($set = false)
	{
		$hashKey = sprintf
			(
				'%s%s%s',
				date('Ymdhis'),
				Configure::read('Security.salt'),
				Security::generateAuthKey()
			);

		$ticket = array
			(
				'hash'		=> md5($hashKey),
				'expires'	=> strtotime('+1 days')
			);

		if ($set)
		{
			$this->data['User']['hash']			= $ticket['hash'];
			$this->data['User']['hash_expires']	= date('Y-m-d H:i:s', $ticket['expires']);
		}

		return $ticket;
	}

	function validateRegistration($data = null)
	{
		if (!empty($data))
		{
			$this->set($data);
		}

		return $this->validates(array('fieldList' => $this->_registrationFields));
	}

	function register($data)
	{
		$this->data = $data;

		$this->data['User']['is_built_in']	= '0';
		$this->data['User']['is_root']		= '0';
		$this->data['User']['is_verified']	= '0';
		$this->data['User']['is_locked']	= '0';
		$this->data['User']['is_banned']	= '0';

		$ticket = $this->getTicket(true);

		if (!$this->save(null, false))
		{
			return false;
		}

		return $ticket;
	}

	function canActivate($hash, $timestamp = null)
	{
		return $this->hasAny
			(
				array
				(
					'is_verified' => 0,
					'is_locked' => 0,
					'is_banned' => 0,
					'hash' => Sanitize::escape($hash),
					'hash_expires >' => date('Y-m-d h:i:s', $timestamp)
				)
			);
	}

	function activate($hash, $timestamp = null)
	{
		$user = $this->find
			(
				'first',
				array
				(
					'conditions' => array
					(
						'is_verified' => 0,
						'is_locked' => 0,
						'is_banned' => 0,
						'hash' => Sanitize::escape($hash),
						'hash_expires >' => date('Y-m-d h:i:s', $timestamp)
					)
				)
			);

		if (empty($user))
		{
			return false;
		}

		$data = array
			(
				'User' => array
				(
					'id'			=> $user['User']['id'],
					'is_verified'	=> 1,
					'hash'			=> null,
					'hash_expires'	=> null
				)
			);

		if (!$this->save($data, false))
		{
			return false;
		}

		return true;
	}

	function canResetPassword($hash, $timestamp = null)
	{
		return $this->hasAny
			(
				array
				(
					'is_verified' => 1,
					'is_locked' => 0,
					'is_banned' => 0,
					'hash' => Sanitize::escape($hash),
					'hash_expires >' => date('Y-m-d h:i:s', $timestamp)
				)
			);
	}

	function validateResetPassword($data)
	{
		$this->data = $data;
		return $this->validates(array('fieldList' => $this->_passwordResetFields));
	}

	function resetPassword($id, $password)
	{
		$this->id = $id;
		$this->data = array
			(
				'User' => array
				(
					'passwd'			=> $password,
					'hash'				=> null,
					'hash_expires'		=> null
				)
			);

		return $this->save(null, array('validate' => false));
	}

	function changePassword($userId, $data)
	{
		$user = $this->find
			(
				'first',
				array
				(
					'conditions' => array
					(
						'id' => $userId,
						'password' => Security::hash($data['User']['password'], null, true)
					)
				)
			);

		$this->id = $userId;
		$this->data = $data;

		if (empty($user) || !$this->validates(array('fieldList' => $this->_passwordResetFields)))
		{
			$this->invalidate('password', __('The password you entered does not match your current password', true));
			return false;
		}

		return $this->save(null, array('validate' => false));
	}

	function assignToGroup($userId, $groupId)
	{

	}
}
