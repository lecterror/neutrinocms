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

App::import('Core', 'ConnectionManager');

/*
 * This controller could be described as "creative mess"...
 * And not the Salvador Dali kind of mess, or even Chuck Palahniuk mess..
 * I mean really, really bad mess.
 * @todo: rewrite..
 * */

/**
 * @property User $User
 */
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
			'connect_database',
			'install_step1',
			'install_step2',
			'install_step3'
		);

	var $_actionMapping =
		array
		(
			'install'			=> 'connect_database',
			'connect_database'	=> 'install_step1',
			'install_step1'		=> 'install_step2',
			'install_step2'		=> 'install_step3',
			'install_step3'		=> 'install_finished'
		);

	// install steps definition - stored in session
	var $_installStepKey = 'Neutrino.InstallSteps';
	var $_installSteps = array();

	function __construct()
	{
		parent::__construct();

		$this->_installSteps = array
		(
			'wizard_title'		=> __('Setup', true),
			'wizard_done'		=> array(),
			'wizard_current'	=> __('Welcome', true),
			'wizard_pending'	=>
				array
				(
					__('Connect to a database', true),
					__('Initialize database', true),
					__('Create admin account', true),
					__('Configure website', true),
					__('Finish', true)
				)
		);
	}

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->_loadAcl();
		$this->Auth->allow('*');

		$this->theme = 'neutrino';

		if (in_array($this->action, $this->_installActions))
		{
			if (!$this->Session->check($this->_installStepKey))
			{
				$this->Session->write($this->_installStepKey, $this->_installSteps);
			}

			if ($this->_installContinue() === false)
			{
				$this->_fail(__('FATAL: Already installed.', true));
			}

			$this->set('isSetup', true);

			if ($this->_isActionComplete($this->_installStepKey))
			{
				$this->_redirectToNextAction();
			}
			else
			{
				$back = array_flip($this->_actionMapping);

				if (isset($back[$this->action]))
				{
					if (!$this->_isActionComplete($this->_installStepKey, $back[$this->action]))
					{
						$this->redirect(array('controller' => 'setup', 'action' => $back[$this->action]));
					}
				}
			}
		}
	}

	function _fail($message)
	{
		$this->log($message, LOG_DEBUG);
		echo __('An error occurred. Further access denied.', true);
		echo '<br />';
		echo sprintf
			(
				__('Go to %s and stop messing with the site', true),
				sprintf
				(
					'<a href="%s" title="%s">%s</a>',
					Router::url('/'),
					__('Home page', true),
					__('home page', true)
				)
			);
		die;
	}

	function _advance_step($sessionKey)
	{
		if (!$this->Session->check($sessionKey))
		{
			return;
		}

		$steps = $this->Session->read($sessionKey);

		array_push($steps['wizard_done'], $steps['wizard_current']);
		$steps['wizard_current'] = $steps['wizard_pending'][0];
		array_shift($steps['wizard_pending']);

		$this->Session->write($sessionKey, $steps);
	}

	function _isActionComplete($stepKey, $action = null)
	{
		if (empty($action))
		{
			$action = $this->action;
		}

		if (!$this->Session->check($stepKey.'.'.$action.'.Complete'))
		{
			return false;
		}

		return $this->Session->read($stepKey.'.'.$action.'.Complete');
	}

	function _markActionComplete($stepKey, $redirectToNextAction = false)
	{
		$this->Session->write($stepKey.'.'.$this->action.'.Complete', true);

		if ($redirectToNextAction)
		{
			$this->_redirectToNextAction();
		}
	}

	function _redirectToNextAction()
	{
		if (isset($this->_actionMapping[$this->action]))
		{
			$this->redirect(array('controller' => 'setup', 'action' => $this->_actionMapping[$this->action]));
		}
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
		$configExists = file_exists(CONFIGS.'database.php');

		if (!$configExists)
		{
			return -1;
		}

		$db = ConnectionManager::getInstance();
		/* @var $conn DboSource */
		@$conn = $db->getDataSource('default');

		if (!$conn->isConnected())
		{
			return -1;
		}

		$tables = $conn->listSources();
		if (empty($tables) || $this->_needsMigration)
		{
			return 1;
		}

		if (!in_array('users', $tables))
		{
			$this->_fail(__('FATAL: No user table found in default database.', true));
		}

		$User =& ClassRegistry::init('User');

		if (!$User->hasAny('1 = 1'))
		{
			return 2;
		}

		$Configuration =& ClassRegistry::init('Configuration');

		if (!$Configuration->hasAny('1 = 1')
			|| $Configuration->hasAny(array('name' => 'Installed', 'value' => '0')))
		{
			return 3;
		}

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

	function connect_database()
	{
		if ($this->_installContinue() !== -1)
		{
			$this->Session->setFlash(__('Already connected to a database!', true));
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}

		/* @var $db ConnectionManager */
		$db = ConnectionManager::getInstance();

		// if we have a config file, and cannot connect,
		// try to get the data and offer that instead of a blank form
		// NB: except for password, for security reasons..
		if (empty($this->data))
		{
			$tmpFile = new File(CONFIGS.'database.php');

			if (!$tmpFile || !$tmpFile->exists())
			{
				return;
			}

			$configContent = $tmpFile->read();
			$hostMatches = array();
			$usernameMatches = array();
			$nameMatches = array();

			preg_match('#\'host\'(?:\s+)?=>(?:\s+)?\'(\w+)?\'#', $configContent, $hostMatches);
			preg_match('#\'login\'(?:\s+)?=>(?:\s+)?\'(\w+)?\'#', $configContent, $usernameMatches);
			preg_match('#\'database\'(?:\s+)?=>(?:\s+)?\'(\w+)?\'#', $configContent, $nameMatches);

			if (empty($hostMatches) && empty($usernameMatches) && empty($passwordMatches) && empty($nameMatches))
			{
				return;
			}

			if (isset($hostMatches[1]))
			{
				$this->data['DB']['Host'] = $hostMatches[1];
			}

			if (isset($usernameMatches[1]))
			{
				$this->data['DB']['Username'] = $usernameMatches[1];
			}

			if (isset($nameMatches[1]))
			{
				$this->data['DB']['Name'] = $nameMatches[1];
			}

			return;
		}

		$host = $this->data['DB']['Host'];
		$username = $this->data['DB']['Username'];
		$password = $this->data['DB']['Password'];
		$name = $this->data['DB']['Name'];

		/* @var $conn DboSource */
		$conn = $db->create
			(
				'attempt',
				array
				(
					'driver'	=> 'mysql',
					'connect'	=> 'mysql_connect',
					'host'		=> $host,
					'login'		=> $username,
					'password'	=> $password,
					'database'	=> $name,
					'prefix'	=> '',
					'encoding'	=> 'utf-8'
				)
			);

		if (!$conn->connection)
		{
			$this->Session->setFlash(__('Could not connect to database!', true));
			return;
		}

		if (!$conn->connected)
		{
			$conn->execute(sprintf('create database %s collate utf8_unicode_ci', $name));

			if ($conn->error)
			{
				$this->Session->setFlash(sprintf(__('The database %s could not be created!', true), $name));
				return;
			}
		}

		// great! now write the config..
		$template = <<<END
<?php

class DATABASE_CONFIG
{
	var \$default = array(
		'driver' => 'mysql',
		'connect' => 'mysql_connect',
		'host' => '{$host}',
		'login' => '{$username}',
		'password' => '{$password}',
		'database' => '{$name}',
		'prefix' => '',
		'encoding' => 'utf8'
	);
}

?>
END;

		$configFile = new File(CONFIGS.'database.php', true);

		if (!$configFile->writable())
		{
			$this->Session->setFlash(__('Could not save database configuration!', true));
			return;
		}

		if (!$configFile->write($template))
		{
			$this->Session->setFlash(__('Could not write configuration file!', true));
			return;
		}

		$this->Session->setFlash(__('Database connection configured successfully!', true));
		$this->_advance_step($this->_installStepKey);
		$this->_markActionComplete($this->_installStepKey, true);
	}

	function install_step1()
	{
		if ($this->_installContinue() !== 1)
		{
			$this->Session->setFlash(__('Database already initialized!', true));
			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
		}

		if ($this->RequestHandler->isPost() && $this->data['Installation']['Step'] == 1)
		{
			$db = ConnectionManager::getInstance();
			$conn = $db->getDataSource('default');

			$requiredMigration = $this->_configuration->requiredMigration();

			$this->Migration->migrate($requiredMigration);

			$dbMigration = $this->Migration->getDbMigration();

			if (is_null($dbMigration) || $dbMigration->id() != $requiredMigration)
			{
				$this->Session->setFlash(__('Database init failed!', true));
				return;
			}

			$this->_advance_step($this->_installStepKey);
			$this->_markActionComplete($this->_installStepKey, true);
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
			return;
		}

		$this->User->data = $this->data;

		if (!$this->User->validateRegistration($this->data))
		{
			$this->Session->setFlash(__('Please correct the errors below', true));
			return;
		}

		$this->data['User']['last_login'] = date('Y-m-d H:i:s', time());
		$this->data['User']['is_built_in'] = 1;
		$this->data['User']['is_root'] = 1;
		$this->data['User']['is_verified'] = 1;

		$this->User->create();

		if (!$this->User->save($this->data, false))
		{
			$this->Session->setFlash(__('There was an error while trying to create a user!', true));
			return;
		}

		$this->Acl->allow
			(
				array('model' => 'User', 'foreign_key' => $this->User->id),
				'Everything',
				'*'
			);

		$this->Session->write('user', $this->data['User']);
		$this->_advance_step($this->_installStepKey);
		$this->_markActionComplete($this->_installStepKey, true);
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
			$this->data['Configuration']['SiteTitle'] = __('My awesome site', true);
			$this->data['Configuration']['SiteDescription'] = __('My unique slogan', true);
			$this->data['Configuration']['SiteKeywords'] = __('some, good, keywords, here', true);
			$this->data['Configuration']['CaptchaSidenote'] = __('Insulting message to spammers', true);
			$this->data['Configuration']['SiteCopyrightNotice'] = sprintf('&copy; %s <strong>%s</strong>', date('Y'), __('YourNameHere', true));
			$this->data['Configuration']['GoogleWebmasterToolsVerificationCode'] = '';
			$this->data['Configuration']['GoogleAnalyticsAccountCode'] = '';
			return;
		}

		if (!($this->Configuration->validateInstall($this->data)))
		{
			return;
		}

		$this->Configuration->createPostData($this->data);

		foreach ($this->_configuration->initialConfiguration as $key => $value)
		{
			$this->Configuration->create();
			$this->Configuration->save(array('name' => $key, 'value' => $value));
		}

		$this->_advance_step($this->_installStepKey);
		$this->_markActionComplete($this->_installStepKey, true);
	}

	function install_finished()
	{
		$this->set('release', $this->_configuration->currentAppVersion);
		$this->Session->destroy();
	}

	function update()
	{
		$this->loadModel('Configuration');
		$this->Configuration->deleteCachedConfig();
		$this->Configuration->load();

		if (!$this->Migration->needsMigration())
		{
			$this->redirect('/');
		}

		$requiredDbVersion = $this->_configuration->dbMigration[$this->_configuration->currentAppVersion];
		$currentDbVersion = $this->Migration->getDbMigration()->id();

		$this->set(compact('requiredDbVersion',	'currentDbVersion'));
	}

	function update_db()
	{
		$this->loadModel('Configuration');
		$this->Configuration->deleteCachedConfig();
		$this->Configuration->load();

		if (!$this->Migration->needsMigration())
		{
			$this->redirect('/');
		}

		if (!$this->RequestHandler->isPost() ||
			!isset($this->data['Installation']['Step']) ||
			$this->data['Installation']['Step'] != 0)
		{
			$this->redirect(array('controller' => 'setup', 'action' => 'update'));
		}

		$requiredMigration = $this->_configuration->dbMigration[$this->_configuration->currentAppVersion];

		$this->Migration->migrate($requiredMigration);

		clearCache(null, 'models', '');
		clearCache(null, 'views', '');
		clearCache(null, 'persistent', '');
	}

	function error()
	{
		$this->set('referrer', $this->referer());
	}
}
