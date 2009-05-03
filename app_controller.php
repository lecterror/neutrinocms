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
App::import('Core', 'Sanitize');

class AppController extends Controller
{
	var $helpers = array(
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

	var $_configuration = null;
	var $components = array('Sitemenu', 'Auth', 'RequestHandler', 'RememberMe');
	var $view = 'Theme';

	/**
	 * Cache will be disabled in beforeRender() for these actions on all controllers.
	 *
	 * @var array
	 */
	var $disableCacheActions = array('edit', 'add', 'configure', 'manage');

	function isAuthorized()
	{
		return $this->Auth->user();
	}

	function beforeFilter()
	{
		$this->_configuration =& new NEUTRINO_CONFIG();
		Security::setHash('md5');

		if (!$this->_loadConfiguration())
		{
			// if the config could not be loaded we are most likely in setup
			$this->helpers = array('Html', 'Text', 'Form', 'Session', 'Javascript', 'Ajax');
			$this->components = array('Auth', 'Session');
			$this->uses = array();

			// make sure we really _are_ in setup, otherwise things explode and people die
			if ($this->params['controller'] != 'setup')
				$this->redirect(array('controller' => 'setup', 'action' => 'install'));

			return;
		}
		else
		{
			$this->Configuration->load();

			// check for a finished install
			if (!Configure::read('Neutrino.Installed')
				&& $this->params['controller'] != 'setup')
				$this->redirect(array('controller' => 'setup', 'action' => 'install'));

			// check for an update
			if (Configure::read('Neutrino.CurrentDbVersion') != $this->_configuration->requiredDbVersion
				&& $this->params['controller'] != 'setup')
				$this->redirect(array('controller' => 'setup', 'action' => 'update'));
		}

		$this->Auth->loginAction	= array('controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect	= '/';
		$this->Auth->loginError		= 'Wrong username / password combination';
		$this->Auth->authError		= 'You must be logged in before you try to do that';
		$this->Auth->authorize		= 'controller';
		$this->Auth->autoRedirect	= false;

		$this->RememberMe->check();

		$this->_selectTheme();

//		if ($this->_isAjaxRequest())
//			sleep(1);
	}

	function beforeRender()
	{
		if ($this->_isAjaxRequest() || in_array($this->action, $this->disableCacheActions))
		{
			$this->disableCache();
			return;
		}
	}

	function appError($method, $messages)
	{
		$this->log('method('.$method.'), messages('.serialize($messages).')');
		$this->redirect(array('controller' => 'setup', 'action' => 'error'));
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

	function _loadConfiguration()
	{
		App::import('Core', 'ConnectionManager');
		$db = ConnectionManager::getInstance();
		$conn = $db->getDataSource('default');

		if (!$conn->isConnected())
		{
			$this->_die('FATAL: Cannot connect to the default database.');
		}

		$tables = $conn->listSources();

		if (empty($tables))
		{
			return false;
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
		echo 'Fatal error occurred. Further access denied.';
		die;
	}
}

?>