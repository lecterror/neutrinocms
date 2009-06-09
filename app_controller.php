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
App::import('Core', 'ConnectionManager');
App::import('Core', 'Sanitize');
App::import('Core', 'Security');
App::import('Core', 'L10n');

/**
 * @property AuthComponent $Auth
 * @property AclComponent $Acl
 * @property SessionComponent $Session
 * @property RequestHandlerComponent $RequestHandler
 */
class AppController extends Controller
{
	var $helpers = array
		(
			'Html',
			'Text',
			'Form',
			'Session',
			'Javascript',
			'Feed',
			'HtmlText',
			'Auth',
			'Paginator',
			'Google'
		);

	/**
	 *
	 * @var NEUTRINO_CONFIG $_configuration
	 */
	var $_configuration = null;
	var $_installed = null;
	var $_needsMigration = false;
	var $view = 'Theme';
	var $uses = array('Migrations.Migration');
	var $components = array
		(
			'Sitemenu',
			'Session',
			'Auth',
			'RequestHandler',
			'RememberMe'
			,'DebugKit.Toolbar'
		);

	/**
	 * Currently logged in user, or null.
	 *
	 * @var array
	 */
	var $_user = null;

	/**
	 * Cache will be disabled in beforeRender() for these actions on all controllers.
	 *
	 * @var array
	 */
	var $disableCacheActions = array('edit', 'add', 'configure', 'manage');

	function isAuthorized($Model = null)
	{
		$user = $this->Auth->user();

		if (!$user)
		{
			return false;
		}

		if ($user['User']['is_root'])
		{
			return true;
		}

		if (Configure::read('debug') > 0 && $this->name == 'ToolbarAccess')
		{
			return true;
		}

		$action = $this->action;

		if (isset($this->Auth->actionMap[$action]))
		{
			$action = $this->Auth->actionMap[$action];
		}

		$allow = $this->Acl->check
			(
				array('model' => 'User', 'foreign_key' => $user['User']['id']),
				sprintf('Everything/%s', $this->name),
				$action
			);

		if ($allow)
		{
			return true;
		}

		if (!in_array($action, array('read', 'update', 'delete'))
			|| is_null($Model)
			|| empty($this->passedArgs))
		{
			return false;
		}

		$owner = $Model->getOwner($this->passedArgs[0]);

		if ($owner != $user['User']['id'])
		{
			return false;
		}

		return $this->Acl->check
			(
				array('model' => 'User', 'foreign_key' => $user['User']['id']),
				sprintf('Everything/%s', $this->name),
				sprintf('%s_own', $action)
			);
	}

	function beforeFilter()
	{
		$this->_configuration =& new NEUTRINO_CONFIG();
		Security::setHash('md5');

		$init = $this->_loadConfigurationModel();

		if ($init !== true)
		{
			// if the config could not be loaded we are most likely in setup
			$this->helpers = array('Html', 'Text', 'Form', 'Session', 'Javascript', 'Ajax');
			$this->components = array('Session', 'Auth');
			$this->uses = array('Migrations.Migration');

			// make sure we really _are_ in setup, otherwise things explode and people die
			if ($this->params['controller'] != 'setup')
			{
				$this->redirect(array('controller' => 'setup', 'action' => 'install'));
			}

			return;
		}

		$this->Configuration->load();
		$this->_installed = Configure::read('Neutrino.Installed');

		// check for a finished install
		if (!$this->_installed && $this->params['controller'] != 'setup')
		{
			$this->redirect(array('controller' => 'setup', 'action' => 'install'));
		}

		// check for an update
		$this->_needsMigration = $this->Migration->needsMigration();

		if ($this->_needsMigration && $this->params['controller'] != 'update')
		{
			$this->redirect(array('controller' => 'update', 'action' => 'update'));
		}

		$this->_setupAuth();
		$this->_loadAcl();

		$this->RememberMe->check();
		$this->_user = $this->Auth->user();

		if ($this->_user)
		{
			$this->_user = $this->_user[$this->Auth->userModel];
		}

		$this->_selectTheme();

//		if ($this->_isAjaxRequest())
//			sleep(1);
	}

	function beforeRender()
	{
		if ($this->_isAjaxRequest() || in_array($this->action, $this->disableCacheActions))
		{
			$this->disableCache();
		}

		$this->_loadAclPermissions();
	}

