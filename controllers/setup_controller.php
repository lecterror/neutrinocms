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

/**
 * @property User $User
 * @property SessionComponent $Session
 * @property WizardComponent $Wizard
 */
class SetupController extends AppController
{
	var $name = 'Setup';
	var $uses = array();
	var $components = array('Wizard');
	var $pageTitle = 'NeutrinoCMS Setup';

	var $_wizardSteps = array
		(
			'intro',
			array('connect_database' => array('connect_database')),
			'initialize_database',
			'create_admin_account',
			'configure_website'
		);

	function beforeFilter()
	{
		parent::beforeFilter();
		$this->theme = 'neutrino';

		$this->Auth->allow('*');

		$this->Wizard->lockdown = true;
		$this->Wizard->autoReset = true;
		$this->Wizard->defaultBranch = false;
		$this->Wizard->wizardAction = 'install';
		$this->Wizard->completeUrl = array('controller' => 'setup', 'action' => 'finished');
		$this->Wizard->steps = $this->_wizardSteps;
	}

	public function install($step = null)
	{
		$this->Wizard->process($step);
	}

	public function _prepareIntro()
	{
		$this->_beforeInstall();
	}

	public function _processIntro()
	{
		if ($this->_beforeInstall() === -1)
		{
			$this->Wizard->branch('connect_database');
		}

		return true;
	}

	public function _prepareConnectDatabase()
	{
		if ($this->_beforeInstall() !== -1)
		{
			$this->Wizard->redirect('initialize_database');
			return;
		}

		/* @var ConnectionManager $db */
		$db = ConnectionManager::getInstance();

		// if we have a config file, and cannot connect,
		// try to get the data and offer that instead of a blank form
		// NB: except for password, for security reasons..
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

	public function _processConnectDatabase()
	{
		if ($this->_beforeInstall() !== -1)
		{
			$this->Wizard->redirect('initialize_database');
			return;
		}
		/* @var ConnectionManager $db */
		$db = ConnectionManager::getInstance();

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
			return false;
		}

		if (!$conn->connected)
		{
			$conn->execute(sprintf('create database %s collate utf8_unicode_ci', $name));

			if ($conn->error)
			{
				$this->Session->setFlash(sprintf(__('The database %s could not be created!', true), $name));
				return false;
			}
		}

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
			return false;
		}

		if (!$configFile->write($template))
		{
			$this->Session->setFlash(__('Could not write configuration file!', true));
			return false;
		}

		$this->Session->setFlash(__('Database connection configured successfully!', true));
		return true;
	}

	public function _prepareInitializeDatabase()
	{
		if ($this->_beforeInstall() !== 1)
		{
			$this->Wizard->redirect('create_admin_account');
			return;
		}
	}

	public function _processInitializeDatabase()
	{
		if ($this->_beforeInstall() !== 1)
		{
			$this->Wizard->redirect('create_admin_account');
			return false;
		}

		$db = ConnectionManager::getInstance();
		$conn = $db->getDataSource('default');

		$requiredMigration = $this->_configuration->requiredMigration();

		$this->Migration->migrate($requiredMigration);

		$dbMigration = $this->Migration->getDbMigration();

		if (is_null($dbMigration) || $dbMigration->id() != $requiredMigration)
		{
			$this->Session->setFlash(__('Database init failed!', true));
			return false;
		}

		return true;
	}

	public function _prepareCreateAdminAccount()
	{
		if ($this->_beforeInstall() !== 2)
		{
			$this->Wizard->redirect('configure_website');
			return;
		}

		$this->data['User']['username'] = 'admin';
	}

	public function _processCreateAdminAccount()
	{
		if ($this->_beforeInstall() !== 2)
		{
			$this->Wizard->redirect('configure_website');
			return false;
		}

		$this->loadModel('User');
		$this->_loadAcl();

		if (!$this->User->validateRegistration($this->data))
		{
			$this->Session->setFlash(__('Please correct the errors below', true));
			return false;
		}

		$this->data['User']['last_login'] = date('Y-m-d H:i:s', time());
		$this->data['User']['is_built_in'] = 1;
		$this->data['User']['is_root'] = 1;
		$this->data['User']['is_verified'] = 1;

		$this->User->create();

		if (!$this->User->save($this->data, false))
		{
			$this->Session->setFlash(__('There was an error while trying to create a user!', true));
			return false;
		}

		$this->Acl->allow
			(
				array('model' => 'User', 'foreign_key' => $this->User->id),
				'Everything',
				'*'
			);

		return true;
	}

	public function _prepareConfigureWebsite()
	{
		if ($this->_beforeInstall() !== 3)
		{
			$this->Wizard->redirect();
			return false;
		}

		$this->loadModel('Configuration');

		$this->set('themes', $this->_configuration->availableThemes);
		$this->set('defaultTheme', $this->_configuration->defaultTheme);

		$this->data['Configuration']['SiteTitle'] = __('My awesome site', true);
		$this->data['Configuration']['SiteDescription'] = __('My unique slogan', true);
		$this->data['Configuration']['SiteKeywords'] = __('some, good, keywords, here', true);
		$this->data['Configuration']['CaptchaSidenote'] = __('Insulting message to spammers', true);
		$this->data['Configuration']['SiteCopyrightNotice'] = sprintf('&copy; %s <strong>%s</strong>', date('Y'), __('YourNameHere', true));
		$this->data['Configuration']['GoogleWebmasterToolsVerificationCode'] = '';
		$this->data['Configuration']['GoogleAnalyticsAccountCode'] = '';
	}

	public function _processConfigureWebsite()
	{
		if ($this->_beforeInstall() !== 3)
		{
			$this->Session->setFlash(__('Site already configured!', true));
			$this->Wizard->redirect();
			return false;
		}

		$this->loadModel('Configuration');

		if (!$this->Configuration->validateInstall($this->data))
		{
			return false;
		}

		$this->Configuration->createPostData($this->data);

		foreach ($this->_configuration->initialConfiguration as $key => $value)
		{
			$this->Configuration->create();
			$this->Configuration->save(array('name' => $key, 'value' => $value));
		}

		return true;
	}

	function finished()
	{
		$this->loadModel('Configuration');
		$this->set('release', $this->_configuration->currentAppVersion);

		$this->Session->destroy();
	}

	function _fail($message)
	{
		$this->log($message, LOG_DEBUG);
		echo __('An error occurred. Further access denied.', true);
		echo '<br />';
		echo sprintf
			(
				__('Go to %s and stop messing with the site.', true),
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

	private function _beforeInstall()
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

		if (!$User->hasAny(array('is_root' => true)))
		{
			return 2;
		}

		$Configuration =& ClassRegistry::init('Configuration');

		if (!$Configuration->hasAny('1 = 1')
			|| $Configuration->hasAny(array('name' => 'Installed', 'value' => '0')))
		{
			return 3;
		}

		$this->_fail(__('FATAL: Already installed.', true));
	}

	function error()
	{
		$this->set('referrer', $this->referer());
	}
}
