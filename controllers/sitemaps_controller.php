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

class SitemapsController extends AppController
{
	var $name = 'Sitemaps';
	var $helpers = array('Time', 'Xml');
	var $uses = array('Article', 'Download');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('*');
	}

	function index()
	{
		$this->redirect('/');
	}

	function sitemap()
	{
		if (Configure::read('debug'))
		{
			Configure::write('debug', 0);
		}

		$articles = $this->Article->getSitemapInformation();
		$downloads = $this->Download->getSitemapInformation();

		$this->set(compact('articles', 'downloads'));
		$this->RequestHandler->respondAs('xml');
		$this->viewPath .= '/xml';
		$this->layoutPath = 'xml';
	}

	function robots()
	{
		if (Configure::read('debug'))
		{
			Configure::write('debug', 0);
		}

		$articles = $this->Article->getSitemapInformation();

		$urls = array();

		foreach ($articles as $article)
		{
			$urls[] = Router::url(
					array
					(
						'controller' => 'articles',
						'action' => 'view'
					)
				).'/'.$article['Article']['slug'].'/page';
		}

		$this->set(compact('urls'));
		$this->RequestHandler->respondAs('text');
		$this->viewPath .= '/text';
		$this->layout = 'ajax';
	}
}

?>