	function appError($method, $messages)
	{
		$error = sprintf('%1$sMethod: "%2$s"%1$sMessages:%1$s%3$s', "\n", $method, var_export($messages, true));
		$this->log($error);

		if ($this->params['controller'] == 'setup')
		{
			return;
		}

		if (!in_array($method, array('missingConnection')))
		{
			$this->redirect(array('controller' => 'setup', 'action' => 'error'));
		}
	}

	function _isAjaxRequest()
	{
		return ($this->RequestHandler->isAjax() || isset($this->passedArgs['ajax']));
	}

	function _redirectToReferrer()
	{
		$this->redirect($this->referer('/', true), null, true);
	}

	/**
	 * Redirect to a specified action in the current controller,
	 * with additional slug parameter.
	 *
	 * @param string $action
	 * @param string $slug
	 */
	function _redirectTo($action, $slug)
	{
		$this->redirect
			(
				array
				(
					'controller' => $this->params['controller'],
					'action' => $action,
					$slug
				),
				null,
				true
			);
	}

	function _selectTheme()
	{
		if ($this->Session->check('Neutrino.Theme'))
		{
			$this->theme = $this->Session->read('Neutrino.Theme');
		}
		else if (Configure::read('Neutrino.Theme'))
		{
			$this->theme = Configure::read('Neutrino.Theme');
		}
		else
		{
			$this->theme = $this->_configuration->defaultTheme;
		}
	}

	function _loadConfigurationModel()
	{
		$configExists = file_exists(CONFIGS.'database.php');

        if (!$configExists)
        {
            return -1;
        }

		$db = ConnectionManager::getInstance();
		@$conn = $db->getDataSource('default');

		if (!$conn->isConnected())
		{
			return -1;
		}

		$tables = $conn->listSources();

		if (empty($tables))
		{
			return 0;
		}

		if (!in_array('configuration', $tables))
		{
			return false;
		}

		$this->loadModel('Configuration');
		return true;
	}

	function _die($message)
	{
		$this->log($message, LOG_DEBUG);
		__('Fatal error occurred. Further access denied.');
		die;
	}

	function _blackHole($message)
	{
		$this->log(__('A request has been blackholed.', true), LOG_ERROR);
		echo $message;
		die;
	}

	function _loadAcl()
	{
		$db = ConnectionManager::getInstance();
		@$conn = $db->getDataSource('default');

		$tables = $conn->listSources();

		if (in_array('aros_acos', $tables))
		{
			App::import('Component', 'Acl');
			$this->Acl = new AclComponent();
		}
	}

	function _setupAuth()
	{
		$this->Auth->loginAction	= array('controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect	= '/';
		$this->Auth->loginError		= __('Wrong username / password combination', true);
		$this->Auth->authError		= __('You are not authorized to access that location', true);
		$this->Auth->authorize		= 'controller';
		$this->Auth->autoRedirect	= false;
		$this->Auth->userScope		= array
			(
				'User.is_verified'	=> 1,
				'User.is_locked'	=> 0,
				'User.is_banned'	=> 0
			);

		$this->Auth->mapActions(NEUTRINO_CONFIG::$ACL_ACTION_MAP);
	}

	function _loadAclPermissions()
	{
		if (Configure::read('debug')) // @todo: remove
		{
			$this->Session->delete('Auth.Permissions');
		}
		
		if (!empty($this->_user) && !empty($this->Acl) && !$this->Session->read('Auth.Permissions'))
		{
			$perm = $this->Acl->Aro->find
				(
					'threaded',
					array
					(
						'conditions' => array('Aro.foreign_key' => $this->_user['id'])
					)
				);

			$perms[] = $perm[0];
			$aroId = Set::extract('/Aro/id', $perms);

			$userPath = $this->Acl->Aro->getpath($aroId[0]);
			$userPath = Set::extract('/Aro[alias=/^.+$/]/alias', $userPath);
			$userPath = array_reverse($userPath);

			foreach ($userPath as $pathNode)
			{
				$perm = $this->Acl->Aro->find
					(
						'threaded',
						array
						(
							'conditions' => array
							(
								'Aro.alias' => $pathNode
							)
						)
					);

				$perms[] = $perm[0];
			}

			$this->Session->write('Auth.Permissions', $perms);
		}
	}

	function blackhole()
	{
		$this->_blackHole(__('Your request appears to be tampered with and it was blackholed.', true));
	}
}
