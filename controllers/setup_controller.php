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
 * Setup controller is a setup && update utility.
 */

uses('model'.DS.'connection_manager');

class SetupController extends AppController
{
	var $name = 'Setup';
	var $helpers = array('Html', 'Text', 'Form', 'Session', 'Javascript', 'Ajax');
	var $components = array('Auth', 'Session');
	var $uses = array();
	var $pageTitle = 'NeutrinoCMS';

	var $_installActions =
		array
		(
			'install',
			'install_step1',
			'install_step2',
			'install_step3'
		);

	var $_actionMapping =
		array
		(
			'install'		=> 'install_step1',
			'install_step1'	=> 'install_step2',
			'install_step2'	=> 'install_step3',
			'install_step3'	=> 'install_finished'
		);

	// install steps definition - stored in session
	var $_installStepKey = 'Neutrino.InstallSteps';
	var $_installSteps =
		array
		(
			'wizard_title'		=> 'Setup',
			'wizard_done'		=> array(),
			'wizard_current'	=> 'Welcome',
			'wizard_pending'	=>
				array
				(
					'Initialize database',
					'Create admin account',
					'Configure website',
					'Finish'
				)
		);

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('*');

		$this->theme = 'neutrino';

		if (in_array($this->action, $this->_installActions))
		{
			if (!$this->Session->check($this->_installStepKey))
				$this->Session->write($this->_installStepKey, $this->_installSteps);

			if ($this->_installContinue() === false)
				$this->_fail(__('FATAL: Already installed.', true));

			if ($this->_isActionComplete($this->_installStepKey))
				$this->_redirectToNextAction();
			else
			{
				$back = array_flip($this->_actionMapping);

				if (isset($back[$this->action]))
					if (!$this->_isActionComplete($this->_installStepKey, $back[$this->action]))
						$this->redirect(array('controller' => 'setup', 'action' => $back[$this->action]));
			}
		}
	}

	function _fail($message)
	{
		$this->log($message, LOG_DEBUG);
		echo __('An error occurred. Further access denied.', true);
		die;
	}

	function _advance_step($sessionKey)
	{
		if (!$this->Session->check($sessionKey))
			return;

		$steps = $this->Session->read($sessionKey);

		array_push($steps['wizard_done'], $steps['wizard_current']);
		$steps['wizard_current'] = $steps['wizard_pending'][0];
		array_shift($steps['wizard_pending']);

		$this->Session->write($sessionKey, $steps);
	}

	function _isActionComplete($stepKey, $action = null)
	{
		if (empty($action))
			$action = $this->action;

		if (!$this->Session->check($stepKey.'.'.$action.'.Complete'))
			return false;

		return $this->Session->read($stepKey.'.'.$action.'.Complete');
	}

	function _markActionComplete($stepKey, $redirectToNextAction = false)
	{
		$this->Session->write($stepKey.'.'.$this->action.'.Complete', true);

		if ($redirectToNextAction)
			$this->_redirectToNextAction();
	}

	function _redirectToNextAction()
	{
		if (isset($this->_actionMapping[$this->action]))
			$this->redirect(array('controller' => 'setup', 'action' => $this->_actionMapping[$this->action]));
	}

	function _executeSqlScript($conn, $sql_script)
	{
		if (file_exists(CONFIGS.'sql'.DS.$sql_script))
		{
			$sql_all = file_get_contents(CONFIGS.'sql'.DS.$sql_script);
			$sql_commands = explode(';', $sql_all);

			foreach ($sql_commands as $command)
			{
				if (strlen(trim($command)) > 0)
				{
					$conn->execute($command);
				}
			}
		}
	}

	function _installContinue()
	{
		$db = ConnectionManager::getInstance();
		$conn = $db->getDataSource('default');

		if (!$conn->isConnected())
			$this->_fail(__('FATAL: Cannot connect to the default database.', true));

		$tables = $conn->listSources();
		if (empty($tables))
			return 1;

		if (!in_array('users', $tables))
			$this->_fail(__('FATAL: No user table found in default database.', true));

		App::import('model', 'User');
		$User = new User();

		if (!$User->hasAny('1 = 1'))
			return 2;

		App::import('model', 'Configuration');
		$Configuration = new Configuration();

		if (!$Configuration->hasAny('1 = 1')
			|| $Configuration->hasAny(array('name' => 'Installed', 'value' => '0')))
			return 3;

		return false;
	}

	function install()
	{
		if (isset($this->data['Installation']['Step']) && $this->data['Installation']['Step'] == 0)
		{
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}
	}

	function install_step1()
	{
		if ($this->_installContinue() !== 1)
		{
			$this->Session->setFlash(__('Database already initialized!', true));
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}

		if ($this->RequestHandler->isPost() && $this->data['Step'] == 1)
		{
			$db = ConnectionManager::getInstance();
			$conn = $db->getDataSource('default');

			$sql_script = $this->_configuration->dbMigration
				[$this->_configuration->currentAppVersion]['install'];

			if (is_array($sql_script))
			{
				foreach ($sql_script as $script)
					$this->_executeSqlScript($conn, $script);
			}
			else if (!empty($sql_script))
				$this->_executeSqlScript($conn, $sql_script);

			$tables = $conn->listSources();

			if (empty($tables))
				$this->Session->setFlash(__('Database init failed!', true));
			else if (in_array('users', $tables))
			{
				$this->_advance_step($this->_installStepKey);
				$this->_markActionComplete($this->_installStepKey, true);
			}
		}
	}

	function install_step2()
	{
		if ($this->_installContinue() !== 2)
		{
			$this->Session->setFlash(__('Administrator account already created!', true));
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}

		$this->loadModel('User');

		if (empty($this->data))
		{
			$this->data['User']['username'] = 'admin';
		}
		else
		{
			$this->User->data = $this->data;

			if (!$this->User->validates())
			{
				$this->data['User']['password'] = '';
				$this->Session->setFlash(__('Please correct the errors below', true));
				return;
			}

			$this->data['User']['last_login'] = date('Y-m-d H:i:s', time());
			$this->User->create();
			if ($this->User->save($this->data))
			{
				$this->Session->write('user', $this->data['User']);
				$this->_advance_step($this->_installStepKey);
				$this->_markActionComplete($this->_installStepKey, true);
			}
			else
			{
				$this->Session->setFlash(__('There was an error while trying to create a user!', true));
			}
		}
	}

	function install_step3()
	{
		if ($this->_installContinue() !== 3)
		{
			$this->Session->setFlash(__('Site already configured!', true));
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}

		$this->loadModel('Configuration');

		$this->set('themes', $this->_configuration->availableThemes);
		$this->set('defaultTheme', $this->_configuration->defaultTheme);

		if (empty($this->data))
		{
			$this->data['Configuration']['SiteTitle'] = 'Iron Maiden';
			$this->data['Configuration']['SiteDescription'] = 'Fear of the Dark';
			$this->data['Configuration']['SiteKeywords'] = 'eddie, the beast, marriner';
			$this->data['Configuration']['CaptchaSidenote'] = 'Spammers: sod off';
			$this->data['Configuration']['SiteCopyrightNotice'] = '&copy; '.date('Y').' <strong>YourNameHere</strong>';
			$this->data['Configuration']['GoogleWebmasterToolsVerificationCode'] = '';
			$this->data['Configuration']['GoogleAnalyticsAccountCode'] = '';
		}
		else
		{
			if (!($this->Configuration->validateInstall($this->data)))
				return;

			$this->Configuration->createPostData($this->data);

			foreach ($this->_configuration->initialConfiguration as $key => $value)
			{
				$this->Configuration->create();
				$this->Configuration->save(array('name' => $key, 'value' => $value));
			}

			$this->Configuration->create();
			$this->Configuration->save(array(
				'name' => 'CurrentDbVersion',
				'value' => $this->_configuration->requiredDbVersion));

			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}
	}

	function install_finished()
	{
		$this->set('release', $this->_configuration->currentAppVersion);
	}

	function update()
	{
		$this->loadModel('Configuration');
		$this->Configuration->deleteCachedConfig();
		$this->Configuration->load();

		if ($this->_configuration->requiredDbVersion == Configure::read('Neutrino.CurrentDbVersion'))
			$this->redirect('/');

		$this->set('requiredDbVersion', $this->_configuration->requiredDbVersion);
	}

	function update_db()
	{
		$this->loadModel('Configuration');
		$this->Configuration->deleteCachedConfig();
		$this->Configuration->load();

		if ($this->_configuration->requiredDbVersion == Configure::read('Neutrino.CurrentDbVersion'))
			$this->redirect('/');

		if (!$this->RequestHandler->isPost()
			|| !isset($this->data['Configuration']['Step'])
			|| $this->data['Configuration']['Step'] != 0)
			$this->redirect(array('controller' => 'setup', 'action' => 'update'));

		$db = ConnectionManager::getInstance();
		$conn = $db->getDataSource('default');

		$sql_script = $this->_configuration->dbMigration
			[$this->_configuration->currentAppVersion][Configure::read('Neutrino.CurrentDbVersion')];

		if (is_array($sql_script))
		{
			foreach ($sql_script as $script)
				$this->_executeSqlScript($conn, $script);
		}
		else if (!empty($sql_script))
		{
			$this->_executeSqlScript($conn, $sql_script);
		}

		$version = $this->Configuration->findByName('CurrentDbVersion');

		$this->Configuration->id = $version['Configuration']['id'];
		$this->Configuration->save(array(
			'name' => 'CurrentDbVersion',
			'value' => $this->_configuration->requiredDbVersion));

		clearCache(null, 'models', '');
		clearCache(null, 'views', '');
		clearCache(null, 'persistent', '');
	}

	function error()
	{
		$this->set('referrer', $this->referer());
	}
}

?>