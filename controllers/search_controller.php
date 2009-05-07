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

uses('Sanitize');

class SearchController extends AppController
{
	var $name = 'Search';
	var $uses = array('Article');

	function beforeFilter()
	{
		parent::beforeFilter();

		$this->Auth->allow('*');
	}

	function index()
	{
		if (empty($this->data))
		{
			$this->redirect('/');
		}
	}

	function begin()
	{
	}

	function results()
	{
		if (empty($this->data['Search']['phrase']))
		{
			$this->Session->setFlash(__('Please provide a search phrase', true));
			$results = array();
		}
		else
		{
			$results = $this->Article->search($this->data['Search']['phrase'], $this->Auth->user());
		}

		$phrase = Sanitize::html($this->data['Search']['phrase']);
		$this->set(compact('results', 'phrase'));
	}
}

?>