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
 * @property NotifyComponent $Notify
 * @property SecurityComponent $Security
 */
class UsersController extends AppController
{
	var $name = 'Users';
	var $uses = array('User');
	var $components = array('Notify', 'Captcha', 'Security');
	var $helpers = array('Captcha');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Security->blackHoleCallback = 'blackhole';

		if ($this->_user)
		{
			$this->Auth->allow
				(
					'logout'
				);
		}
		else
		{
			$this->Auth->allow
				(
					'login',
					'register',
					'activate',
					'password_reset',
					'password_reset_input'
				);
		}
	}

	function isAuthorized()
	{
		$model = ((isset($this->User) && !is_null($this->User)) ? $this->User : null);

		return parent::isAuthorized($model);
	}

	function _modifyAuthMessage()
	{
		if (!isset($this->data['User']['username']) || empty($this->data['User']['username']))
		{
			return;
		}

		$username = $this->data['User']['username'];

		if (!$this->User->existingUser($username))
		{
			return;
		}

		if (!$this->User->isVerified($username))
		{
			$this->Session->setFlash
				(
					__('Your account information is not verified yet', true),
					'default',
					array(),
					'auth'
				);
		}
		else if ($this->User->isLocked($username))
		{
			$this->Session->setFlash
				(
					__('Your account is temporarily locked', true),
					'default',
					array(),
					'auth'
				);
		}
		else if ($this->User->isBanned($username))
		{
			$this->Session->setFlash
				(
					__('Your account is banned', true),
					'default',
					array(),
					'auth'
				);
		}

		return;
	}

	public function login()
	{
		if (!$this->Auth->user())
		{
			$this->_modifyAuthMessage();
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
		$this->User->updateLoginInfo($this->data);
		$this->Session->setFlash(__('You have logged in successfully', true), 'default', array(), 'auth');
		$this->redirect($this->Auth->redirect());
	}

	public function logout()
	{
		$this->RememberMe->delete();
		$this->Session->destroy();
		$this->Session->setFlash(__('You have logged out successfully', true), 'default', array(), 'auth');
		$this->redirect($this->Auth->logout());
	}

	public function register()
	{
		if (empty($this->data) || $this->_user)
		{
			$this->set('showForm', true);
			return;
		}

		if (!$this->User->validateRegistration($this->data))
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		$ticket = $this->User->register($this->data);

		if ($ticket === false)
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('There was an error while creating your account', true));
			return;
		}

		$user = $this->User->read();
		$this->Notify->accountActivation($user);
	}

	public function activate()
	{
		if (!isset($this->passedArgs['code']) || empty($this->passedArgs['code']))
		{
			$this->Session->setFlash(__('Invalid activation code provided', true));
			$this->redirect('/');
		}

		$timestamp = time();

		if (!$this->User->canActivate($this->passedArgs['code'], $timestamp))
		{
			$this->Session->setFlash(__('Invalid activation code provided', true));
			$this->redirect('/');
		}

		if (!$this->User->activate($this->passedArgs['code'], $timestamp))
		{
			return;
		}

		$this->set('success', true);
	}

	public function password_reset()
	{
		if (empty($this->data))
		{
			$this->set('showForm', true);
			return;
		}

		$this->User->data = $this->data;
	
		$validCaptcha = $this->Captcha->check($this->data['User']['captcha']);

		if (!$validCaptcha)
		{
			$this->User->invalidate('captcha', __('Please type the code from the image above', true));
			$this->set('showForm', true);
			return;
		}

		$user = $this->User->find
			(
				'first',
				array
				(
					'fields' => array
					(
						'id',
						'username',
						'email',
						'first_name',
						'last_name'
					),
					'conditions' => array
					(
						'is_verified'	=> '1',
						'is_locked'		=> '0',
						'is_banned'		=> '0',
						'email'			=> Sanitize::escape($this->data['User']['email'])
					)
				)
			);

		if (empty($user))
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('No valid account has been found with that email address', true));
			return;
		}

		$this->User->data = $user;
		$ticket = $this->User->getTicket(true);
		$this->User->id = $this->User->data['User']['id'];
		$this->User->save(null, false, array('hash', 'hash_expires'));

		$user = $this->User->read();
		$this->Notify->passwordReset($user);
	}

	/**
	 *
	 * @todo Somehow rewrite this...too fat.
	 */
	public function password_reset_input()
	{
		$timestamp = time();

		if (empty($this->data))
		{
			if (!isset($this->passedArgs['code']) || empty($this->passedArgs['code']))
			{
				$this->Session->setFlash(__('Invalid security code provided', true));
				$this->redirect('/');
			}

			if (!$this->User->canResetPassword($this->passedArgs['code'], $timestamp))
			{
				$this->Session->setFlash(__('Invalid security code provided', true));
				$this->redirect('/');
			}
			
			$this->Session->write('PasswordResetCode', $this->passedArgs['code']);
		}
		else
		{
			if (	!isset($this->data['User']['code']) ||
					empty($this->data['User']['code']) ||
					$this->data['User']['code'] != $this->Session->read('PasswordResetCode')
				)
			{
				$this->Session->setFlash(__('Invalid security code provided', true));
				$this->redirect('/');
			}
		}

		if (empty($this->data))
		{
			$this->set('showForm', true);
			$this->set('code', $this->passedArgs['code']);
			return;
		}
		else
		{
			$this->set('code', $this->data['User']['code']);
		}

		if (!$this->User->validateResetPassword($this->data))
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		$user = $this->User->find
			(
				'first',
				array
				(
					'fields' => array('id'),
					'conditions' => array
					(
						'is_verified' => 1,
						'is_locked' => 0,
						'is_banned' => 0,
						'hash' => Sanitize::escape($this->data['User']['code']),
						'hash_expires >' => date('Y-m-d h:i:s', $timestamp)
					)
				)
			);

		if (empty($user))
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('Invalid account or code provided', true));
			return;
		}

		$resetOk = $this->User->resetPassword($user['User']['id'], $this->data['User']['passwd']);

		if (!$resetOk)
		{
			$this->set('showForm', true);
			$this->Session->setFlash(__('An error occurred during password reset', true));
			return;
		}
	}

	public function change_password()
	{
		$this->set('user', $this->_user);

		if (!$this->RequestHandler->isPost())
		{
			return;
		}

		if (!$this->User->changePassword($this->_user['id'], $this->data))
		{
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}
		
		$this->Session->setFlash(__('Your password has been saved successfully', true));
		$this->_redirectToReferrer();
	}
}
