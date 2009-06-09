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
 * @property WizardComponent $Wizard
 */
class UpdateController extends AppController
{
	var $name = 'Update';
	var $uses = array();
	var $components = array('Wizard');

	var $_wizardSteps = array
		(
			'intro'
		);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('*');
		$this->theme = 'neutrino';

		$this->Wizard->autoReset = true;
		$this->Wizard->wizardAction = 'update';
		$this->Wizard->completeUrl = array('controller' => 'update', 'action' => 'finished');
		$this->Wizard->steps = $this->_wizardSteps;
	}

	public function update($step = null)
	{
		$this->Wizard->process($step);
	}

	public function _prepareIntro()
	{
		$this->_beforeUpdate();

		$requiredDbVersion = $this->_configuration->dbMigration[$this->_configuration->currentAppVersion];
		$currentDbVersion = $this->Migration->getDbMigration()->id();

		$this->set(compact('requiredDbVersion',	'currentDbVersion'));
	}

	public function _processIntro()
	{
		$this->_beforeUpdate();

		$requiredMigration = $this->_configuration->requiredMigration();
		$this->Migration->migrate($requiredMigration);

		clearCache(null, 'models', '');
		clearCache(null, 'views', '');
		clearCache(null, 'persistent', '');

		return true;
	}

	private function _beforeUpdate()
	{
		$this->loadModel('Configuration');
		$this->Configuration->deleteCachedConfig();
		$this->Configuration->load();

		if (!$this->Migration->needsMigration())
		{
			$this->redirect('/');
		}
	}

	public function finished()
	{
	}
}
