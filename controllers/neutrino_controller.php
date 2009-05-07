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

class NeutrinoController extends AppController
{
	var $name = 'Neutrino';
	var $uses = array('Configuration');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->deny('configure', 'markdown');
	}

	function configure()
	{
		$this->set('themes', $this->_configuration->availableThemes);

		if (empty($this->data))
		{
			$this->Configuration->deleteCachedConfig();
			$this->Configuration->load();

			$currentConfig = Configure::read('Neutrino');

			$this->data['Configuration']['SiteTitle'] = $currentConfig['SiteTitle'];
			$this->data['Configuration']['SiteDescription'] = $currentConfig['SiteDescription'];
			$this->data['Configuration']['SiteKeywords'] = $currentConfig['SiteKeywords'];
			$this->data['Configuration']['CaptchaSidenote'] = $currentConfig['CaptchaSidenote'];
			$this->data['Configuration']['SiteCopyrightNotice'] = $currentConfig['SiteCopyrightNotice'];
			$this->data['Configuration']['GoogleWebmasterToolsVerificationCode'] = $currentConfig['GoogleWebmasterToolsVerificationCode'];
			$this->data['Configuration']['GoogleAnalyticsAccountCode'] = $currentConfig['GoogleAnalyticsAccountCode'];
			$this->set('defaultTheme', $currentConfig['Theme']);
		}
		else
		{
			if (!($this->Configuration->validateConfigure($this->data)))
			{
				$this->Session->setFlash(__('Please correct the errors below', true));
				return;
			}

			$this->Configuration->updatePostData($this->data);
			clearCache();
			Cache::clear(false);

			$this->Session->setFlash(__('Configuration saved successfully', true));
			$this->_redirectTo('configure', '');
		}
	}

	function markdown()
	{
		$this->theme = 'neutrino';
		$this->layout = 'neutrino-help';
	}
}

?>