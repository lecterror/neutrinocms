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

	This class contains MIT-licenced url caching code written by Matt Curry (www.PseudoCoder.com)
	Copyright (c) 2009 Matt Curry
	http://github.com/mcurry/cakephp/tree/master/snippets/app_helper_url
	http://www.pseudocoder.com/archives/2009/02/27/how-to-save-half-a-second-on-every-cakephp-requestand-maintain-reverse-routing

*/

class AppHelper extends Helper
{
	var $_cache = array();
	var $_key = '';
	var $_extras = array();
	var $_paramFields = array('controller', 'plugin', 'action', 'prefix');

	function __construct()
	{
		parent::__construct();

		if (Configure::read('UrlCache.pageFiles'))
		{
			$view =& ClassRegistry::getObject('view');
			$path = $view->here;

			if ($this->here == '/')
			{
				$path = 'home';
			}

			$this->_key = '_' . strtolower(Inflector::slug($path));
		}

		$this->_key = 'url_map' . $this->_key;
		$this->_cache = Cache::read($this->_key, '_cake_core_');
	}

	function beforeRender()
	{
		$this->_extras = array_intersect_key($this->params, array_combine($this->_paramFields, $this->_paramFields));
	}

	function afterLayout()
	{
		if (is_a($this, 'HtmlHelper'))
		{
			Cache::write($this->_key, $this->_cache, '_cake_core_');
		}
	}

	function url($url = null, $full = false)
	{
		$keyUrl = $url;

		if (is_array($keyUrl))
		{
			$keyUrl += $this->_extras;
		}

		$key = md5(serialize($keyUrl));

		if (!empty($this->_cache[$key]))
		{
			return $this->_cache[$key];
		}

		$url = parent::url($url, $full);
		$this->_cache[$key] = $url;

		return $url;
	}
}
