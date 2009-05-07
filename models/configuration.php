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

class Configuration extends AppModel
{
	var $name = 'Configuration';
	var $useTable = 'configuration';
	var $cacheKey = 'neutrino_config';
	var $cachePeriod = '+2 weeks';

	var $validate = array();

	function __construct()
	{
		parent::__construct();

		$this->validate = array
			(
				'name' =>
					array
					(
						'rule'		=> VALID_NOT_EMPTY,
						'message'	=> __('FATAL: No variable name specified', true)
					)
			);
	}

	function load()
	{
		$settings = Cache::read($this->cacheKey);

		if ($settings == false)
		{
			$settings = $this->find('all');
			Cache::write($this->cacheKey, $settings, $this->cachePeriod);
		}

		foreach ($settings as $variable)
		{
			Configure::write
				(
					'Neutrino.'.$variable['Configuration']['name'],
					$variable['Configuration']['value']
				);
		}
	}

	function deleteCachedConfig()
	{
		Cache::delete($this->cacheKey);
	}

	function validateInstall($data)
	{
		if (empty($data['Configuration']['SiteTitle']))
			$this->invalidate('SiteTitle', __('Please enter a site title', true));

		if (empty($data['Configuration']['SiteDescription']))
			$this->invalidate('SiteDescription', __('Please enter a site description', true));

		if (empty($data['Configuration']['SiteKeywords']))
			$this->invalidate('SiteKeywords', __('Please enter site keywords', true));

		if (empty($data['Configuration']['SiteCopyrightNotice']))
			$this->invalidate('SiteCopyrightNotice', __('Please enter a site copyright notice', true));

		if (empty($data['Configuration']['Theme']))
			$this->invalidate('Theme', __('Please select a theme', true));

		return !$this->invalidFields();
	}

	function validateConfigure($data)
	{
		return $this->validateInstall($data); // for now, it's the same
	}

	function afterSave($created) { $this->deleteCachedConfig(); }
	function afterDelete() { $this->deleteCachedConfig(); }

	function createPostData($data)
	{
		foreach ($data['Configuration'] as $key => $value)
		{
			$this->create();
			$this->save(array('name' => $key, 'value' => $value));
		}
	}

	function updatePostData($data)
	{
		foreach ($data['Configuration'] as $key => $value)
		{
			$existing = $this->find(array('name' => $key));

			if (!$existing)
			{
				$this->create();
			}
			else
			{
				$this->id = $existing['Configuration']['id'];
			}

			$this->save(array('name' => $key, 'value' => $value));
		}
	}
}

?>