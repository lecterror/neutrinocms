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

class SitemenuComponent extends Object
{
	var $controller = null;
	var $ArticleCategory = null;
	var $DownloadCategory = null;

	function initialize(&$controller)
	{
		$this->controller =& $controller;
	}

	function _active()
	{
		return Configure::read('Neutrino.Installed') &&
			$this->controller->_configuration->requiredDbVersion == Configure::read('Neutrino.CurrentDbVersion');
	}

	function startup(&$controller)
	{
		if ($this->_active())
		{
			$this->ArticleCategory =& ClassRegistry::init('ArticleCategory');
			$this->DownloadCategory =& ClassRegistry::init('DownloadCategory');
		}
	}

	function beforeRender()
	{
		$article_menu_items = array();
		$download_menu_items = array();

		if ($this->_active())
		{
			$this->ArticleCategory->recursive = -1;
			$this->DownloadCategory->recursive = -1;
			$article_menu_items = $this->ArticleCategory->findAll();
			$download_menu_items = $this->DownloadCategory->findAll();
		}

		$this->controller->set('article_menu_items', $article_menu_items);
		$this->controller->set('download_menu_items', $download_menu_items);
	}
}

?>