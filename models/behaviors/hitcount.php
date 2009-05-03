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

class HitcountBehavior extends ModelBehavior
{
	var $name = 'Hitcount';
	var $__defaultOptions = array('keyField' => 'id', 'hitField' => 'hitcount');
	var $mapMethods = array('/hit/' => 'hit');

	/**
	 * Setup behaviour.
	 *
	 * @param Model $Model
	 * @param array $settings
	 */
	function setup(&$Model, $settings = array())
	{
		if (!isset($this->settings[$Model->alias]))
		{
			if (empty($settings))
			{
				$this->settings[$Model->alias] = $this->__defaultOptions;
			}
			else if (is_array($settings))
			{
				$this->settings[$Model->alias] = array_merge($this->__defaultOptions, $settings);
			}
		}
	}

	/**
	 * Register a hit in the database.
	 *
	 * @param Model $Model
	 * @param mixed $key
	 */
	function hit(&$Model, $key = null, $settings = null)
	{
		$_settings = array();

		if (isset($this->settings[$Model->alias]))
			$_settings = $this->settings[$Model->alias];

		if (is_array($settings))
			$_settings = array_merge($_settings, $settings);

		if (!isset($_settings['hitField']) || !isset($_settings['keyField']))
		{
			$this->log('Miscofigured hitcount behavior!', LOG_WARNING);
			return;
		}

		if (!$Model->hasField($_settings['keyField']))
			return;

		if (!$Model->hasField($_settings['hitField']))
			return;

		if (empty($key))
			$key = $Model->id;

		if (empty($key))
		{
			$this->log('Invalid call to hitcount behavior!', LOG_WARNING);
			return;
		}

		$Model->updateAll
			(
				array ($_settings['hitField'] => $Model->alias.'.'.$_settings['hitField'].' + 1'),
				array ($Model->alias.'.'.$_settings['keyField'] => $key)
			);
	}
}

?>